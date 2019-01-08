<?php
//deal with Google Suite API

class GSuiteDirectoryModel extends CModel
{
    private static $_names=array();
    
    static public function GSuiteUserInfo($uemail)
    {
        if (empty($uemail))
            throw new CHttpException(404, 'Invalid request. Please generate portal username first.');
        // Get the API client and construct the service object.
        $client = self::getServiceClient();
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

    static public function GSuiteDeleteUser($uemail)
    {
        if (empty($uemail)) {
            throw new CHttpException(404, 'Invalid request. Please generate portal username first.');
        }
        
        // Get the API client and construct the service object.
        $client = self::getServiceClient();
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
    static public function GSuiteUpdateUser($user, $type)
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
            $tmpFaculty = self::getStudentFaculty2Directory($_card->st1); //COMPABILITY: get old faculty ID/name
            $tmpSchoolID = $tmpFaculty['school_id'];
            //$tmpOrgUnitPath = $tmpFaculty['google_org_unit_path'];
            $tmpOrgUnitPath = '/dont_sync/projects/tests';  //test only!
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
        $client = self::getServiceClient();
        $service = new Google_Service_Directory($client);
        
        //construct Google User Object
        $gNameObject = new Google_Service_Directory_UserName(
                      array(
                         'familyName' =>  $tmpLastName,
                         'givenName'  =>  $tmpFname,
                         'fullName'   =>  "$tmpFname $tmpLastName"));
                         
        //get Google user if exist
        unset($gUser);
        $gUser = self::GSuiteUserInfo($user->u4);

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
    static public function getStudentFaculty2Directory($st1){
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

    /**
     * Returns an authorized API client (based on Service Account).
     * @return Google_Client the authorized client object
     */
    static public function getServiceClient()
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
        $client->setApplicationName('G Suite Directory API PHP - TSMU service account client');
        $client->setScopes(Google_Service_Directory::ADMIN_DIRECTORY_USER);
        $client->setSubject('admin@tdmu.edu.ua');
        return $client;
    }

    /**
     * Expands the home directory alias '~' to the full path.
     * @param string $path the path to expand.
     * @return string the expanded path.
     */
    static public function expandHomeDirectory($path)
    {
        $homeDirectory = getenv('HOME');
        if (empty($homeDirectory)) {
            $homeDirectory = getenv('HOMEDRIVE') . getenv('HOMEPATH');
        }
        return str_replace('~', realpath($homeDirectory), $path);
    }

	/**
	 * Returns the list of attribute names.
	 * By default, this method returns all public properties of the class.
	 * You may override this method to change the default.
	 * @return array list of attribute names. Defaults to all public properties of the class.
	 */
	public function attributeNames()
	{
		$className=get_class($this);
		if(!isset(self::$_names[$className]))
		{
			$class=new ReflectionClass(get_class($this));
			$names=array();
			foreach($class->getProperties() as $property)
			{
				$name=$property->getName();
				if($property->isPublic() && !$property->isStatic())
					$names[]=$name;
			}
			return self::$_names[$className]=$names;
		}
		else
			return self::$_names[$className];
	}
}