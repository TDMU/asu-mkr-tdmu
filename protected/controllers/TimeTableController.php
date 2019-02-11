<?php

class TimeTableController extends Controller
{
    public function beforeAction($action){

        $result = parent::beforeAction($action);
        if($this->mobileCheck())
            if(in_array($action->id, array(
                'group', 'student', 'teacher', 'self'
            ))) {
                if (in_array($this->universityCode, array(
                    U_XNMU,
                    U_NMU,
                    U_KRNU,
                    U_KNAME,
                    U_NULAU,
                    U_KHADI,
                    U_URFAK,
                    U_FGU,
                    U_UIPA,
                    U_SEM_MGU,
                    U_ONMU,
                    U_NOBEL,
                    U_IRPEN,
                    U_FARM,
                    U_KIEV_MVD,
                    U_UMAN,
                    U_RGIIS,
                    U_KNU,
                    U_HTEI
                ))){
                        $message = tt(' <br>Новое мобильное приложение для Android : <strong><a href="{url}" target="_blank" style="font-size: 18px">здесь</a></strong>! <br>Новое мобильное приложение для iOs : <strong><a href="{url_apple}" target="_blank" style="font-size: 18px">здесь</a></strong>! <br>Также читайте инструкцию к мобильному приложению: <strong><a href="{url-instruction}" target="_blank" style="font-size: 18px">здесь</a></strong>!', array(
                            '{url}' => SH::MOBILE_URL,
                            '{url_apple}' => SH::MOBILE_URL_APPLE,
                            '{url-instruction}' => SH::MOBILE_URL_INSTRUCTION
                        ));
                    Yii::app()->user->setFlash('info', '<strong>' . tt('Внимание!') . '</strong>' . $message);
                }
            }

        return $result;
    }

    public function filters() {

        return array(
            'accessControl',
        );
    }

    public function accessRules() {

        return array(
            array('allow',
                'actions' => array(
                    'self',
                    'selfExcel'
                ),
                'expression' => 'Yii::app()->user->isStd || Yii::app()->user->isTch',
            ),
            /*array('deny',
                'users' => array('*'),
            ),*/
        );
    }

    public  function actionSelf()
    {
        /*if($this->mobileCheck())
            $this->redirect('/mobile/timeTableSelf');*/
        $model = new TimeTableForm;
        //$model->scenario = 'self';
        if (isset($_REQUEST['TimeTableForm']))
            $model->attributes=$_REQUEST['TimeTableForm'];
        $model->date1 = Yii::app()->session['date1'];
        $model->date2 = Yii::app()->session['date2'];
        $type=Yii::app()->user->getState('timeTable',Yii::app()->params['timeTable']);
        $timeTable = $minMax = $maxLessons= array();
        $rasp=0;
        if(Yii::app()->user->isStd)
        {
            $model->student=Yii::app()->user->dbModel->st1;
            if($type==0)
                list($minMax, $timeTable, $maxLessons) = $model->generateStudentTimeTable();
            else
                $timeTable=Gr::getTimeTable($model->student, $model->date1, $model->date2, 1);
            $rasp=1;
        }
        elseif(Yii::app()->user->isTch)
        {
            $model->teacher=Yii::app()->user->dbModel->p1;
            if($type==0)
                list($minMax, $timeTable, $maxLessons) = $model->generateTeacherTimeTable();
            else
                $timeTable=Gr::getTimeTable($model->teacher, $model->date1, $model->date2, 2);
            $rasp=2;

        }else
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $this->render('self', array(
            'model'      => $model,
            'timeTable'  => $timeTable,
            'minMax'     => $minMax,
            'maxLessons' => $maxLessons,
            'rasp'=>$rasp,
            'rz'         => Rz::model()->getRzArray($model->filial),
            'type'=>$type
        ));
    }

