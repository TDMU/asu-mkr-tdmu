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
                'actions' => array('group','chair','searchStudent','virtualGroup','virtualGroupExcel','groupExcel')
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
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

        $year = Yii::app()->session['year'];
        $sem = Yii::app()->session['sem'];
        $discipline = D::model()->findByPk($model->discipline);
        $group = Gr::model()->getVirtualGroupByDiscipline($model->group);

        $sheet->setCellValue('A1', tt('Дисциплина').': ');
        $sheet->setCellValue('B1', $discipline->d2);

        $sheet->setCellValue('A2', tt('Год').': ');
        $sheet->setCellValue('B2', $year.'/'.($year+1));

        $sheet->setCellValue('A3', tt('Семестр').': ');
        $sheet->setCellValue('B3',  SH::convertSem5($sem));

        $sheet->setCellValue('A4', tt('Название группы').': ');
        $sheet->setCellValue('B4',  $group['gr3']);


        $rowStart=6;

        $sheet->setCellValue('A'.$rowStart,"№");
        $sheet->setCellValue('B'.$rowStart, tt('ФИО'));
        $sheet->setCellValue('C'.$rowStart, tt('Академ. группа'));
        $sheet->setCellValue('D'.$rowStart,'№ '.tt('зач. книжки'));

        $sheet->getColumnDimensionByColumn(0)->setWidth(20);
        $sheet->getColumnDimensionByColumn(1)->setWidth(40);
        $sheet->getColumnDimensionByColumn(2)->setWidth(20);
        $sheet->getColumnDimensionByColumn(3)->setWidth(20);

        $i=1;
        foreach($students as $student):
            $name = $student['st2'].' '.$student['st3'].' '.$student['st4'];

            $sheet->setCellValueByColumnAndRow(0,$i+ $rowStart,$i);
            $sheet->setCellValueByColumnAndRow(1,$i+ $rowStart,$name);
            $sheet->setCellValueByColumnAndRow(2,$i+ $rowStart,Gr::model()->getGroupName($model->course, $student));
            $sheet->setCellValueByColumnAndRow(3,$i+ $rowStart,$student['st5']);
            $i++;
        endforeach;


        $sheet->setTitle('ListVirtualGroup');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a clientâ€™s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="ACY_ListVirtualGroup_'.date('Y-m-d H-i').'.xls"');
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

        $year = Yii::app()->session['year'];
        $sem = Yii::app()->session['sem'];

        $group = Gr::model()->findByPk($model->group);

        $sheet->setCellValue('A2', tt('Год').': ');
        $sheet->setCellValue('B2', $year.'/'.($year+1));

        $sheet->setCellValue('A3', tt('Семестр').': ');
        $sheet->setCellValue('B3',  SH::convertSem5($sem));

        $sheet->setCellValue('A4', tt('Название группы').': ');
        $sheet->setCellValue('B4',  Gr::model()->getGroupName($model->course, $group));


        $rowStart=6;

        $sheet->setCellValue('A'.$rowStart,"№");
        $sheet->setCellValue('B'.$rowStart, tt('ФИО'));
        //$sheet->setCellValue('C'.$rowStart, tt('Академ. группа'));
        $sheet->setCellValue('C'.$rowStart,'№ '.tt('зач. книжки'));

        $sheet->getColumnDimensionByColumn(0)->setWidth(20);
        $sheet->getColumnDimensionByColumn(1)->setWidth(40);
        //$sheet->getColumnDimensionByColumn(2)->setWidth(20);
        $sheet->getColumnDimensionByColumn(2)->setWidth(20);

        $i=1;
        foreach($students as $student):
            $name = $student['st2'].' '.$student['st3'].' '.$student['st4'];

            $sheet->setCellValueByColumnAndRow(0,$i+ $rowStart,$i);
            $sheet->setCellValueByColumnAndRow(1,$i+ $rowStart,$name);
            //$sheet->setCellValueByColumnAndRow(2,$i+ $rowStart,Gr::model()->getGroupName($model->course, $group));
            $sheet->setCellValueByColumnAndRow(2,$i+ $rowStart,$student['st5']);
            $i++;
        endforeach;


        $sheet->setTitle('ListGroup');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a clientâ€™s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="ACY_ListGroup_'.date('Y-m-d H-i').'.xls"');
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