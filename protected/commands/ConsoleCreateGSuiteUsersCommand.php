<?php
//CLI command
require_once ('..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php');

class ConsoleCreateGSuiteUsersCommand extends CConsoleCommand
{
    const GOOGLE = 'googledirectory';

    public function actionIndex()
    {
        // Get the API client and construct the service object.
        $client = GSuiteDirectoryModel::getServiceClient();
        $service = new Google_Service_Directory($client);
        //get all students along with their portal's userdata
        $students = St::model()->getStudentsForConsoleWithUserdata();
        //process each student
        $i=1;  //debug - stop on 10
        print_r(mb_internal_encoding()."\n");
        foreach ($students as $student) {
            //print_r($student->st2.' '.$student->st3.' '.$student->st4.' email='.$student->account->u4."\n");
            print_r(mb_convert_encoding($student['st2'].' '.$student['st3'].' '.$student['st4'].' email='.$student['u4']."\n", "CP-1251", "UTF-8"));
            //update existing GSuite Users
            if (!empty($student['u4'])) {
                $guser = GSuiteDirectoryModel::GSuiteUserInfo($student['u4']);
                //$guser = Yii::app()->getModule('admin')->default->GsuiteInfo($student['u4']);
                var_dump($guser);
            }
            $i++;
            if ($i > 50) { break; };
        }
        
        return;

    }
}