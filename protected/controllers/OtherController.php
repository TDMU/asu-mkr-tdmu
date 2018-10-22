<?php


// Grab the Apostle namespace
use Apostle\Mail;

class OtherController extends Controller
{
    public function filters() {

        return array(
            'accessControl',
        );
    }

    public function accessRules() {

        return array(
            array('allow',
                'actions' => array(
                    'orderLesson', 'freeRooms', 'saveLessonOrder',
                    'deleteComment',
                    'renderAddSpkr',
                    'addSpkr'
                ),
                'expression' => 'Yii::app()->user->isTch',
            ),
            array('allow',
                'actions' => array(
                    'gostem',
                    'deleteGostem',
                    'subscription',
                    'saveCiklVBloke',
                    'saveDisciplines',
                    'cancelSubscription',
                    'renderAddSpkr',
                    'addSpkr',
                    'studentInfoExcel',
                    'studentInfoPdf',
                    'antiplagiat'
                ),
                'expression' => 'Yii::app()->user->isStd',
            ),
            array('allow',
                'actions' => array(
                    'phones',
                    'employment',
                    'studentInfo', //проверка идет в самом методе

                    'studentPassport', //Проверка идет в самом методе
                    'deletePassport', //проверка идет в самом методе
                    'showPassport', //проверка идет в самом методе
                    'uploadPassport', //проверка идет в самом методе
                    'changePassport', //проверка идет в самом методе
                    'autocompleteTeachers',
                    'studentCard', //проверка идет в самом методе
                    'studentCardExcel',
                    'showRetake', //проверка идет в самом методе
                ),
            ),
            array('allow',
                'actions' => array(
                    'searchStudent',
                    'updateNkrs',
                ),
                'users' => array('@'),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    public function actionShowRetake()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $uo1 = Yii::app()->request->getParam('uo1', null);
        $sem1 = Yii::app()->request->getParam('sem1', null);
        $type = Yii::app()->request->getParam('type', null);
        $st1 = Yii::app()->request->getParam('st1', null);
        $gr1 = Yii::app()->request->getParam('gr1', null);

        if (empty($uo1) || empty($sem1) || empty($st1) || $type === null)
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');

        if (Yii::app()->user->isAdmin) {


        } elseif (Yii::app()->user->isPrnt) {
            if($st1!=Yii::app()->user->dbModel->st1)
                throw new CHttpException(403, 'Invalid request. Please do not repeat this request again.');
        } elseif (Yii::app()->user->isStd) {
            if($st1!=Yii::app()->user->dbModel->st1)
                throw new CHttpException(403, 'Invalid request. Please do not repeat this request again.');
        } else
            throw new CHttpException(403, 'You don\'t have an access to this service');

        $uo = Uo::model()->findByPk($uo1);
        $sem = Sem::model()->findByPk($sem1);

        $gr1 = St::model()->getGroupByStudent($st1, $uo->uo19, $sem->sem3, $sem->sem5);

        $info = Elg::model()->getDispByStSemUoType($st1,$uo1,$sem1,$type);
        $error = false;

        $title = "";
        if(!empty($info)) {
            if(!empty($info['d27'])&&Yii::app()->language=="en")
                $d2=$info['d27'];
            else
                $d2=$info['d2'];
            $title = sprintf("%s | %s (%s)",$d2,$info['k2'],SH::convertUS4($info['us4']));
        }

        $html = $this->renderPartial('studentCard/_show_retake', array(
            'uo1'=>$uo1,
            'sem1'=>$sem1,
            'type'=>$type,
            'st1'=>$st1,
            'gr1'=>$gr1
        ), true);

        $res = array(
            'title'=>$title,
            'html'=>$html,
            'error' => $error,
        );

        Yii::app()->end(CJSON::encode($res));
    }

    public function actionStudentCard()
    {
        $model = new TimeTableForm;
        $model->scenario = 'student';
        if (isset($_REQUEST['TimeTableForm']))
            $model->attributes=$_REQUEST['TimeTableForm'];

        $student = new St;
        $student->unsetAttributes();
        if (Yii::app()->user->isAdmin) {


        }
        elseif (Yii::app()->user->isPrnt) {
            $model->student = Yii::app()->user->dbModel->st1;
        } elseif (Yii::app()->user->isStd) {

            $model->student = Yii::app()->user->dbModel->st1;
        } else
            throw new CHttpException(404, 'You don\'t have an access to this service');

        $this->render('studentCard', array(
            'student'=>$student,
            'model'=>$model
        ));
    }

    public function actionStudentCardExcel()
    {
        $model = new TimeTableForm;
        $model->scenario = 'student';
        if (isset($_REQUEST['TimeTableForm']))
            $model->attributes=$_REQUEST['TimeTableForm'];

        if (Yii::app()->user->isAdmin) {


        }
        elseif (Yii::app()->user->isPrnt) {
            $model->student = Yii::app()->user->dbModel->st1;
        } elseif (Yii::app()->user->isStd) {
            $model->student = Yii::app()->user->dbModel->st1;
        }

        $st1 =  $model->student;
        $st = St::model()->findByPk($st1);

        $studentInfo = $st->getStudentInfoForCard();

        if(!empty($studentInfo)) {
            Yii::import('ext.phpexcel.XPHPExcel');
            $objPHPExcel = XPHPExcel::createPHPExcel();
            $objPHPExcel->getProperties()->setCreator("ACY")
                ->setLastModifiedBy("ACY " . date('Y-m-d H-i'))
                ->setTitle("StudentCard " . date('Y-m-d H-i'))
                ->setSubject("StudentCard " . date('Y-m-d H-i'))
                ->setDescription("StudentCard document, generated using ACY Portal. " . date('Y-m-d H:i:'))
                ->setKeywords("")
                ->setCategory("Result file");
            $objPHPExcel->setActiveSheetIndex(0);
            $sheet = $objPHPExcel->getActiveSheet();

            $sheet->mergeCells('A1:D8');

            $data = St::model()->getFoto($st->st1);
            if ($data != null) {

                $objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
                $objDrawing->setName('logo');
                $objDrawing->setImageResource(imagecreatefromstring($data));
                $objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_DEFAULT);
                $objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
                $objDrawing->setHeight(100);
                $objDrawing->setCoordinates('A1');
                $objDrawing->setOffsetX(10);
                $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
            }


            $sheet->mergeCells('E1:F1');
            $sheet->setCellValue('E1', tt('ФИО'));
            $sheet->mergeCells('G1:J1');
            $sheet->setCellValue('G1', $st->st2.' '.$st->st3.' '.$st->st4);

            $sheet->mergeCells('E2:F2');
            $sheet->setCellValue('E2', tt('Гражданство'));
            $sheet->mergeCells('G2:J2');
            $sheet->setCellValue('G2', $studentInfo['sgr2']);

            $sheet->mergeCells('E3:F3');
            $sheet->setCellValue('E3', tt('Дата рождения'));
            $sheet->mergeCells('G3:J3');
            $sheet->setCellValue('G3', date("m.d.y",strtotime($st->st7)));

            $sheet->mergeCells('E4:F4');
            $sheet->setCellValue('E4', tt('Факультет'));
            $sheet->mergeCells('G4:J4');
            $sheet->setCellValue('G4', $studentInfo['f3']);

            $sheet->mergeCells('E5:F5');
            $sheet->setCellValue('E5', tt('Специальность'));
            $sheet->mergeCells('G5:J5');
            $sheet->setCellValue('G5', $studentInfo['sp2']);

            $sheet->mergeCells('E6:F6');
            $sheet->setCellValue('E6', tt('Форма обучения'));
            $sheet->mergeCells('G6:J6');
            $sheet->setCellValue('G6',SH::convertEducationType($studentInfo['sg4']));

            $sheet->mergeCells('E7:F7');
            $sheet->setCellValue('E7', tt('Курс'));
            $sheet->mergeCells('G7:J7');
            $sheet->setCellValue('G7', $studentInfo['sem4']);

            $sheet->mergeCells('E8:F8');
            $sheet->setCellValue('E8', tt('Группа'));
            $sheet->mergeCells('G8:J8');
            $sheet->setCellValue('G8',Gr::model()->getGroupName($studentInfo['sem4'], $studentInfo));

            $sheet->setTitle(tt('Карточка ст.') . ' ' . date('Y-m-d H-i'));

            /*---------------------------------------------------*/
            if(PortalSettings::model()->findByPk(48)->ps2==1) {
                $objWorkSheet = $objPHPExcel->createSheet(1);
                $objPHPExcel->setActiveSheetIndex(1);
                $sheet = $objPHPExcel->getActiveSheet();
                $sheet->setTitle(tt('Тек. задолженость'));

                $disciplines = Elg::model()->getDispBySt($st->st1);

                $sheet->mergeCells('A1:A2');
                $sheet->setCellValue('A1', tt('№ пп'));

                $sheet->mergeCells('B1:B2');
                $sheet->setCellValue('B1', tt('Кафедра'));

                $sheet->mergeCells('C1:C2');
                $sheet->setCellValue('C1', tt('Дисциплина'));

                $sheet->mergeCells('D1:D2');
                $sheet->setCellValue('D1', tt('Тип занятий'));

                $sheet->mergeCells('E1:E2');
                $sheet->setCellValue('E1', tt('Общее к-во занятий'));

                $sheet->mergeCells('F1:G1');
                $sheet->setCellValue('F1', tt('Количество пропусков'));
                $sheet->setCellValue('F2', tt('Уваж.'));
                $sheet->setCellValue('G2', tt('Неув.'));

                $sheet->mergeCells('H1:H2');
                $sheet->setCellValue('H1', tt('К-во "2"'));

                $sheet->mergeCells('I1:J1');
                $sheet->setCellValue('I1', tt('К-во отработанных занятий'));
                $sheet->setCellValue('I2', tt('"нб"'));
                $sheet->setCellValue('J2', tt('"2"'));

                $sheet->mergeCells('K1:K2');
                $sheet->setCellValue('K1', tt('% задолженности'));

                $sheet->getColumnDimension('B')->setWidth(20);
                $sheet->getColumnDimension('C')->setWidth(40);
                $sheet->getColumnDimension('E')->setWidth(12);

                $i=1;
                $k=2;
                foreach($disciplines as $discipline)
                {
                    $type= $discipline['type_journal'];
                    /*if($discipline['us4']>1)
                        $type=1;*/
                    list($respectful,$disrespectful,$f,$nbretake,$fretake,$count) = Elg::model()->getRetakeInfo($discipline['uo1'],$discipline['sem1'],$type,$st->st1, PortalSettings::model()->getSettingFor(55));
                    $sheet->setCellValueByColumnAndRow(0,$i+$k, $i);
                    $sheet->setCellValueByColumnAndRow(1,$i+$k, $discipline['k2']);
                    if(!empty($discipline['d27'])&&Yii::app()->language=="en")
                        $d2=$discipline['d27'];
                    else
                        $d2=$discipline['d2'];
                    $sheet->setCellValueByColumnAndRow(2,$i+$k, $d2);
                    $sheet->setCellValueByColumnAndRow(3,$i+$k, SH::convertTypeJournal($discipline['type_journal']));
                    $sheet->setCellValueByColumnAndRow(4,$i+$k, $count);
                    $sheet->setCellValueByColumnAndRow(5,$i+$k, $respectful);
                    $sheet->setCellValueByColumnAndRow(6,$i+$k, $disrespectful);
                    $sheet->setCellValueByColumnAndRow(7,$i+$k, $f);
                    $sheet->setCellValueByColumnAndRow(8,$i+$k, $nbretake);
                    $sheet->setCellValueByColumnAndRow(9,$i+$k, $fretake);
                    if($count!=0)
                        $proc = round((($respectful+$disrespectful-$nbretake)+($f-$fretake))/$count*100);
                    else
                        $proc=0;
                    $sheet->setCellValueByColumnAndRow(10,$i+$k, $proc);
                    $i++;
                }
                $sheet->getStyleByColumnAndRow(0,1,10,$i+$k-1)->getBorders()->getAllBorders()->applyFromArray(array('style'=>PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => '000000')));
                $sheet->getStyleByColumnAndRow(0,1,10,2)->getFont()->setSize(12)->setBold(true);
                $sheet->getStyleByColumnAndRow(0,1,10,2)->getAlignment()->setWrapText(true)->
                setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            }


            $objPHPExcel->setActiveSheetIndex(0);

            // Redirect output to a clientâ€™s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="ACY_' . date('Y-m-d H-i') . '.xls"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');
            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            Yii::app()->end();
        }
    }

