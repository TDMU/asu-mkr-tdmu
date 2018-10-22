<?php
/**
 * Created by PhpStorm.
 * User: NEFF
 * Date: 06.11.2017
 * Time: 16:27
 */

/**
 * Class DistEducation
 * Класс для дистанционного обучения
 * @property string $host
 * @property string $apiKey
 */
abstract class DistEducation implements IDistEducation
{
    /*
     *
     */
    const ID_FIELD_NAME = 'id';

    const KEY_CACHE_COURS_LIST = 'de_courses_list';

    /**
     * @var string АпиКей
     */
    private $_apiKey;

    /**
     * apiKey
     * @return string
     */
    public function getApiKey()
    {
        return $this->_apiKey;
    }

    /**
     * @var string Хост
     */
    private $_host;

    /**
     * Host
     * @return string
     */
    public function getHost()
    {
        return $this->_host;
    }

    /**
     * Отправка запроса для регистрации
     * @param Users $user
     * @param $params array
     * @return array
     */
    abstract protected function sendSignUp($user, $params);

    /**
     * параметры для запроса регистрации
     * @param Users $user
     * @return array
     */
    abstract protected function getParamsForSignUp($user);

    /**
     * Привязка к уже существующей учетек
     * @param $user Users
     * @param $params array
     * @return array
     */
    protected function saveSignUpOld($user, $params){
        if(empty($user))
        {
            return array(false, tt('Пользователь пустой'));
        }
        if(!$user->isStudent)
            return array(false, 'DistEducation:'.tt('Пользователь не студент'));

        if(!isset($params['email']))
            return array(false, 'EdxDistEducation: params don`t contains email');

        $transaction = Yii::app()->db->beginTransaction();
        try {
            //Сохраняем приязку студента к акунту дистанционного образлвания
            $stDist = Stdist::model()->findByPk($user->u6);
            if($stDist==null) {
                $stDist = new Stdist();
                $stDist->stdist1 = $user->u6;
            }
            $stDist->stdist2 = $params['email'];

            $stDist->stdist3 = isset($params['id'])? $params['id'] : 0;

            if(!$stDist->save()){
                $transaction->rollback();

                $text = 'Ошибка сохранения!';

                $errors = $stDist->getErrors('stdist2');
                if(is_array($errors))
                    $text = implode('; ',$errors);

                if(is_string($errors))
                    $text = $errors;

                return array(false, $text);
            }
            //ставим метку студенту что он имеет привязку
            if(!$this->_setStudentSignUp($user->u6))
                $transaction->rollback();

            $transaction->commit();

            return array(true, '');

        } catch (Exception $e) {
            $transaction->rollback();
            return array(false, $e->getMessage());
        }
    }

    /**
     * @param $user Users
     * @return mixed
     */
    abstract protected function runLogin($user);

    /**
     * DistEducation constructor.
     * @param string $host
     * @throws Exception empty host
     */
    public function __construct($host, $apiKey)
    {
        if(empty($host))
            throw  new CHttpException(500,'DistEducation: Host empty');
        if(empty($apiKey))
            throw  new CHttpException(500,'DistEducation: ApiKey empty');

        $this->host = $host;
        $this->apiKey = $apiKey;
        //parent::__construct($host);
    }

    /**
     * Регистрация в системе дистанционного обучения
     * @param $user Users
     * @return array
     */
    public function signUp($user)
    {
        if(empty($user))
        {
            return array(false, tt('Пользователь пустой'));
        }
        if($this->validateEmail($user->u4)){
            return array(false, tt('Уже есть пользователь в дистанционном образовании с таким email!'));
        }
        $params = $this->getParamsForSignUp($user);

        $res = $this->sendSignUp($user, $params);

        if($res[0])
        {
            if(isset($res[2]))
            {
                $params['id'] = $res[2];
            }
            $res2 = $this->saveSignUpOld($user, $params);

            if(!$res2[0])
                return $res2;

            else
            {
                $message = PortalSettings::model()->getSettingFor(PortalSettings::REGISTRATION_EMAIL_DIST_EDUCATION);

                if(empty($message)) {
                    $message = '';
                }

                list($status, $message) = Controller::mailByTemplate($user->u4, tt('Регистрация дист. образование'), $message, array(
                    'username'=> $params['username'],
                    'email'=>$params['email'],
                    'fio' => $user->name,
                    'password' => $params['password']
                ));

                if (!$status) {
                    $adminMail = PortalSettings::model()->getSettingFor(PortalSettings::ADMIN_EMAIL_DIST_EDUCATION);
                    if(!empty($adminMail))
                        list($status, $message) = Controller::mailByTemplate($adminMail, tt('Регистрация дист. образование'), $message, array(
                            'username'=> $params['username'],
                            'email'=>$params['email'],
                            'fio' => $user->name,
                            'password' => $params['password']
                        ));

                    return array(false, tt('Регистрация прошла успешно, но ошибка отправки извещения на почту. Обратитесь в тех.поддержку.'));
                }
            }
        }

        return $res;
    }

