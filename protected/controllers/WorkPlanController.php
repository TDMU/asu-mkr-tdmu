<?php

class WorkPlanController extends Controller
{
    const SPECIALITY = 1;
    const GROUP = 2;
    const STUDENT = 3;

    public function filters() {

        return array(
            //'accessControl',
        );
    }

    public function accessRules() {

        return array(
            array('allow',
                'actions' => array(
                ),
                'expression' => 'Yii::app()->user->isAdmin || Yii::app()->user->isTch',
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }



    public function actionStudent()
    {
        $model = new FilterForm();
        $model->scenario = 'workPlan-student';

        if (isset($_REQUEST['FilterForm']))
            $model->attributes=$_REQUEST['FilterForm'];


        $this->render('student', array(
            'model' => $model,
        ));
    }

    public function actionGroup()
    {
        $model = new FilterForm();
        $model->scenario = 'workPlan-group';

        if (isset($_REQUEST['FilterForm']))
            $model->attributes=$_REQUEST['FilterForm'];

        $this->render('group', array(
            'model' => $model,
        ));
    }

    public function actionSpeciality()
    {
        $model = new FilterForm();
        $model->scenario = 'workPlan-speciality';

        if (isset($_REQUEST['FilterForm']))
            $model->attributes=$_REQUEST['FilterForm'];


        $this->render('speciality', array(
            'model' => $model,
        ));
    }

    public function actionPrintGroups($type,$sem)
    {
        $sql=<<<SQL
          SELECT d3,d2,us4,us6,sem4,sem3,sem5 FROM us
            INNER JOIN uo ON (us.us2 = uo.uo1)
            INNER JOIN d ON (uo.uo3 = d.d1)
            INNER JOIN sem ON (us.us3 = sem.sem1)
          WHERE uo1=:uo1 AND sem1=:sem1
          GROUP BY d3,d2,us4,us6,sem4,sem3,sem5;
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':uo1', $type);
        $command->bindValue(':sem1', $sem);
        $discipline = $command->queryRow();
        if(!empty($discipline))
        {
            $year=$discipline['sem3'];
            $sem5=$discipline['sem5'];
            $disp_name=$discipline['d2'];
            $hours=$discipline['us6'];
            $tip=$discipline['us4'];
            $course=$discipline['sem4'];

            $objPHPExcel=new PHPExcel();
            $objPHPExcel->getProperties()->setCreator("ACY")
                ->setLastModifiedBy("ACY ".date('Y-m-d H-i'))
                ->setTitle("WorkPLAN ".date('Y-m-d H-i'))
                ->setSubject("WorkPLAN ".date('Y-m-d H-i'))
                ->setDescription("WorkPLAN document, generated using ACY Portal. ".date('Y-m-d H:i:'))
                ->setKeywords("")
                ->setCategory("Result file");
            $objPHPExcel->setActiveSheetIndex(0);
            $sheet=$objPHPExcel->getActiveSheet();

            $sheet->setCellValue('A1', tt('Дисциплина').': ');
            $sheet->setCellValue('B1', $disp_name);

            $sheet->setCellValue('A2', tt('Тип занятий').': ');
            $sheet->setCellValue('B2', SH::convertUS4($tip));

            $sheet->setCellValue('A3', tt('Количетсво часов').': ');
            $sheet->setCellValue('B3', $hours)->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

            $sheet->setCellValue('A4', tt('Год').': ');
            $sheet->setCellValue('B4', $year.'/'.($year+1));

            $sheet->setCellValue('A5', tt('Семестр').': ');
            $sheet->setCellValue('B5',  SH::convertSem5($sem5));

            $sheet->getColumnDimension('A')->setWidth(20);
            $sheet->getColumnDimension('B')->setWidth(40);

            $students = St::model()->getStudentsOfUo($type, $year, $sem5);

            $gr1=-1;
            $i=0;
            $number=0;
            foreach($students as $student):
                if($gr1!=$student['gr1'])
                {
                    $i++;
                    $gr1=$student['gr1'];
                    $gr=Gr::model()->findByPk($gr1);
                    $name   = Gr::model()->getGroupName($course, $gr);
                    $sheet->mergeCellsByColumnAndRow(0, $i+ 6, 1, $i+6);
                    $sheet->setCellValueByColumnAndRow(0,$i + 6,tt('Группа ').' '.$name)->getStyleByColumnAndRow(0,$i + 6)->getAlignment()-> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $i++;
                    $number=0;
                }
                $sheet->setCellValueByColumnAndRow(0,$i + 6,$number+1);
                $sheet->setCellValueByColumnAndRow(1,$i + 6,$student['stud']);
                $i++;
                $number++;
            endforeach;


            $sheet->setTitle('WorkPlan '.date('Y-m-d H-i'));

            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);

            // Redirect output to a clientâ€™s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="ACY_WORKPLAN_'.date('Y-m-d H-i').'.xls"');
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
        }else
            throw new CHttpException(404, 'Please, do not repeat this request nay more!');
    }
}