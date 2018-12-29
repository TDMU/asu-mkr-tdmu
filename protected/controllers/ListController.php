<?php

class ListController extends Controller
{
    public function filters() {

        return array(
            'accessControl',
        );
    }

    public function accessRules() {

        return array(
            /*array('allow',
                'actions' => array(
                    'journal',
                ),
                'expression' => 'Yii::app()->user->isAdmin || Yii::app()->user->isTch',
            ),*/
            array('allow',
                'actions' => array('stream','group','chair', 'students2moodle', 'searchStudent','virtualGroup','virtualGroupExcel','groupExcel', 'streamExcel')
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    public function actionStream()
    {
        $model = new FilterForm();
        $model->scenario = 'list-stream';

        if (isset($_REQUEST['FilterForm']))
            $model->attributes=$_REQUEST['FilterForm'];

        $this->render('stream', array(
            'model' => $model,
        ));
    }

    public function actionStudents2Moodle()
    {
        $model = new FilterForm();
        $model->scenario = 'list-students2moodle';

        if (isset($_REQUEST['FilterForm']))
            $model->attributes=$_REQUEST['FilterForm'];

        $this->render('students2moodle', array(
            'model' => $model,
        ));
    }

    public function actionGroup()
    {
        $model = new TimeTableForm();
        $model->scenario = 'list-group';

        if (isset($_REQUEST['TimeTableForm']))
            $model->attributes=$_REQUEST['TimeTableForm'];
        $res=PortalSettings::model()->findByPk(35);
        $ps35=0;
        if(!empty($res))
            $ps35 = $res->ps2;
        $dbh=null;
        if($ps35==1&&Yii::app()->user->isAdmin)
        {
            $string = Yii::app()->db->connectionString;
            $parts  = explode('=', $string);

            $host     = trim($parts[1].'d');
            $login    = Yii::app()->db->username;
            $password = Yii::app()->db->password;
            $dbh      = ibase_connect($host, $login, $password);
        }

        $student = new St;
        $student->unsetAttributes();

        $this->render('group', array(
            'model' => $model,
            'dbh'=> $dbh,
            'ps35'=>$ps35,
            'student'=>$student
        ));
    }

    public function actionVirtualGroup()
    {
        $model = new FilterForm();
        $model->scenario = 'list-virtual-group';

        if (isset($_REQUEST['FilterForm']))
            $model->attributes=$_REQUEST['FilterForm'];

        $this->render('virtual-group', array(
            'model' => $model,
        ));
    }
    /*
     * печать спискавиртуальной группы
     * */
    public function actionVirtualGroupExcel()
    {
        $model = new FilterForm();
        $model->scenario = 'list-virtual-group';

        if (isset($_REQUEST['FilterForm']))
            $model->attributes=$_REQUEST['FilterForm'];

        if(empty($model->group))
            throw new Exception("Error");

        $students=St::model()->getListVirtualGroup($model->group);

        Yii::import('ext.phpexcel.XPHPExcel');
        $objPHPExcel= XPHPExcel::createPHPExcel();
        $objPHPExcel->getProperties()->setCreator("ACY")
            ->setLastModifiedBy("ACY ".date('Y-m-d H-i'))
            ->setTitle("ListVirtualGroup ".date('Y-m-d H-i'))
            ->setSubject("ListVirtualGroup ".date('Y-m-d H-i'))
            ->setDescription("ListVirtualGroup document, generated using ACY Portal. ".date('Y-m-d H:i:'))
            ->setKeywords("")
            ->setCategory("Result file");
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet=$objPHPExcel->getActiveSheet();

        list($year, $sem) = SH::getCurrentYearAndSem();

        $discipline = D::model()->findByPk($model->discipline);
        $disciplineName = $discipline->d2;
        if(Yii::app()->language=='en' && !(empty($discipline->d27))){
            $disciplineName = $discipline->d27;
        }

        $group = Gr::model()->getVirtualGroupByDiscipline($model->group);
        $groupInfo = Gr::model()->getInfo($model->group);
        $groupName = $group['gr3'];

        $speciality = isset($groupInfo['pnsp2']) ? $groupInfo['pnsp2'] : '';
        $faculty = isset($groupInfo['f3']) ? $groupInfo['f3'] : '';

        if(Yii::app()->language=='en' && !(empty($groupInfo))){
            if(!empty($groupInfo['pnsp17']))
                $speciality = $groupInfo['pnsp17'];

            if(!empty($groupInfo['f26']))
                $faculty = $groupInfo['f26'];
        }

        $sheet->mergeCellsByColumnAndRow(0, 2, 2, 2);
        $sheet->setCellValue('A2', tt('Список студентов'))->getStyle('A2')->getAlignment()-> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $sheet->mergeCellsByColumnAndRow(0, 3, 2, 3);
        $sheet->setCellValue('A3', tt('{course} курса {group} группы {speciality} специальности',array(
            '{course}' => $model->course,
            '{group}' => $groupName,
            '{speciality}' => $speciality
        )))->getStyle('A3')->getAlignment()-> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $sheet->mergeCellsByColumnAndRow(0, 4, 2, 4);
        $sheet->setCellValue('A4', $faculty
        /*tt('{faculty} факультета', array(
            '{faculty}' => $faculty
        ))*/
        )->getStyle('A4')->getAlignment()-> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);


        $sheet->mergeCellsByColumnAndRow(0, 5, 2, 5);
        $sheet->setCellValue('A5', tt(' за {sem} семестр {year} учебный год', array(
            '{sem}' => SH::convertSem5($sem),
            '{year}' => $year.'/'.($year+1)
        )).',')->getStyle('A5')->getAlignment()-> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $sheet->mergeCellsByColumnAndRow(0, 6, 2, 6);
        $sheet->setCellValue('A5', tt('которые изучают дисциплину {discipline}', array(
                '{discipline}' => $disciplineName
            )))->getStyle('A5')->getAlignment()-> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);


        $sheet->getStyle('A2:A5')->getFont()->setSize(14);

        $sheet->getRowDimension(2)->setRowHeight(-1);
        $sheet->getStyle('A2')->getAlignment()->setWrapText(true);
        $sheet->getRowDimension(3)->setRowHeight(-1);
        $sheet->getStyle('A3')->getAlignment()->setWrapText(true);
        $sheet->getRowDimension(4)->setRowHeight(-1);
        $sheet->getStyle('A4')->getAlignment()->setWrapText(true);
        $sheet->getRowDimension(5)->setRowHeight(-1);
        $sheet->getStyle('A5')->getAlignment()->setWrapText(true);

        $rowStart=8;

        $sheet->setCellValue('A'.$rowStart,"№");
        $sheet->setCellValue('B'.$rowStart, tt('ФИО студента'));
        $sheet->setCellValue('C'.$rowStart,'№ '.tt('зач. книжки'));
        $sheet->setCellValue('D'.$rowStart, tt('Академ. группа'));


        $sheet->getStyle('A'.$rowStart.':D'.$rowStart)->getAlignment()-> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A'.$rowStart.':D'.$rowStart)->getFont()->setBold( true );


        $sheet->getColumnDimensionByColumn(0)->setWidth(5);
        $sheet->getColumnDimensionByColumn(1)->setWidth(40);
        $sheet->getColumnDimensionByColumn(2)->setWidth(15);
        $sheet->getColumnDimensionByColumn(3)->setWidth(15);

        $i=1;
        foreach($students as $student):
            $name = $student['st2'].' '.$student['st3'].' '.$student['st4'];
            if(Yii::app()->language == 'en' && !empty($student['st74']))
                $name = $student['st74'].' '.$student['st75'].' '.$student['st76'];

            $sheet->setCellValueByColumnAndRow(0,$i+ $rowStart,$i);
            $sheet->setCellValueByColumnAndRow(1,$i+ $rowStart,$name);
            $sheet->setCellValueByColumnAndRow(2,$i+ $rowStart,$student['st5']);
            $sheet->setCellValueByColumnAndRow(3,$i+ $rowStart,Gr::model()->getGroupName($model->course, $student));

            $i++;
        endforeach;

        $sheet->getStyle('A'.$rowStart.':A'.($rowStart+$i-1))->getAlignment()-> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $sheet->getStyle('C'.$rowStart.':C'.($rowStart+$i-1))->getAlignment()-> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);


        $sheet->getStyleByColumnAndRow(0,$rowStart,3,$i+$rowStart-1)->getBorders()->getAllBorders()->applyFromArray(array('style'=>PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => '000000')));