    /**
     * Регистрация в системе дистанционного обучения
     * @param $user Users
     * @param $params array
     * @return array
     */
    public function signUpOld($user, $params)
    {
        return $this->saveSignUpOld($user, $params);
    }

    /**
     * Сохранить метку по студенту что регистрация в дист образовании прошла
     * @param $st1 int
     * @return bool
     */
    protected function _setStudentSignUp($st1){
        $st = St::model()->findByPk($st1);

        if($st == null)
            return false;

        if(!$st->saveAttributes(array('st168'=>1)))
            return false;

        return true;
    }

    /**
     * Авторизация в системе дистанционного обучения
     * @param $user Users Логин
     * @return bool
     */
    public function login($user)
    {
        return $this->runLogin($user);
    }

    /**
     * Список курсов
     * @return mixed
     */
    public function getCoursesList()
    {
        $id = self::KEY_CACHE_COURS_LIST;

        $value=Yii::app()->cache->get($id);
        if($value===false)
        {
            // устанавливаем значение $value заново, т.к. оно не найдено в кэше,
            // и сохраняем его в кэше для дальнейшего использования:
            $list = $this->_getCoursesList();
            Yii::app()->cache->set($id,$list,600);
            return $list;
        }

        return $value;
    }

    /**
     * Список курсов
     * @return mixed
     */
    abstract protected function _getCoursesList();

    /**
     * Инфо по курсу по $id
     * @param string|int $id
     * @return mixed
     */
    public function getCourse($id)
    {
        if(empty($id))
            return null;

        return $this->_getCourse($id);
    }

    /**
     * Список курсов
     * @return mixed
     */
    abstract protected function _getCourse($id);

    /**
     * Колокнки для ГридВьюв
     * @return array
     */
    public function getColumnsForGridView()
    {
        return $this->_getColumnsForGridView();
    }

    /**
     * Колокнки для ГридВьюв
     * @return array
     */
    abstract protected function _getColumnsForGridView();

    /**
     * Сохранения привязки
     * @param $uo1 int
     * @param $course object|array
     * @return bool
     */
    public function saveLinkCourse($uo1, $course)
    {
        $model = DispDist::model()->findByPk($uo1);

        if($model==null)
        {
            $model = new DispDist();
            $model->dispdist1 = $uo1;
        }

        $model->setAttributes($this->_getParamsLink($course));

        $model->dispdist4 = Yii::app()->user->dbModel->p1;
        $model->dispdist5 = date('Y-m-d H:i:s');

        return $model->save();

    }

    /**
     * Сохранения привязки
     * @param $course object|array
     * @return array
     */
    abstract protected function _getParamsLink($course);

    /**
     * Валидировать email (есть ли пользователь стаким email)
     * @param string $email
     * @return bool
     */
    public function validateEmail($email)
    {
        return $this->_validateEmail($email);
    }

    /**
     * Валидировать email
     * @param string $email
     * @return bool
     */
    abstract protected function _validateEmail($email);

    /**
     * @return string
     */
    public function getNameIdFiled(){
        return self::ID_FIELD_NAME;
    }

    /**
     * Записать студента на курс по дисциплине
     * @param St $st
     * @param array $dispInfo
     * @return array
     */
    abstract public function subscribeToCourse($st, $dispInfo);

    /**
     * Записать студентов на курс по дисциплине
     * @param St[] $students
     * @param array $dispInfo
     * @return array
     */
    abstract public function subscribeStudentsToCourse($students, $dispInfo);