    public function actionPhones()
    {
        $department = Yii::app()->request->getParam('department', null);

        $phones = Tso::model()->getAllPhonesInArray($department);

        $this->render('phones', array(
            'phones' => $phones,
            'department' => $department
        ));
    }

    public function actionGostem()
    {
        $model = new FilterForm;
        $model->scenario = 'gostem';

        if (isset($_REQUEST['FilterForm'])) {
            $model->attributes=$_REQUEST['FilterForm'];

            if (isset($_REQUEST['subscribe'])) {
                $nrst = new Nrst;
                $nrst->nrst1 = $model->nr1;
                $nrst->nrst2 = Yii::app()->user->dbModel->st1;
                $nrst->nrst3 = $model->gostem1;
                $nrst->save();
                $this->redirect('/other/gostem');
            }
        }


        $this->render('gostem', array(
            'model' => $model,
        ));
    }

    public function actionDeleteGostem()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $nrst1 = Yii::app()->request->getParam('nrst1', null);
        $nrst3 = Yii::app()->request->getParam('nrst3', null);

        if (empty($nrst1) || empty($nrst3))
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $deleted = (bool)Nrst::model()->deleteAllByAttributes(array(
            'nrst1' => $nrst1,
            'nrst2' => Yii::app()->user->dbModel->st1,
            'nrst3' => $nrst3,
        ));

