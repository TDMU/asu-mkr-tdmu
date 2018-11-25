<?php
//require_once (dirname(__FILE__) . '..'.DS.'..'.DS.'..'.DS.'..'.DS.'vendor'.DS.'autoload.php');

class DefaultController extends AdminController
{
    private static $ukrainianToEnglishRules = [
        'А' => 'A',
        'Б' => 'B',
        'В' => 'V',
        'Г' => 'G',
        'Ґ' => 'G',
        'Д' => 'D',
        'Е' => 'E',
        'Є' => 'E',
        'Ж' => 'J',
        'З' => 'Z',
        'И' => 'Y',
        'І' => 'I',
        'Ї' => 'Yi',
        'Й' => 'J',
        'К' => 'K',
        'Л' => 'L',
        'М' => 'M',
        'Н' => 'N',
        'О' => 'O',
        'П' => 'P',
        'Р' => 'R',
        'С' => 'S',
        'Т' => 'T',
        'У' => 'U',
        'Ф' => 'F',
        'Х' => 'H',
        'Ц' => 'Ts',
        'Ч' => 'Ch',
        'Ш' => 'Sh',
        'Щ' => 'Shch',
        'Ь' => '',
        'Ю' => 'Yu',
        'Я' => 'Ya',
        'а' => 'a',
        'б' => 'b',
        'в' => 'v',
        'г' => 'g',
        'ґ' => 'g',
        'д' => 'd',
        'е' => 'e',
        'є' => 'e',
        'ж' => 'j',
        'з' => 'z',
        'и' => 'y',
        'і' => 'i',
        'ї' => 'yi',
        'й' => 'j',
        'к' => 'k',
        'л' => 'l',
        'м' => 'm',
        'н' => 'n',
        'о' => 'o',
        'п' => 'p',
        'р' => 'r',
        'с' => 's',
        'т' => 't',
        'у' => 'u',
        'ф' => 'f',
        'х' => 'h',
        'ц' => 'ts',
        'ч' => 'ch',
        'ш' => 'sh',
        'щ' => 'shch',
        'ь'  => '',
        'ю' => 'yu',
        'я' => 'ya',
        '\'' => ''
    ];
    
/**
 * Returns an authorized API client (based on Service Account).
 * @return Google_Client the authorized client object
 */
protected function getServiceClient()
{
    $client = new Google_Client();
    //prepare service account credentials
    //$client_secret_file = YiiBase::getPathOfAlias('application.config').DIRECTORY_SEPARATOR.'phpdirectoryapi-719704fe21c9.json';
    $client_secret_file = YiiBase::getPathOfAlias('application.config').DIRECTORY_SEPARATOR.'phpdirectoryapi-serviceaccount.json';    
    putenv('GOOGLE_APPLICATION_CREDENTIALS='.$client_secret_file);
    if (file_exists($client_secret_file)) {
        // set the location manually
        $client->setAuthConfig($client_secret_file);
    } elseif (getenv('GOOGLE_APPLICATION_CREDENTIALS')) {
        // use the application default credentials
        $client->useApplicationDefaultCredentials();
    } else {
        echo missingServiceAccountDetailsWarning();
        return;
    }
    $client->setApplicationName('G Suite Directory API PHP Quickstart-service account');
    $client->setScopes(Google_Service_Directory::ADMIN_DIRECTORY_USER);
    $client->setSubject('admin@tdmu.edu.ua');
    return $client;

}

/**
 * Expands the home directory alias '~' to the full path.
 * @param string $path the path to expand.
 * @return string the expanded path.
 */
protected function expandHomeDirectory($path)
{
    $homeDirectory = getenv('HOME');
    if (empty($homeDirectory)) {
        $homeDirectory = getenv('HOMEDRIVE') . getenv('HOMEPATH');
    }
    return str_replace('~', realpath($homeDirectory), $path);
}

    public function beforeAction($action)
    {
        if(!Yii::app()->user->isAdmin)
            throw new CHttpException(403, 'Forbidden');

        return parent::beforeAction($action);
    }

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
        
        $model->createGoogle=1;     //TDMU - forced - always "ON"
        
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