    /**
     * Записать студентов на курс по дисциплине
     * @param St[] $students
     * @param array $dispInfo
     * @return array
     */
    abstract public function unsubscribeStudentsToCourse($students, $dispInfo);

    /**
     * Записать студента на курс
     * @param Stdist $stDist
     * @param string $courseId
     * @return array
     */
    abstract protected function _subscribeToCourse($stDist, $courseId);

    /**
     * Записать студента на курс по дисциплине
     * @param St $st
     * @param array $dispInfo
     * @return array
     */
    abstract public function unsubscribeToCourse($st, $dispInfo);

    /**
     * Записать студента на курс
     * @param Stdist $stDist
     * @param string $courseId
     * @return array
     */
    abstract protected function _unsubscribeToCourse($stDist, $courseId);

    /**
     * Отправка соощения об записи/віпеске на курс
     * @param $email string email student
     * @param $subject string
     * @param $mailParams array
     * @param $pattern string
     * @return array
     */
    public function sendMail($email, $subject, $mailParams, $pattern){
        return Controller::mailByTemplate($email, $subject, $pattern, $mailParams);
    }

    /**
     * Создать или удалить запись-лог о записи на дисциплину
     * @param $uo1 int
     * @param $st1 int
     * @param bool $isSub запись или выписка
     * @return bool
     */
    public function stDistSub($uo1, $st1, $isSub = true){
        try {
            if ($isSub) {
                $model = Stdistsub::model()->findByAttributes(array(
                    'stdistsub1' => $st1,
                    'stdistsub2' => $uo1
                ));
                if(!empty($model))
                    return true;
                $model = new Stdistsub();
                $model->stdistsub1 = $st1;
                $model->stdistsub2 = $uo1;
                if(!$model->save()) {
                    $errors = $model->getErrors();
                    if (!empty($errors))
                        throw new Exception(implode(';', $errors));
                    return false;
                }
                else
                    return true;
            } else {
                Stdistsub::model()->deleteAllByAttributes(array(
                    'stdistsub1' => $st1,
                    'stdistsub2' => $uo1
                ));
                return true;
            }
        }catch (Exception $error){
            //var_dump($error->getMessage());
            return false;
        }
    }

