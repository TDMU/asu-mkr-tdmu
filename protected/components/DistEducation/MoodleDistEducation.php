<?php
/**
 * Created by PhpStorm.
 * User: NEFF
 * Date: 06.11.2017
 * Time: 16:24
 */

/**
 * Class MoodleDistEducation
 * Конектор для мудла
 */
class MoodleDistEducation extends DistEducation
{
    protected $roleId;

    public function __construct($host, $apiKey)
    {
        parent::__construct($host, $apiKey);

        $this->roleId = PortalSettings::model()->getSettingFor(PortalSettings::ROLE_ID_FOR_MOODLE_STUDENTS);
    }

    /**
     * @param Users $user
     * @return array
     * @throws CHttpException
     */
    protected function getParamsForSignUp($user)
    {
        $model = St::model()->findByPk($user->u6);
        if($model==null)
            throw new CHttpException(400, tt('Студент не найден'));

        return array(
            'username'=>$user->u2,
            'firstname'=>CHtml::encode($model->st3),
            'lastname'=>CHtml::encode($model->st2),
            'email'=>$user->u4,
            'idnumber'=>'student'.$user->u6,
            'password'=>'St_password'.$user->u6,
        );
    }

    /**
     * отправка запроса для регистрации
     * @param Users $user
     * @return array
     */
    protected function sendSignUp($user, $params)
    {
        $body = $this->_sendQuery('core_user_create_users','GET', array(
            'users'=>array(
                $params
            )
        ));

        $array = json_decode($body);

        if(isset($array->errorcode)){
            return array(false, 'Ошибка '.$array->message);
        }else {

            if (count($array) != 1) {
                return array(false, 'Ошибка');
            } else {
                return array($array[0]->username == $user->u2, '', $array[0]->id);
            }
        }

    }

    /**
     * Авторизация в ситием дистанционого обучения
     * @param Users $user
     * @return bool
     */
    protected function runLogin($user)
    {
        Yii::app()->request->redirect($this->host);
        return true;
    }

    /**
     * Проверка email
     * @param string $email
     * @return bool
     */
    protected function _validateEmail($email)
    {
        $body = $this->_sendQuery('core_user_get_users','GET', array(
            'criteria'=>array(
                '0'=>array(
                    'key'=>'email',
                    'value'=>$email
                )
            )
        ));

        $array = json_decode($body);

        if(!isset($array->users))
            return false;
        else {
            if(count($array->users)!=1){
                return false;
            }else{
                return $array->users[0]->email == $email;
            }
        }
    }

    /**
     * @return mixed
     * @throws CHttpException
     */
    protected function _getCoursesList()
    {
        $body = $this->_sendQuery('core_course_get_courses','GET');

        $array = json_decode($body);

        if(!is_array($array))
            throw new CHttpException(500, 'EdxDistEducation: Ошибка загрузки курсов. Неверный формат ответа');
        else
            return $array;
    }

    /**
     * Инфо по курсу по id
     * Сейчас береться из @see getCoursesList
     * @param string $id
     * @return object|null|array
     * @throws CHttpException
     */
    protected function _getCourse($id)
    {
        $course = array_filter(
            $this->getCoursesList(),
            function ($e) use (&$id) {
                return $e->id == $id;
            }
        );

        if(empty($course))
            return null;

        if(count($course)>1)
            throw  new CHttpException(500, 'EdxDistEducation:Несколько курсов с id');

        return current($course);
    }

    /**
     * Список курсов для combobox @see CHtml::listData()
     * @return mixed
     */
    /*protected function _getCoursesListForLisData()
    {
        $list = $this->getCoursesList();

        return CHtml::listData($list,'id', function ($data){
            return $data->displayname. ' / '. $data->id;
        });
    }*/

