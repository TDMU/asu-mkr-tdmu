<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
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

    public function getPageSizeArray()
    {
        return array(5=>5,10=>10,20=>20,50=>50,100=>100);
    }

    public function init()
    {
        $ps58 = PortalSettings::model()->findByPk(58)->ps2;
        Yii::app()->params['defaultLanguage'] = $ps58;
        ELangPick::setLanguage();
        parent::init();
    }

    public function mobileCheck(){
        $detect = Yii::app()->mobileDetect;

        //$detect->isMobile();
        //$detect->isTablet();
        //$detect->isIphone();

        return $detect->isMobile()||$detect->isTablet();
    }

    public function beforeAction($action)
    {
        if($this->checkLoginUser()) {


            Yii::app()->user->setFlash(
                'warning',
                '<strong>'.tt('Внимание!').'</strong> '.tt('Ошибка сессии, вы разлогинены!')
            );

            $this->refresh();
            //return false;
        }

        $this->checkBlockUser($action);

        $this->checkClosePortal($action);

        $this->checkCloseChair($action);

        $this->processAccess($action);

        $this->processYearAndSem();

        $this->processDate1AndDate2();

        $this->processDateLesson();

        $this->mobileCheck();

        $this->setCode();

        return parent::beforeAction($action);
    }

    /**
     * проверка пользователя
     * @return bool если тру значит нужно разлогинить
     */
    private function checkLoginUser(){
        if(Yii::app()->user->isGuest)
            return false;

        $logout = false;

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

    private function checkBlockUser($action){

        $enable_close=true;

        if(Yii::app()->controller->id=='site'&&($action->id=='index'||$action->id=='logout'||$action->id=='error'))
            $enable_close=false;

        if(Yii::app()->user->isBlock&&$enable_close)
            throw new CHttpException(403, tt('Доступ закрыт! Учетная запись заблокирована!'));
    }

    private function processAccess($action)
    {

        if (! SH::checkServiceFor(MENU_ELEMENT_VISIBLE, Yii::app()->controller->id, $action->id, true))
            throw new CHttpException(500, tt('Сервис временно недоступен!'));

        if (! SH::checkServiceFor(MENU_ELEMENT_NEED_AUTH, Yii::app()->controller->id, $action->id, true))
            if (Yii::app()->user->isGuest)
                throw new CHttpException(500, tt('Сервис доступен только для авторизированных пользователей!'));

        if (! SH::checkServiceFor(MENU_ELEMENT_VISIBLE, Yii::app()->controller->id, $action->id, true))
            throw new CHttpException(404, tt('Сервис закрыт!'));
    }

    private function checkCloseChair($action)
    {
        if(Yii::app()->user->isTch&&Yii::app()->controller->id=='journal')
        {
            $today = date('d.m.Y 00:00');
            $sql = <<<SQL
            SELECT PD4
            FROM P
                INNER JOIN PD ON (P1=PD2)
            WHERE P1 = :P1 and PD28 in (0,2,5,9) and PD3=0 and (PD13 IS NULL or PD13>'{$today}')
            group by PD4
SQL;
            $command = Yii::app()->db->createCommand($sql);
            $command->bindValue(':P1', Yii::app()->user->dbModel->p1);
            $chairs = $command->queryAll();
            $close = false;
            $text = PortalSettings::model()->findByPk(43)->ps2;
            if(empty($text))
                $text = tt('Сервис закрыт!');
            foreach($chairs as $chair) {
                $close = Kcp::model()->findByAttributes(array('kcp2' => $chair['pd4']))!=null;
                if($close){
                    throw new CHttpException(403,$text);
                    break;
                }
            }
        }
    }

    private function checkClosePortal($action)
    {
        $enable_close=true;
        if(Yii::app()->user->isAdmin)
            $enable_close=false;
        if(Yii::app()->controller->id=='site'&&$action->id!='index')
            $enable_close=false;

        $close=PortalSettings::model()->findByPk(38)->ps2;
        $text_close=PortalSettings::model()->findByPk(39)->ps2;
        if(empty($text_close))
            $text_close=tt('Портал закрыт на тех. обслуживание');
        if($close&&$enable_close)
        {
            Yii::app()->setComponents(array(
                'errorHandler'=>array(
                    'errorAction'=>'site/close',
                ),
            ));
            throw new CHttpException(403,$text_close);
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
            $date2 = date('d.m.Y', strtotime('+8 week', strtotime($date1)));


        $datetime1 = new DateTime($date1);
        $datetime2 = new DateTime($date2);
        $interval = $datetime1->diff($datetime2);

        if ($interval->days >= 150)
            $date2 = date('d.m.Y', strtotime('+8 week', strtotime($date1)));

        Yii::app()->session['date2'] = $date2;
    }

    /*public function mailsend($to,$from,$subject,$message){
        $mail=Yii::app()->Smtpmail;
        $mail->SetFrom($from, 'From Name');
        $mail->Subject    = $subject;
        $mail->MsgHTML($message);
        $mail->AddAddress($to, "");
        if(!$mail->Send()) {
            return array(false, "Mailer Error: " . $mail->ErrorInfo);
        }else {
            return array(true,  "Message sent!");
        }
    }*/

    public function mailsend($to,$subject,$message,$file = null){
        return self::mail($to,$subject,$message,$file);
    }

    public static function mail($to,$subject,$message,$file = null){
        $mail=Yii::app()->Smtpmail;
        $mail->SetFrom($mail->Username,$_SERVER['HTTP_HOST']);
        $mail->Subject = $subject;
        $mail->MsgHTML($message);
        $mail->AddAddress($to, "");
        $mail->CharSet = "UTF-8";
        if($file !== null){
            $path= $file->tempName;
            $name=$file->name;
            $mail->AddAttachment($path,$name);
        }

        if(!$mail->Send()) {
            $result = array(false, "Mailer Error: " . $mail->ErrorInfo);
        }else {
            $result = array(true,  "Message sent!");
        }
        $mail->ClearAddresses();
        return $result;
    }

    private function setCode(){
        $this->_universityCode=SH::getUniversityCod();
    }
    /*Код вуза*/
    private $_universityCode;

    public function getUniversityCode(){
        return $this->_universityCode;
    }

    //public function setUniversityCode($value){
        //$this->_universityCode = $value;
    //}
}