        $res = array(
            'deleted' => $deleted
        );

        Yii::app()->end(CJSON::encode($res));
    }

    public function actionOrderLesson()
    {
        $model = new TimeTableForm;
        $model->scenario = 'group';
        if (isset($_REQUEST['TimeTableForm']))
            $model->attributes=$_REQUEST['TimeTableForm'];
        $model->date1 = Yii::app()->session['date1'];
        $model->date2 = Yii::app()->session['date2'];

        $timeTable = $minMax = $maxLessons = array();
        if (! empty($model->group))
        {
            list($minMax, $timeTable, $maxLessons) = $model->generateGroupTimeTable();;
        }


        $this->render('orderLesson', array(
            'model'      => $model,
            'timeTable'  => $timeTable,
            'minMax'     => $minMax,
            'maxLessons' => $maxLessons,
            'rz'         => Rz::model()->getRzArray($model->filial),
            'type'=>-1
        ));
    }

    public function actionFreeRooms()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $zpz6   = Yii::app()->request->getParam('zpz6', null);
        $zpz7   = Yii::app()->request->getParam('zpz7', null);
        $filial = Yii::app()->request->getParam('filial', null);

        if (empty($zpz6) || empty($zpz7))
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $rooms = CHtml::listData(A::model()->getFreeRooms($filial, $zpz6, $zpz7), 'a1', 'a2', 'ka2');

        $html = CHtml::dropDownList('ZPZ[zpz8]', null, $rooms, array('style'=>'width:155px'));

        Yii::app()->end(
            CJSON::encode(array('html' => $html))
        );
    }

    public function actionSaveLessonOrder()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $res = null;

        $params = Yii::app()->request->getParam('params');
        $params = explode('/',$params);

        if (isset($_POST['ZPZ'])) {

            $model = new Zpz;
            $model->attributes = $_POST['ZPZ'];
            $model->zpz1 = new CDbExpression('GEN_ID(GEN_ZPZ, 1)');
            $model->zpz2 = $params[0];
            $model->zpz3 = $params[1];
            $model->zpz4 = $params[2];
            $model->zpz5 = $params[3];
            $model->zpz9 = Yii::app()->user->dbModel->p1;
            $model->zpz10 = new CDbExpression('CURRENT_TIMESTAMP');
            $res = $model->save();
        }

        Yii::app()->end(
            CJSON::encode(array(
                'res'    => $res,
                'errors' => isset($model)?$model->getErrors():null
            ))
        );
    }

    public function actionEmployment()
    {
        $st1 = Yii::app()->request->getParam('id', null);

        if (empty($st1)) {

            $model = new FilterForm;
            $model->scenario = 'employment';

            if (isset($_REQUEST['FilterForm']))
                $model->attributes=$_REQUEST['FilterForm'];

            $this->render('employment', array(
                'model' => $model,
            ));

        } else {

            $student = St::model()->findByPk($st1);

            $model = Sdp::model()->loadModel($st1);

            $user = Yii::app()->user;
            $isEditable = $user->isAdmin ||
                          ($user->isStd && $user->dbModel->st1 == $st1);

            if ($isEditable && isset($_REQUEST['Sdp'])) {

                $model->attributes = $_REQUEST['Sdp'];

                if ($model->validate()) {
                    $model->save();
                } else
                    Yii::app()->user->setFlash('error', tt('Пожалуйста, исправьте возникшие ошибки!'));

            }

            if ($user->isTch) {
                $message = Yii::app()->request->getParam('message', null);
                if (! empty($message)) {
                    $comment = new Psto;
                    $comment->psto1 = $user->dbModel->p1;
                    $comment->psto2 = $st1;
                    $comment->psto3 = $message;
                    $comment->psto4 = new CDbExpression('current_timestamp');
                    $comment->save();
                    $this->redirect($this->createUrl('/other/employment', array('id' => $st1)));
                }

            }

            $criteria = new CDbCriteria;
            $criteria->compare('psto2', $st1);
            $criteria->order = 'psto4 DESC';
            $comments = Psto::model()->findAll($criteria);


            $this->render('employment/_st_info', array(
                'model'      => $model,
                'student'    => $student,
                'isEditable' => $isEditable,
                'comments'   => $comments,
            ));

        }
    }

    public function actionDeleteComment()
    {
        //if (! Yii::app()->request->isAjaxRequest)
        //    throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $user = Yii::app()->user;

        if (! $user->isTch)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $psto1 = Yii::app()->request->getParam('psto1', null);
        $psto2 = Yii::app()->request->getParam('psto2', null);
        $psto4 = Yii::app()->request->getParam('psto4', null);


        if (empty($psto1) || empty($psto2) || empty($psto4))
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        if ($psto1 != $user->dbModel->p1)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $sql = <<<SQL
            DELETE
            FROM psto
            WHERE psto1 = :PSTO1 and psto2 = :PSTO2 and psto4 >= :PSTO4
            ROWS 1
SQL;


        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':PSTO1', $psto1);
        $command->bindValue(':PSTO2', $psto2);
        $command->bindValue(':PSTO4', $psto4);
        $res = $command->execute();

        Yii::app()->end(
            CJSON::encode(array('res' => $res))
        );
    }

    public function actionSubscription()
    {
        $model = Yii::app()->user->dbModel;

        $this->render('subscription', array(
            'model' => $model,
        ));
    }

    public function actionSaveCiklVBloke()
    {
        $u1_cikl = Yii::app()->request->getParam('u1_cikl', null);

        $res = false;
        if (! empty($u1_cikl)) {

            $_SESSION['u1_vib'] .= ','.$u1_cikl;
            $_SESSION['u1_vib_disc'] = $u1_cikl;

            $_SESSION['min_block']++;
            $_SESSION['func'] = 'PROCEDURA_VIBOR_DISCIPLIN';

            $res = true;
        }

        Yii::app()->end(CJSON::encode(array('res' => $res)));
    }

    public function actionSaveDisciplines()
    {
        $disciplines = Yii::app()->request->getParam('disciplines', null);

        $res = false;
        if (! empty($disciplines)) {
            try {
                foreach ($disciplines as $ucg1_kod) {
                    U::model()->doUpdates($ucg1_kod);
                }

                $_SESSION['min_block']++;
                $_SESSION['func'] = 'PROCEDURA_CIKL_PO_BLOKAM';

                $res = true;
                $msg = '';
            }catch (Exception $error){
                $res = false;
                $msg = $error->getMessage();
            }
        }

        Yii::app()->end(CJSON::encode(array('res' => $res, 'msg' => $msg)));
    }

    public function actionCancelSubscription()
    {
        if(St::model()->enableSubcription(Yii::app()->user->dbModel->st1)) {
            $model = St::model()->findByPk(Yii::app()->user->dbModel->st1);
            if(!empty($model)) {
                $params = $model->subscriptionParams;
                if(!empty($params)) {
                    U::model()->cancelSubscription();
                    unset($_SESSION['u1_vib'], $_SESSION['u1_vib_disc'], $_SESSION['func'], $_SESSION['min_block']);
                }
            }
        }

        Yii::app()->end(CJSON::encode(array('res' => true)));
    }
    
    private function checkAccessPasport($psp1)
    {
        if (Yii::app()->user->isTch) {

            $grants = Yii::app()->user->dbModel->grants;

            if (empty($grants))
                throw new CHttpException(403, 'You don\'t have an access to this service');

            $type = $grants->getGrantsFor(Grants::STUDENT_INFO);

            if ($type == 0)
                throw new CHttpException(403, 'You don\'t have an access to this service');

        } elseif (Yii::app()->user->isStd) {
             if(Yii::app()->user->dbModel->st1!=$psp1)   
                 throw new CHttpException(403, 'You don\'t have an access to this service');
        } else
            throw new CHttpException(403, 'You don\'t have an access to this service');
    }
    
    public function actionStudentPassport($type,$psp1)
    {
        $this->checkAccessPasport($psp1);
        $stInfoForm= new StInfoForm;
        $stInfoForm->getPassport($psp1,$type);
    }
    
    public function actionDeletePassport()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');
        
        $error=false;
        
        $psp1 = Yii::app()->request->getParam('psp1', null);
        $type = Yii::app()->request->getParam('type', null);
        
        if($psp1!=null && $type!=null)
        {
            $this->checkAccessPasport($psp1);
            $stInfoForm= new StInfoForm;
            $stInfoForm->deletePassport($psp1,$type); 
        }else
           $error=true;  

        $res = array(
            'error'       => $error,
        );
        
        Yii::app()->end(CJSON::encode($res));
    }
    
    public function actionShowPassport()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');
        
        $error=false;
        $html='';
        
        $psp1 = Yii::app()->request->getParam('psp1', null);
        $type = Yii::app()->request->getParam('type', null);
        
        if($psp1!=null && $type!=null)
        {
            $this->checkAccessPasport($psp1);
            $stInfoForm= new StInfoForm;
            $html = $this->renderPartial('studentInfo/_show',array(
                'psp1'=>$psp1,
                'type'=>$type
            ), true);
        }else
           $error=true;  

        $res = array(
            'error'       => $error,
            'html'=>$html,
            'title'=>tt('Просмотр')
        );
        
        Yii::app()->end(CJSON::encode($res));
    }
    
     public function actionChangePassport()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');
        
        $error=false;
        $html='';
        
        $psp1 = Yii::app()->request->getParam('psp1', null);
        $type = Yii::app()->request->getParam('type', null);
        
        if($psp1!=null && $type!=null)
        {
            $this->checkAccessPasport($psp1);
            $stInfoForm= new StInfoForm;
            $html = $this->renderPartial('studentInfo/_change',array(
                'psp1'=>$psp1,
                'type'=>$type
            ), true);
        }else
           $error=true;  

        $res = array(
            'error'       => $error,
            'html'=>$html,
            'title'=>tt('Изменить')
        );
        
        Yii::app()->end(CJSON::encode($res));
    }

    public function actionRenderAddSpkr()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        if(PortalSettings::model()->findByPk(72)->ps2==1)
            throw new CHttpException(403, 'Редактирование тем закрыто.');
        $error=false;
        $html='';

        $model=new Spkr;
        $model->unsetAttributes();
        $html = $this->renderPartial('studentInfo/_render_spkr',array('model'=>$model), true);

        $res = array(
            'error'=> $error,
            'html'=>$html,
            'title'=>tt('Добавить тему курсовой')
        );

        Yii::app()->end(CJSON::encode($res));
    }

    public function actionAddSpkr()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        if(PortalSettings::model()->findByPk(72)->ps2==1)
            throw new CHttpException(403, 'Редактирование тем закрыто.');

        $error=false;
        $html='';

        $model=new Spkr;
        $model->unsetAttributes();

        $spkr2= Yii::app()->request->getParam('spkr2', null);
        $spkr3 = Yii::app()->request->getParam('spkr3', null);

        if(empty($spkr2)||empty($spkr3))
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $sql=<<<SQL
                SELECT MAX(spkr1) FROM spkr
