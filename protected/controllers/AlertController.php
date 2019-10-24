<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 14.12.2016
 * Time: 20:46
 */

class AlertController extends Controller
{
    public function beforeAction($action){
        if(!Yii::app()->user->isGuest)
            Yii::app()->user->model->saveAttributes(array(
                'u15' => date('Y-m-d H:i:s')
            ));

        return parent::beforeAction($action);
    }

    public function filters() {

        return array(
            'accessControl',
            'checkPermission + send, autocomplete, validateSend '
        );
    }

    public function accessRules() {

        return array(
            array('allow',
                'actions' => array(

                ),
                'expression' => 'Yii::app()->user->isAdmin || Yii::app()->user->isTch',
            ),
            array('allow',
                'actions' => array(
                    'index',
                    'send',
                    'validateSend',
                    'autocomplete'
                ),
                'expression' => 'Yii::app()->user->isAdmin || Yii::app()->user->isTch || Yii::app()->user->isStd',
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    public function filterCheckPermission($filterChain)
    {
        if(!Yii::app()->user->isTch)
            if(PortalSettings::model()->getSettingFor(PortalSettings::STUDENT_SEND_IN_ALERT) != 1)
                throw new CHttpException(403, tt('Доступ запрещен'));

        $filterChain->run();
    }

    /**
     * Индесная страница
     * @param $period string
     * @throws
     */
    public function actionIndex($period = Um::TIME_PERIOD_MONTH)
    {
        if(Yii::app()->user->isGuest)
            throw new CHttpException(403, tt('Доступ запрещен'));

        if(!in_array($period, array(Um::TIME_PERIOD_MONTH, Um::TIME_PERIOD_YEAR)))
            $period = Um::TIME_PERIOD_MONTH;

        $this->render('index', array(
            'model'   => Yii::app()->user->model,
            'period' => $period
        ));
    }

    public function actionValidateSend(){
        $model=new CreateMessageForm();
        $model->unsetAttributes();
        echo CActiveForm::validate($model);
        Yii::app()->end();
    }

    public function actionSend()
    {
        if(Yii::app()->request->isAjaxRequest)
            throw new CHttpException(400, 'Только POST');
        $model=new CreateMessageForm();
        $model->unsetAttributes();
        // Uncomment the following line if AJAX validation is needed
        //$this->performAjaxValidation($model);

        if(isset($_POST['CreateMessageForm']))
        {
            $model->attributes=$_POST['CreateMessageForm'];
            if($model->type != $model::TYPE_STUDENT && $model->type != $model::TYPE_TEACHER)
                $model->notification = false;

            if($model->validate())
            {
                //try {
                if ($model->save()) {
                    try {

                        Yii::app()->user->setFlash('success', 'Сообщение успешно отправлено');

                        if($model->sendMail){
                            $model->sendMails();
                        }
                    }catch (Exception $error){
                        Yii::app()->user->setFlash('error', 'Сообщение успешно отправлено, но ошибка оправки уведомления на почту: '. $error->getMessage());
                    }
                } else {
                    Yii::app()->user->setFlash('error', 'Ошибка отправления сообщения ');
                }
                /*}catch (Exception $error){
                    Yii::app()->user->setFlash('error', 'Ошибка отправления сообщения: '.$error->getMessage());
                }*/
            }else{
                $error = $model->getErrorString('</br>');
                Yii::app()->user->setFlash('error', 'Ошибка отправления сообщения: '. $error);

            }
        }
        $this->redirect(array('alert/index'), false, 301);
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='create-message-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionAutocomplete($type, $faculty)
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $query = Yii::app()->request->getParam('query', null);

        $suggestions = array();

        if($type == CreateMessageForm::TYPE_TEACHER){
            $teachers = P::model()->findUsersByNameTeacher($query);

            foreach($teachers as $tch)
            {
                $t = '';
                if ($tch['pd7'] == 1)
                    $t = 'совм.';
                elseif ($tch['pd7'] == 3 || $tch['pd7'] == 5)
                    $t = 'почас.';
                elseif ($tch['pd7'] == 4)
                    $t = 'совмещ.';

                $suggestions[] = array(
                    'value' => implode(' ', array($tch['dol2'], SH::getShortName($tch['p3'], $tch['p4'], $tch['p5']))) .' '. $t,
                    'id'    => $tch['u1']
                );
            }
        }else if ($type == CreateMessageForm::TYPE_STUDENT){
            $students = St::model()->findUsersByStudentName($query, $faculty);

            foreach($students as $st)
            {
                $suggestions[] = array(
                    'value' => strtr('{lastName} {firstName} {secondName} ({group})',
                        array(
                            '{lastName}' => $st['st2'],
                            '{firstName}' => $st['st3'],
                            '{secondName}' => $st['st4'],
                            '{group}' => Gr::model()->getGroupName($st['st20'], $st)
                        )
                    ),
                    'id'    => $st['u1']
                );
            }
        } else if($type == CreateMessageForm::TYPE_GROUP){
            $groups = Gr::model()->getGroupsForFaculty($faculty, $query);
            foreach($groups as $key=>$group)
            {
                $suggestions[] = array(
                    'value' => Gr::model()->getGroupName($group['sem4'], $group),
                    'id'    => $group['gr1']
                );
            }
        } else if($type == CreateMessageForm::TYPE_STREAM){
            $streams = Gr::model()->getStreamsForFaculty($faculty, $query);
            foreach($streams as $key=>$stream)
            {
                $suggestions[] = array(
                    'value' => $stream,
                    'id'    => $key
                );
            }
        }

        $res = array(
            'query'       => $query,
            'suggestions' => $suggestions,
        );

        Yii::app()->end(CJSON::encode($res));
    }
}