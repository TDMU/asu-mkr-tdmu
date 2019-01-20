<?php
//CLI command
require_once ('..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php');

class ConsoleCreateGSuiteUsersCommand extends CConsoleCommand
{
    const GOOGLE = 'googledirectory';

    public function actionIndex()
    {   
        //create Excel document
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
        $sheet->setCellValueByColumnAndRow(1,1,tt('ПІБ'));
        $sheet->setCellValueByColumnAndRow(2,1,tt('Факультет'));
        $sheet->setCellValueByColumnAndRow(3,1,tt('Група'));
        $sheet->setCellValueByColumnAndRow(4,1,tt('ASU_id'));
        $sheet->setCellValueByColumnAndRow(5,1,tt('логін'));
        $sheet->setCellValueByColumnAndRow(6,1,tt('пароль'));

        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(40);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(18);
        $sheet->getColumnDimension('E')->setWidth(10);
        $sheet->getColumnDimension('F')->setWidth(25);
        $sheet->getColumnDimension('G')->setWidth(18);        
        //TDMU-specific
        $sheet->setCellValueByColumnAndRow(7,1,tt('Google account created'));
        $sheet->getColumnDimension('H')->setWidth(28);

        // Get the API client and construct the service object.
        $client = GSuiteDirectoryModel::getServiceClient();
        $service = new Google_Service_Directory($client);

        //process by faculty
        $faculties = F::model()->FindAll();
        foreach ($faculties as $faculty) {
            if ($faculty->f1 == 3) { //debug - remove!
            
            //get all students along with their portal's userdata
            $students = St::model()->getStudentsForConsoleWithUserdata($faculty->f1);
            //process each student
            //$j=1;  //debug - stop on xx
            $i=2;
            //print_r(mb_internal_encoding()."\n");
            foreach ($students as $student) {
                print_r('Student: id='.$student['st1'].' name='.$student['st2'].' '.$student['st3'].' '.$student['st4'].' email='.$student['u4']."\n");

                $typeName = 'student';
                $bDate = $student['st7'];
                $name = $student['st2'].' '.$student['st3'].' '.$student['st4'];
                //$faculty = F::model()->findByPk($student['f1']);

                if (!empty($student['u4'])) {
                    //ASU user exist - create / update GSuite Users
                    //get ASU full userdata
                    $asuuser = Users::model()->findByAttributes(array('u4' => $student['u4']));
                    //get Google user
                    unset($guser);
                    $guser = GSuiteDirectoryModel::GSuiteUserInfo($student['u4']);
                    if (!empty($guser)) {
                        //only update EXISTING Google users
                        unset($ischanged);
                        //check for name / faculty / status changes - and only there update
                        $ischanged = GSuiteDirectoryModel::CheckUserDifference($guser, $asuuser);
                        print_r('Is GUser data has been changed?: '.var_export($ischanged, true)."\n");
                        if ($ischanged){
                            unset($gResults);
                            //$gResults = GSuiteDirectoryModel::GSuiteUpdateUser($asuuser, $asuuser->u5);
                            if ($gResults[0] !== true) {  //error
                                print_r('FAILED to create new Google user!'."\n");
                            } else {  //success
                                print_r('Google user\'s data has been updated successfully!'."\n");
                            }
                        } else {
                            print_r('MATCH! No need to change Google user\'s data'."\n");
                        }
                    } else {
                        $asuuser->password = bin2hex(openssl_random_pseudo_bytes(5)); //generate new password

                        $sheet->setCellValueByColumnAndRow(0,$i,$typeName);
                        $sheet->setCellValueByColumnAndRow(1,$i,$name);
                        $sheet->setCellValueByColumnAndRow(2,$i,$faculty->f3);
                        $sheet->setCellValueByColumnAndRow(3,$i,$student['gr3']);
                        $sheet->setCellValueByColumnAndRow(4,$i,$student['st1']);
                        $sheet->setCellValueByColumnAndRow(5,$i,$asuuser->u2);
                        $sheet->setCellValueByColumnAndRow(6,$i,$asuuser->password);
                        //creating a Google Directory useer account
                        if ($type == 0||$type == 1) { //not for parents!
                            unset($gResults);
                            //$gResults = GSuiteDirectoryModel::GSuiteUpdateUser($asuuser, $asuuser->u5);
                            if ($gResults[0] !== true) {  //error
                                $sheet->setCellValueByColumnAndRow(7,$i,$gResults[1]);
                                print_r('FAILED to create new Google user!'."\n");
                            } else {  //success
                                $sheet->setCellValueByColumnAndRow(7,$i,$gResults[1]->creationTime);
                                print_r('New Google user has been created! Username='.$gResults[1]->primaryEmail."\n");
                                $asuuser->u3 = $asuuser->password;
                                if ($asuuser->save(false)) {
                                    print_r('ASU user\'s password has been changed successfully!'."\n");
                                } else {
                                    print_r('Failed to change ASU user\'s password!'."\n");
                                }
                            }
                        }
                        $i++;
                    }
                } else {
                    //create first ASU and than Google users
                    if (($student['st2']!=''||$student['st3']!=''||$student['st4']!='')||($student['st74']!=''||$student['st75']!=''||$student['st76']!='')) {
                        //create only for active!
                        if ($student['std11']==0 || $student['std11'] > 5) {
                            //Create ASU USER
                            $type = 0; //student only
                            $asuuser = GSuiteDirectoryModel::ASUCLICreateUser($student['st1'], $type);
                            print_r('New ASU user has been created successfully! Username='.$asuuser->u2."\n");

                            if ($asuuser) {
                                $sheet->setCellValueByColumnAndRow(0,$i,$typeName);
                                $sheet->setCellValueByColumnAndRow(1,$i,$name);
                                $sheet->setCellValueByColumnAndRow(2,$i,$faculty->f3);
                                $sheet->setCellValueByColumnAndRow(3,$i,$student['gr3']);
                                $sheet->setCellValueByColumnAndRow(4,$i,$student['st1']);
                                $sheet->setCellValueByColumnAndRow(5,$i,$asuuser->u2);
                                $sheet->setCellValueByColumnAndRow(6,$i,$asuuser->password);
                                //creating a Google Directory useer account
                                if ($type == 0||$type == 1) { //not for parents!
                                    unset($gResults);
                                    //$gResults = GSuiteDirectoryModel::GSuiteUpdateUser($asuuser, $asuuser->u5);
                                    if ($gResults[0] !== true) {  //error
                                        $sheet->setCellValueByColumnAndRow(7,$i,$gResults[1]);
                                        print_r('FAILED to create new Google user!'."\n");
                                    } else {  //success
                                        $sheet->setCellValueByColumnAndRow(7,$i,$gResults[1]->creationTime);
                                        print_r('New Google user has been created! Username='.$gResults[1]->primaryEmail."\n");
                                    }
                                }
                            } else {
                                $sheet->mergeCellsByColumnAndRow(0, $i, 4, $i)->setCellValueByColumnAndRow(0, $i,'Ошибка сохранения '.$typeName.' '.$name.' '.$bDate);
                                print_r('FAILED to create new ASU user!'."\n");
                                continue;
                            }
                            $i++;
                        } else {
                            print_r('ERROR! Inactive student!'."\n");
                        }
                    } else {
                        print_r('ERROR! No any name for student'."\n");
                    }
                }
                $j++;
                //if ($j > 10 || $student['st1'] > 41) { break; };//debug only
            }
            
            $sheet->getStyleByColumnAndRow(0,1,4,$i-1)->getBorders()->getAllBorders()->applyFromArray(array('style'=>PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => '000000')));
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

            //save file with passwords in wwwroot/export folder
            $jobdate = new DateTime();
            $jobdatestr = $jobdate->format('Y-m-d_H-i');
            $tmpdocfilename = transliterator_transliterate ('Any-Latin; [\u0100-\u7fff] Remove; Latin-ASCII; NFD; [:Nonspacing Mark:] Remove; NFC; Lower();', $faculty->f2);
            $tmpdocfilename = preg_replace('/[^A-Za-z0-9_-]/', '_', $tmpdocfilename );
            $tmpdocfilename = transliterator_transliterate ('Ukrainian-Latin/BGN', $tmpdocfilename);
            $tmpfullfilename = 'CLIGeneratedUsers_'.$tmpdocfilename.'_'.$jobdatestr.'.xls';
            $tmp_path = realpath(Yii::app()->basePath . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR  . 'export');
            $objWriter->save($tmp_path . DIRECTORY_SEPARATOR . $tmpfullfilename);
            
            //email file as attacment preparations
            $tmpSavedFile = $tmp_path . DIRECTORY_SEPARATOR . $tmpfullfilename;
            $savedfile = new stdClass;
            $savedfile->tempName = $tmp_path . DIRECTORY_SEPARATOR;
            $savedfile->name = $tmpfullfilename;
            if (file_exists($tmpSavedFile)) {
                //email to faculty department
                $messageBody = $faculty->f3."\n".' The following Google Directroy users has been created / updated (see attached file)';
                Controller::mail('semteacher@gmail.com', 'Google Directroy Sync', $messageBody, $savedfile);
            } else {
                print_r('FAILED to save file with passwords!'."\n");
            }
        } //debug - remove!
        }
    }
}