SQL;
        $command = Yii::app()->db->createCommand($sql);
        //$command->bindValue(':us1', $ustem2);
        $spkr1 = (int)$command->queryScalar();

        $model->spkr1= $spkr1+1;
        $model->spkr2=$spkr2;
        $model->spkr3=$spkr3;
        $model->spkr4=1;
        $model->spkr5=1;
        $model->spkr6=0;
        $model->spkr8 =  date('Y-m-d H:i:s');
        $model->spkr9=0;

        $res = array(
            'error'=> !$model->save(),
        );

        Yii::app()->end(CJSON::encode($res));
    }
    
     public function actionUploadPassport()
    {
        if (! Yii::app()->request->isPostRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');
        
        $html='';
        
        $psp1 = Yii::app()->request->getParam('psp1', null);
        $type = Yii::app()->request->getParam('type', null);
        
        $error = "";
        $msg = "";
        $result = "";
        $fileElementName = 'document_psp';
        
        if($psp1!=null && $type!=null)
        {
            $this->checkAccessPasport($psp1);
            $stInfoForm= new StInfoForm;
            $files_count = (isset($_FILES[$fileElementName]["name"])) ? count($_FILES[$fileElementName]["name"]) : 0;
            for ($i = 0; $i < $files_count; $i++) {
		if($i > 0) break;

		$_FILES[$fileElementName]['name'][$i] = mb_strtolower($_FILES[$fileElementName]['name'][$i]);
		$_FILES[$fileElementName]['name'][$i] = str_replace(" ","_",$_FILES[$fileElementName]['name'][$i]);		
			
			$res=1;
       if(!empty($_FILES[$fileElementName]['error'][$i]))
			{
				switch($_FILES[$fileElementName]['error'][$i])
				{
					case '1':
						$error = 'размер загруженного файла превышает размер установленный параметром upload_max_filesize  в php.ini ';
						break;
					case '2':
						$error = 'размер загруженного файла превышает размер установленный параметром MAX_FILE_SIZE в HTML форме. ';
						break;
					case '3':
						$error = 'загружена только часть файла ';
						break;
					case '4':
						$error = 'файл не был загружен (Пользователь в форме указал неверный путь к файлу). ';					
						break;
					case '6':
						$error = 'неверная временная дирректория';
						break;
					case '7':
						$error = 'ошибка записи файла на диск';
						break;
					case '8':
						$error = 'загрузка файла прервана';
						break;
					case '999':
					default:
						$error = 'No error code avaiable';
				}
			}elseif(empty($_FILES[$fileElementName]['tmp_name'][$i]) || $_FILES[$fileElementName]['tmp_name'][$i] == 'none')
			{
				$error = 'No file was uploaded..';
                                echo $error;
			}else 
			{				
                            $res=$stInfoForm->setPassport($psp1,$type);
                                
                        }   
                        if(empty($error)&&$res>-1)
                        {
                            Yii::app()->user->setFlash("upload_passport_success", tt('Загрузка успешна!'));
                        }else
                        {
                            if(!empty($error))
                            {
                                Yii::app()->user->setFlash("upload_passport_error", $error);
                            }else
                            {
                                Yii::app()->user->setFlash("upload_passport_error", tt('Ошибка сохранения'));
                            }
                        }
			unset($error);
		}
		echo $result;
            $this->redirect(array('/other/studentinfo'));
            
        }else
        {
            throw new CHttpException(404, '');
        }
        
    }
    
    public function actionSearchStudent()
    {
        $model = new St;
        $model->unsetAttributes();
        if (isset($_REQUEST['St']))
            $model->attributes = $_REQUEST['St'];
		
        $this->render('/timeTable/search_student', array(
            'model' => $model,
            'url'=>array('other/studentInfo')
        ));
    }
    
    public function actionStudentInfo()
    {
        $model = new TimeTableForm;
        $model->scenario = 'student';
        if (isset($_REQUEST['TimeTableForm']))
            $model->attributes=$_REQUEST['TimeTableForm'];

        $canSelectSt = false;
        if (Yii::app()->user->isTch) {

            $grants = Yii::app()->user->dbModel->grants;

            if (empty($grants))
                throw new CHttpException(404, 'You don\'t have an access to this service');

            $type = $grants->getGrantsFor(Grants::STUDENT_INFO);

            if ($type == 0)
                throw new CHttpException(404, 'You don\'t have an access to this service');

            $canSelectSt = true;

        } elseif (Yii::app()->user->isStd) {

            $model->student = Yii::app()->user->dbModel->st1;

        } else
            throw new CHttpException(404, 'You don\'t have an access to this service');

        /*if (! empty($_FILES)) {
            $nkrs1 = Yii::app()->request->getParam('nkrs1', null);
            $nkrs6 = Yii::app()->request->getParam('nkrs6', null);

            $this->proccessAntiplagiat($model->student, $nkrs1, $nkrs6);
        }*/

        $stInfoForm = new StInfoForm();
        $stInfoForm->fillData($model);
        if (isset($_REQUEST['StInfoForm'])) {
            $stInfoForm->attributes=$_REQUEST['StInfoForm'];
            if ($stInfoForm->validate())
                $stInfoForm->customSave($model);
        }
        $student = new St;
        $student->unsetAttributes();
        
        $this->render('studentInfo', array(
            'canSelectSt' => $canSelectSt,
            'stInfoForm'  => $stInfoForm,
            'model' => $model,
            'student'=>$student
        ));
    }

    public  function actionAntiplagiat(){

        $student = St::model()->findByPk(Yii::app()->user->dbModel->st1);

        if(empty($student))
            throw new Exception("Error load student!", 400);

        $nkrsList = $student->getNkrsList();

        list($year, $sem) = SH::getCurrentYearAndSem();

        if(!$student->checkAntiplagiatAccess($year)){
            Yii::app()->user->setFlash('error', tt('Доступ на проверку в Антиплагиат запрещен, превышен лимит проверок'));
            $this->redirect('/other/studentInfo');
        }

        if (! empty($_FILES) && !empty($nkrsList)) {

            $last = $nkrsList[count($nkrsList)-1];
            $nkrs1 = $last['nkrs1'];
            $nkrs6 = $last['nkrs6'];

            $this->proccessAntiplagiat($student->st1, $nkrs1, $nkrs6);
        }

        $this->render('antiplagiat', array(
            'student'=>$student,
            'nkrsList'=>$nkrsList,
            'year'=>$year
        ));
    }

    /**
     * Обработка докумнента для отправки и отправка в антиплагиат
     * @param $student int студент
     * @param $nkrs1 int
     * @param $nkrs6 mixed
     */
    private function proccessAntiplagiat($student, $nkrs1, $nkrs6){
        $st = St::model()->findByPk($student);
        if($st == null) {
            Yii::app()->user->setFlash('error', tt('Ошибка: не найден студент'));
            return false;
        }
        list($year, $sem) = SH::getCurrentYearAndSem();

        if (! empty($_FILES)) {

            $document = CUploadedFile::getInstanceByName('document');
            $tmpName = tempnam(sys_get_temp_dir(), '_');
            $saved = $document->saveAs($tmpName);


            if ($saved) {
                list($id, $url) = $this->sendToAntiPlagiarism($document, $tmpName);
                if ($nkrs1)
                    D::model()->updateNkrs($nkrs1, 'nkrs8', $id);
                Yii::app()->user->setFlash('success', tt('Документ был отправлен на проверку в Антиплагиат'));
                Yii::app()->user->setFlash('info', tt('Результат можно посмотреть здесь:') .' '. CHtml::link(tt('Отчет'), $url, array('target' => '_blank')));

                $_modelLimit = $st->getAntio($year);
                $_modelLimit->antio3++;
                $_modelLimit->save();

                $this->sendEmails($student, $nkrs6, $url);
            }

            unset($_FILES);
        }
    }

    /**
     * Печать заявления на утверждения курсовой в пдф
     * @throws CHttpException
     */
    public function actionStudentInfoPdf()
    {
        $model = new TimeTableForm;
        if (Yii::app()->user->isStd) {

            $model->student = Yii::app()->user->dbModel->st1;

        } else
            throw new CHttpException(404, '1You don\'t have an access to this service');

        $stInfoForm = new StInfoForm();
        $stInfoForm->fillData($model);

        if(empty($model->student))
            throw new CHttpException(404, '2You don\'t have an access to this service');
        else
        {
            $student=St::model()->findByPk($model->student);
            if(empty($student))
                throw new CHttpException(404, '3You don\'t have an access to this service');
            else
            {
                $discipline = D::model()->getDisciplineForCourseWork($model->student);
                if ($discipline) {
                    $courseWork = D::model()->getFirstCourseWork($model->student, $discipline['us1']);
                    //list($rus, $eng) = Spkr::model()->findAllInArray();
                    $nkrs1 = $courseWork['nkrs1'];
                    $p1    = $courseWork['nkrs6'];
                    $nkrs6 = P::model()->getTeacherNameWithDol($courseWork['nkrs6']);
                    $nkrs7 = $courseWork['nkrs7'];
                    $nkrs4 = $courseWork['nkrs4'];
                    $nkrs5 = $courseWork['nkrs5'];
                    list($zav, $kav) = P::model()->getZavKavByTeacher($p1);
                    $k2=$kav['k3'];
                    $st_info=St::model()->getInfoForStudentInfoExcel($model->student);
                    $zav_name=Sh::getShortName($zav['p3'],$zav['p4'],$zav['p5']);
                    /*if(empty($nkrs4))
                    {*/
                        $spkr=Spkr::model()->findByPk($nkrs7);
                        if(!empty($spkr))
                        {
                            $nkrs4=$spkr->spkr2;
                            $nkrs5=$spkr->spkr3;
                        }
                    //}
                    /* @var $mPDF1 mPDF*/
                    $mPDF1 = Yii::app()->ePdf->mpdf();
                    //$mPDF1->showImageErrors = true;

                    $patternTitle = <<<HTML
                        <style>
                        p {
                            text-indent: 20px; /* Отступ первой строки в пикселах */
                        }
                        td,th {
                        border: 1px solid black; /* Параметры рамки */
                       }
                        </style>
                        <div style="margin-left: 50%%">
                            <span>
                                На кафедру <br>
                                %s <br>
                                (заведующий - профессор %s) <br>
                                студента %s курса %s группы <br>
                                %s <br>
                                %s
                            </span>
                        </div>
                        <h2 style="text-align: center">Заявление</h2>

                        <p>Прошу утвердить тему моей курсовой/дипломной работы</p>
                        <table>
                            <tbody>
                                <tr>
                                    <td>Название на русском языке</td>
                                    <td><strong>%s</strong></td>
                                </tr>
                               <tr>
                                    <td>Название на английском языке</td>
                                    <td><strong>%s</strong></td>
                                </tr>
                            </tbody>
                        </table>
                        <p>Научный руководитель %s</p>
                        <p>
                            <div style="float: left; width: 40%%">« ___ » __________ 20__г.</div><div style="float: right;text-align: right; width: 40%%">_____________</div>
                        </p>

                        <p>
                            <div style="float: left; width: 30%%">«не возражаю»</div><div style="float: left; text-align: center;width: 30%%">_____________</div><div style="float: right;text-align: right; width: 30%%">Научный руководитель</div>
                        </p>

                        <p>
                            <div style="float: left; width: 30%%">«не возражаю»</div><div style="float: left; text-align: center;width: 30%%">_____________</div><div style="float: right;text-align: right; width: 30%%">Заведующий кафедрой</div>
                            <br>
                            <div style="float: left; width: 30%%">(только для дипломных работ)</div>
                        </p>
HTML;

                    $mPDF1->WriteHTML(sprintf(
                        $patternTitle,
                        $k2,
                        $zav_name,
                        $st_info['sem4'],
                        $st_info['name'],
                        $st_info['f3'],
                        SH::getShortName($student->st2,$student->st3,$student->st4),
                        $nkrs4,
                        $nkrs5,
                        $nkrs6
                    ));

                    $data=St::model()->getShortCodesImage($model->student);
                    if($data!=null)
                    {
                        //штрихкод
                        $url = $this->createUrl('/site/studentBarcode', array('_id' => $model->student));
                        $htmlBarcode = '<p style="text-align:right;margin-top: 100px;"><img src="'.$url.'" alt=""/><p>';
                        $mPDF1->WriteHTML($htmlBarcode);
                    }


                    $mPDF1->Output();
                }
                else
                    throw new CHttpException(404, '4You don\'t have an access to this service');
            }
        }

    }

    /**
     * печать заявления на утверждение курсовой в ексель
     * @throws CHttpException
     */
    public function actionStudentInfoExcel()
    {
        $model = new TimeTableForm;
        if (Yii::app()->user->isStd) {

            $model->student = Yii::app()->user->dbModel->st1;

        } else
            throw new CHttpException(404, '1You don\'t have an access to this service');

        $stInfoForm = new StInfoForm();
        $stInfoForm->fillData($model);

        if(empty($model->student))
            throw new CHttpException(404, '2You don\'t have an access to this service');
        else
        {
            $student=St::model()->findByPk($model->student);
            if(empty($student))
                throw new CHttpException(404, '3You don\'t have an access to this service');
            else
            {
                $discipline = D::model()->getDisciplineForCourseWork($model->student);
                if ($discipline) {
                    $courseWork = D::model()->getFirstCourseWork($model->student, $discipline['us1']);
                    //list($rus, $eng) = Spkr::model()->findAllInArray();
                    $nkrs1 = $courseWork['nkrs1'];
                    $p1    = $courseWork['nkrs6'];
                    $nkrs6 = P::model()->getTeacherNameWithDol($courseWork['nkrs6']);
                    $nkrs7 = $courseWork['nkrs7'];
                    $nkrs4 = $courseWork['nkrs4'];
                    $nkrs5 = $courseWork['nkrs5'];
                    list($zav, $kav) = P::model()->getZavKavByTeacher($p1);
                    $k2=$kav['k3'];
                    $st_info=St::model()->getInfoForStudentInfoExcel($model->student);
                    $zav_name=Sh::getShortName($zav['p3'],$zav['p4'],$zav['p5']);
                    if(empty($nkrs4))
                    {
                        $spkr=Spkr::model()->findByPk($nkrs7);
                        if(!empty($spkr))
                        {
                            $nkrs4=$spkr->spkr2;
                            $nkrs5=$spkr->spkr3;
                        }
                    }
                    $pattern='студента %s курса %s группы';
                    Yii::import('ext.phpexcel.XPHPExcel');
                    $objPHPExcel= XPHPExcel::createPHPExcel();
                    $objPHPExcel->getProperties()->setCreator("ACY")
                        ->setLastModifiedBy("ACY ".date('Y-m-d H-i'))
                        ->setTitle("Jornal ".date('Y-m-d H-i'))
                        ->setSubject("Jornal ".date('Y-m-d H-i'))
                        ->setDescription("Jornal document, generated using ACY Portal. ".date('Y-m-d H:i:'))
                        ->setKeywords("")
                        ->setCategory("Result file");
                    $objPHPExcel->setActiveSheetIndex(0);
                    $sheet=$objPHPExcel->getActiveSheet();
                    $sheet->getColumnDimension('A')->setWidth(30);
                    $sheet->getColumnDimension('B')->setWidth(20);
                    $sheet->getColumnDimension('C')->setWidth(20);
                    $sheet->getColumnDimension('D')->setWidth(20);
                    $sheet->mergeCells('C1:D1')
                        ->setCellValue('C1', "На кафедру");
                    $sheet->mergeCells('C2:D2')
                        ->setCellValue('C2', $k2);
                    $sheet->getRowDimension(2)->setRowHeight(40);
                    $sheet->mergeCells('C3:D3')
                        ->setCellValue('C3',  '(заведующий - профессор '. $zav_name.')');
                    $sheet->mergeCells('C4:D4')
                        ->setCellValue('C4', sprintf($pattern,$st_info['sem4'],$st_info['name']));
                    /*$sheet->mergeCells('C5:D5')
                        ->setCellValue('C5', 'Форма обучения: '.SH::convertEducationType($st_info['sg4']));*/
                    $sheet->mergeCells('C5:D5')
                        ->setCellValue('C5', $st_info['f3']);
                    $sheet->getRowDimension(5)->setRowHeight(40);
                    $sheet->mergeCells('C6:D6')
                        ->setCellValue('C6', SH::getShortName($student->st2,$student->st3,$student->st4));
                    $sheet->getStyleByColumnAndRow(0,1,3,6)->getAlignment()->setWrapText(true)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
                    $sheet->mergeCells('A7:D7')
                        ->setCellValue('A7', 'Заявление')->getStyle('A7')->getFont()->setSize(18);
                    $sheet->getStyle('A7')->getAlignment()-> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $sheet->getRowDimension(7)->setRowHeight(40);
                    $sheet->mergeCells('A8:D8')
                        ->setCellValue('A8', '   Прошу утвердить тему моей курсовой/дипломной работы');
                    $sheet->setCellValue('A9', 'Название на русском языке')->getStyle('A9')->getAlignment()-> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $sheet->setCellValue('A10', 'Название на английском языке')->getStyle('A10')->getAlignment()-> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $sheet->mergeCells('B9:D9')
                        ->setCellValue('B9', $nkrs4)->getStyle('B9')->getAlignment()->setWrapText(true)-> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $sheet->mergeCells('B10:D10')
                        ->setCellValue('B10', $nkrs5)->getStyle('B10')->getAlignment()->setWrapText(true)-> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $sheet->getStyleByColumnAndRow(0,9,3,10)->getBorders()->getAllBorders()->applyFromArray(array('style'=>PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => '000000')));
                    $sheet->getRowDimension(9)->setRowHeight(50);
                    $sheet->getRowDimension(10)->setRowHeight(50);
                    $sheet->mergeCells('A11:D11')->setCellValue('A11', 'Научный руководитель '.$nkrs6);
                    $sheet->setCellValue('A13','« ___ » __________ 20__г.');
                    $sheet->setCellValue('D13','__________');
                    $sheet->setCellValue('A15','«не возражаю»');
                    $sheet->setCellValue('D15','Научный руководитель');
                    $sheet->mergeCells('B15:C15')->setCellValue('B15', '____________________');
                    $sheet->setCellValue('A17','«не возражаю»');
                    $sheet->mergeCells('B17:C17')->setCellValue('B17', '____________________');
                    $sheet->setCellValue('D17','Заведующий кафедрой');
                    $sheet->setCellValue('A18','(только для дипломных работ)')->getStyle('A18')->getAlignment()->setWrapText(true)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
                    $sheet->getRowDimension(18)->setRowHeight(30);

                    $data=St::model()->getShortCodesImage($model->student);
                    if($data!=null)
                    {
                        $sheet->setCellValue('A19','Штрихкод');
                        $objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
                        $objDrawing->setName('logo');
                        $objDrawing->setImageResource( imagecreatefromstring($data));
                        $objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_DEFAULT);
                        $objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
                        $objDrawing->setHeight(100);
                        $objDrawing->setCoordinates('A20');
                        $objDrawing->setOffsetX(10);
                        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
                    }

                    $sheet->getProtection()->setSheet(true);
                    $sheet->getProtection()->setSort(true);
                    $sheet->getProtection()->setInsertRows(true);
                    $sheet->getProtection()->setFormatCells(true);

                    $sheet->getProtection()->setPassword('TNDF12451ghtreds54213');

                    $sheet->setTitle('Заявление '.date('Y-m-d H-i'));
                    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
                    $objPHPExcel->setActiveSheetIndex(0);

                    // Redirect output to a clientâ€™s web browser (Excel5)
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="ACY_'.date('Y-m-d H-i').'.xls"');
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
                    Yii::app()->end();
                }
                else
                    throw new CHttpException(404, '4You don\'t have an access to this service');
            }
        }
        /*$this->render('studentInfo', array(
            'stInfoForm'  => $stInfoForm,
        ));*/
    }

    public function sendToAntiPlagiarism($document, $tmpName)
    {
        //$ANTIPLAGIAT_URI = Yii::app()->params['antiPlagiarism']['antiplagiat_uri'];

        // Создать клиента сервиса(http, unsecured)
        //$COMPANY_NAME = Yii::app()->params['antiPlagiarism']['company_name'];
        //$APICORP_ADDRESS = Yii::app()->params['antiPlagiarism']['apicorp_address'];
        /*$client = new SoapClient("http://$APICORP_ADDRESS/apiCorp/$COMPANY_NAME?singleWsdl",
            array("trace"=>1,
                "soap_version" => SOAP_1_1,
                "features" => SOAP_SINGLE_ELEMENT_ARRAYS));*/

        //$LOGIN = "testapi";////начало
        //$PASSWORD = "testapi";
        //$COMPANY_NAME = 'testapi';
        //$APICORP_ADDRESS = 'api.antiplagiat.ru:5433';

        $LOGIN = PortalSettings::model()->findByPk(68)->ps2;
        $PASSWORD = PortalSettings::model()->findByPk(69)->ps2;
        $COMPANY_NAME = PortalSettings::model()->findByPk(70)->ps2;
        $APICORP_ADDRESS = PortalSettings::model()->findByPk(71)->ps2;

        $client = new SoapClient("https://$APICORP_ADDRESS/apiCorp/$COMPANY_NAME?singleWsdl",
            array("trace"=>1,
                "login"=>$LOGIN,
                "password"=>$PASSWORD,
                "soap_version" => SOAP_1_1,
                "features" => SOAP_SINGLE_ELEMENT_ARRAYS,
                //"timeout" => 300,
                # PHP 5.6
                "stream_context"=>stream_context_create(
                    array(
                        "ssl"=>array(
                            "verify_peer"=>false,
                            "allow_self_signed"=>true,
                            "verify_peer_name"=>false
                        )
                    )
                )));


// Используется для получения ссылок на отчеты
        $ANTIPLAGIAT_URI = "http://$COMPANY_NAME.antiplagiat.ru";///конец

        // Описание загружаемого файла
        $data = array(
            "Data"     => file_get_contents($tmpName),
            "FileName" => $document->name,
            "FileType" => '.'.$document->extensionName
        );

        // Загрузка файла
        $uploadResult = $client->UploadDocument(array("data"=>$data));

        // Идентификатор документа. Если загружается не архив, то список загруженных документов будет состоять из одного элемента.
        $id = $uploadResult->UploadDocumentResult->Uploaded[0]->Id;

        // Иницировать проверку с использованием собственного модуля поиска и модуля поиска "wikipedia"
        $client->CheckDocument(array("docId" => $id));

        // Получить текущий статус последней проверки
        $status = $client->GetCheckStatus(array("docId" => $id));

        // Цикл ожидания окончания проверки
        while ($status->GetCheckStatusResult->Status === "InProgress")
        {
            sleep($status->GetCheckStatusResult->EstimatedWaitTime * 0.2);
            $status = $client->GetCheckStatus(array("docId" => $id));
        }

        // Проверка закончилась неудачно.
        if ($status->GetCheckStatusResult->Status === "Failed")
        {
            echo("При проверке документа произошла ошибка:" . $status->GetCheckStatusResult->FailDetails);
            return;
        }

        $url = $ANTIPLAGIAT_URI.$status->GetCheckStatusResult->Summary->ReportWebId;
        return array($id->Id, $url);
    }

    public function actionAutocompleteTeachers()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $query = Yii::app()->request->getParam('query', null);

        $teachers = P::model()->findTeacherByName($query);

        foreach($teachers as $tch)
        {
            $t = '';
            if ($tch['pd7'] == 1)
                $t = 'совм.';
            elseif ($tch['pd7'] == 3 || $tch['pd7'] == 5)
                $t = 'почас.';
            elseif ($tch['pd7'] == 4)
                $t = 'совмещ.';

            $suggestions[] = array(
                'value' => implode(' ', array($tch['dol2'], SH::getShortName($tch['p3'], $tch['p4'], $tch['p5']))) .' '. $t,
                'p1'    => $tch['p1']
            );
        }

        $res = array(
            'query'       => $query,
            'suggestions' => $suggestions,
        );

        Yii::app()->end(CJSON::encode($res));
    }

    public function actionUpdateNkrs()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        if(PortalSettings::model()->findByPk(72)->ps2==1)
            throw new CHttpException(403, 'Редактирование тем закрыто.');

        $field = Yii::app()->request->getParam('field', null);
        $value = Yii::app()->request->getParam('value', null);
        $nkrs1 = Yii::app()->request->getParam('nkrs1', null);
        $st1 = Yii::app()->request->getParam('st1', null);
        $us1 = Yii::app()->request->getParam('us1', null);

        $sg1=St::model()->getSg1BySt1($st1);

        if(Cwb::model()->findByPk($sg1)!==null)
            throw new CHttpException(403, 'Редактирование тем закрыто.');

        if (! in_array($field, array('nkrs6', 'nkrs7')))
            throw new CHttpException(404, '1Invalid request. Please do not repeat this request again.');

        $nkrs=Nkrs::model()->findByPk($nkrs1);
        if(!empty($nkrs))
            $res = D::model()->updateNkrs($nkrs1, $field, $value);
        else
        {
            $nkrs= new Nkrs();
            $nkrs->nkrs1=new CDbExpression('GEN_ID(GEN_NKRS, 1)');
            $nkrs->nkrs2=$st1;
            $nkrs->nkrs3=$us1;
            $nkrs->nkrs4='';
            $nkrs->nkrs5='';
            if($field=='nkrs6')
                $nkrs->nkrs7=0;
            else
                $nkrs->nkrs6=0;
            $nkrs->nkrs8='';
            //$nkrs->$field=$value;
            $res=$nkrs->save();
            /*if(!$res)
            {
                print_r($nkrs->getErrors());
               */ //throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');
            /*}*/

        }

        Yii::app()->end(CJSON::encode(array('res' => $res)));
    }

    /**
     * @param $st1 int student id
     * @param $p1 int teacher id (nkrs6)
     * @param $url string url antiplagiat report
     */
    public function sendEmails($st1, $p1, $url)
    {
        $student = Users::model()->find('u5 = 0 and u6 = '.$st1);

        $st = St::model()->findByPk($st1);
        $studentName = $st->getShortName();

        $ps118 = PortalSettings::model()->getSettingFor(118);
        $body = '{student} You can find you antiplagiat results here: {link}';
        if(!empty($ps118))
            $body = $ps118;

        $link = tt('Отчет');
        $body = str_replace('{student}',$studentName,$body);
        $body = str_replace('{link}','<a href="'.$url.'">'.$link.'</a>',$body);

        if (! empty($student)) {

            if ($student->u4) {
                /*Apostle::setup("a596c9f9cb4066dd716911ef92be9bd040b0664d");
                $mail = new Mail( "antiplagiat-notification", array( "email" => $student->u4 ) );
                $mail->url  = $url;
                $mail->textFrom = implode(' ', array($st->st2, $st->st3, $st->st4));
                $mail->deliver();*/
                $message = str_replace('{username}',$student->u2,$body);
                $message = str_replace('{name}',$studentName,$message);

                list($status, $message)  = $this->mailsend($student->u4, 'Antiplagiat results', $message);

                if(!$status)
                    Yii::app()->user->setFlash('error', tt('Ошибка отправки email. Текст ошибки: ').$message);
            }

        }

        if ($p1 && PortalSettings::model()->getSettingFor(121)==1 ) {
            $teacher = Users::model()->find('u5 = 1 and u6 = '.$p1);
            if(empty($teacher))
                return;

            if ($teacher->u4) {

                $p = P::model()->findByPk($p1);
                /*Apostle::setup("a596c9f9cb4066dd716911ef92be9bd040b0664d");
                $mail = new Mail( "antiplagiat-notification", array( "email" => $teacher->u4 ) );
                $mail->url  = $url;
                $mail->textFrom = implode(' ', array($st->st2, $st->st3, $st->st4));
                $mail->deliver();*/

                $message = str_replace('{username}',$teacher->u2,$body);
                $message = str_replace('{name}',$p->getShortName(),$message);

                list($status, $message) =  $this->mailsend($teacher->u4, 'Antiplagiat results '.$studentName, $message);
                if(!$status)
                    Yii::app()->user->setFlash('error', tt('Ошибка отправки email для преподавателя. Текст ошибки: ').$message);
            }
        }

    }
}