    protected function _getColumnsForGridView()
    {
        return array(
            'id'=>array(
                'header'=>tt('Id'),
                'name'=>'id'/*,
                'value'=>'$data->id',*/
            ),
            'shortname'=>array(
                'header'=>tt('Сокр. название'),
                'name'=>'shortname'
            ),
            'displayname'=>array(
                'header'=>tt('Название'),
                'name'=>'displayname'
            ),
            'startdate'=>array(
                'header'=>tt('Дата начала'),
                'name'=>'startdate',
                'value'=>'date("d.m.Y",$course->startdate)'
            ),
            'enddate'=>array(
                'header'=>tt('Дата окончания'),
                'name'=>'enddate',
                'value'=>'date("d.m.Y",$course->enddate)'
            )

        );
    }

    /**
     * Массив параметров для привязки
     * @param $course object|array
     * @return array
     */
    protected function _getParamsLink($course)
    {
        return array(
            'dispdist3'=> $course->id,
            'dispdist2' => $course->displayname
        );
    }

    /**
     * Отправка запроса по мудлу
     * @param string $method метод
     * @param array $params параметрі
     * @return string
     * @throws CHttpException
     */
    private function _sendQuery($method, $type = null, $params=null){

        $uri = sprintf('%s/webservice/rest/server.php',$this->host);

        $params1['wstoken']=$this->apiKey;
        $params1['wsfunction']=$method;
        $params1['moodlewsrestformat']='json';

        $curl = new MoodleCurl();

        switch ($type) {
            case 'GET':
                $params = array_merge($params, $params1);
                $resp = $curl->get($uri, $params);
            break;
            case 'POST':
                $uri .= (stripos($uri, '?') !== false) ? '&' : '?';
                $uri .= http_build_query($params1, '', '&');

                $resp = $curl->post($uri, $params);
                break;
            default:
                $resp = $curl->get($uri, $params);
        }

        if($resp)
        {
            return $resp;
        }
        else
            throw new CHttpException(500, 'MOODLEDistEducation: Ошибка отправки запроса. '.$resp);
    }

    /**
     * Записать студента на курс
     * @param Stdist $st
     * @param string $courseId
     * @return array
     */
    protected function _unsubscribeToCourse($st, $courseId){
        $body = $this->_sendQuery('enrol_manual_unenrol_users','POST', array(
            'enrolments'=>array(
                '0'=>array(
                    'roleid' => $this->roleId,
                    'courseid' => $courseId,
                    'userid'=> $st->stdist3
                )
            )
        ));

        $array = json_decode($body);

        if(isset($array->errorcode)){
            return array(false, 'Ошибка '.$array->message);
        }else {
            return array(true, '');
        }
    }

    /**
     * Записать студента на курс
     * @param Stdist $st
     * @param string $courseId
     * @return array
     */
    protected function _subscribeToCourse($st, $courseId){

        $body = $this->_sendQuery('enrol_manual_enrol_users','POST', array(
            'enrolments'=>array(
                '0'=>array(
                    'roleid' => $this->roleId,
                    'courseid' => $courseId,
                    'userid'=> $st->stdist3
                )
            )
        ));

        $array = json_decode($body);

        if(isset($array->errorcode)){
            return array(false, 'Ошибка '.$array->message);
        }else {
            return array(true, '');
        }
    }

    /**
     * Записать студента на курс по дисциплине
     * @param St $st
     * @param array $dispInfo
     * @return array
     */
    public function unsubscribeToCourse($st, $dispInfo)
    {
        return $this->unsubscribeStudentsToCourse(array($st), $dispInfo);
    }

    /**
     * Записать студента на курс
     * @param St $st
     * @param array $uo1
     * @return array
     */
    public function subscribeToCourse($st, $dispInfo)
    {
        return $this->subscribeStudentsToCourse(array($st), $dispInfo);
    }