    public function actionGenerateUserExcel($createGogle=false)
    {
        $model = new GenerateUserForm();
        $model->unsetAttributes();  // clear any default values
        if(isset($_POST['GenerateUserForm']))
            $model->attributes=$_POST['GenerateUserForm'];

        if(empty($model->users))
            throw new CHttpException(400,'Invalid request. Empty params.');

        $users = explode(',',$model->users);
        $createGogle = (boolean)$model->createGoogle; //TDMU-specific
        
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
        
        if ($createGogle) { //TDMU-specific
            $sheet->setCellValueByColumnAndRow(6,1,tt('Google account created'));
            $sheet->getColumnDimension('G')->setWidth(28);
        }

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
                    $bDate = $_card->st7;
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

            $username = $this->create_Google_username($_card, $type); //TDMU-specific
            //$username = 'user'.($id+100000000).$type; //origin
            $password = bin2hex(openssl_random_pseudo_bytes(5));
            $model = new Users;
            $model->u1 = new CDbExpression('GEN_ID(GEN_USERS, 1)');
            $model->u2 = $username;
            $model->u3 = $password;
            $model->password = $password; //for GSuiteUpdateUser compability
            $model->u4 = $username.'@tdmu.edu.ua'; //TDMU-specific
            //$model->u4 = '';//origin
            $model->u5 = $type;
            $model->u6 = $id;
            if($model->save(false)){
                $sheet->setCellValueByColumnAndRow(0,$i,$typeName);
                $sheet->setCellValueByColumnAndRow(1,$i,$name);
                $sheet->setCellValueByColumnAndRow(2,$i,$bDate);
                $sheet->setCellValueByColumnAndRow(3,$i,$id);
                $sheet->setCellValueByColumnAndRow(4,$i,$username);
                $sheet->setCellValueByColumnAndRow(5,$i,$password);
                //creating a Google Directory useer account
                if (($createGogle==true)&&($type == 0||$type == 1)) { //not for parents!
                    unset($gResults);
                    $gResults = $this->GSuiteUpdateUser($model, $type);
                    if ($gResults[0] !== true) {  //error
                        $sheet->setCellValueByColumnAndRow(6,$i,$gResults[1]);
                    } else {  //success
                        $sheet->setCellValueByColumnAndRow(6,$i,$gResults[1]->creationTime);
                    }
                }
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
    
    /*
     * TDMU - create user name for GMail
     */    
    private function create_Google_username($_card, $type){
        if($type==1){
            $tmpFname = $this->_create_username($this->_name_cleanup($_card->p4));
            $tmpMname = $this->_create_username($this->_name_cleanup($_card->p5));
            $tmpLastName = $this->_create_username($this->_name_cleanup($_card->p3));
        } else {
            if ($_card->st32 == 804){ //ukrainians
                $tmpFname = $this->_create_username($this->_name_cleanup($_card->st3));
                $tmpMname = $this->_create_username($this->_name_cleanup($_card->st4));
                $tmpLastName = $this->_create_username($this->_name_cleanup($_card->st2));
            } else {                            //foreign
                $tmpFname = $this->_create_username(($_card->st75!=null?$this->_name_cleanup($_card->st75):$this->_name_cleanup($_card->st3)));
                $tmpMname = $this->_create_username(($_card->st76!=null?$this->_name_cleanup($_card->st76):$this->_name_cleanup($_card->st4)));
                $tmpLastName = $this->_create_username(($_card->st74!=null?$this->_name_cleanup($_card->st74):$this->_name_cleanup($_card->st2)));
            }
        }
        if (strlen($tmpLastName)<2) {
            if (strlen($tmpFname)>2){
                $tmpLastName = $tmpFname;
            } elseif (strlen($tmpMname)>2) {
                $tmpLastName = $tmpMname;
            } else {
                $tmpLastName = 'nolastname';
            }
        }
        if (strlen($tmpFname)<2) {
            if (strlen($tmpFname)>2){
                $tmpFname = substr($tmpLastName,0,3);
            } elseif (strlen($tmpMname)>2) {
                $tmpFname = substr($tmpMname,0,3);
            } else {
                $tmpFname = 'nfn';
            }
        } else {
            $tmpFname = substr($tmpFname,0,3);
        }
        if (strlen($tmpMname)<2) {
            if (strlen($tmpFname)>2){
                $tmpMname = substr($tmpLastName,0,3);
            } elseif (strlen($tmpFname)>2) {
                $tmpMname = substr($tmpFname,0,3);
            } else {
                $tmpMname = 'nmn';
            }
        } else {
            $tmpMname = substr($tmpMname,0,3);
        }
        $username = $tmpLastName."_".$tmpFname.$tmpMname;
        $username = str_replace(" ","",$username); //finally: remove all possible ocasional spaces
        return $username; //TDMU-ASU-specific
    }
    
    /*
     * TDMU - create user name
     */
    private function _create_username($ukrainianText){
            $transliteratedText = '';
            if (mb_strlen($ukrainianText) > 0) {
                $transliteratedText = str_replace(
                    array_keys(self::$ukrainianToEnglishRules),
                    array_values(self::$ukrainianToEnglishRules),
                    $ukrainianText
                );
            }
            return strtolower($transliteratedText);
    }
    /*
     * TDMU - clean-up string (especially - for names clean-up)
     */
    private function _name_cleanup($str){
        //if ($str[0]==' '){$str = substr($str, 1);}  
        $str = trim($str); //Remove all leading and trailing spaces 
        $str = str_replace("(","",$str);
        $str = str_replace(")","",$str);
        $str = str_replace("-","",$str);
        $str = str_replace("'","",$str);
        $str = str_replace(":","",$str);
        $str = str_replace(".","",$str);
        $str = str_replace("`","",$str);
        $str = str_replace("’","",$str);
        $str = str_replace("\"","",$str);
        return $str;
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
                    Yii::app()->user->setFlash('success',tt("Настройки почты сохранены!"));
                else
                    Yii::app()->user->setFlash('error',tt("Ошибка! Настройки почты не сохранены!"));

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

    /**
     * Рендер списка врачей
     * @throws CHttpException
     */
    public function actionDoctors()
    {
        if($this->universityCode!=U_XNMU)
            throw new CHttpException(403, 'Access denied');

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

        if (isset($_REQUEST['D_page']))
        {
            Yii::app()->user->setState('CurrentPageD',$_REQUEST['D_page']-1);
        } else
        {
            if (Yii::app()->user->hasState('D_page'))
            {
                $_REQUEST['D_page'] = Yii::app()->user->getState('CurrentPageD')+1;
            }
        }

        $this->render('doctors', array(
            'model' => $model,
        ));
    }

    /**
     * Список администраторов
     */
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
            $model->u13 = '';
            $model->u10 = '';
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
		    $model->attributes = $_POST['ConfigForm'];
		    if($model->validate()) {
                $config = array(
                    //'attendanceStatistic'=>$_POST['ConfigForm']['attendanceStatistic'],
                    'timeTable' => $_POST['ConfigForm']['timeTable'],
                    'fixedCountLesson' => $_POST['ConfigForm']['fixedCountLesson'],
                    'countLesson' => $_POST['ConfigForm']['countLesson'],
                    'analytics' => $_POST['ConfigForm']['analytics'],
                    'analyticsYandex' => $_POST['ConfigForm']['analyticsYandex'],
                    'top1' => $_POST['ConfigForm']['top1'],
                    'top2' => $_POST['ConfigForm']['top2'],
                    'banner' => $_POST['ConfigForm']['banner'],
                    'month' => $_POST['ConfigForm']['month'],
                    'login-key' => $_POST['ConfigForm']['loginKey'],
                );
                //var_dump($_POST);
                //var_dump($_FILES);
                //var_dump($_POST['ConfigForm']['favicon']);

                if (!empty($_FILES['ConfigForm'])) {
                    $model->favicon = $_FILES['ConfigForm'];
                    //var_dump($model->favicon);
                    if($model->favicon!=null && !empty($model->favicon['name']['favicon'])) {
                        $path = Yii::getPathOfAlias('webroot') . '/favicon.ico';
                        $model->favicon = CUploadedFile::getInstance($model, 'favicon');
                        if (!$model->favicon->saveAs($path)) {
                            throw new CException('Ошибка сохранениея ' . $path);
                        }
                    }
                }

                $str = base64_encode(serialize($config));
                $errors = !file_put_contents($file, $str);
                if (!$errors)
                    Yii::app()->user->setFlash('success', tt('Новые настройки сохранены!'));
                else
                    Yii::app()->user->setFlash('error', tt('Ошибка! Новые настройки не сохранены!'));
            }

		}
        $this->render('settings',array('model'=>$model));
    }

    public function actionStGrants($id)
    {
        if (empty($id))
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        if (isset($_POST['cancel'])) {
            $this->redirect(array('students'));
        }

        $type = 0; // student
        $user = $this->loadUsersModel($type, $id);

        $user->scenario='admin-update';
        $user->password=$user->u3;

        if (isset($_REQUEST['Users'])) {
            $user->attributes = $_REQUEST['Users'];
            $res = $user->save();
            if ($res) {
                Yii::app()->user->setFlash('success', "User's data has been saved!");
                if (($_REQUEST['Users']['updategoogle']==true)&&($type == 0||$type == 1)) { //not for parents!
                    $gResults = $this->GSuiteUpdateUser($user, $type);
                    if ($gResults[0] !== true) {
                        $user->addError('updategoogle', $gResults[1]);
                    } else {
                        Yii::app()->user->setFlash('success', "Google Directory User's account has been updated/created!");
                    }
                }
            }
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
        
        if (isset($_POST['cancel'])) {
            $this->redirect(array('teachers'));
        }
        
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

            $user->u7 = isset($_REQUEST['role']) ? (int)$_REQUEST['role'] : 0;
            
            //if($user->save())
            //    $this->redirect(array('teachers')); //original
            $res = $user->save();
            if($res) {
                Yii::app()->user->setFlash('success', "User's data has been saved!");
                if (($_REQUEST['Users']['updategoogle']==true)&&($type == 0||$type == 1)) { //not for parents!
                    $gResults = $this->GSuiteUpdateUser($user, $type);
                    if ($gResults[0] !== true) {
                        $user->addError('updategoogle', $gResults[1]);
                    } else {
                        Yii::app()->user->setFlash('success', "Google Directory User's account has been updated/created!");
                    }
                }
                //$this->redirect(array('teachers')); //TDMU - stay on teache's page
            }
        }

        $this->render('pGrants', array(
            'model' => $model,
            'user'  => $user
        ));
    }

    public function actionDGrants($id)
    {
        if (empty($id))
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $model = $this->loadGrantsModel($id);
        $model->scenario = 'admin-doctor';
        if (isset($_REQUEST['Grants'])) {
            $model->attributes = $_REQUEST['Grants'];
            $model->save();
        }

        $type = 3; // doctors
        $user = $this->loadUsersModel($type, $id);

        $user->scenario='admin-update';
        $user->password=$user->u3;

        if (isset($_REQUEST['Users'])) {
            $user->attributes = $_REQUEST['Users'];

            $user->u7 = isset($_REQUEST['role']) ? (int)$_REQUEST['role'] : 0;

            if($user->save())
                $this->redirect(array('doctors'));
        }

        $this->render('dGrants', array(
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
            
            //TDMU-specific - prepare user by template
            unset($username);
            unset($_card);
            if($type==0||$type==2){
                $_card = St::model()->findByPk($id);
            } elseif($type==1){
                $_card = P::model()->findByPk($id);
            }
            if(!empty($_card)) {
                $username = $this->create_Google_username($_card, $type);
                $password = bin2hex(openssl_random_pseudo_bytes(5));
                $user->u2 = $username;
                $user->u3 = $password;
                $user->u4 = $username.'@tdmu.edu.ua'; //TDMU-specific
            } else {
                $user->u2 = '';
                $user->u3 = '';
                $user->u4 = '';
            }
            //back to original            
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

        if (isset($_REQUEST['menu'])) {
            if(file_put_contents($file, $_REQUEST['menu']))
                Yii::app()->user->setFlash('success',tt('Настройки меню сохранены!'));
            else
                Yii::app()->user->setFlash('error',tt('Ошибка! Настройки меню не сохранены!'));
        }

        $settings = file_get_contents($file);

        $this->render('menu', array(
            'settings' => $settings
        ));
    }

    public function actionSeo()
    {
        $webroot = Yii::getPathOfAlias('application');
        $file = $webroot . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'seo.txt';

        //var_dump($_REQUEST);

        if (isset($_REQUEST['seo']))
            file_put_contents($file, $_REQUEST['seo']);

        if(file_exists($file))
            $settings = file_get_contents($file);
        else
            $settings = '';

        //var_dump($settings);

        $this->render('seo', array(
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
    
    public function actionGsuiteInfo($uemail)
    {
        if (empty($uemail))
            throw new CHttpException(404, 'Invalid request. Please generate portal username first.');
        // Get the API client and construct the service object.
        $client = $this->getServiceClient();
        $service = new Google_Service_Directory($client);

        //get Google user
        try {
            $guser = $service->users->get($uemail);
        }
        catch (Google_IO_Exception $gioe) {
            throw new CHttpException(500, 'Error in connection to Google: '.(string)$gioe->getMessage());
        }
        catch (Google_Service_Exception $gse) {
            if(Yii::app()->request->isAjaxRequest){
                throw new CHttpException(403, 'Error to retreive Google user account data: '.(string)$gse->getMessage());
                Yii::app()->end();
            } else {
                return false;
            }
        }
        
        //succesfully - return Google Directory user's info: 
        if(Yii::app()->request->isAjaxRequest){
            //var_dump($guser);
            $suspendedstr = ($guser->suspended) ? 'Yes' : 'No';
            print_r('<div><span>ID: '.$guser->id.'</span><br>');
            print_r('<span>FullName: '.$guser->name->fullName.'</span><br>');
            print_r('<span>PrimaryEmail: '.$guser->primaryEmail.'</span><br>');
            print_r('<span>Organization: '.$guser->orgUnitPath.'</span><br>');
            print_r('<span>Notes: '.$guser->notes.'</span><br>');
            print_r('<span>Suspended: '.$suspendedstr.'</span><br></div>');
            print_r($guser->externalIds);
            //print_r('<div>External IDs: '.implode(" ",$guser->externalIds).'</div>');
            //print_r(json_encode($guser));
            Yii::app()->end();
        } else {
            return $guser;
        }
    }
    
    public function actionGsuiteDeleteUser($uemail)
    {
        if (empty($uemail)) {
            throw new CHttpException(404, 'Invalid request. Please generate portal username first.');
        }
        
        // Get the API client and construct the service object.
        $client = $this->getServiceClient();
        $service = new Google_Service_Directory($client);

        //delete Google user account
        try {
            //get Google user if exist
            unset($gUser);
            $gUser = $service->users->get($uemail);
            //$gUser = $service->users->get($uname.'@tdmu.edu.ua');
            if ($gUser->suspended == true) {
                $gUser = $service->users->delete($uemail);
            } else {
                throw new CHttpException(403, 'Error: Suspend user account before deletion!');
            }
        }
        catch (Google_IO_Exception $gioe) {
            throw new CHttpException(500, 'Error on connection to Google: '.(string)$gioe->getMessage());
        }
        catch (Google_Service_Exception $gse) {
            throw new CHttpException(403, 'Error during Google user account deletion: '.(string)$gse->getMessage());
        }
        
        //deleting was success
        if(Yii::app()->request->isAjaxRequest){
            print_r('<div><span>Account: '.$uemail.'has been deleted!</span></div>');
            Yii::app()->end();
        } else {
            return $gUser;
        }
    }

    //insert or update Google Directory User Account
    public function GSuiteUpdateUser($user, $type)
    {
        //get person's model
        unset($_card);
        if($type==0||$type==2){
            $_card = St::model()->findByPk($user->u6);
        } elseif($type==1){
            $_card = P::model()->findByPk($user->u6);
        }

        //prepare person's data values
        $gPrimaryEmail = $user->u4;
        if($type==1){  //teachers
            $tmpID = $_card->p1;
            $tmpFname = $_card->p4;
            $tmpMname = $_card->p5;
            $tmpLastName = $_card->p3;
            $tmpSchoolID = -1;
            $tmpGrade = -1;
            $tmpOrgUnitPath = '/dont_sync/Кафедри/Викладачі';
        } else {        //students
            $tmpID = $_card->st1;
            $tmpFaculty = $this->getStudentFaculty2Directory($_card->st1); //COMPABILITY: get old faculty ID/name
            $tmpSchoolID = $tmpFaculty['school_id'];
            $tmpOrgUnitPath = $tmpFaculty['google_org_unit_path'];
            //$tmpOrgUnitPath = '/dont_sync/projects/tests';  //test only!
            $tmpGrade = (!is_null($_card->st71)?$_card->st71:0);
            if ($_card->st32 == 804){ //ukrainians
                $tmpFname = $_card->st3;
                $tmpMname = $_card->st4;
                $tmpLastName = $_card->st2;
            } else {                            //foreign
                $tmpFname = ($_card->st75!=null?$_card->st75:$_card->st3);
                $tmpMname = ($_card->st76!=null?$_card->st76:$_card->st4);
                $tmpLastName = ($_card->st74!=null?$_card->st74:$_card->st2);
            }
        }

        // Get the API client and construct the service object.
        $client = $this->getServiceClient();
        $service = new Google_Service_Directory($client);
        
        //construct Google User Object
        $gNameObject = new Google_Service_Directory_UserName(
                      array(
                         'familyName' =>  $tmpLastName,
                         'givenName'  =>  $tmpFname,
                         'fullName'   =>  "$tmpFname $tmpLastName"));
                         
        //get Google user if exist
        unset($gUser);
        $gUser = $this->actionGsuiteInfo($user->u4);

        //create new Google user if NOT exist or point to existing
        if (!$gUser) {
            $gUserObject = new Google_Service_Directory_User();
        } else {
            $gUserObject = $gUser;
        }

        //set Google User data
        $gUserObject->setName($gNameObject);
        $gUserObject->setPrimaryEmail($gPrimaryEmail);
        $gUserObject->setSuspended(boolval($user->u8));
        $gUserObject->setPassword($user->password);
        $gUserObject->setOrgUnitPath($tmpOrgUnitPath);
        // the JSON object shows us that externalIds is an array, so that's how we set it here
        $gUserObject->setExternalIds(array(
                            array('value'=>$tmpSchoolID,'type'=>'custom','customType'=>'school_id'),
                            array('value'=>$tmpGrade,'type'=>'custom','customType'=>'grade'),
                            array('value'=>$tmpID,'type'=>'custom','customType'=>'person_id')));
        try {
            if ($gUser) {
                $updateGUserResult = $service->users->update($gPrimaryEmail, $gUserObject);
            } else {
                $updateGUserResult = $service->users->insert($gUserObject);
            }
        } 
        catch (Google_IO_Exception $gioe) {
            return array(false, "Error in connection to Google: ".(string)$gioe->getMessage());
        } 
        catch (Google_Service_Exception $gse) {
            return array(false, "Error on Google user account update/create: ".(string)$gse->getMessage());
        }
        return array(true, $updateGUserResult);
    }
    
    //convert ASU MKR faculty ID into TSMU Contingent ID and set TSMU Google OrgUnit Name
    private function getStudentFaculty2Directory($st1){
        $tmpFacultyInfo = St::model()->getStudentFacultyInfo($st1); //get faculty
        switch ($tmpFacultyInfo['faculty_id']) {
            case '2': $google_org = array('school_id'=>'43', 'google_org_unit_path'=>'/Students of Medical Faculty'); break;
            case '3': $google_org = array('school_id'=>'14', 'google_org_unit_path'=>'/Students of Faculty of Pharmacy'); break;
            case '4': $google_org = array('school_id'=>'13', 'google_org_unit_path'=>'/Students of Faculty of Dentistry'); break;
            case '5': $google_org = array('school_id'=>'12', 'google_org_unit_path'=>'/Students of Faculty Foreign Students'); break;
            case '8': $google_org = array('school_id'=>'44', 'google_org_unit_path'=>'/Students of Nursing School'); break;
            default: $google_org = array('school_id'=>'0', 'google_org_unit_path'=>'/dont_sync/projects/tests'); break;
        } 
        return $google_org;
    }
}