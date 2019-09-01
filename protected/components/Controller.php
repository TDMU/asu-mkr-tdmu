<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 * @property $universityCode int
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();

    public $pageHeader = '';

    public function init()
    {
        parent::init();
    }

    public function beforeAction($action)
    {
        if($this->checkLoginUser()) {


            Yii::app()->user->setFlash(
                'warning',
                '<strong>'.tt('Внимание!').'</strong> '.tt('Ошибка сессии, вы разлогинены!')
            );

            $this->refresh();
        }

        $this->checkBlockUser($action);

        $this->processAccess($action);

        $this->processYearAndSem();

        $this->processDate1AndDate2();

        $this->processDateLesson();

        return parent::beforeAction($action);
    }

    /**
     * проверка пользователя
     * @return bool если тру значит нужно разлогинить
     */
    private function checkLoginUser(){
        if(Yii::app()->user->isGuest)
            return false;

        $user = Users::model()->findByPk(Yii::app()->user->id);
        /**
         * @var $user Users
         */
        if($user==null)
            $logout = true;
        else{
            $logout = !$user->validateLogin();
        }

        if($logout)
        {
            Yii::app()->user->logout();
            Yii::app()->session->destroy();
            Yii::app()->request->cookies->clear();
            Yii::app()->session->open();
        }

        return $logout;
    }

    /**
     * Проверка пользователя на блокировку
     * @param $action
     * @throws CHttpException
     */
    private function checkBlockUser($action){

        $enable_close=true;

        if(Yii::app()->controller->id=='site'&&($action->id=='index'||$action->id=='logout'||$action->id=='error'))
            $enable_close=false;
        if(!Yii::app()->user->isAdmin) {
            if (!Yii::app()->user->isGuest && !Yii::app()->user->isBlock) {
                if (Yii::app()->user->dbModel->checkBlocked()) {
                    throw new CHttpException(403, tt('Доступ закрыт! Учетная запись заблокирована!'));
                }
            }
        }

        if(Yii::app()->user->isBlock&&$enable_close)
            throw new CHttpException(403, tt('Доступ закрыт! Учетная запись заблокирована!'));
    }

    private function processAccess($action)
    {

        if (! SH::checkServiceFor(MENU_ELEMENT_VISIBLE, Yii::app()->controller->id, $action->id, true))
            throw new CHttpException(403, tt('Сервис временно недоступен!'));

        if (! SH::checkServiceFor(MENU_ELEMENT_NEED_AUTH, Yii::app()->controller->id, $action->id, true))
            if (Yii::app()->user->isGuest)
                throw new CHttpException(403, tt('Сервис доступен только для авторизированных пользователей!'));
            else{
                //TODO: Сделать проверку на доступ пользователя по роли

                switch (Yii::app()->user->model->u5){
                    case Users::ST1:
                        if (! SH::checkServiceFor(MENU_ELEMENT_AUTH_STUDENT, Yii::app()->controller->id, $action->id, true))
                            throw new CHttpException(403, tt('Сервис недоступен для студентов!'));
                        break;
                    case Users::P1:
                        if (! SH::checkServiceFor(MENU_ELEMENT_AUTH_TEACHER, Yii::app()->controller->id, $action->id, true))
                            throw new CHttpException(403, tt('Сервис недоступен для преподавателей!'));
                        break;
                    case Users::PRNT:
                        if (! SH::checkServiceFor(MENU_ELEMENT_AUTH_PARENT, Yii::app()->controller->id, $action->id, true))
                            throw new CHttpException(403, tt('Сервис недоступен для родителей!'));
                        break;
                    case Users::DOCTOR:
                        if (! SH::checkServiceFor(MENU_ELEMENT_AUTH_DOCTOR, Yii::app()->controller->id, $action->id, true))
                            throw new CHttpException(403, tt('Сервис недоступен для врачей!'));
                        break;
                }
            }
    }

    private function processYearAndSem()
    {
        $arr = ShortCodes::getCurrentYearAndSem();
        $year = Yii::app()->request->getParam('year', null);
        if ($year === null)
            $year = Yii::app()->session['year'];
        if ($year === null)
            $year = $arr[0];

        Yii::app()->session['year'] = $year;

        $sem = Yii::app()->request->getParam('sem', null);

        if ($sem === null)
            $sem = Yii::app()->session['sem'];
        if ($sem === null)
            $sem = $arr[1];

        Yii::app()->session['sem']  = $sem;
    }

    private function processDateLesson()
    {
        $date1 = isset($_REQUEST['TimeTableForm']['dateLesson']) ? $_REQUEST['TimeTableForm']['dateLesson'] : null;
        if ($date1 === null)
            $date1 = Yii::app()->session['dateLesson'];
        if ($date1 === null)
            $date1 = date('d.m.Y');

        Yii::app()->session['dateLesson'] = $date1;
    }

    private function processDate1AndDate2()
    {
        $date1 = isset($_REQUEST['TimeTableForm']['date1']) ? $_REQUEST['TimeTableForm']['date1'] : null;
        if ($date1 === null)
            $date1 = Yii::app()->session['date1'];
        if ($date1 === null)
            $date1 = date('d.m.Y');

        Yii::app()->session['date1'] = $date1;


        $date2 = isset($_REQUEST['TimeTableForm']['date2']) ? $_REQUEST['TimeTableForm']['date2'] : null;
        if ($date2 === null)
            $date2 = Yii::app()->session['date2'];
        if ($date2 === null)
            $date2 = date('d.m.Y', strtotime('+20 week', strtotime($date1)));


        $datetime1 = new DateTime($date1);
        $datetime2 = new DateTime($date2);
        $interval = $datetime1->diff($datetime2);

        if ($interval->days >= 150)
            $date2 = date('d.m.Y', strtotime('+20 week', strtotime($date1)));

        Yii::app()->session['date2'] = $date2;
    }

    /**
     * @param $to
     * @param $subject
     * @param $template
     * @param array $params
     * @param null $file
     * @return array
     */
    public function sendMailByTemplate($to,$subject,$template, $params = array(),$file = null){
        return self::mailByTemplate($to,$subject,$template, $params,$file);
    }
    /**
     * @param $to
     * @param $subject
     * @param $template
     * @param array $params
     * @param null $file
     * @return array
     */
    public static function mailByTemplate($to,$subject,$template, $params = array(),$file = null){
        $message = $template;

        if(!empty($params)){
            foreach ($params as $key=>$param){
                $message = str_replace('{'.$key.'}', $param, $message);
            }
        }

        return self::mail($to,$subject,$message,$file);
    }

    /**
     * @param $to string|array
     * @param $subject
     * @param $message
     * @param null $file
     * @return array
     * @throws phpmailerException
     */
    public function mailsend($to,$subject,$message,$file = null){
        return self::mail($to,$subject,$message,$file);
    }

    /**
     * @param $to string|array
     * @param $subject
     * @param $message
     * @param null $file
     * @return array
     * @throws phpmailerException
     */
    public static function mail($to,$subject,$message,$file = null){
        /**
         * @var PHPMailer $mail
         */
        $mail=Yii::app()->Smtpmail;
        $mail->SetFrom($mail->Username,$_SERVER['HTTP_HOST']);
        $mail->Subject = $subject;
        $mail->MsgHTML($message);
        if(is_array($to)){
            foreach ($to as $email=>$name) {
                $mail->AddAddress($email, $name);
            }
        }else
            $mail->AddAddress($to, "");
        $mail->CharSet = "UTF-8";
        if($file !== null){
            $path= $file->tempName;
            $name=$file->name;
            $mail->AddAttachment($path,$name);
        }

        if(!$mail->Send()) {
            $result = array(false, "Ошибка отправки Еmail: " . $mail->ErrorInfo);
        }else {
            $result = array(true,  "Email отправлено!");
        }
        $mail->ClearAddresses();
        return $result;
    }

    /**
     * @return int
     */
    public function getUniversityCode(){
        return Yii::app()->core->universityCode;
    }
}