    /**
     * Записать студентов на курс по дсициплине
     * @param St[] $students
     * @param array $dispInfo
     * @return array
     */
    public function subscribeStudentsToCourse($students, $dispInfo)
    {
        //Массив для хранения удчно записаных для рассылки по почте
        $successLog = array();

        $globalResult = true;
        $log = '';

        $model = DispDist::model()->findByPk($dispInfo['uo1']);

        if($model==null)
        {
            $log .= '<br>' . $dispInfo['d2']. ' : Дисциплина не привязана ';
            $globalResult = false;
            return array($globalResult, $log);
        }

        $id = $model->dispdist3;

        foreach ($students as $student) {
            $log .='<br>';
            $stModel = Stdist::model()->findByPk($student->st1);
            if($stModel==null){
                $globalResult = false;
                $log .= $student->getShortName(). ' Ошибка записи: Студент не зарегистрирован в Дист.образовании';
                continue;
            }

            list($result, $message) = $this->_subscribeToCourse($stModel, $id);

            if(!$result) {
                $globalResult = false;
                $log .= $student->getShortName(). ' Ошибка записи: '. $message;
            }else{
                $log .= $student->getShortName(). ' Запись удачна. '. $message;

                if(!$this->stDistSub($dispInfo['uo1'], $student->st1, true)){
                    $log .= ' Ошибка создания записи-лога';
                    $globalResult = false;
                }

                $successLog[] = array(
                    'fio' => $student->getShortName(),
                    'email' => $stModel->stdist2,
                    'discipline' => $dispInfo['d2'],
                    'course' => $model->dispdist2
                );
            }
        }

        if(!empty($successLog)){
            list($success, $message) = $this->sendMails($successLog, PortalSettings::model()->getSettingFor(PortalSettings::SUBSCRIPTION_EMAIL_DIST_EDUCATION), tt('Запись на курс'));
            if(!$success){
                $globalResult = false;
                $log.=$message;
            }
        }

        return array($globalResult, $log);
    }

    /**
     * Выписать студентов с курса по дсициплине
     * @param St[] $students
     * @param array $dispInfo
     * @return array
     */
    public function unsubscribeStudentsToCourse($students, $dispInfo)
    {
        //Массив для хранения удчно отписаніх для рассылки по почте
        $successLog = array();

        $globalResult = true;
        $log = '';

        $model = DispDist::model()->findByPk($dispInfo['uo1']);

        if($model==null)
        {
            $log .= '<br>' . $dispInfo['d2']. ' : Дисциплина не привязана ';
            $globalResult = false;
            return array($globalResult, $log);
        }

        $id = $model->dispdist3;

        foreach ($students as $student) {
            $log .='<br>';
            $stModel = Stdist::model()->findByPk($student->st1);
            if($stModel==null){
                $globalResult = false;
                $log .= $student->getShortName(). ' Ошибка записи: Студент не зарегистрирован в Дист.образовании';
                continue;
            }

            list($result, $message) = $this->_unsubscribeToCourse($stModel, $id);

            if(!$result) {
                $globalResult = false;
                $log .= $student->getShortName(). ' Ошибка записи: '. $message;
            }else{
                $log .= $student->getShortName(). ' успешно выписан. '. $message;

                if(!$this->stDistSub($dispInfo['uo1'], $student->st1, false)){
                    $log .= ' Ошибка удаления записи-лога';
                    $globalResult = false;
                }

                $successLog[] = array(
                    'fio' => $student->getShortName(),
                    'email' => $stModel->stdist2,
                    'discipline' => $dispInfo['d2'],
                    'course' => $model->dispdist2
                );
            }

        }

        if(!empty($successLog)){
            list($success, $message) = $this->sendMails($successLog, PortalSettings::model()->getSettingFor(PortalSettings::UNSUBSCRIPTION_EMAIL_DIST_EDUCATION), tt('Выписка с курса'));
            if(!$success){
                $globalResult = false;
                $log.=$message;
            }
        }

        return array($globalResult, $log);
    }

    /**
     * Отпарвка сообщений
     * @param $mails array массив масивов паратетров для сообщений
     * @param $pattern string шаблон письма
     * @param $subject string
     * @return array
     */
    private function sendMails($mails, $pattern, $subject){
        $result = true;
        $log = false;
        foreach ($mails as $mail){
            if(!isset($mail['email']))
                continue;
            if(!isset($mail['fio']))
                continue;

            list($success, $message) = $this->sendMail($mail['email'], $subject, $mail, $pattern);

            if(!$success) {
                $result = false;
                $log.=sprintf('<br> %s: Ошибка отправки письма на почту %s',$mail['fio'], $mail['email'] );
            }
        }

        return array($result, $log);
    }
}