<?php

// Grab the Apostle namespace
use Apostle\Mail;

class SiteController extends Controller
{
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
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
                )
            ),
			'captcha'=>array(
					'class'=>'CCaptchaAction',
					'backColor'=>0xFFFFFF,
			),
        );
    }
	
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		if(Yii::app()->user->isPrnt)
			$this->redirect("other/studentCard");
		else
			$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

    public function actionClose()
    {
        $this->layout='//layouts/clear';
        if($error=Yii::app()->errorHandler->error)
        {
            if(Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('close', $error);
        }
    }

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
          
        $serviceName = Yii::app()->request->getQuery('service');
		if (isset($serviceName)) {
			/** @var $eauth EAuthServiceBase */
			$eauth = Yii::app()->eauth->getIdentity($serviceName);
			$eauth->redirectUrl = Yii::app()->user->returnUrl;
//var_dump(Yii::app()->user);            
			$eauth->cancelUrl = $this->createAbsoluteUrl('site/login');

			try {
				if ($eauth->authenticate()) {
					//var_dump($eauth->getIsAuthenticated(), $eauth->getAttributes());
					$identity = new EAuthUserIdentity($eauth);

					// successful authentication
					if ($identity->authenticate()) {
						//Yii::app()->user->login($identity);
//var_dump($identity);                        
                        var_dump($identity->attributes);
						var_dump($identity->id, $identity->name, Yii::app()->user->id); die();  exit;

						// special redirect with closing popup window
						$eauth->redirect();
					}
					else {
						// close popup window and redirect to cancelUrl
						$eauth->cancel();
					}
				}

				// Something went wrong, redirect to login page
				$this->redirect(array('site/login'));
			}
			catch (EAuthException $e) {
				// save authentication error to session
				Yii::app()->user->setFlash('error', 'EAuthException: '.$e->getMessage());

				// close popup window and redirect to cancelUrl
				$eauth->redirect($eauth->getCancelUrl());
			}
		}
        
        if (! Yii::app()->request->isAjaxRequest)
            $this->redirect('index');
            
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login()) {
				$message = '';
				$user = Users::model()->findByPk(Yii::app()->user->id);
				switch($user->u5){
					case 0:
						$message = (isset(PortalSettings::model()->findByPk(92)->ps2)?PortalSettings::model()->findByPk(92)->ps2:'');
						break;
					case 1:
						//$message = PortalSettings::model()->findByPk(93)->ps2;
                        $message = (isset(PortalSettings::model()->findByPk(93)->ps2)?PortalSettings::model()->findByPk(93)->ps2:'');
						break;
					case 2:
						//$message = PortalSettings::model()->findByPk(94)->ps2;
                        $message = (isset(PortalSettings::model()->findByPk(94)->ps2)?PortalSettings::model()->findByPk(94)->ps2:'');
						break;
				}

				if(!empty($message))
					Yii::app()->user->setState('info_message', $message);


				if($this->universityCode==U_ZSMU){
					//$sKeySettings = PortalSettings::model()->findByPk(PortalSettings::ZAP_SUPPORT_SECRET_KEY_ID);
					/*$password ='';
					if(!empty($sKeySettings))*/
					$password = crypt($model->password,$user->u9);
					$image = '<img src="http://'.UniversityCommon::ZAP_SUPPORT_HREF.'/api-login.php?email='.$user->u4.'&pass='.$password.'" style="display:none"/>';
					Yii::app()->user->setState('api-func-login', $image);
				}

				Yii::app()->end('ok');
			}
				//$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	public function actionChangePassword()
	{
		if (! Yii::app()->request->isAjaxRequest)
			$this->redirect('index');

		if (Yii::app()->user->isGuest)
			$this->redirect('index');

		$model=Users::model()->findByPk(Yii::app()->user->id);


		if($model==null){
			throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');
		}


		$model->password = $model->u3;

		$model->scenario = "change-password";
		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='changePassword-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['Users']))
		{
			$model->u3=$_POST['Users']['u3'];
			$model->password=$_POST['Users']['password'];
			$password = $model->password;
			$model->u2=$_POST['Users']['u2'];
			$model->u4=$_POST['Users']['u4'];
			// validate user input and redirect to the previous page if valid
			if($model->save()) {
				if($this->universityCode==U_ZSMU){
					$message = '"account" : {
						"email" : "%s",
						"pass" : "%s",
						"salt" : "%s"
					}';

					$message = sprintf($message, $model->u4, $password, $model->u9);
					if(UniversityCommon::SendZapApiRequest(UniversityCommon::CHANGE_PASSWORD_TYPE, $message)){
						//Yii::app()->user->setState('info_message', $message);
					}else{
						Yii::app()->user->setState('info_message', tt('Ошибка отправки сообщения на поодержку!'));
					}
				}
				Yii::app()->end('ok');
			}
			/*else
				print_r($model->getErrors());*/
			//$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('changePassword',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		$user = Users::model()->findByPk(Yii::app()->user->id);

		if($this->universityCode==U_ZSMU&&!empty($user)){
			Yii::app()->user->logout(false);
			Yii::app()->user->setState('api-func-logout', '<img src="http://'.UniversityCommon::ZAP_SUPPORT_HREF.'/api-logout.php?email='.$user->u4.'" style="display:none"/>');
		}else{
			Yii::app()->user->logout();
		}
		$this->redirect(Yii::app()->homeUrl);
	}

    /**
     * Displays the login page
     */
    public function actionRegistration()
    {
        if (!Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

		if (PortalSettings::model()->findByPk(102)->ps2==1)
			throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $model=new RegistrationForm;

        if(isset($_POST['RegistrationForm']))
        {
            $model->attributes=$_POST['RegistrationForm'];

            if ($model->validate() && $model->register()) {
				if($this->universityCode==U_ZSMU){
					$message = '"accounts" : {
							"account": {
								"name" : "%s",
								"email" : "%s",
								"password" : "%s",
								"salt" : "%s",
								"timezone" : "Europe\/Kiev",
								"ip" : "%s",
								"language" : "%s",
								"notes" : ""
							}
					}';

					$user = Users::model()->findByAttributes(array('u4'=>$model->email));
					$message = sprintf($message, $model->getFio(), $model->email, $model->password,$user->u9,Yii::app()->request->userHostAddress, Yii::app()->language);
					if(UniversityCommon::SendZapApiRequest(UniversityCommon::REGISTER_TYPE, $message)){
						//Yii::app()->user->setState('info_message', $message);
					}else{
						Yii::app()->user->setState('info_message', tt('Ошибка отправки сообщения на поодержку!'));
					}
				}
				Yii::app()->end('registered');
			}

        }

        $this->render('registration',array('model'=>$model));
    }

	/**
	 * Displays the RegistrationInternational
	 */
	public function actionRegistrationInternational()
	{
		if (PortalSettings::model()->findByPk(98)->ps2==1)
			throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

		$model=new RegistrationInternationalForm();
		$model->scenario = 'step-1';

		if(isset($_POST['RegistrationInternationalForm']))
		{
			$model->attributes=$_POST['RegistrationInternationalForm'];

			//print_r($model);

			if(!empty($model->number)&&$model->validate(array('number'))) {
				$model->scenario = 'step-2';
			}

			if(!empty($model->serial)&&$model->validate(array('serial'))){
				$model->scenario = 'step-3';
			}

			if ($model->scenario == 'step-3'&&$model->validate() && $model->register())
			{
				if($this->universityCode==U_ZSMU){
					$message = '"accounts" : {
							"account": {
								"name" : "%s",
								"email" : "%s",
								"password" : "%s",
								"timezone" : "Europe\/Kiev",
								"ip" : "%s",
								"language" : "%s",
								"notes" : ""
							}
					}';

					$message = sprintf($message, $model->getFio(), $model->email, $model->password,Yii::app()->request->userHostAddress, Yii::app()->language);
					if(UniversityCommon::SendZapApiRequest(UniversityCommon::REGISTER_TYPE, $message)){
						//Yii::app()->user->setState('info_message', $message);
					}else{
						Yii::app()->user->setState('info_message', tt('Ошибка отправки сообщения на поодержку!'));
					}
				}
				return $this->redirect('index');
			}

		}

		$this->render('registration-international',array('model'=>$model));
	}

    public function actionForgotPassword()
    {
        if (!Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

		if (PortalSettings::model()->findByPk(103)->ps2==1)
			throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $model=new ForgotPasswordForm;

        if(isset($_POST['ForgotPasswordForm']))
        {
            $model->attributes=$_POST['ForgotPasswordForm'];

            if ($model->validate()) {

				$user = Users::model()->findByAttributes(array('u4'=>$model->email));
				if($user===null)
					throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

                /*Apostle::setup("a596c9f9cb4066dd716911ef92be9bd040b0664d");
                $mail = new Mail( "forgot-password", array( "email" => $user->u4 ) );
                $mail->name = $user->name;
                $mail->url  = Yii::app()->createAbsoluteUrl('site/index');
                $mail->login    = $user->u2;
                $mail->password = $user->u3;
                $t = $mail->deliver();*/

				$user->generatePasswordResetToken();
				if($user->saveAttributes(array('u10'=>$user->u10))) {

					$url = Yii::app()->createAbsoluteUrl('site/resetPassword', array('token' => $user->u10));
					$link = tt('Востановить пароль');

					$ps86 = PortalSettings::model()->findByPk(86)->ps2;
					if(empty($ps86)) {
						$text = tt('Для востановления пароля перейдите по сслыке:');
						$message = <<<HTML
							{$text} <a href="{$url}">{$link}</a>
HTML;
					}else{
						$message = str_replace('{username}',$user->u2,$ps86);
						$message = str_replace('{link}','<a href="'.$url.'">'.$link.'</a>',$message);
					}
					list($status, $message) = $this->mailsend($model->email, tt('Забыл пароль'), $message);

					if ($status) {
						Yii::app()->user->setFlash('user', $message);
						Yii::app()->end('send');
					} else {
						Yii::app()->user->setFlash('user_error', $message);
						throw new CHttpException(500, $message);
					}
				}else
				{
					print_r($user->getErrors());
				}
            }
        }

        $this->render('forgotPassword',array('model'=>$model));
    }

	public function actionResetPassword($token){
		try {
			$model = new ResetPasswordForm($token,'reset-password');
		} catch (Exception $e) {
			throw new CHttpException(404, '1Invalid request. Please do not repeat this request again.');
		}

		if(!$model->isValid($token))
			throw new CHttpException(404, '2Invalid request. Please do not repeat this request again.');

		if(isset($_POST['ResetPasswordForm'])) {
			$model->attributes = $_POST['ResetPasswordForm'];

			if ($model->validate() && $model->resetPassword()) {
				Yii::app()->user->setFlash('user', tt('Новый пароль сохранен!'));

				return $this->redirect('index');
			}
		}

		$this->render('resetPassword',array('model'=>$model));
	}

    public function actionUserPhoto()
    {
        $id   = Yii::app()->request->getParam('_id', null);
        $type = Yii::app()->request->getParam('type', null);

        if (is_null($id) || is_null($type))
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        Users::model()->renderPhoto($id, $type);
    }

	public function actionStudentBarcode()
	{
		$id   = Yii::app()->request->getParam('_id', null);

		if (is_null($id))
			throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

		St::model()->getShortCodesImageRender($id);
	}

	public function actionStudentPassport()
	{
		if(!Yii::app()->user->isAdmin)
			throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

		$id   = Yii::app()->request->getParam('_id', null);
		$type = Yii::app()->request->getParam('type', null);

		if (is_null($id) || is_null($type))
			throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

		St::model()->renderPassport($id, $type);
	}

	public function actionIFrame($id)
	{
		$model = Pm::model()->findByPk($id);
		if ($model==null)
			throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

		if($model->pm8!=2||$model->pm7!=1)
			throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

		$this->render('iframe',array('model'=>$model));
	}
}