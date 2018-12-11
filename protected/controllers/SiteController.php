<?php

// Grab the Apostle namespace
use Apostle\Mail;

class SiteController extends Controller
{
    public function filters()
    {
        return array(
            'accessControl',
            'checkPermissionDist + signUpDistEducation, signUpNewDistEducation, signUpOldDistEducation, loginDistEducation',
            'existDist + signUpDistEducation, signUpNewDistEducation, signUpOldDistEducation',
            'checkAcceptEmail + signUpNewDistEducation, signUpOldDistEducation'
        );
    }

    public function accessRules() {

        return array(
            array('allow',
                'actions'=>array(
                    'logout',
                    'changePassword',
                    'resendCheckEmail'
                ),
                'users'=>array('@'),
            ),
            array('allow',
                'actions'=>array(
                    'resetPassword',
                    'forgotPassword',
                    'login',
                    'registrationInternational',
                    'registration'
                ),
                'users'=>array('?'),
            ),
            array('allow',
                'actions'=>array(
                    'index',
                    'captcha',
                    'error',
                    'studentBarcode',
                    'userPhoto',
                    'iFrame',
                    'acceptEmail'
                ),
                'users'=>array('*'),
            ),
            array('allow',
                'actions' => array(
                    'signUpDistEducation',
                    'signUpNewDistEducation',
                    'signUpOldDistEducation',
                    'loginDistEducation',
                ),
                'expression' => 'Yii::app()->user->isStd',
            ),
            array('allow',
                'actions' => array(
                    'studentPassport',
                ),
                'expression' => 'Yii::app()->user->isAdmin',
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actions()
    {
        return array(
			'captcha'=>array(
					'class'=>'CCaptchaAction',
					'backColor'=>0xFFFFFF,
			),
        );
    }

    /**
     * Фильтр для акшенов с дист. образованием кроме логина
     * @param $filterChain
     * @throws CHttpException
     */
    public function filterExistDist($filterChain)
    {
        if(Yii::app()->user->dbModel->st168>0) {
            throw new CHttpException(400, tt('Пользователь уже зарегистрирован в дистанционном образовании'));
        }

        $filterChain->run();
    }

    /**
     * Фильтр для акшенов где нужно подтвреждение почты
     * @param $filterChain
     * @throws CHttpException
     */
    public function filterCheckAcceptEmail($filterChain)
    {
        if($this->universityCode == U_NMU) {
            $model = UsersEmail::model()->findByPk(Yii::app()->user->model->u4);
            //если у нас нет записи что почта подтврждена или в прцессе потвреждения
            if ($model == null) {
                throw new CHttpException(400, tt('Почта не подтверждена!'));
            }else{
                if(!empty($model->ue3)) {
                    throw new CHttpException(400, tt('Почта не подтверждена!'));
                }
            }
        }

        $filterChain->run();
    }

    /**
     * Фильтр для акшенов с дист. образованием
     * @param $filterChain
     * @throws CHttpException
     */
    public function filterCheckPermissionDist($filterChain)
    {
        //негость
        if(Yii::app()->user->isGuest){
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again. 1');
        }
        // студент
        if(!Yii::app()->user->isStd){
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again. 2');
        }
        //включена ли синхронизация с дист образованием
        $ps122 = PortalSettings::model()->getSettingFor(PortalSettings::ENABLE_DIST_EDUCATION);
        if($ps122==0){
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again. 3');
        }

        $filterChain->run();
    }

    /**
     * Регитсрация в дистанционном образовании
     * Вопрос есть уже акаунт или нет
     */
    public function actionLoginDistEducation(){

        //зарегисророван в дистанцооном образовании
        if(Yii::app()->user->dbModel->st168==0) {
            throw new CHttpException(400, tt('Пользователь не зарегистрирован в дистанционном образовании'));
        }

        $connector = SH::getDistEducationConnector(
            $this->universityCode
        );

        $connector->login(Yii::app()->user->model);
    }

    private function _needAcceptEmail(){
        //если пустая почта
        if(empty(Yii::app()->user->model->u4))
        {
            $message = tt('Для продолжения введите почту!');
            Yii::app()->user->setFlash('error', '<strong>'.tt('Внимание!').'</strong> '. $message);
            $this->redirect('index');
        }
        $model = UsersEmail::model()->findByPk(Yii::app()->user->model->u4);
        //если у нас нет записи что почта подтврждена или в прцессе потвреждения
        if($model == null){

            $model = new UsersEmail();
            $model->ue1 = Yii::app()->user->model->u4;
            $model->ue2 = Yii::app()->user->id;
            $model->generateToken();

            //ошибка сохранения записи что почта находитсья в процессе подвреждения
            if(!$model->save())
                Yii::app()->user->setFlash('error', '<strong>'.tt('Внимание!').'</strong> '. tt('Ошибка!'));
            else
            {
                $this->_sendCheckEmail($model);
            }

            $this->redirect('index');
        }else{
            if( Yii::app()->user->id != $model->ue2)
                throw new CHttpException(400, 'Некорректный пользователь.');

            //если есть запись, проверяем не пустоя ли токен, если да то почта уже подтверждена, если нет :
            if(!empty($model->ue3)) {

                Yii::app()->user->setFlash('error', '<strong>' . tt('Внимание!') . '</strong> ' . tt('Для продолжения нужно подтвредить почту, письмо было выслано на вашу почту! <a href="{link}">Отправить снова</a>', array(
                        '{link}' => Yii::app()->createAbsoluteUrl('site/resendCheckEmail', array('_token' => $model->ue3))
                    )));
                $this->redirect('index');
            }
        }
    }

    /**
     * @param $model UsersEmail
     * @throws CHttpException
     */
    public function _sendCheckEmail($model){
        $url = Yii::app()->createAbsoluteUrl('site/acceptEmail', array('_token' => $model->ue3));
        $linkText = tt('Подтвердить почту');

        $message = PortalSettings::model()->getSettingFor(PortalSettings::ACCEPT_EMAIL_DIST_EDUCATION);
        if(empty($message)){
            $message = tt('Для подтверждения почты {email} перейдите по ссылке:');
        }

        $mailParams = array(
            'email' => $model->ue1,
            'fio' => Yii::app()->user->dbModel->getShortName(),
            'link' => <<<HTML
							<a href="{$url}">{$linkText}</a>
HTML
        ,
            'username' => Yii::app()->user->model->u2
        );
        list($status, $message) = $this->mailByTemplate($model->ue1, tt('Подтверждение почты'), $message, $mailParams);
        //отправка письма на почту с токеном

        if ($status) {
            Yii::app()->user->setFlash('success', tt('Вам на почту было отправлено письмо для подтверждения! <a href="{link}">Отправить снова</a>',
                array(
                    '{link}' => Yii::app()->createAbsoluteUrl('site/resendCheckEmail', array('_token' => $model->ue3))
                )));
        } else {
            Yii::app()->user->setFlash('error', $message);
            $model->delete();
            throw new CHttpException(500, $message);
        }
    }

    /**
     * Акшен для подтверждения
     * @throws CHttpException
     */
    public function actionResendCheckEmail(){
        //ищем токен
        $token = Yii::app()->request->getParam('_token', null);

        if ($token == null) {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }

        $model = UsersEmail::model()->findByAttributes(array('ue3' => $token));
        //если у нас нет записи что почта подтврждена или в прцессе потвреждения
        if($model == null){
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }

        if($model->ue2 != Yii::app()->user->id){
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }

        if(empty($model->ue3)){
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }

        $model->generateToken();

        //ошибка сохранения записи что почта находитсья в процессе подвреждения
        if(!$model->save())
            Yii::app()->user->setFlash('error', '<strong>'.tt('Внимание!').'</strong> '. tt('Ошибка!'));
        else
        {
            $this->_sendCheckEmail($model);
        }

        $this->redirect('index');
    }
    /**
     * Акшен для подтверждения
     * @throws CHttpException
     */
    public function actionAcceptEmail(){
        //ищем токен
        $token = Yii::app()->request->getParam('_token', null);

        if ($token == null) {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        } else {

            $model = UsersEmail::model()->findByAttributes(array('ue3' => $token));
            //если у нас нет записи что почта подтврждена или в прцессе потвреждения
            if($model == null){
                throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
            }
            //если токен не валидній
            if (!$model->validateToken($token))
                throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
            else {
                $model->ue3 = '';
                if(!$model->save()){
                    Yii::app()->user->setFlash('error', '<strong>'.tt('Внимание!').'</strong> '. tt('Ошибка сохранения подтверждения почты!'));

                }else{
                    Yii::app()->user->setFlash('success', '<strong>'.tt('Внимание!').'</strong> '. tt('Почта подтверждена!'));
                }

                $this->redirect('index');
            }
        }
    }

    /**
     * Регитсрация в дистанционном образовании
     * Вопрос есть уже акаунт или нет
     */
    public function actionSignUpDistEducation(){

        if($this->universityCode == U_NMU) {
            $this->_needAcceptEmail();
        }

        $this->render('signUpDistEducation');
    }

    /**
     * Регитсрация в дистанционном образовании
     * Вопрос есть уже акаунт или нет
     */
    public function actionSignUpOldDistEducation(){

        if(in_array($this->universityCode, array(U_NMU, U_KNAME)) ){
            throw new CHttpException('Access denied');
        }
        /**
         * @var $model SingUpOldDistEducationForm
         */
        $model=SH::getSingUpOldDistEducationForm($this->universityCode);

        $className = get_class($model);

        if(!$className)
            throw new CHttpException(500,'Error...');

        $model->unsetAttributes();

        $model->email = Yii::app()->user->model->u4;

        if(isset($_POST[$className])) {

            $model->attributes=$_POST[$className];

            if($model->validate()) {

                $connector = SH::getDistEducationConnector(
                    $this->universityCode
                );

                $user = Yii::app()->user->model;

                list($result, $message) = $connector->signUpOld($user, $model->params);

                if (!$result) {
                    Yii::app()->user->setFlash('error', '<strong>'.tt('Внимание!').'</strong> '. $message);
                } else {
                    Yii::app()->user->setFlash('success','<strong>'.tt('Внимание!').'</strong> '. tt('Регистрация прошла успешно!'));
                }

                $this->redirect('index');
            }
        }

        $this->render('signUpOldDistEducation',array(
            'model'=>$model
        ));
    }

    /**
     * Регитсрация в дистанционном образовании
     */
    public function actionSignUpNewDistEducation(){
        $connector = SH::getDistEducationConnector(
            $this->universityCode
        );

        $user = Yii::app()->user->model;

        list($result, $message) = $connector->signUp(Yii::app()->user->model);

        if(!$result){
            Yii::app()->user->setFlash('error', '<strong>'.tt('Внимание!').'</strong> '. $message);
        }else{
            Yii::app()->user->setFlash('success', '<strong>'.tt('Внимание!').'</strong> '. tt('Регистрация прошла успешно!'));
        }

        $this->redirect('index');
    }

	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		if(Yii::app()->user->isPrnt)
			$this->redirect("/other/studentCard");
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
	/*public function actionContact()
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
	}*/

	private function servicesLogin(){
        if(Yii::app()->params['enableEAuth']!==true)
            return;

        $serviceName = Yii::app()->request->getQuery('service');
        if (isset($serviceName)) {
            /** @var $eauth EAuthServiceBase */
            $eauth = Yii::app()->eauth->getIdentity($serviceName);
            $eauth->redirectUrl = Yii::app()->user->returnUrl;
            $eauth->cancelUrl = $this->createAbsoluteUrl('site/login');

            try {
                if ($eauth->authenticate()) {
                    //var_dump($eauth->getIsAuthenticated(), $eauth->getAttributes());
                    $identity = new AsuEAuthUserIdentity($eauth);

                    // successful authentication
                    if ($identity->authenticate()) {

                        Yii::app()->user->login($identity);
                        $user = Users::model()->findByPk(Yii::app()->user->id);
                        $user->afterLogin();
                        UsersHistory::getNewLogin();

                        $this->redirect(array('site/index'));
                    }
                    else {
                        // save authentication error to session
                        Yii::app()->user->setFlash('error', 'Exception: error authenticate '. $identity->errorCode );

                        // close popup window and redirect to cancelUrl
                        $eauth->cancel();
                    }
                }

                // Something went wrong, redirect to login page
                $this->redirect(array('site/login'));
            }
            catch (EAuthException $e) {
                // save authentication error to session
                Yii::app()->user->setFlash('error', 'Exception: '.$e->getMessage());

                // close popup window and redirect to cancelUrl
                $eauth->redirect($eauth->getCancelUrl());
            }
        }
    }
	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
        if(!Yii::app()->user->isGuest)
            $this->redirect('index');

        $this->servicesLogin();
		/*if (! Yii::app()->request->isAjaxRequest)
            $this->redirect('index');*/

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
			if($model->validate()&&$model->login()) {
				$message = '';
				$user = Users::model()->findByPk(Yii::app()->user->id);
				$user->afterLogin();

				if($this->universityCode==U_ZSMU){
					//$sKeySettings = PortalSettings::model()->findByPk(PortalSettings::ZAP_SUPPORT_SECRET_KEY_ID);
					/*$password ='';
					if(!empty($sKeySettings))*/
					$password = crypt($model->password,$user->u9);
					$image = '<img src="http://'.UniversityCommon::ZAP_SUPPORT_HREF.'/api-login.php?email='.$user->u4.'&pass='.$password.'" style="display:none"/>';
					Yii::app()->user->setState('api-func-login', $image);
				}
                $this->redirect('index');
				//Yii::app()->end('ok');
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
						Yii::app()->user->setState('error', tt('Ошибка отправки сообщения на поодержку!'));
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
						Yii::app()->user->setState('error', tt('Ошибка отправки сообщения на поодержку!'));
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

			if((!empty($model->serial)||!empty($model->emptySerial)) && $model->validate(array('serial'))){
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
						Yii::app()->user->setState('error', tt('Ошибка отправки сообщения на поодержку!'));
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
				$key = $user->getValidationKey();
				if($user->saveAttributes(array('u10'=>$user->u10))) {

					$url = Yii::app()->createAbsoluteUrl('site/resetPassword', array('token' => $user->u10, 'key'=>$key));
					$link = tt('Востановить пароль');

					$ps86 = PortalSettings::model()->findByPk(86)->ps2;
					if(empty($ps86)) {
						$text = tt('Для востановления пароля перейдите по ссылке:');
						$message = <<<HTML
							{$text} <a href="{$url}">{$link}</a>
HTML;
					}else{
						$message = str_replace('{username}',$user->u2,$ps86);
						$message = str_replace('{link}','<a href="'.$url.'">'.$link.'</a>',$message);
					}
					list($status, $message) = $this->mailsend($model->email, tt('Забыл пароль'), $message);

					if ($status) {
						Yii::app()->user->setFlash('success', $message);
						Yii::app()->end('send');
					} else {
						Yii::app()->user->setFlash('error', $message);
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

	public function actionResetPassword($token, $key){
		try {
			$model = new ResetPasswordForm($token,'reset-password');
		} catch (Exception $e) {
			throw new CHttpException(404, '1Invalid request. Please do not repeat this request again.');
		}

		if(!$model->isValid($token))
			throw new CHttpException(404, '2Invalid request. Please do not repeat this request again.');

		if(!$model->isValidKey($key))
			throw new CHttpException(404, '3Invalid request. Please do not repeat this request again.');

		if(isset($_POST['ResetPasswordForm'])) {
			$model->attributes = $_POST['ResetPasswordForm'];

			if ($model->validate() && $model->resetPassword()) {
				Yii::app()->user->setFlash('success', tt('Новый пароль сохранен!'));

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