    public function actionSelfExcel()
    {
        $model = new TimeTableForm;
        //$model->scenario = 'self';
        if (isset($_REQUEST['TimeTableForm']))
            $model->attributes=$_REQUEST['TimeTableForm'];
        $model->date1 = Yii::app()->session['date1'];
        $model->date2 = Yii::app()->session['date2'];

        $model->printAttr = Yii::app()->request->getParam('type', 0);
        Yii::app()->session['printAttr'] = $model->printAttr;

        $timeTable = $minMax = $maxLessons = array();
        $title=tt('Расписание личное');
        if(Yii::app()->user->isStd)
        {
            $title=tt('Расписание студента: Личное');
            $model->student=Yii::app()->user->dbModel->st1;
            list($minMax, $timeTable) = $model->generateStudentTimeTable();
        }
        elseif(Yii::app()->user->isTch)
        {
            $title=tt('Расписание преподавателя: Личное');
            $model->teacher=Yii::app()->user->dbModel->p1;
            list($minMax, $timeTable) = $model->generateTeacherTimeTable();

        }else
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');
        $rz= Rz::model()->getRzArray($model->filial);
        $this->generateExcel($timeTable,$minMax,$maxLessons,$rz,$model,$title);

    }

	public function actionSearchTeacher()
    {
        $model = new P;
        $model->unsetAttributes();
        if (isset($_REQUEST['P']))
            $model->attributes = $_REQUEST['P'];
		
        $this->render('search_teacher', array(
            'model' => $model,
        ));
	}
	
    public function actionSearchStudent()
    {
        $model = new St;
        $model->unsetAttributes();
        if (isset($_REQUEST['St']))
            $model->attributes = $_REQUEST['St'];
		
        $this->render('search_student', array(
            'model' => $model,
            'url'=>array('timeTable/student')
        ));
    }
    
    public function actionChair()
    {
        $model = new TimeTableForm;
        $model->scenario = 'chair';
        if (isset($_REQUEST['TimeTableForm']))
            $model->attributes=$_REQUEST['TimeTableForm'];
        $model->date1 = Yii::app()->session['date1'];
        $model->date2 = Yii::app()->session['date2'];
        $datetime1 = new DateTime($model->date1);
        $datetime2 = new DateTime($model->date2);
        $interval = $datetime1->diff($datetime2);

        if ($interval->days >= 23)
        {
            $date2 = date('d.m.Y', strtotime('+3 week', strtotime($model->date1)));
            Yii::app()->session['date2'] = $date2;
            $model->date2 = Yii::app()->session['date2'];
        }

        $this->render('chair', array(
            'model'      => $model,
        ));
    }

    public function actionChairByGroup()
    {
        $model = new TimeTableForm;
        $model->scenario = 'chair';
        if (isset($_REQUEST['TimeTableForm']))
            $model->attributes=$_REQUEST['TimeTableForm'];
        $model->date1 = Yii::app()->session['date1'];
        $model->date2 = Yii::app()->session['date2'];
        $datetime1 = new DateTime($model->date1);
        $datetime2 = new DateTime($model->date2);
        $interval = $datetime1->diff($datetime2);

        if ($interval->days >= 23)
        {
            $date2 = date('d.m.Y', strtotime('+3 week', strtotime($model->date1)));
            Yii::app()->session['date2'] = $date2;
            $model->date2 = Yii::app()->session['date2'];
        }

        $this->render('chair-by-group', array(
            'model'      => $model,
        ));
    }

    /**
     * Загрузка для прямой ссылки
     * @param $model TimeTableForm
     * @return TimeTableForm
     */
    private  function teacherRasp($model){
        if (isset($_GET['teacherId'])) {
            $model->loadByP1((int)strip_tags($_GET['teacherId']));
            unset($_GET['teacherId']);
        }

        return $model;
    }

