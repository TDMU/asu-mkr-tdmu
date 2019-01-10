<?php
//CLI command
require_once ('..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php');

class ConsoleGetGSuiteUsersInfoCommand extends CConsoleCommand
{

    public function actionIndex()
    {
        // Get the API client and construct the service object.
        $client = GSuiteDirectoryModel::getServiceClient();
        $service = new Google_Service_Directory($client);
        //get all ASU users
        $asuusers = Users::model()->findAll();
        //process each user
        foreach ($asuusers as $asuuser) {
            //var_dump($asuuser);
            if (!empty($asuuser->u4)) { //only if email exist
                print_r('ASU user: ' . $asuuser->u4 . "\n");
                //get Google user
                unset($guser);
                try {
                    $guser = $service->users->get($asuuser->u4);
                }
                catch (Google_IO_Exception $gioe) {
                    //TODO: write log
                    print_r('Error in connection to Google: ' . (string)$gioe->getMessage() . "\n");
                    continue;
                    //throw new CHttpException(500, 'Error in connection to Google: '.(string)$gioe->getMessage());
                }
                catch (Google_Service_Exception $gse) {
                    //TODO: write log
                    print_r('Error to retreive Google user account data: ' . (string)$gse->getMessage() . "\n");
                    continue;
                    //throw new CHttpException(403, 'Error to retreive Google user account data: '.(string)$gse->getMessage());
                }
                print_r('Google user: ' . $guser->id .' - ' . $guser->primaryEmail . "\n");
                //get socialrecord info
                $condition = '(userid=:userID)AND(usertype=:userType)AND(personid=:personID)AND(service=:service)';
                $params = array(':userID' => $asuuser->u1,':userType' => $asuuser->u5,':personID' => $asuuser->u6, ':service' => UsersSocialRecords::GOOGLE);

                $transaction = UsersSocialRecords::model()->dbConnection->beginTransaction();
                try {
                    $userSocialRecord = UsersSocialRecords::model()->find($condition,$params);
                    if (empty($userSocialRecord)) {
                        //crea new record
                        $userSocialRecord = new UsersSocialRecords;
                        $userSocialRecord->id = new CDbExpression('GEN_ID(GEN_USOCIALRECORDS, 1)');
                        $userSocialRecord->userid = $asuuser->u1;
                        $userSocialRecord->usertype = $asuuser->u5;
                        $userSocialRecord->personid = $asuuser->u6;
                        $userSocialRecord->service = UsersSocialRecords::GOOGLE;
                    }
                    //update socialrecord info
                    $userSocialRecord->serviceid = $guser->id;
                    $userSocialRecord->created = date('d.m.Y H:i:s', strtotime($guser->creationTime));
                    $userSocialRecord->updated = date('d.m.Y H:i:s');
                    //var_dump($userSocialRecord);
                    if($userSocialRecord->save()) {
                        //$userSocialRecord->save();
                        $transaction->commit();
                        print_r('Record saved OK!' . "\n");
                    } else {
                        $transaction->rollback();
                        print_r($userSocialRecord->getErrors());
                    }
                }
                catch(Exception $e)
                {
                    $transaction->rollback();
                    print_r($e);
                    continue;
                }
            }
        }
    }
}