        $sheet->setTitle(tt('Список виртуальной группы'));

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a clientâ€™s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="ACY_ListVirtualGroup_'.$groupName.'_'.date('Y-m-d H-i').'.xls"');
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

    /*
     * печать списка потока
     * */
    public function actionStreamExcel()
    {
        $model = new FilterForm();
        $model->scenario = 'list-stream';

        if (isset($_REQUEST['FilterForm']))
            $model->attributes=$_REQUEST['FilterForm'];

        if(empty($model->stream))
            throw new Exception("Error");

        $students=St::model()->getListStream($model->stream);

        Yii::import('ext.phpexcel.XPHPExcel');
        $objPHPExcel= XPHPExcel::createPHPExcel();
        $objPHPExcel->getProperties()->setCreator("ACY")
            ->setLastModifiedBy("ACY ".date('Y-m-d H-i'))
            ->setTitle("ListStream ".date('Y-m-d H-i'))
            ->setSubject("ListStream ".date('Y-m-d H-i'))
            ->setDescription("ListStream document, generated using ACY Portal. ".date('Y-m-d H:i:'))
            ->setKeywords("")
            ->setCategory("Result file");
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet=$objPHPExcel->getActiveSheet();

        list($year, $sem) = SH::getCurrentYearAndSem();

        $streamInfo = Gr::model()->getInfoBySg($model->stream);

        $speciality = isset($streamInfo['pnsp2']) ? $streamInfo['pnsp2'] : '';
        $faculty = isset($streamInfo['f3']) ? $streamInfo['f3'] : '';

        if(Yii::app()->language=='en' && !(empty($streamInfo))){
            if(!empty($streamInfo['pnsp17']))
                $speciality = $streamInfo['pnsp17'];

            if(!empty($streamInfo['f26']))
                $faculty = $streamInfo['f26'];
        }

        $sheet->mergeCellsByColumnAndRow(0, 2, 2, 2);
        $sheet->setCellValue('A2', tt('Список студентов потока'))->getStyle('A2')->getAlignment()-> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $sheet->mergeCellsByColumnAndRow(0, 3, 2, 3);
        $sheet->setCellValue('A3', tt('{course} курса {speciality} специальности',array(
            '{course}' => $model->course,
            '{speciality}' => $speciality
        )))->getStyle('A3')->getAlignment()-> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $sheet->mergeCellsByColumnAndRow(0, 4, 2, 4);
        $sheet->setCellValue('A4', $faculty
        /*tt('{faculty} факультета', array(
            '{faculty}' => $faculty
        ))*/
        )->getStyle('A4')->getAlignment()-> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);