    /**
     * Загрузить оценки с дист. образования
     */
    public function uploadMarks($uo1, $gr1, $year, $sem){

        $uo = Uo::model()->findByPk($uo1);
        if(empty($uo))
            throw new Exception('Ошибочный код дисциплины учебного плана!');

        $gr = Gr::model()->findByPk($gr1);
        if(empty($gr))
            throw new Exception('Ошибочный код группы!');

        $sem7 = Yii::app()->db->createCommand(<<<SQL
                  select
                   first 1 sem7
                    from sem
                       inner join sg on (sem.sem2 = sg.sg1)
                       inner join gr on (sg.sg1 = gr.gr2)
                    WHERE gr1=:GR1 and sem3=:YEAR and sem5=:SEM
			
SQL
            )->queryScalar(
            array(
                ':GR1' => $gr1,
                ':YEAR' => $year,
                ':SEM' => $sem
            ));

        $vvmp = Vvmp::model()->findByAttributes(
            array(
                'vvmp3' => $uo->uo3,
                'vvmp4' => $sem7,
                'vvmp5' => $uo->uo4,
                'vvmp25' => $gr->gr2
                //'vvmp6' => 0
            )
        );

        if(empty($vvmp))
            return array(false, 'Модуль не создан, обратитесь в деканат!');

        $vedomost = null;

        try {
            $vedomost = new DistVedomost($uo1, $gr1, $vvmp->vvmp6);

            $vedomost = $this->_getMarks($vedomost);
        }catch (Exception $error){
            return array(false, $error->getMessage());
        }

        $students = St::model()->getStudentsOfGroupForDistEducation($gr1);

        if(empty($students))
            return array(false, 'Не найдены студенты в группе АСУ');

        $f2 = Yii::app()->db->createCommand(<<<SQL
          select 
               f2
            from gr
               inner join sg on (gr.gr2 = sg.sg1)
               inner join sp on (sg.sg2 = sp.sp1)
               inner join f on (sp.sp5 = f.f1)
          where gr1=:GR1
SQL
        )->queryScalar(array(
            ':GR1' => $gr1
        ));

        $selectVmpv1 = <<<SQL
          SELECT vmpv1 from vmpv WHERE vmpv2=:VVMP1 and vmpv6 is NULL and vmpv7=:GR1
SQL;

        $vmpv1 = Yii::app()->db->createCommand( $selectVmpv1)->queryScalar(array(
            ':VVMP1' => $vvmp->vvmp1,
            ':GR1' => $gr1
        ));

        $isOld = true;

        if(empty($vmpv1)) {

            $isOld = false;

            $transaction = Yii::app()->db->beginTransaction();
            $insertVmpv = <<<SQL
                INSERT into vmpv(vmpv1,vmpv2,vmpv3,vmpv4,vmpv5,vmpv6,vmpv7,vmpv8,vmpv9,vmpv10,vmpv11) 
                  VALUES (
                    :VMPV1,
                    :VMPV2,
                    :VMPV3,
                    :VMPV4,
                    :VMPV5,
                    null,
                    :VMPV7,
                    :VMPV8,
                    :VMPV9,
                    0,
                    :VMPV11
                );
SQL;
            try {
                $vmpv1 = Yii::app()->db->createCommand("select first 1 id1 from pr1('vmpv1','vmpv') left join vmpv on (vmpv1=id1) where vmpv1 is null")->queryScalar();
                $currentDate = date('Y-m-d H:i:s');
                Yii::app()->db->createCommand($insertVmpv)->queryScalar(array(
                    ':VMPV1' => $vmpv1,
                    ':VMPV2' => $vvmp->vvmp1,
                    ':VMPV3' => '-',
                    ':VMPV7' => $gr1,
                    ':VMPV11' => Yii::app()->user->dbModel->p1,
                    ':VMPV4' => $currentDate,
                    ':VMPV5' => $currentDate,
                    ':VMPV8' => $currentDate,
                    ':VMPV9' => $currentDate
                ));

                $transaction->commit();
            } catch (Exception $error) {
                $transaction->rollback();
                return array(false, 'Ошибка создания ведомости: ' . $error->getMessage());
            }
        }

        $transaction = Yii::app()->db->beginTransaction();

        try {

            if(!$isOld) {
                $name = sprintf('%s-%d.%d-%d', $f2, $sem7, (int)$year - 2000, $vmpv1);

                Yii::app()->db->createCommand('UPDATE vmpv set vmpv3=:VMPV3 WHERE vmpv1= :VMPV1')->execute(array(
                    ':VMPV1' => $vmpv1,
                    ':VMPV3' => $name
                ));
            }

            $marks = $vedomost->getMarks();
            //var_dump($marks);

            foreach ($students as $student) {

                $vmp = Vmp::model()->findByAttributes(array(
                    'vmp1' => $vmpv1,
                    'vmp2' => $student->st1
                ));

                //var_dump($vmp);

                if(empty($vmp)) {
                    $vmp = new Vmp();
                    $vmp->vmp1 = $vmpv1;
                    $vmp->vmp2 = $student->st1;
                }

                if(isset($marks[$student->st1])) {

                    $vmp->vmp4 = round($marks[$student->st1],2);
                    $vmp->vmp7 = round($marks[$student->st1],2);
                    //var_dump($vmp);
                }else{
                    $vmp->vmp4 = 0;
                    $vmp->vmp7 = -4;
                }

                $vmp->vmp5 = 0;
                $vmp->vmp6 = 0;
                $vmp->vmp9 = 0;
                $vmp->vmp10 = date('Y-m-d H:i:s');
                $vmp->vmp11 = 0;
                $vmp->vmp12 = Yii::app()->user->dbModel->p1;
                $vmp->vmp13 = 0;
                $vmp->vmp14 = 0;

                if(!$vmp->save()){
                    //var_dump($vmp->getErrors());
                    throw new Exception('Ошибка сохранения оценки ');
                }
            }

            $transaction->commit();

            return array(true, 'Успешно!');
        }catch (Exception $error){
            $transaction->rollback();

            return array(false, $error->getMessage());
        }
    }

    /**
     * Получить оценки
     * @param $vedomost DistVedomost
     * @return DistVedomost
     */
    protected abstract function _getMarks($vedomost);

}