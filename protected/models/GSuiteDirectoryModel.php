<?php
//deal with Google Suite API

class GSuiteDirectoryModel extends CModel
{
    private static $_names=array();
    
    static public function GsuiteInfo($uemail)
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
        $client->setApplicationName('G Suite Directory API PHP Quickstart-service account');
        $client->setScopes(Google_Service_Directory::ADMIN_DIRECTORY_USER);
        $client->setSubject('admin@tdmu.edu.ua');
        return $client;
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