<?php

class ProgressController extends Controller
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
                    'getGroups',
                    'insertMejModule',
                    'deleteMejModule',
                    'module',
                    'updateVvmp',
                    'insertVmpMark',
                    'updateStus',
                    'closeModule',
                    'renderExtendedModule',
                    'examSession',
                    'insertStus',
                    //'insertVmp',

                    'modules',
                    'insertJpvd',
                    'getCxmb'
                ),
                'expression' => 'Yii::app()->user->isAdmin || Yii::app()->user->isTch',
            ),

            array('allow',
                'actions' => array('attendanceStatistic','rating','ratingExcel', 'ratingStudent')
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }
    
    public function actionRating()
    {
        //throw new CHttpException(400, 'Сервис времено недоступен по техническим причинам. В близжайшее время работа возобновиться.');
        $model = new RatingForm();
        $model->scenario = 'rating-group';
        if (isset($_REQUEST['RatingForm']))
            $model->attributes=$_REQUEST['RatingForm'];
        $this->render('rating', array(
            'model' => $model,
        ));
    }

    /**
     * Отображение дисциплин которіе входять в рейтинг студента
     * @throws CHttpException
     */
    public function actionRatingStudent()
    {
        $st1 = Yii::app()->request->getParam('st1', null);
        $semStart = Yii::app()->request->getParam('semStart', null);
        $semEnd = Yii::app()->request->getParam('semEnd', null);

        if (empty($semStart) || empty($semEnd) || empty($st1))
            throw new CHttpException(400, 'Invalid params. Please do not repeat this request again.');

        $student = St::model()->findByPk($st1);

        if (empty($student))
            throw new CHttpException(400, 'Invalid params. Please do not repeat this request again.');

        $model = new RatingForm();
        $model->semStart = $semStart;
        $model->semEnd = $semEnd;
        $model->student = $st1;

        $html = $this->renderPartial('rating/_student', array(
            'model' => $model,
            'student' => $student
        ), true);

        $res = array(
            'html' => $html,
            'title' => $student->getFullName()
        );

        Yii::app()->end(CJSON::encode($res));
    }

    public function actionRatingExcel(){

        $model = new RatingForm();
        $model->scenario = 'rating-group-excel';

        $model->group = Yii::app()->request->getParam('group', null);
        $model->semStart = Yii::app()->request->getParam('semStart', null);
        $model->semEnd = Yii::app()->request->getParam('semEnd', null);
        $model->stType = Yii::app()->request->getParam('stType', null);
        $model->ratingType = Yii::app()->request->getParam('ratingType', null);
        $model->course = Yii::app()->request->getParam('course', null);

        if (empty($model->course) || empty($model->semEnd) || empty($model->semStart) || empty($model->group))
            throw new CHttpException(400, 'Invalid params. Please do not repeat this request again.');

        $rating = $model->getRating($model->ratingType==1 ? RatingForm::COURSE : RatingForm::GROUP);

        if(empty($rating))
            throw new CHttpException(400, tt('Нет данных.'));

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

        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(10);
        $sheet->getColumnDimension('C')->setWidth(40);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('E')->setWidth(20);

        $sheet->setCellValue('A3','№')->getStyle('A3')->getAlignment()->setHorizontal(
            PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('B3', tt('Рейтинг'))->getStyle('B3')->getAlignment()->setHorizontal(
            PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('C3', tt('Студент'))->getStyle('C3')->getAlignment()->setHorizontal(
            PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('D3', tt('Группа'))->getStyle('D3')->getAlignment()->setHorizontal(
            PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('E3', tt('Балл'))->getStyle('E3')->getAlignment()->setHorizontal(
            PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $sheet->getStyle('A3')->getFont()->setName('Arial')->setSize(15)->getColor()->applyFromArray(array('rgb' => '000000'));
        $sheet->getStyle('B3')->getFont()->setName('Arial')->setSize(15)->getColor()->applyFromArray(array('rgb' => '000000'));
        $sheet->getStyle('C3')->getFont()->setName('Arial')->setSize(15)->getColor()->applyFromArray(array('rgb' => '000000'));
        $sheet->getStyle('D3')->getFont()->setName('Arial')->setSize(15)->getColor()->applyFromArray(array('rgb' => '000000'));
        $sheet->getStyle('E3')->getFont()->setName('Arial')->setSize(15)->getColor()->applyFromArray(array('rgb' => '000000'));

        $i=0;
        $val='';
        $k=0;

        foreach($rating as $key)
        {
            $_bal = round($key['value'], 2);
            if($_bal!=$val)
            {
                $val=$_bal;
                $i++;
            }

            $name = ShortCodes::getShortName($key['stInfo']['st2'], $key['stInfo']['st3'], $key['stInfo']['st4']);

            $sheet->setCellValueByColumnAndRow(0,$k+4,$k+1);
            $sheet->setCellValueByColumnAndRow(1,$k+4,$i);
            $sheet->setCellValueByColumnAndRow(2,$k+4,$name);
            $sheet->setCellValueByColumnAndRow(3,$k+4,$key['stInfo']['group']);
            $sheet->setCellValueByColumnAndRow(4,$k+4,$_bal);
            $k++;
        }

        $sheet->getStyleByColumnAndRow(0,3,4,$k+3)->getBorders()->getAllBorders()->applyFromArray(array('style'=>PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => '000000')));

        $sheet->getProtection()->setSheet(true);
        $sheet->getProtection()->setSort(true);
        $sheet->getProtection()->setInsertRows(true);
        $sheet->getProtection()->setFormatCells(true);

        $sheet->getProtection()->setPassword('TNDF12451ghtreds54213');

        $sheet->setTitle('Рейтинг '.date('Y-m-d H-i'));
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a clientâ€™s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="ACY_RATING_'.date('Y-m-d H-i').'.xls"');
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
}