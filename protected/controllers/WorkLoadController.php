<?php

class WorkLoadController extends Controller
{
    public function filters() {

        return array(
            'accessControl',
            'checkPermission -self, -getGroups, -printGroups'//не выполянеться для екшенов
        );
    }

    public function accessRules() {

        return array(
            array('allow',
                'actions' => array(
                    'self',
                    'teacher',
                    'amount',
                    'getGroups',
                    'printGroups'
                ),
                'expression' => 'Yii::app()->user->isAdmin || Yii::app()->user->isTch',
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    public function filterCheckPermission($filterChain)
    {
        if(!Yii::app()->user->isAdmin) {
            /**
             * @var $grants Grants
             */
            $grants = Yii::app()->user->dbModel->grants;

            if (empty($grants))
                throw new CHttpException(403, 'Invalid request. You don\'t have access to the service.');

            $type = $grants->getGrantsFor(Grants::WORKLOAD);

            if ($type == 0)
                throw new CHttpException(403, 'You don\'t have an access to this service');
        }
        $filterChain->run();
    }

    public function actionTeacher()
    {
        $model = new FilterForm();
        $model->scenario = 'workLoad-teacher';

        list($year,$semester) = SH::getCurrentYearAndSem();
        $model->year     = $year;
        $model->semester = $semester;

        if (isset($_REQUEST['FilterForm']))
            $model->attributes=$_REQUEST['FilterForm'];


        $this->render('teacher', array(
            'model' => $model,
        ));
    }

    public function actionSelf()
    {
        if(!Yii::app()->user->isTch)
            throw new CHttpException(404, 'Please, do not repeat this request nay more!');
        $model = new FilterForm();
        $model->scenario = 'workLoad-teacher';

        list($year,$semester) = SH::getCurrentYearAndSem();
        $model->year     = $year;
        $model->semester = $semester;
        $teachers=P::model()->getArrayPd(Yii::app()->user->dbModel->p1);

        if (isset($_REQUEST['FilterForm']))
            $model->attributes=$_REQUEST['FilterForm'];


        $this->render('self', array(
            'model' => $model,
            'teachers'=>$teachers
        ));
    }

    public function actionAmount()
    {
        $model = new FilterForm();
        $model->scenario = 'workLoad-teacher';

        list($year,) = SH::getCurrentYearAndSem();
        $model->year = $year;

        if (isset($_REQUEST['FilterForm']))
            $model->attributes=$_REQUEST['FilterForm'];


        $this->render('amount', array(
            'model' => $model,
        ));
    }

    public function actionGetGroups()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Please, do not repeat this request nay more!');

        $ids  = Yii::app()->request->getParam('ids', null);
        $year = Yii::app()->request->getParam('year', null);
        $sem  = Yii::app()->request->getParam('sem', null);
		$nr  = Yii::app()->request->getParam('nr', null);

        if (empty($ids) || empty($year) || is_null($sem)|| empty($nr))
            throw new CHttpException(404, 'Please, do not repeat this request nay more!');

        $html = $this->renderPartial('_groups', array(
            'year'   => $year,
            'sem'    => $sem,
			'nr'=>$nr
        ), true);

        Yii::app()->end(CJSON::encode(array('html' => $html)));
    }

    public function actionPrintGroups($type)
    {
        $sql=<<<SQL
          SELECT d3,d2,us4,us6,sem4,sem3,sem5,nr3 FROM nr
            INNER JOIN us ON (nr.nr2 = us.us1)
            INNER JOIN uo ON (us.us2 = uo.uo1)
            INNER JOIN d ON (uo.uo3 = d.d1)
            INNER JOIN sem ON (us.us3 = sem.sem1)
          WHERE nr1=:nr1
          GROUP BY d3,d2,us4,us6,sem4,sem3,sem5,nr3;
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':nr1', $type);
        $discipline = $command->queryRow();
        if(!empty($discipline))
        {
            $year=$discipline['sem3'];
            $sem=$discipline['sem5'];
            $disp_name=$discipline['d2'];
            $hours=$discipline['nr3'];
            $tip=$discipline['us4'];
            $course=$discipline['sem4'];

            $objPHPExcel=new PHPExcel();
            $objPHPExcel->getProperties()->setCreator("ACY")
                ->setLastModifiedBy("ACY ".date('Y-m-d H-i'))
                ->setTitle("WorkLoad ".date('Y-m-d H-i'))
                ->setSubject("WorkLoad ".date('Y-m-d H-i'))
                ->setDescription("WorkLoad document, generated using ACY Portal. ".date('Y-m-d H:i:'))
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
            $sheet->setCellValue('B5',  SH::convertSem5($sem));

            $sheet->getColumnDimension('A')->setWidth(20);
            $sheet->getColumnDimension('B')->setWidth(40);

            $students = St::model()->getStudentsOfNr($type/*, $year, $sem*/);

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


            $sheet->setTitle('WorkLoad '.date('Y-m-d H-i'));

            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);

            // Redirect output to a clientâ€™s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="ACY_WORKLOAD_'.date('Y-m-d H-i').'.xls"');
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