    public function actionTeacher()
    {
        /*if($this->mobileCheck())
            $this->redirect('/mobile/timeTableTeacher');*/

        $model = new TimeTableForm;
        $model->scenario = 'teacher';

        $model = $this->teacherRasp($model);
		
		if (isset($_REQUEST['timeTable'])) {
            Yii::app()->user->setState('timeTable',(int)$_REQUEST['timeTable']);
            unset($_REQUEST['timeTable']);
        }
        if (isset($_REQUEST['TimeTableForm']))
            $model->attributes=$_REQUEST['TimeTableForm'];
		$type=Yii::app()->user->getState('timeTable',Yii::app()->params['timeTable']);
        $model->date1 = Yii::app()->session['date1'];
        $model->date2 = Yii::app()->session['date2'];

        $timeTable = $minMax = $maxLessons = array();
        if (! empty($model->teacher))
			if($type==0)
				list($minMax, $timeTable, $maxLessons) = $model->generateTeacherTimeTable();
			else
				$timeTable=Gr::getTimeTable($model->teacher, $model->date1, $model->date2, 2);

		$teacher = new P;
        $teacher->unsetAttributes();
		
        $this->render('teacher', array(
            'model'      => $model,
			'teacher'	 =>$teacher,
            'timeTable'  => $timeTable,
            'minMax'     => $minMax,
            'maxLessons' => $maxLessons,
            'rz'         => Rz::model()->getRzArray($model->filial),
			'type'=>$type
        ));
    }

    public function actionGroupExcel()
    {
        $model = new TimeTableForm;
        $model->scenario = 'group';

        if (isset($_REQUEST['TimeTableForm']))
            $model->attributes=$_REQUEST['TimeTableForm'];

        $model->printAttr = Yii::app()->request->getParam('type', 0);
        Yii::app()->session['printAttr'] = $model->printAttr;

        $model->date1 = Yii::app()->session['date1'];
        $model->date2 = Yii::app()->session['date2'];

        $timeTable = $minMax = $maxLessons = array();
        if (! empty($model->group))
        {
            $title=tt('Расписание академ. группы');
            $gr=Gr::model()->findByPk($model->group);
            $title.=':'.Gr::model()->getGroupName($model->course,$gr);
            list($minMax, $timeTable, $maxLessons) = $model->generateGroupTimeTable();
            $rz= Rz::model()->getRzArray($model->filial);
            $this->generateExcel($timeTable,$minMax,$maxLessons,$rz,$model,$title);

        }else
            throw new CHttpException(404, '4Invalid request. Please do not repeat this request again.');

    }

    public function actionStudentExcel()
    {
        $model = new TimeTableForm;
        $model->scenario = 'student';

        if (isset($_REQUEST['TimeTableForm']))
            $model->attributes=$_REQUEST['TimeTableForm'];

        $model->date1 = Yii::app()->session['date1'];
        $model->date2 = Yii::app()->session['date2'];

        $model->printAttr = Yii::app()->request->getParam('type', 0);
        Yii::app()->session['printAttr'] = $model->printAttr;

        $timeTable = $minMax = $maxLessons = array();
        if (! empty($model->student))
        {
            $title=tt('Расписание студента');
            $gr=Gr::model()->findByPk($model->group);
            $title.=' Группы '.Gr::model()->getGroupName($model->course,$gr);
            $st=St::model()->getStudentName($model->student);
            if(!empty($st))
                $title.=' '.ShortCodes::getShortName($st[0]['st2'],$st[0]['st3'],$st[0]['st4']);
            list($minMax, $timeTable) = $model->generateStudentTimeTable();
            $rz= Rz::model()->getRzArray($model->filial);
            $this->generateExcel($timeTable,$minMax,$maxLessons,$rz,$model,$title);

        }else
            throw new CHttpException(404, '4Invalid request. Please do not repeat this request again.');

    }

    public function actionClassroomExcel()
    {
        $model = new TimeTableForm;
        $model->scenario = 'classroom';

        if (isset($_REQUEST['TimeTableForm']))
            $model->attributes=$_REQUEST['TimeTableForm'];

        $model->date1 = Yii::app()->session['date1'];
        $model->date2 = Yii::app()->session['date2'];

        $model->printAttr = Yii::app()->request->getParam('type', 0);
        Yii::app()->session['printAttr'] = $model->printAttr;

        $timeTable = $minMax = $maxLessons = array();
        /*if ($model->validate())
            list($minMax, $timeTable) = $model->generateClassroomTimeTable();*/

        if ($model->validate())
        {
            $title=tt('Расписание аудитории');
            $a=A::model()->findByPk($model->classroom);
            $title.=' '.$a->a2;
            list($minMax, $timeTable) = $model->generateClassroomTimeTable();
            $rz= Rz::model()->getRzArray($model->filial);
            $this->generateExcel($timeTable,$minMax,$maxLessons,$rz,$model,$title);

        }else
            throw new CHttpException(404, '4Invalid request. Please do not repeat this request again.');
    }

