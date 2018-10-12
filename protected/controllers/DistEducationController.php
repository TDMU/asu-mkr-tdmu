<?php

class DistEducationController extends Controller
{
    public function filters() {

        return array(
            'accessControl',
            'checkPermission'
        );
    }

    public function accessRules() {

        return array(
            array('allow',
                'actions' => array(
                    'index',
                    'addLink',
                    'saveLink',
                    'removeLink',
                    'searchCourse',
                    'acceptDisp',
                    'disacceptDisp',
                    'subscription',
                    'showGroup',
                    'subscriptionGroup',
                    'subscriptionStudent',
                    'subscriptionDisp',
                    'signUpNewDistEducation',
                    'uploadMarks',
                    'subscriptionsList'
                ),
                'expression' => 'Yii::app()->user->isTch',
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    /**
     * @param $filterChain
     * @throws CHttpException
     */
    public function filterCheckPermission($filterChain)
    {
        if(!Yii::app()->user->isAdmin) {
            /**
             * @var $grants Grants
             */
            $grants = Yii::app()->user->dbModel->grants;

            if (empty($grants))
                throw new CHttpException(404, 'Invalid request. You don\'t have access to the service.');

            if ($grants->getGrantsFor(Grants::DIST_EDUCATION_ADMIN) != 1) {
                if ($grants->getGrantsFor(Grants::DIST_EDUCATION) != 1)
                    throw new CHttpException(404, 'Invalid request. You don\'t have access to the service.');
            }
        }

        $filterChain->run();
    }

    /**
     * метод записи группы
     */
    public function actionSignUpNewDistEducationGroup(){
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');


        $model = new DistEducationFilterForm(Yii::app()->user);

        if(!$model->isAdminDistEducation){
            throw new CHttpException(400, tt('Нет доступа'));
        }
        $gr1 = Yii::app()->request->getParam('gr1', null);

        $group = Gr::model()->findByPk($gr1);
        if (empty($group)) {
            throw new CHttpException(400, tt('Нет доступа'));
        }

        $connector = SH::getDistEducationConnector(
            $this->universityCode
        );

        if (empty($connector)) {
            throw new CHttpException(400, tt('Ошибка создания конектора'));
        }

        $students = St::model()->getStudentsOfGroupForDistEducation($group['gr1']);

        $success = true;
        $html = '';

        foreach ($students as $student) {
            $stDist = Stdist::model()->findByPk($student['st1']);
            if($stDist==null) {
                $user = Users::model()->findByAttributes(array(
                    'u5' => 0,
                    'u6' => $student['st1']
                ));
                if (empty($user)) {
                    $success = false;
                    continue;
                }

                list($_success, $_html) = $connector->signUp($user);
                if (!$_success)
                    $success = false;
            }else{
                $_html = 'Уже зарегистрирован';
            }

            $html.=SH::getShortName($student['st2'], $student['st3'], $student['st4']).': ' .$_html.'<br>';
        }


        $title = $group->gr3;
        $res = array(
            'error'=>!$success,
            'html' => $html,
            'title' => $group->gr3
        );

        Yii::app()->user->setFlash($success ? 'success' : 'error', '<h4>'.$title.'</h4>'.$html);

        Yii::app()->end(CJSON::encode($res));
    }
    /**
     * Регитсрация в дистанционом образовании студента
     */
    public function actionSignUpNewDistEducation(){
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $model = new DistEducationFilterForm(Yii::app()->user);

        if (! $model->isAdminDistEducation)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $st1 = Yii::app()->request->getParam('st1', null);
        $type = Yii::app()->request->getParam('type', null);

        if(empty($st1))
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');


        $st = St::model()->findByPk($st1);
        if (empty($st)) {
            throw new CHttpException(400, tt('Нет доступа3'));
        }

        $user = Users::model()->findByAttributes(array(
            'u5'=>0,
            'u6'=>$st->st1
        ));
        if (empty($user)) {
            throw new CHttpException(400, tt('Нет доступа4'));
        }


        $connector = SH::getDistEducationConnector(
            $this->universityCode
        );

        if (empty($connector)) {
            throw new CHttpException(400, tt('Ошибка создания конектора'));
        }

        if($type==1) {
            list($success, $html) = $connector->signUp($user);
        }else{
            $stDist = Stdist::model()->findByPk($st->st1);
            if($stDist==null) {
                throw new CHttpException(400, tt('Пользователь не зарегистророван в дист. образовании'));
            }else{
                $html = '';
                $success = true;
                if(!$stDist->delete()){
                    $success = false;
                    $html = 'Ошибка удаления привязки учеток пользователей';
                }else{
                    if(!$st->saveAttributes(array('st168'=>0)))
                        $success = false;
                }
            }
        }

        $res = array(
            'error'=>!$success,
            'html' => $html,
            'title' => $st->getShortName()
        );

        Yii::app()->end(CJSON::encode($res));
    }

    /**
     *
     */
	public function actionIndex()
	{
	    $model = new DistEducationFilterForm(Yii::app()->user);
        $model->unsetAttributes();

        if (isset($_GET['pageSize'])) {
            Yii::app()->user->setState('pageSize',(int)$_GET['pageSize']);
            unset($_GET['pageSize']);  // сбросим, чтобы не пересекалось с настройками пейджера
        }

	    if(isset($_REQUEST['DistEducationFilterForm'])){
            $model->attributes = $_REQUEST['DistEducationFilterForm'];

            if(!$model->validate()){
                throw new CHttpException(400, tt('Неверные параметры'));
            }
        }



	    if($model->isAdminDistEducation){
            $chairId = Yii::app()->request->getParam('chairId', null);

            $model->setChairId($chairId);
        }

		$this->render('index', array(
            'model' => $model
        ));
	}

    /**
     * Потвердить закрпеление дисциплин
     */
    public function actionAcceptDisp(){
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $model = new DistEducationFilterForm(Yii::app()->user);

        $k1 = Yii::app()->request->getParam('k1', null);

        $model->setChairId($k1);

        $error=false;
        $message = '';
        $title=tt('Подтверждение закрепления по кафедре'). ' '. $model->chair->k2;

        $kdist = $model->getKdist();

        if($kdist!=null) {
            $error = true;
            $message = tt('Уже подтверждено!');
        }
        else{
            $kdist = new Kdist();
            $kdist->kdist1=$model->chairId;
            $kdist->kdist2=Yii::app()->session['year'];
            $kdist->kdist3=Yii::app()->session['sem'];
            $kdist->kdist4=Yii::app()->user->dbModel->p1;
            $kdist->kdist5 = date('Y-m-d H:i:s');

            if($kdist->save())
            {
                $message = tt('Успешно сохранено');
            }else{
                $message = tt('Ошибка сохранния');
                $error = true;
            }
        }

        $res = array(
            'title'=>$title,
            'message'=> $message,
            'error' => $error
        );

        Yii::app()->end(CJSON::encode($res));
    }

    /**
     * Удалить закрпеление дисциплин
     */
    public function actionDisacceptDisp(){
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');
        if (! Yii::app()->user->isAdmin)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $model = new DistEducationFilterForm(Yii::app()->user);

        $k1 = Yii::app()->request->getParam('k1', null);

        $model->setChairId($k1);

        $error=false;
        $message = '';
        $title=tt('Подтверждение закрепления по кафедре'). ' '. $model->chair->k2;

        $kdist = $model->getKdist();

        if($kdist==null) {
            $error = true;
            $message = tt('Ошибка удаления!');
        }
        else{
            if($kdist->delete())
            {
                $message = tt('Успешно удалено');
            }else{
                $message = tt('Ошибка удаления!');
                $error = true;
            }
        }

        $res = array(
            'title'=>$title,
            'message'=> $message,
            'error' => $error
        );

        Yii::app()->end(CJSON::encode($res));
    }

    /**
     * Рендер формы для привязки дисциплины к дист образованию
     */
	public function actionAddLink(){
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $model = new DistEducationFilterForm(Yii::app()->user);

        $uo1 = Yii::app()->request->getParam('uo1', null);
        $k1 = Yii::app()->request->getParam('k1', null);

        $model->setChairId($k1);

        $error=false;
        $html='';
        $title=tt('Закрепление дисциплины');

        if(empty($uo1))
            $error=true;

        $connector = SH::getDistEducationConnector(
            $this->universityCode
        );

        if(empty($connector))
            $error = true;

        if(!$error)
        {
            $disp = $model->getDispInfo($uo1);

            if(empty($disp)) {
                $error = true;
            }
            else{
                /*$html = $this->renderPartial('_add_link_form', array(
                    'disp' => $disp,
                    'model'=>$model,
                    'coursesList' => $connector->getCoursesListForLisData()
                ), true);*/

                /*$searchModel = new DistEducationFilterModel();
                $searchModel->unsetAttributes();

                $searchModel->setFilters(array_keys($connector->getColumnsForGridView()));*/
                //var_dump($connector->getCoursesList());

                $html = $this->renderPartial('_add_link_form', array(
                    //'searchModel' => $searchModel,
                    'disp' => $disp,
                    'model'=>$model,
                    'connector'=>$connector,
                    'coursesList' => $connector->getCoursesList()
                    //'dataProvider' => $searchModel->getDataProvider($connector->getCoursesList()),
                ), true);
            }
        }

        $res = array(
            'title'=>$title,
            'html' => $html,
            'error' => $error
        );

        Yii::app()->end(CJSON::encode($res));
    }

    /**
     * Сохранение привязки дисциплины к дист образованию
     */
    public function actionSaveLink(){
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $model = new DistEducationFilterForm(Yii::app()->user);

        $uo1 = Yii::app()->request->getParam('uo1', null);
        $k1 = Yii::app()->request->getParam('k1', null);
        $id = Yii::app()->request->getParam('id', null);

        $title = tt('Закрепление дисциплины');
        $message = '';
        $error = false;

        if($uo1==null  || $k1==null || $id==null)
            $error = true;
        else {
            $model->setChairId($k1);

            if (empty($uo1))
                $error = true;

            $connector = SH::getDistEducationConnector(
                $this->universityCode
            );

            if (empty($connector)) {
                $error = true;
                $message = tt('Ошибка создания конектора');
            }

            if (!$error) {
                $disp = $model->getDispInfo($uo1);

                if (empty($disp)) {
                    $error = true;
                    $message = tt('Не найдена дисциплина');
                } else {
                    $course = $connector->getCourse($id);

                    if(empty($course))
                    {
                        $error = true;
                        $message = tt('Не найден курс');
                    }
                    else
                    {
                        if(!$connector->saveLinkCourse($uo1, $course)){
                            $error = true;
                            $message = tt('Ошибка сохранения привязки');
                        }
                    }
                }
            }
        }

        $res = array(
            'title' => $title,
            'message' => $message,
            'error' => $error
        );

        Yii::app()->end(CJSON::encode($res));
    }

    /**
     * Сохранение привязки дисциплины к дист образованию
     */
    public function actionRemoveLink(){
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $model = new DistEducationFilterForm(Yii::app()->user);

        $uo1 = Yii::app()->request->getParam('uo1', null);
        $k1 = Yii::app()->request->getParam('k1', null);

        $title = tt('Открепление дисциплины');
        $message = '';
        $error = false;

        if($uo1==null  || $k1==null)
            $error = true;
        else {
            $model->setChairId($k1);

            if (empty($uo1))
                $error = true;

            if (!$error) {
                $disp = $model->getDispInfo($uo1);

                if (empty($disp)) {
                    $error = true;
                    $message = tt('Не найдена дисциплина');
                } else {
                    $link = DispDist::model()->findByPk($uo1);

                    if($link==null)
                    {
                        $error = true;
                        $message = tt('Ссылка не найдена');
                    }else{
                        $error = !$link->delete();
                        if($error){
                            $message = tt('Ошибка удаления');
                        }
                    }
                }
            }
        }

        $res = array(
            'title' => $title,
            'message' => $message,
            'error' => $error
        );

        Yii::app()->end(CJSON::encode($res));
    }

    /**
     * метод для записи
     */
    public function actionSubscription(){

        $model = new DistEducationFilterForm(Yii::app()->user);
        $model->unsetAttributes();

        if(!$model->isAdminDistEducation){
            throw new CHttpException(400, tt('Нет доступа'));
        }

        if (isset($_GET['pageSize'])) {
            Yii::app()->user->setState('pageSize',(int)$_GET['pageSize']);
            unset($_GET['pageSize']);  // сбросим, чтобы не пересекалось с настройками пейджера
        }

        if(isset($_REQUEST['DistEducationFilterForm'])){
            $model->attributes = $_REQUEST['DistEducationFilterForm'];
        }

        $chairId = Yii::app()->request->getParam('chairId', null);

        $model->setChairId($chairId);


        $this->render('subscription', array(
            'model' => $model
        ));
    }

    /**
     * метод для отображения записаных студентов
     */
    public function actionSubscriptionsList(){

        $model = new DistEducationFilterForm(Yii::app()->user);
        $model->unsetAttributes();

        if (isset($_GET['pageSize'])) {
            Yii::app()->user->setState('pageSize',(int)$_GET['pageSize']);
            unset($_GET['pageSize']);  // сбросим, чтобы не пересекалось с настройками пейджера
        }

        if(isset($_REQUEST['DistEducationFilterForm'])){
            $model->attributes = $_REQUEST['DistEducationFilterForm'];

            if(!$model->validate()){
                throw new CHttpException(400, tt('Неверные параметры'));
            }
        }

        if($model->isAdminDistEducation){
            $chairId = Yii::app()->request->getParam('chairId', null);

            $model->setChairId($chairId);
        }

        $this->render('subscriptionsList', array(
            'model' => $model
        ));
    }

    /**
     * метод просмтра групп по записи
     */
    public function actionShowGroup(){

        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');


        $model = new DistEducationFilterForm(Yii::app()->user);

        if(!$model->isAdminDistEducation){
            throw new CHttpException(400, tt('Нет доступа'));
        }

        $chairId = Yii::app()->request->getParam('chairId', null);
        $gr1 = Yii::app()->request->getParam('gr1', null);
        $uo1 = Yii::app()->request->getParam('uo1', null);

        $model->setChairId($chairId);

        $disp = $model->getDispInfo($uo1);

        if (empty($disp)) {
            throw new CHttpException(400, tt('Нет доступа1'));
        }

        if (empty($disp['dispdist2'])) {
            throw new CHttpException(400, tt('Нет доступа2'));
        }

        $group = $model->getGroupsByUo1($uo1, $gr1);
        if (empty($group)) {
            throw new CHttpException(400, tt('Нет доступа3'));
        }

        $html = $this->renderPartial('subscription/_group', array(
            'model' => $model,
            'uo1'=>$uo1,
            'group' => $group
        ), true);

        $res = array(
            'html' => $html,
            'title' => Gr::model()->getGroupName($group['sem4'], $group)
        );

        Yii::app()->end(CJSON::encode($res));
    }

    /*
    * метод переноса оценок
    */
    public function actionUploadMarks(){
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');


        $model = new DistEducationFilterForm(Yii::app()->user);

        if(!$model->isAdminDistEducation){
            throw new CHttpException(400, tt('Нет доступа'));
        }

        $chairId = Yii::app()->request->getParam('chairId', null);
        $gr1 = Yii::app()->request->getParam('gr1', null);
        $uo1 = Yii::app()->request->getParam('uo1', null);

        $model->setChairId($chairId);

        $disp = $model->getDispInfo($uo1);

        if (empty($disp)) {
            throw new CHttpException(400, tt('Нет доступа'));
        }

        if (empty($disp['dispdist2'])) {
            throw new CHttpException(400, tt('Нет доступа'));
        }

        $group = $model->getGroupsByUo1($uo1, $gr1);
        if (empty($group)) {
            throw new CHttpException(400, tt('Нет доступа'));
        }

        $connector = SH::getDistEducationConnector(
            $this->universityCode
        );

        if (empty($connector)) {
            throw new CHttpException(400, tt('Ошибка создания конектора'));
        }

        $grName = Gr::model()->getGroupName($group['sem4'], $group);

        $disp['gr1'] = $group['gr1'];
        $disp['grName'] = $grName;

        list($success, $html) = $connector->uploadMarks($uo1, $gr1, Yii::app()->session['year'], Yii::app()->session['sem']);

        $title = $grName;
        $res = array(
            'error'=>!$success,
            'html' => $html,
            'title' => $title
        );

        Yii::app()->user->setFlash($success ? 'success' : 'error', '<h4>'.$title.'</h4>'.$html);

        Yii::app()->end(CJSON::encode($res));
    }

    /**
     * метод записи группы
     */
    public function actionSubscriptionGroup(){
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');


        $model = new DistEducationFilterForm(Yii::app()->user);

        if(!$model->isAdminDistEducation){
            throw new CHttpException(400, tt('Нет доступа'));
        }

        $chairId = Yii::app()->request->getParam('chairId', null);
        $gr1 = Yii::app()->request->getParam('gr1', null);
        $uo1 = Yii::app()->request->getParam('uo1', null);
        $subscription = Yii::app()->request->getParam('subscription', null);

        if($subscription==null)
            throw new CHttpException(400, tt('Неверный параметры'));

        $model->setChairId($chairId);

        $disp = $model->getDispInfo($uo1);

        if (empty($disp)) {
            throw new CHttpException(400, tt('Нет доступа'));
        }

        if (empty($disp['dispdist2'])) {
            throw new CHttpException(400, tt('Нет доступа'));
        }

        $group = $model->getGroupsByUo1($uo1, $gr1);
        if (empty($group)) {
            throw new CHttpException(400, tt('Нет доступа'));
        }

        $connector = SH::getDistEducationConnector(
            $this->universityCode
        );

        if (empty($connector)) {
            throw new CHttpException(400, tt('Ошибка создания конектора'));
        }

        $grName = Gr::model()->getGroupName($group['sem4'], $group);

        $students = St::model()->getStudentsOfGroupForDistEducation($group['gr1']);

        $disp['gr1'] = $group['gr1'];
        $disp['grName'] = $grName;

        if($subscription==1) {
            list($success, $html) = $connector->subscribeStudentsToCourse($students, $disp);
        }else{
            list($success, $html) = $connector->unsubscribeStudentsToCourse($students, $disp);
        }

        $title = $grName;
        $res = array(
            'error'=>!$success,
            'html' => $html,
            'title' => $title
        );

        Yii::app()->user->setFlash($success ? 'success' : 'error', '<h4>'.$title.'</h4>'.$html);

        Yii::app()->end(CJSON::encode($res));
    }

    /**
     * метод записи всех групп по дисциплине
     */
    public function actionSubscriptionDisp(){
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');


        $model = new DistEducationFilterForm(Yii::app()->user);

        if(!$model->isAdminDistEducation){
            throw new CHttpException(400, tt('Нет доступа'));
        }

        $chairId = Yii::app()->request->getParam('chairId', null);
        //$gr1 = Yii::app()->request->getParam('gr1', null);
        $uo1 = Yii::app()->request->getParam('uo1', null);
        $subscription = Yii::app()->request->getParam('subscription', null);

        if($subscription==null)
            throw new CHttpException(400, tt('Неверный параметры'));

        $model->setChairId($chairId);

        $disp = $model->getDispInfo($uo1);

        if (empty($disp)) {
            throw new CHttpException(400, tt('Нет доступа'));
        }

        if (empty($disp['dispdist2'])) {
            throw new CHttpException(400, tt('Нет доступа'));
        }

        /*$group = $model->getGroupsByUo1($uo1, $gr1);
        if (empty($group)) {
            throw new CHttpException(400, tt('Нет доступа'));
        }*/

        $connector = SH::getDistEducationConnector(
            $this->universityCode
        );

        if (empty($connector)) {
            throw new CHttpException(400, tt('Ошибка создания конектора'));
        }

        $log = '';
        $globalResult = true;

        $groups = $model->getGroupsByUo1($uo1);
        foreach ($groups as $group) {
            $grName = Gr::model()->getGroupName($group['sem4'], $group);

            $students = St::model()->getStudentsOfGroupForDistEducation($group['gr1']);

            $disp['gr1'] = $group['gr1'];
            $disp['grName'] = $grName;

            if ($subscription == 1) {
                list($success, $html) = $connector->subscribeStudentsToCourse($students, $disp);
            } else {
                list($success, $html) = $connector->unsubscribeStudentsToCourse($students, $disp);
            }

            if(!$success)
                $globalResult = false;

            $log .= '<h5>'.$grName.'</h5>';
            $log .= $html;
            //$title = $grName;
        }
        $res = array(
            'error'=>!$globalResult,
            'html' => $log,
            'title' => $disp['d2']
        );

        Yii::app()->user->setFlash($globalResult ? 'success' : 'error', '<h4>'.$disp['d2'].'</h4>'.$log);

        Yii::app()->end(CJSON::encode($res));
    }

    /**
     * метод записи группы
     */
    public function actionSubscriptionStudent(){
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');


        $model = new DistEducationFilterForm(Yii::app()->user);

        if(!$model->isAdminDistEducation){
            throw new CHttpException(400, tt('Нет доступа 11'));
        }

        $chairId = Yii::app()->request->getParam('chairId', null);
        $st1 = Yii::app()->request->getParam('st1', null);
        $gr1 = Yii::app()->request->getParam('gr1', null);
        $uo1 = Yii::app()->request->getParam('uo1', null);
        $subscription = Yii::app()->request->getParam('subscription', null);

        if($subscription==null)
            throw new CHttpException(400, tt('Неверный параметры'));

        $model->setChairId($chairId);

        $disp = $model->getDispInfo($uo1);

        if (empty($disp)) {
            throw new CHttpException(400, tt('Нет доступа1'));
        }

        if (empty($disp['dispdist2'])) {
            throw new CHttpException(400, tt('Нет доступа2'));
        }


        $group = $model->getGroupsByUo1($uo1, $gr1);
        if (empty($group)) {
            throw new CHttpException(400, tt('Нет доступа'));
        }

        $st = St::model()->findByPk($st1);
        if (empty($st)) {
            throw new CHttpException(400, tt('Нет доступа3'));
        }

        $connector = SH::getDistEducationConnector(
            $this->universityCode
        );

        if (empty($connector)) {
            throw new CHttpException(400, tt('Ошибка создания конектора'));
        }
        
        $grName = Gr::model()->getGroupName($group['sem4'], $group);

        $disp['gr1'] = $group['gr1'];
        $disp['grName'] = $grName;


        if($subscription==1) {
            list($success, $html) = $connector->subscribeToCourse(
                $st,
                $disp
            );
        }else{
            list($success, $html) = $connector->unsubscribeToCourse(
                $st,
                $disp
            );
        }

        $res = array(
            'error'=>!$success,
            'html' => $html,
            'title' => $st->getShortName()
        );

        Yii::app()->end(CJSON::encode($res));
    }
}