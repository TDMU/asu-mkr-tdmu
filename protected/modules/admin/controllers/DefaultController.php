<?php

class DefaultController extends AdminController
{
    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'connector' => array(
                'class' => 'ext.elFinder.ElFinderConnectorAction',
                'settings' => array(
                    'root' => Yii::getPathOfAlias('webroot') . '/images/uploads/',
                    'URL' => Yii::app()->request->baseUrl . '/images/uploads/',
                    'rootAlias' => 'Home',
                    'mimeDetect' => 'none',
                    'uploadAllow'=>array('doc', 'xls', 'ppt', 'pps', 'pdf', 'bmp','jpg','jpeg','gif','png'),
                    'uploadDeny'=>array('php', 'exe', 'js', 'sh', 'pdf', 'pl','rb','java','py','sql')
                )
            ),
            'captcha'=>array(
                'class'=>'CCaptchaAction',
                'backColor'=>0xFFFFFF,
            ),
        );
    }

    public function actionSecurity()
    {
        $settings = Yii::app()->request->getParam('settings', array());

        foreach ($settings as $key => $value) {
            PortalSettings::model()
                ->findByPk($key)
                ->saveAttributes(array(
                    'ps2' => $value
                ));
        }

        $this->render('security');
    }

    public function actionSt165($id)
    {
        $model = St::model()->findByPk($id);

        if(empty($model))
            throw new CHttpException(404,'The requested page does not exist.');

        /*if (!isset($_POST["St"])) {
            if (isset(Yii::app()->session["St"])){
                $_POST["St"]=Yii::app()->session["St"];
            }
        }
        else{
            Yii::app()->session["St"]=$_POST["St"];
        }*/

        if(isset($_POST['St'])) {
            $model->st165 = $_POST['St']['st165'];
            $model->saveAttributes(array(
                'st165'=>$model->st165
            ));

            $this->redirect(array('students'));
        }

        $this->render('st165',array('model'=>$model));
    }

    public function actionGenerateUser()
    {
        $model = new GenerateUserForm();
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['pageSize'])) {
            Yii::app()->user->setState('pageSize',(int)$_GET['pageSize']);
            unset($_GET['pageSize']);  // сбросим, чтобы не пересекалось с настройками пейджера
        }
        if(isset($_GET['GenerateUserForm']))
            $model->attributes=$_GET['GenerateUserForm'];

        $this->render('generateUser',array(
            'model'=>$model
        ));
    }

    public function actionGenerateUserExcel()
    {
        $model = new GenerateUserForm();
        $model->unsetAttributes();  // clear any default values
        if(isset($_POST['GenerateUserForm']))
            $model->attributes=$_POST['GenerateUserForm'];

        //var_dump($model->users);

        if(empty($model->users))
            throw new CHttpException(400,'Invalid request. Empty params.');

        $users = explode(',',$model->users);

        Yii::import('ext.phpexcel.XPHPExcel');
        $objPHPExcel= XPHPExcel::createPHPExcel();
        $objPHPExcel->getProperties()->setCreator("ACY")
            ->setLastModifiedBy("ACY ".date('Y-m-d H-i'))
            ->setTitle("GENERATE_USER ".date('Y-m-d H-i'))
            ->setSubject("GENERATE_USER ".date('Y-m-d H-i'))
            ->setDescription("GENERATE_USER document, generated using ACY Portal. ".date('Y-m-d H:i:'))
            ->setKeywords("")
            ->setCategory("Result file");
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet=$objPHPExcel->getActiveSheet();

        $sheet->setCellValueByColumnAndRow(0,1,tt('тип'));
        $sheet->setCellValueByColumnAndRow(1,1,tt('ФИО'));
        $sheet->setCellValueByColumnAndRow(2,1,tt('Дата рождения'));
        $sheet->setCellValueByColumnAndRow(3,1,tt('id'));
        $sheet->setCellValueByColumnAndRow(4,1,tt('логин'));
        $sheet->setCellValueByColumnAndRow(5,1,tt('пароль'));

        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(18);
        $sheet->getColumnDimension('D')->setWidth(18);
        $sheet->getColumnDimension('E')->setWidth(18);
        $sheet->getColumnDimension('F')->setWidth(18);

        $i = 2;

        foreach($users as $user){
            if(empty($user))
                continue;


            list($id,$type) = explode('-',$user);

            if(!in_array($type, array(0,1,2))){
                $sheet->mergeCellsByColumnAndRow(0, $i, 4, $i)->setCellValueByColumnAndRow(0, $i,'Не верный тип '.$type);
                continue;
            }

            $_card = null;
            $name = $bDate = '';
            $typeName = GenerateUserForm::getType($type);

            if($type==0||$type==2){
                /* @var $_card St*/
                $_card = St::model()->findByPk($id);
                if(!empty($_card)) {
                    //$name = SH::getShortName($_card->st2, $_card->st3, $_card->st4);
                    $name = $_card->st2 .' '. $_card->st3 .' '. $_card->st4;
                    $bDate = $_card->st7;;
                }
            }
            if($type==1){
                /* @var $_card P*/
                $_card = P::model()->findByPk($id);
                if(!empty($_card)) {
                    //$name = SH::getShortName($_card->p3, $_card->p4, $_card->p5);
                    $name = $_card->p3 .' '. $_card->p4 .' '. $_card->p5;
                    $bDate = $_card->p9;
                }
            }
            if(empty($_card)){
                $sheet->mergeCellsByColumnAndRow(0, $i, 4, $i)->setCellValueByColumnAndRow(0, $i,'Не найдена карточка '.$typeName.' '.$id);
                continue;
            }

            $count = Users::model()->countByAttributes(array('u5'=>$type, 'u6'=>$id));
            if($count>0){
                //уже есть зарегистрированные пользователи
                $sheet->mergeCellsByColumnAndRow(0, $i, 4, $i)->setCellValueByColumnAndRow(0, $i,'уже есть зарегистрированные пользователи '.$typeName.' '.$name.' '.$bDate);
                continue;
            }

            $username = 'user'.($id+100000000).$type;
            $password = bin2hex(openssl_random_pseudo_bytes(5));
            $model = new Users;
            $model->u1 = new CDbExpression('GEN_ID(GEN_USERS, 1)');
            $model->u2 = $username;
            $model->u3 = $password;
            $model->u4 = '';
            $model->u5 = $type;
            $model->u6 = $id;
            if($model->save(false)){
                $sheet->setCellValueByColumnAndRow(0,$i,$typeName);
                $sheet->setCellValueByColumnAndRow(1,$i,$name);
                $sheet->setCellValueByColumnAndRow(2,$i,$bDate);
                $sheet->setCellValueByColumnAndRow(3,$i,$id);
                $sheet->setCellValueByColumnAndRow(4,$i,$username);
                $sheet->setCellValueByColumnAndRow(5,$i,$password);
            }else{
                //ошибка сохранения
                $sheet->mergeCellsByColumnAndRow(0, $i, 4, $i)->setCellValueByColumnAndRow(0, $i,'Ошибка сохранения '.$typeName.' '.$name.' '.$bDate);
                continue;
            }
            $i++;
        }

        $sheet->getStyleByColumnAndRow(0,1,4,$i-1)->getBorders()->getAllBorders()->applyFromArray(array('style'=>PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => '000000')));

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a clientâ€™s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="ACY_GENERATE_USER_'.date('Y-m-d H-i').'.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

    public function actionDeleteUser($id)
    {
        if(Yii::app()->request->isPostRequest)
        {
            // we only allow deletion via POST request
            $model=Users::model()->findByPk($id);
            if($model===null)
                throw new CHttpException(404,'The requested page does not exist.');
            $model->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if(!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        }
        else
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    }

    public function actionEnter($id){
        $user = Users::model()->findByPk($id);
        if($user === null){
            throw new CHttpException(404,'The requested page does not exist.');
        }

        //Yii::app()->user->logout();

        $identity = new CUserIdentity($user->u2, 'passwords are broken');
        Yii::app()->user->login($identity);
        Yii::app()->user->id = $user->u1;
        $user->afterLogin();

        UsersHistory::getNewLogin();

        $this->redirect(array('/site/index'));
    }

    public function actionStudentCard()
    {
        $settings = Yii::app()->request->getParam('settings', array());

        foreach ($settings as $key => $value) {
            PortalSettings::model()
                ->findByPk($key)
                ->saveAttributes(array(
                    'ps2' => $value
                ));
        }

        $this->render('studentCard');
    }

    public function actionRating()
    {
        $settings = Yii::app()->request->getParam('settings', array());

        foreach ($settings as $key => $value) {
            PortalSettings::model()
                ->findByPk($key)
                ->saveAttributes(array(
                    'ps2' => $value
                ));
        }

        $this->render('rating');
    }
	
	public function actionMail()
    {
        $file = YiiBase::getPathOfAlias('application.config').'/mail.inc';
        $content = file_get_contents($file);
        $arr = unserialize(base64_decode($content));
        $model = new ConfigMailForm();
        $model->setAttributes($arr);

        if (isset($_POST['ConfigMailForm']))
        {
            $config = array(
                //'Class'=>'application.extensions.smtpmail.PHPMailer',
                'Host'=>$_POST['ConfigMailForm']['Host'],
                'Username'=>$_POST['ConfigMailForm']['Username'],
                'Password'=>$_POST['ConfigMailForm']['Password'],
                'Mailer'=>$_POST['ConfigMailForm']['Mailer'],
                'Port'=>$_POST['ConfigMailForm']['Port'],
                'SMTPSecure'=>$_POST['ConfigMailForm']['SMTPSecure'],
                //'SMTPAuth'=>true,
            );
            $model->setAttributes($config);
            if($model->validate())
            {
                $str = base64_encode(serialize($config));
                if(file_put_contents($file, $str))
                    Yii::app()->user->setFlash('config',tt("Настройки почты сохранены!"));
                else
                    Yii::app()->user->setFlash('config_error',tt("Ошибка! Настройки почты не сохранены!"));

            }
        }

        $this->render('mail',array('model'=>$model));
    }
	
    public function actionCloseChair()
    {
        $model = new Kcp();
        $model->unsetAttributes();
        if (isset($_GET['pageSize'])) {
            Yii::app()->user->setState('pageSize',(int)$_GET['pageSize']);
            unset($_GET['pageSize']);  // сбросим, чтобы не пересекалось с настройками пейджера
        }
        if (isset($_REQUEST['Kcp']))
            $model->attributes = $_REQUEST['Kcp'];

        $this->render('closeChair', array(
            'model' => $model,
        ));
    }

    public function actionUserHistory()
    {
        /*if (!isset($_SERVER['HTTP_REFERER'])or(!strpos($_SERVER['HTTP_REFERER'], 'userHistory'))) //change _ControllerName_ to your controller page
        {
            Yii::app()->user->setState('SearchParamsUH', null);
            Yii::app()->user->setState('CurrentPageUH', null);
        }*/
        $model = new UsersHistory();
        $model->unsetAttributes();
        if (isset($_GET['pageSize'])) {
            Yii::app()->user->setState('pageSize',(int)$_GET['pageSize']);
            unset($_GET['pageSize']);  // сбросим, чтобы не пересекалось с настройками пейджера
        }

        if (isset($_REQUEST['UsersHistory']))
        {
            $model->attributes = $_REQUEST['UsersHistory'];
            Yii::app()->user->setState('SearchParamsUH', $_REQUEST['UsersHistory']);
            Yii::app()->user->setState('CurrentPageUH', null);
        }
        else
        {
            $searchParams = Yii::app()->user->getState('SearchParamsUH');
            if ( isset($searchParams) )
            {
                $model->attributes = $searchParams;
            }
        }

        /*if (isset($_GET['UsersHistory_page']))
        {
            Yii::app()->user->setState('CurrentPageUH', $_GET['UsersHistory_page']);
        }
        else
        {
            $page = Yii::app()->user->getState('CurrentPageUH');
            if ( isset($page) )
            {
                $_GET['UsersHistory_page'] = $page;
            }
        }*/

        /*if (isset($_REQUEST['UsersHistory']))
            $model->attributes = $_REQUEST['UsersHistory'];*/

        $this->render('userHistory', array(
            'model' => $model,
        ));
    }

    public function actionUserHistoryExcel()
    {
        $model = new UsersHistory();
        $model->unsetAttributes();  // clear any default values

        $searchParams = Yii::app()->user->getState('SearchParamsUH');
        if ( isset($searchParams) )
        {
            $model->attributes = $searchParams;
        }

        $dataProvider = $model->search();

        Yii::import('ext.phpexcel.XPHPExcel');
        $objPHPExcel= XPHPExcel::createPHPExcel();
        $objPHPExcel->getProperties()->setCreator("ACY")
            ->setLastModifiedBy("ACY ".date('Y-m-d H-i'))
            ->setTitle("USER_HISTORY ".date('Y-m-d H-i'))
            ->setSubject("USER_HISTORY ".date('Y-m-d H-i'))
            ->setDescription("USER_HISTORY document, generated using ACY Portal. ".date('Y-m-d H:i:'))
            ->setKeywords("")
            ->setCategory("Result file");
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet=$objPHPExcel->getActiveSheet();

        $sheet->setCellValue('A1', '#');
        $sheet->setCellValue('B1', UsersHistory::model()->getAttributeLabel('name'));
        $sheet->setCellValue('C1', UsersHistory::model()->getAttributeLabel('type'));
        $sheet->setCellValue('D1', UsersHistory::model()->getAttributeLabel('adm'));
        $sheet->setCellValue('E1', UsersHistory::model()->getAttributeLabel('uh3'));
        $sheet->setCellValue('F1', UsersHistory::model()->getAttributeLabel('uh4'));
        $sheet->setCellValue('G1', UsersHistory::model()->getAttributeLabel('uh5'));
        $sheet->setCellValue('H1', UsersHistory::model()->getAttributeLabel('uh6'));

        $i = 2;
        $dataProvider->pagination=false;
        foreach($dataProvider->getData(true) as $data){
            $sheet->setCellValueByColumnAndRow(0,$i,$i-1);
            $sheet->setCellValueByColumnAndRow(1,$i,($data->type==1)?$data->getTchName():$data->getStdName());
            $sheet->setCellValueByColumnAndRow(2,$i,$data->getType());
            $sheet->setCellValueByColumnAndRow(3,$i,$data->getAdminType());
            $sheet->setCellValueByColumnAndRow(4,$i,$data->getDeviceType());
            $sheet->setCellValueByColumnAndRow(5,$i,$data->uh4);
            $sheet->setCellValueByColumnAndRow(6,$i,$data->uh5);
            $sheet->setCellValueByColumnAndRow(7,$i,$data->uh6);
            $i++;
        }

        $sheet->getStyleByColumnAndRow(0,1,7,$i-1)->getBorders()->getAllBorders()->applyFromArray(array('style'=>PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => '000000')));

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a clientâ€™s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="ACY_USER_HISTORY_'.date('Y-m-d H-i').'.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

    public function actionDeleteUserHistory($id)
    {
        if(Yii::app()->request->isPostRequest)
        {
            // we only allow deletion via POST request
            $model = UsersHistory::model()->findByPk($id);
            $model->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            //if(!isset($_GET['ajax']))
                $this->redirect(array('userHistory'));
        }
        else
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    }

    public function actionCreateCloseChair()
    {
        $model=new Kcp();
        $model->unsetAttributes();
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Kcp']))
        {
            $model->attributes=$_POST['Kcp'];
            $model->kcp1 = $model->getMax()+1;
            if($model->save())
                $this->redirect(array('closeChair'));
            print_r($model->getErrors());
        }else
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    }

    public function actionDeleteCloseChair($id)
    {
        if(Yii::app()->request->isPostRequest)
        {
            // we only allow deletion via POST request
            $model = Kcp::model()->findByPk($id);
            $model->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if(!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('closeChair'));
        }
        else
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    }

	public function actionTeachers()
	{
       $chairId = Yii::app()->request->getParam('chairId', null);

        /*if (!isset($_SERVER['HTTP_REFERER'])or(!strpos($_SERVER['HTTP_REFERER'], 'teachers'))) //change _ControllerName_ to your controller page
        {
            Yii::app()->user->setState('SearchParamsP', null);
            Yii::app()->user->setState('CurrentPageP', null);
        }*/

        $model = new P;
        $model->unsetAttributes();
        if (isset($_GET['pageSize'])) {
            Yii::app()->user->setState('pageSize',(int)$_GET['pageSize']);
            unset($_GET['pageSize']);  // сбросим, чтобы не пересекалось с настройками пейджера
        }

        if (isset($_REQUEST['P']))
        {
            $model->attributes = $_REQUEST['P'];
            Yii::app()->user->setState('SearchParamsPAdmin', $_REQUEST['P']);
        }
        else
        {
            $searchParams = Yii::app()->user->getState('SearchParamsPAdmin');
            if ( isset($searchParams) )
            {
                $model->attributes = $searchParams;
            }
        }

        //$page = null;
        /*if (isset($_REQUEST['P_page']))
        {
            Yii::app()->user->setState('CurrentPageP', $_REQUEST['P_page']-1);
            $page = $_REQUEST['P_page'];
        }
        else
        {
            $page = Yii::app()->user->getState('CurrentPageP');
            //print_r($page);
            if ( isset($page) )
            {
                $_REQUEST['P_page'] = $page;
            }
        }*/

        if (isset($_REQUEST['P_page']))
        {
            Yii::app()->user->setState('CurrentPageP',$_REQUEST['P_page']-1);
        } else
        {
            if (Yii::app()->user->hasState('P_page'))
            {
                $_REQUEST['P_page'] = Yii::app()->user->getState('CurrentPageP')+1;
            }
        }

        $this->render('teachers', array(
            'model' => $model,
            'chairId' => $chairId,
            //'page'=>$page
        ));
	}

    public function actionAdmin()
    {
        $model = new Users();
        $model->unsetAttributes();
        if (isset($_GET['pageSize'])) {
            Yii::app()->user->setState('pageSize',(int)$_GET['pageSize']);
            unset($_GET['pageSize']);  // сбросим, чтобы не пересекалось с настройками пейджера
        }
        if (isset($_POST['Users']))
            $model->attributes = $_POST['Users'];

        $model->u7=1;
        $this->render('admin/admin', array(
            'model' => $model,
        ));
    }

    public function actionAdminCreate()
    {
        $model=new Users('admin-create');
        $model->unsetAttributes();

        if(isset($_POST['Users']))
        {

            $model->attributes=$_POST['Users'];
            $model->u1=0;
            //$model->u1=new CDbExpression('GEN_ID(GEN_USERS, 1)');
            $model->u7=1;
            //$model->u6=0;
            $model->u5=1;
            if($model->validate())
            {
                $model->u1=new CDbExpression('GEN_ID(GEN_USERS, 1)');
                if($model->save(false))
                    $this->redirect(array('admin'));
            }

        }

        $this->render('admin/create',array(
            'model'=>$model,
        ));
    }

    public function actionAdminUpdate($id)
    {
        $model=$this->loadAdminModel($id);
        $model->scenario='admin-update';
        $model->password=$model->u3;

        if(isset($_POST['Users']))
        {
            $model->attributes=$_POST['Users'];
            $model->u7=1;
            //$model->u6=0;
            $model->u5=1;
            if($model->save())
                $this->redirect(array('admin'));
        }


        $this->render('admin/update',array(
            'model'=>$model,
        ));
    }

    public function actionAdminDelete($id)
    {
        if(Yii::app()->request->isPostRequest)
        {
            if(Yii::app()->user->id==$id)
                throw new CHttpException(400,'Вы не можете удалить пользователя под которым авторизировались.');
            // we only allow deletion via POST request
            $this->loadAdminModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if(!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
        else
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    }

    public function loadAdminModel($id)
    {
        $model=Users::model()->findByAttributes(array('u1'=>$id,'u7'=>'1'));
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    public function actionStudents()
    {
        $model = new St;
        $model->unsetAttributes();
        if (isset($_GET['pageSize'])) {
            Yii::app()->user->setState('pageSize',(int)$_GET['pageSize']);
            unset($_GET['pageSize']);  // сбросим, чтобы не пересекалось с настройками пейджера
        }

        if (isset($_REQUEST['St']))
        {
            $model->attributes = $_REQUEST['St'];
            Yii::app()->user->setState('SearchParamsStAdmin', $_REQUEST['St']);
        }
        else
        {
            $searchParams = Yii::app()->user->getState('SearchParamsStAdmin');
            if ( isset($searchParams) )
            {
                $model->attributes = $searchParams;
            }
        }

        if (isset($_REQUEST['St_page']))
        {
            Yii::app()->user->setState('CurrentPageSt',$_REQUEST['St_page']-1);
        } else
        {
            if (Yii::app()->user->hasState('St_page'))
            {
                $_REQUEST['St_page'] = Yii::app()->user->getState('CurrentPageSt')+1;
            }
        }

        $this->render('students', array(
            'model' => $model,
        ));
    }

    public function actionParents()
    {
        $model = new St;
        $model->unsetAttributes();

        if (isset($_REQUEST['St']))
            $model->attributes = $_REQUEST['St'];

        $this->render('parents', array(
            'model' => $model,
        ));
    }

    public function actionSettingsPortal()
    {
        $settings = Yii::app()->request->getParam('settings', array());

        foreach ($settings as $key => $value) {
            /*if ($key == 38)
                $value = intval($value);*/
            PortalSettings::model()
                ->findByPk($key)
                ->saveAttributes(array(
                    'ps2' => $value
                ));
        }

        $this->render('portal_settings', array());
    }
	
    public function actionSettings()
    {
        $file = YiiBase::getPathOfAlias('application.config').'/params.inc';
        $content = file_get_contents($file);
        $arr = unserialize(base64_decode($content));
        $model = new ConfigForm();
        $model->setAttributes($arr);
		if (isset($_POST['ConfigForm']))
		{
			$config = array(
				//'attendanceStatistic'=>$_POST['ConfigForm']['attendanceStatistic'],
				'timeTable'=>$_POST['ConfigForm']['timeTable'],
				'fixedCountLesson'=>$_POST['ConfigForm']['fixedCountLesson'],
				'countLesson'=>$_POST['ConfigForm']['countLesson'],
				'analytics'=>$_POST['ConfigForm']['analytics'],
                'analyticsYandex'=>$_POST['ConfigForm']['analyticsYandex'],
				'top1'=>$_POST['ConfigForm']['top1'],
				'top2'=>$_POST['ConfigForm']['top2'],
                'banner'=>$_POST['ConfigForm']['banner'],
                'month'=>$_POST['ConfigForm']['month'],
                'login-key'=>$_POST['ConfigForm']['loginKey'],
			);
			$str = base64_encode(serialize($config));
			$errors=!file_put_contents($file, $str);
			if(!$errors)
				Yii::app()->user->setFlash('config', tt('Новые настройки сохранены!'));
			else
				Yii::app()->user->setFlash('config_error', tt('Ошибка! Новые настройки не сохранены!'));
			$model->setAttributes($config);
		}
        $this->render('settings',array('model'=>$model));
    }

    public function actionStGrants($id)
    {
        if (empty($id))
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $type = 0; // student
        $user = $this->loadUsersModel($type, $id);

        $user->scenario='admin-update';
        $user->password=$user->u3;

        if (isset($_REQUEST['Users'])) {
            $user->attributes = $_REQUEST['Users'];
            $user->save();
        }

        $this->render('stGrants', array(
            'user'  => $user
        ));
    }

    public function actionPGrants($id)
    {
        if (empty($id))
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $model = $this->loadGrantsModel($id);
		$model->scenario = 'admin-teachers';
        if (isset($_REQUEST['Grants'])) {
            $model->attributes = $_REQUEST['Grants'];
            $model->save();
        }

        $type = 1; // teacher
        $user = $this->loadUsersModel($type, $model->grants2);

        $user->scenario='admin-update';
        $user->password=$user->u3;

        if (isset($_REQUEST['Users'])) {
            $user->attributes = $_REQUEST['Users'];
            if($user->save())
                $this->redirect(array('teachers'));
        }

        $this->render('pGrants', array(
            'model' => $model,
            'user'  => $user
        ));
    }

    public function actionPrntGrants($id)
    {
        if (empty($id))
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $type = 2; // parent
        $user = $this->loadUsersModel($type, $id);

        $user->scenario='admin-update';
        $user->password=$user->u3;

        if (isset($_REQUEST['Users'])) {
            $user->attributes = $_REQUEST['Users'];
            $user->save();
        }

        $this->render('prntGrants', array(
            'user'  => $user
        ));
    }

    public function loadGrantsModel($id)
    {
        $model = Grants::model()->findByAttributes(array(
            'grants2' => $id,
        ));

        if (empty($model)) {
            $model = new Grants();
            $model->grants1 = new CDbExpression('GEN_ID(GEN_GRANTS, 1)');
            $model->grants2 = $id;
			$model->grants7 = 0;
            $model->save(false);
        }

        return $model;
    }

    public function loadUsersModel($type, $id)
    {
        $user = Users::model()->findByAttributes(array(
            'u5' => $type,  // teacher || student || parents
            'u6' => $id     // p1 || st1
        ));

        if (empty($user)) {
            $user = new Users();
            $user->u1 = new CDbExpression('GEN_ID(GEN_USERS, 1)');
            $user->u2 = '';
            $user->u3 = '';
            $user->u4 = '';
            $user->u5 = $type;
            $user->u6 = $id;
            $user->u7 = 0;
            $user->save(false);

            $user->scenario = 'create';
        }

        return $user;
    }

    public function actionJournal()
    {
        $settings = Yii::app()->request->getParam('settings', array());

        foreach ($settings as $key => $value) {

            if ($key == 27)
                $value = intval($value);

            PortalSettings::model()
                ->findByPk($key)
                ->saveAttributes(array(
                    'ps2' => $value
                ));
        }


        $this->render('journal', array(
        ));
    }

    public function actionTimeTable()
    {
        $settings = Yii::app()->request->getParam('settings', array());

        foreach ($settings as $key => $value) {

            PortalSettings::model()
                ->findByPk($key)
                ->saveAttributes(array(
                    'ps2' => $value
                ));
        }


        $this->render('timeTable', array(
        ));
    }

    public function actionList()
    {
        $settings = Yii::app()->request->getParam('settings', array());

        foreach ($settings as $key => $value) {

            PortalSettings::model()
                ->findByPk($key)
                ->saveAttributes(array(
                    'ps2' => $value
                ));
        }


        $this->render('list', array(
        ));
    }

    public function actionModules()
    {
        $settings = Yii::app()->request->getParam('settings', array());
        print_r($settings);
        foreach ($settings as $key => $value) {
            PortalSettings::model()
                ->findByPk($key)
                ->saveAttributes(array(
                    'ps2' => $value
                ));
        }

        $this->render('modules', array(
        ));
    }

    public function actionEntrance()
    {
        $settings = Yii::app()->request->getParam('settings', array());

        foreach ($settings as $key => $value) {
            PortalSettings::model()
                ->findByPk($key)
                ->saveAttributes(array(
                    'ps2' => $value
                ));
        }

        $this->render('entrance', array(
        ));
    }

    public function actionMenu()
    {
        $webroot = Yii::getPathOfAlias('application');
        $file = $webroot . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'menu.txt';

        if (isset($_REQUEST['menu']))
            file_put_contents($file, $_REQUEST['menu']);

        $settings = file_get_contents($file);

        $this->render('menu', array(
            'settings' => $settings
        ));
    }

    public function actionEmployment()
    {
        $settings = Yii::app()->request->getParam('settings', array());

        foreach ($settings as $key => $value) {
            PortalSettings::model()
                ->findByPk($key)
                ->saveAttributes(array(
                    'ps2' => $value
                ));
        }

        $this->render('employment', array(
        ));
    }
}