    public function actionTeacherExcel()
    {
        $model = new TimeTableForm;
        $model->scenario = 'teacher';

        if (isset($_REQUEST['TimeTableForm']))
            $model->attributes=$_REQUEST['TimeTableForm'];

        $model->printAttr = Yii::app()->request->getParam('type', 0);
        Yii::app()->session['printAttr'] = $model->printAttr;

        $model->date1 = Yii::app()->session['date1'];
        $model->date2 = Yii::app()->session['date2'];

        $timeTable = $minMax = $maxLessons = array();
        if (! empty($model->teacher))
        {
            $title=tt('Расписание преподавателя');
            $p=P::model()->getTeacherNameBy($model->teacher,true);
            $title.=' '.$p;
            list($minMax, $timeTable) = $model->generateTeacherTimeTable();
            $rz= Rz::model()->getRzArray($model->filial);
            $this->generateExcel($timeTable,$minMax,$maxLessons,$rz,$model,$title);

        }else
            throw new CHttpException(404, '4Invalid request. Please do not repeat this request again.');

    }

    private function generateExcel($timeTable,$minMax,$maxLessons,$rz,$model,$title)
    {
        Yii::import('ext.phpexcel.XPHPExcel');
        $objPHPExcel= XPHPExcel::createPHPExcel();
        $objPHPExcel->getProperties()->setCreator("ACY")
            ->setLastModifiedBy("ACY ".date('Y-m-d H-i'))
            ->setTitle("timeTable ".date('Y-m-d H-i'))
            ->setSubject("timeTable ".date('Y-m-d H-i'))
            ->setDescription("timeTable document, generated using ACY Portal. ".date('Y-m-d H:i:'))
            ->setKeywords("")
            ->setCategory("Result file");
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet=$objPHPExcel->getActiveSheet();
        $sheet->setTitle('timeTable '.date('Y-m-d H-i'));

        $sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->SetPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
        $sheet->getPageMargins()->setTop(0.1);
        $sheet->getPageMargins()->setRight(0.1);
        $sheet->getPageMargins()->setLeft(0.1);
        $sheet->getPageMargins()->setBottom(0.1);

        $sheet->getColumnDimension('A')->setWidth(9);

        $sheet->mergeCells('A1:D1');
        $sheet->setCellValue('A1',$title);
        $sheet->mergeCells('A2:D2');
        $sheet->setCellValue('A2',$model->date1.'-'.$model->date2);
        $sheet->getStyleByColumnAndRow(0, 1,1,2)->getAlignment()->setHorizontal(
            PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        // Устанавливаем шрифт
        $sheet->getStyleByColumnAndRow(0, 1,1,2)->getFont()->setName('Arial')->setSize(13);
        // Применяем заливку
        $sheet->getStyleByColumnAndRow(0, 1,1,2)->getFill()->
            setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getRowDimension(1)->setRowHeight(13);
        $sheet->getRowDimension(2)->setRowHeight(13);

        $timestamps    = array_keys($timeTable);
        $amountOfWeeks =  ceil(((current($timestamps) - end($timestamps))/86400) / -7);
        reset($timestamps);
        $stroka=2;
        $header_height=19;
        $column_width=25;
        foreach(range(1,7) as $dayOfWeek) {
            $str=$stroka;
            $name = $minMax['names'][$dayOfWeek];
            if(Yii::app()->params['fixedCountLesson']!=1)
            {
                $min  = $minMax['min'][$dayOfWeek];
                $max  = $minMax['max'][$dayOfWeek];
            }else
            {
                $min  = 1;
                $max  = Yii::app()->params['countLesson'];
            }
            $stroka++;

            $sheet->setCellValueByColumnAndRow(0,$stroka,$name);
            $sheet->getStyleByColumnAndRow(0, $stroka)->getAlignment()->setHorizontal(
                PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setWrapText(true);
                // Устанавливаем шрифт
            $sheet->getStyleByColumnAndRow(0, $stroka)->getFont()->setName('Arial')->setSize(15)->getColor()->applyFromArray(array('rgb' => 'ffffff'));
                // Применяем заливку
            $sheet->getStyleByColumnAndRow(0, $stroka)->getFill()->
                setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $sheet->getStyleByColumnAndRow(0, $stroka)->getFill()->
                getStartColor()->applyFromArray(array('rgb' => '6FB3E0'));
            $sheet->getRowDimension($stroka)->setRowHeight($header_height);

            foreach (range($min, $max) as $lesson) {
                $start=isset($rz[$lesson]) ? $rz[$lesson]['rz2']: null;
                $finish=isset($rz[$lesson]) ? $rz[$lesson]['rz3']: null;
                $tmp=$lesson.' '.tt('пара').': '.$start.'-'.$finish;
                $stroka++;
                $sheet->setCellValueByColumnAndRow(0,$stroka,$tmp)->getStyleByColumnAndRow(0, $stroka)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setWrapText(true);
                $sheet->getStyleByColumnAndRow(0, $stroka)->getFont()->setName('Arial')->setSize(12)->getColor()->applyFromArray(array('rgb' => '000000'));
                $sheet->getRowDimension($stroka)->setRowHeight(-1);
            }

            $stroka_day=$str;
            $firstTs = $timestamps[0];
            for($week = 0; $week < $amountOfWeeks; $week++) {

                $day  = $firstTs + $week*7*86400; // timestamp of current day
                $tt   = $timeTable[$day]['timeTable'];

                $date = $timeTable[$day]['date'];

                //$stroka_day++;
                $sheet->setCellValueByColumnAndRow($week+1,$stroka_day+1,$date);
                $sheet->getStyleByColumnAndRow($week+1, $stroka_day+1)->getAlignment()->setHorizontal(
                    PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                // Устанавливаем шрифт
                $sheet->getStyleByColumnAndRow($week+1, $stroka_day+1)->getFont()->setName('Arial')->setSize(15)->getColor()->applyFromArray(array('rgb' => 'ffffff'));
                // Применяем заливку
                $sheet->getStyleByColumnAndRow($week+1, $stroka_day+1)->getFill()->
                    setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                $sheet->getStyleByColumnAndRow($week+1, $stroka_day+1)->getFill()->
                    getStartColor()->applyFromArray(array('rgb' => '6FB3E0'));
                $sheet->getRowDimension($stroka_day+1)->setRowHeight($header_height);

                $k=1;
                foreach (range($min, $max) as $lesson) {
                    $k++;
                    $less = '';
                    if (isset($tt[$lesson])) {
                        $less = CHtml::decode($tt[$lesson]['printText']);
                        $color = $tt[$lesson]['color'];
                        $color=str_replace('#','',$color);
                        $sheet->getStyleByColumnAndRow($week+1, $stroka_day+$k)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                        $sheet->getStyleByColumnAndRow($week+1, $stroka_day+$k)->getFill()->getStartColor()->applyFromArray(array('rgb' => $color));
                    }
                    $sheet->setCellValueByColumnAndRow($week+1,$stroka_day+$k,$less)->getStyleByColumnAndRow($week+1,$stroka_day+$k)->getAlignment()->setWrapText(true);
                }
                $sheet->getColumnDimensionByColumn($week+1)->setWidth($column_width);
            }
            array_shift($timestamps);
        }
        $sheet->getStyleByColumnAndRow(0,3,$amountOfWeeks,$stroka)->getBorders()->getAllBorders()->applyFromArray(array('style'=>PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => '000000')));

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a clientâ€™s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="ACY_TIMETABLE_'.date('Y-m-d H-i').'.xls"');
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

    public function actionGroup()
    {
        $model = new TimeTableForm;
        $model->scenario = 'group';
		
        if (isset($_REQUEST['timeTable'])) {
            Yii::app()->user->setState('timeTable',(int)$_REQUEST['timeTable']);
            unset($_REQUEST['timeTable']);
        }
        if (isset($_REQUEST['TimeTableForm']))
            $model->attributes=$_REQUEST['TimeTableForm'];
        $type=Yii::app()->user->getState('timeTable',Yii::app()->params['timeTable']);
        $model->date1 = Yii::app()->session['date1'];
        $model->date2 = Yii::app()->session['date2'];
		
        $timeTable = $minMax = $maxLessons = array();
        if (! empty($model->group))
        {
            if($type==0)
               list($minMax, $timeTable, $maxLessons) = $model->generateGroupTimeTable();
            else
                    $timeTable=Gr::getTimeTable($model->group, $model->date1, $model->date2, 0);

        }
		
        $this->render('group', array(
            'model'      => $model,
            'timeTable'  => $timeTable,
            'minMax'     => $minMax,
            'maxLessons' => $maxLessons,
            'rz'         => Rz::model()->getRzArray($model->filial),
            'type'=>$type
        ));
    }

    public function actionStudent()
    {
        /*if($this->mobileCheck())
            $this->redirect('/mobile/timeTableStudent');*/

        $model = new TimeTableForm;
        $model->scenario = 'student';
		
		if (isset($_REQUEST['timeTable'])) {
            Yii::app()->user->setState('timeTable',(int)$_REQUEST['timeTable']);
            unset($_REQUEST['timeTable']);
        }
		
        if (isset($_REQUEST['TimeTableForm']))
            $model->attributes=$_REQUEST['TimeTableForm'];
		
		$type=Yii::app()->user->getState('timeTable',Yii::app()->params['timeTable']);
        $model->date1 = Yii::app()->session['date1'];
        $model->date2 = Yii::app()->session['date2'];

        $timeTable = $minMax = $maxLessons = array();
        if (! empty($model->student))
			if($type==0)
				list($minMax, $timeTable, $maxLessons) = $model->generateStudentTimeTable();
			else
				$timeTable=Gr::getTimeTable($model->student, $model->date1, $model->date2, 1);

		$student = new St;
        $student->unsetAttributes();
		
        $this->render('student', array(
            'model'      => $model,
            'timeTable'  => $timeTable,
            'maxLessons' => $maxLessons,
            'minMax'     => $minMax,
            'rz'         => Rz::model()->getRzArray($model->filial),
			'type'=>$type,
			'student'=>$student
        ));
    }

    public function actionClassroom()
    {
        $model = new TimeTableForm;
        $model->scenario = 'classroom';

        if (isset($_REQUEST['TimeTableForm']))
            $model->attributes=$_REQUEST['TimeTableForm'];

        $model->date1 = Yii::app()->session['date1'];
        $model->date2 = Yii::app()->session['date2'];

        $timeTable = $minMax = $maxLessons = array();
        if ($model->validate())
            list($minMax, $timeTable, $maxLessons) = $model->generateClassroomTimeTable();


        $this->render('classroom', array(
            'model'      => $model,
            'timeTable'  => $timeTable,
            'minMax'     => $minMax,
            'maxLessons' => $maxLessons,
            'rz'         => Rz::model()->getRzArray($model->filial),
        ));
    }

    public function actionFreeClassroom()
    {
        $model = new TimeTableForm;
        $model->scenario = 'free-classroom';

        $model->date1 = Yii::app()->session['date1'];
        $model->date2 = Yii::app()->session['date2'];

        $classrooms = $occupiedRooms = array();
        if (isset($_REQUEST['TimeTableForm'])){

            $model->attributes=$_REQUEST['TimeTableForm'];

            if ($model->validate()) {
                $classrooms    = A::model()->getClassRooms($model->filial, $model->housing);
                $occupiedRooms = A::model()->getOccupiedRooms($model);
            }
        }


        $this->render('freeClassroom', array(
            'model'         => $model,
            'classrooms'    => $classrooms,
            'occupiedRooms' => $occupiedRooms,
        ));
    }
}