<?php
//CLI command
require_once ('..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php');

class ConsoleGetGSuiteUsersInfoCommand extends CConsoleCommand
{
    const GOOGLE = 'googledirectory';

    public function actionIndex()
    {
        // Get the API client and construct the service object.
        $client = $this->getServiceClient();
        $service = new Google_Service_Directory($client);
        //get all ASU users
        $asuusers = Users::model()->findAll();
        //process each user
        foreach ($asuusers as $asuuser) {
            if (!empty($asuuser->u4)) { //only if email exist
                //get Google user
                unset($guser);
                try {
                    $guser = $service->users->get($asuuser->u4);
                }
                catch (Google_IO_Exception $gioe) {
                    //TODO: write log
                    throw new CHttpException(500, 'Error in connection to Google: '.(string)$gioe->getMessage());
                }
                catch (Google_Service_Exception $gse) {
                    //TODO: write log
                    throw new CHttpException(403, 'Error to retreive Google user account data: '.(string)$gse->getMessage());
                }
                print_r($guser->id .' - '. $guser->primaryEmail . "\n");
                //get socialrecord info
                $condition = '(userid=:userID)AND(usertype=:userType)AND(personid=:personID)AND(service=:service)';
                $params = array(':userID' => $asuuser->u1,':userType' => $asuuser->u5,':personID' => $asuuser->u6, ':service' => GOOGLE);
                $userSocialRecord = UsersSocialRecords::model()->find($condition,$params);
                var_dump(empty($userSocialRecord));
                if (empty($userSocialRecord)) {
                    //crea new record
                    $userSocialRecord = new UsersSocialRecords;
                    $userSocialRecord->userid = (int)$asuuser->u1;
                    $userSocialRecord->usertype = (int)$asuuser->u5;
                    $userSocialRecord->personid = (int)$asuuser->u6;
                    $userSocialRecord->service = GOOGLE;
                }
                //update socialrecord info
                $userSocialRecord->serviceid = $guser->id;
                $userSocialRecord->created = $guser->creationTime;
                $userSocialRecord->updated = time();
                var_dump($userSocialRecord);
                $res = $userSocialRecord->save();
                var_dump($res);
            }
        }
    }

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
}