        $sheet->mergeCellsByColumnAndRow(0, 5, 2, 5);
        $sheet->setCellValue('A5', tt(' за {sem} семестр {year} учебный год', array(
                '{sem}' => SH::convertSem5($sem),
                '{year}' => $year.'/'.($year+1)
            )))->getStyle('A5')->getAlignment()-> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $sheet->getStyle('A2:A5')->getFont()->setSize(14);

        $sheet->getRowDimension(2)->setRowHeight(-1);
        $sheet->getStyle('A2')->getAlignment()->setWrapText(true);
        $sheet->getRowDimension(3)->setRowHeight(-1);
        $sheet->getStyle('A3')->getAlignment()->setWrapText(true);
        $sheet->getRowDimension(4)->setRowHeight(-1);
        $sheet->getStyle('A4')->getAlignment()->setWrapText(true);
        $sheet->getRowDimension(5)->setRowHeight(-1);
        $sheet->getStyle('A5')->getAlignment()->setWrapText(true);

        $rowStart=7;

        $sheet->setCellValue('A'.$rowStart,"№");
        $sheet->setCellValue('B'.$rowStart, tt('ФИО студента'));
        $sheet->setCellValue('C'.$rowStart,'№ '.tt('зач. книжки'));
        $sheet->setCellValue('D'.$rowStart, tt('Академ. группа'));


        $sheet->getStyle('A'.$rowStart.':D'.$rowStart)->getAlignment()-> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A'.$rowStart.':D'.$rowStart)->getFont()->setBold( true );


        $sheet->getColumnDimensionByColumn(0)->setWidth(5);
        $sheet->getColumnDimensionByColumn(1)->setWidth(40);
        $sheet->getColumnDimensionByColumn(2)->setWidth(15);
        $sheet->getColumnDimensionByColumn(3)->setWidth(15);

        $i=1;
        foreach($students as $student):
            $name = $student['st2'].' '.$student['st3'].' '.$student['st4'];
            if(Yii::app()->language == 'en' && !empty($student['st74']))
                $name = $student['st74'].' '.$student['st75'].' '.$student['st76'];

            $sheet->setCellValueByColumnAndRow(0,$i+ $rowStart,$i);
            $sheet->setCellValueByColumnAndRow(1,$i+ $rowStart,$name);
            $sheet->setCellValueByColumnAndRow(2,$i+ $rowStart,$student['st5']);
            $sheet->setCellValueByColumnAndRow(3,$i+ $rowStart,Gr::model()->getGroupName($model->course, $student));

            $i++;
        endforeach;

        $sheet->getStyle('A'.$rowStart.':A'.($rowStart+$i-1))->getAlignment()-> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $sheet->getStyle('C'.$rowStart.':C'.($rowStart+$i-1))->getAlignment()-> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);


        $sheet->getStyleByColumnAndRow(0,$rowStart,3,$i+$rowStart-1)->getBorders()->getAllBorders()->applyFromArray(array('style'=>PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => '000000')));

        $sheet->setTitle(tt('Список потока'));

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a clientâ€™s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="ACY_ListSTREAM_'.date('Y-m-d H-i').'.xls"');
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

    /*
     * печать списка группы
     * */
    public function actionGroupExcel()
    {
        $model = new TimeTableForm();
        $model->scenario = 'list-group';

        if (isset($_REQUEST['TimeTableForm']))
            $model->attributes=$_REQUEST['TimeTableForm'];

        if(empty($model->group))
            throw new Exception("Error");

        $students=St::model()->getListGroup($model->group);

        Yii::import('ext.phpexcel.XPHPExcel');
        $objPHPExcel= XPHPExcel::createPHPExcel();
        $objPHPExcel->getProperties()->setCreator("ACY")
            ->setLastModifiedBy("ACY ".date('Y-m-d H-i'))
            ->setTitle("ListVirtualGroup ".date('Y-m-d H-i'))
            ->setSubject("ListVirtualGroup ".date('Y-m-d H-i'))
            ->setDescription("ListVirtualGroup document, generated using ACY Portal. ".date('Y-m-d H:i:'))
            ->setKeywords("")
            ->setCategory("Result file");
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet=$objPHPExcel->getActiveSheet();

        /*$year = Yii::app()->session['year'];
        $sem = Yii::app()->session['sem'];*/

        list($year, $sem) = SH::getCurrentYearAndSem();

        $group = Gr::model()->findByPk($model->group);
        $groupInfo = Gr::model()->getInfo($group->gr1);
        $groupName = Gr::model()->getGroupName($model->course, $group);

        $speciality = isset($groupInfo['pnsp2']) ? $groupInfo['pnsp2'] : '';
        $faculty = isset($groupInfo['f3']) ? $groupInfo['f3'] : '';

        if(Yii::app()->language=='en' && !(empty($groupInfo))){
            if(!empty($groupInfo['pnsp17']))
                $speciality = $groupInfo['pnsp17'];

            if(!empty($groupInfo['f26']))
                $faculty = $groupInfo['f26'];
        }

        $sheet->mergeCellsByColumnAndRow(0, 2, 2, 2);
        $sheet->setCellValue('A2', tt('Список студентов'))->getStyle('A2')->getAlignment()-> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $sheet->mergeCellsByColumnAndRow(0, 3, 2, 3);
        $sheet->setCellValue('A3', tt('{course} курса {group} группы {speciality} специальности',array(
            '{course}' => $model->course,
            '{group}' => $groupName,
            '{speciality}' => $speciality
        )))->getStyle('A3')->getAlignment()-> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $sheet->mergeCellsByColumnAndRow(0, 4, 2, 4);
        $sheet->setCellValue('A4', $faculty
        /*tt('{faculty} факультета', array(
            '{faculty}' => $faculty
        ))*/
        )->getStyle('A4')->getAlignment()-> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);


        $sheet->mergeCellsByColumnAndRow(0, 5, 2, 5);
        $sheet->setCellValue('A5', tt(' за {sem} семестр {year} учебный год', array(
            '{sem}' => SH::convertSem5($sem),
            '{year}' => $year.'/'.($year+1)
        )))->getStyle('A5')->getAlignment()-> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $sheet->getStyle('A2:A5')->getFont()->setSize(14);

        $sheet->getRowDimension(2)->setRowHeight(-1);
        $sheet->getStyle('A2')->getAlignment()->setWrapText(true);
        $sheet->getRowDimension(3)->setRowHeight(-1);
        $sheet->getStyle('A3')->getAlignment()->setWrapText(true);
        $sheet->getRowDimension(4)->setRowHeight(-1);
        $sheet->getStyle('A4')->getAlignment()->setWrapText(true);
        $sheet->getRowDimension(5)->setRowHeight(-1);
        $sheet->getStyle('A5')->getAlignment()->setWrapText(true);


        $rowStart=7;

        $sheet->setCellValue('A'.$rowStart,"№");
        $sheet->setCellValue('B'.$rowStart, tt('ФИО студента'));
        //$sheet->setCellValue('C'.$rowStart, tt('Академ. группа'));
        $sheet->setCellValue('C'.$rowStart,'№ '.tt('зач. книжки'));

        $sheet->getStyle('A'.$rowStart.':C'.$rowStart)->getAlignment()-> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A'.$rowStart.':C'.$rowStart)->getFont()->setBold( true );

        $sheet->getColumnDimensionByColumn(0)->setWidth(5);
        $sheet->getColumnDimensionByColumn(1)->setWidth(40);
        //$sheet->getColumnDimensionByColumn(2)->setWidth(20);
        $sheet->getColumnDimensionByColumn(2)->setWidth(15);

        $i=1;
        foreach($students as $student):
            $name = $student['st2'].' '.$student['st3'].' '.$student['st4'];

            if(Yii::app()->language == 'en' && !empty($student['st74']))
                $name = $student['st74'].' '.$student['st75'].' '.$student['st76'];

            $sheet->setCellValueByColumnAndRow(0,$i+ $rowStart,$i);
            $sheet->setCellValueByColumnAndRow(1,$i+ $rowStart,$name);
            $sheet->setCellValueByColumnAndRow(2,$i+ $rowStart,$student['st5']);
            $i++;
        endforeach;

        $sheet->getStyle('A'.$rowStart.':A'.($rowStart+$i-1))->getAlignment()-> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $sheet->getStyle('C'.$rowStart.':C'.($rowStart+$i-1))->getAlignment()-> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $sheet->setTitle(tt('Список группы'). ' '. $groupName);

        $sheet->getStyleByColumnAndRow(0,$rowStart,2,$i+$rowStart-1)->getBorders()->getAllBorders()->applyFromArray(array('style'=>PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => '000000')));


        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a clientâ€™s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="ACY_ListGroup_'.$groupName.'_'.date('Y-m-d H-i').'.xls"');
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

    public function actionChair()
    {
        $model = new TimeTableForm();
        $model->scenario = 'list-chair';

        if (isset($_REQUEST['TimeTableForm']))
            $model->attributes=$_REQUEST['TimeTableForm'];

        $this->render('chair', array(
            'model' => $model,
        ));
    }

    public function actionSearchStudent()
    {
        $model = new St;
        $model->unsetAttributes();
        if (isset($_REQUEST['St']))
            $model->attributes = $_REQUEST['St'];

        $this->render('/filter_form/default/search_student', array(
            'model' => $model,
        ));
    }
}