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
                'actions' => array(
                    'test',
                ),
                'expression' => 'Yii::app()->user->isStd',
            ),
            array('allow',
                'actions' => array('attendanceStatistic','rating','ratingExcel', 'ratingStudent')
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }
    
    public function actionTest()
    {
        $model = new Test('search');
        $model->unsetAttributes();
        $model->test4=Yii::app()->user->DbModel->st1;
        $this->render('test', array(
            'model' => $model,
        )); 
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
    
    public function actionGetGroups()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $type = Yii::app()->request->getParam('type', 0);
        $discipline = Yii::app()->request->getParam('discipline', 0);

        $groups = CHtml::listData(Gr::model()->getGroupsFor($discipline, $type), 'gr1', 'name');

        echo CHtml::dropDownList('FilterForm[group]', '',$groups, array('id'=>'FilterForm_group', 'class'=>'chosen-select', 'autocomplete' => 'off', 'empty' => '&nbsp;'));
    }

    public function actionInsertMejModule()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $mej3  = Yii::app()->request->getParam('mej3', null);
        $mej4  = Yii::app()->request->getParam('mej4', null);
        $mej5  = Yii::app()->request->getParam('mej5', null);
        $vvmp1 = Yii::app()->request->getParam('vvmp1', null);

        $model = new Mej();
        $model->mej1 = new CDbExpression('GEN_ID(GEN_MEJ, 1)');
        $model->mej3 = $mej3;
        $model->mej4 = $mej4;
        $model->mej5 = $mej5;

        $error = !$model->save();

        if (! $error)
            Vmp::model()->recalculateModulesFor($vvmp1, $mej3);

        Yii::app()->end(CJSON::encode(array('error' => $error, 'errors' => $model->getErrors())));
    }

    public function actionDeleteMejModule()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $mej1  = Yii::app()->request->getParam('mej1', null);
        $nr1   = Yii::app()->request->getParam('nr1', null);
        $vvmp1 = Yii::app()->request->getParam('vvmp1', null);

        $deleted = Mej::model()->deleteByPk($mej1);

        if ($deleted)
            Vmp::model()->recalculateModulesFor($vvmp1, $nr1);
    }


	
	public function actionModule()
    {
        $type = 0; // own modules

		$type = P::getPermition(Yii::app()->user->dbModel->p1);	
        $model = new FilterForm;
        $model->scenario = 'module';
        if (isset($_REQUEST['FilterForm']))
            $model->attributes=$_REQUEST['FilterForm'];

        $this->render('module', array(
            'model'      => $model,
            'type'       => $type,
        ));
    }

    /*private function fillModulesFor($model)
    {
        $gr1  = $model->group;
        $d1   = $model->discipline;
        $year = Yii::app()->session['year'];
        $sem  = Yii::app()->session['sem'];
        $res = Vvmp::model()->fillDataForGroup($gr1, $d1, $year, $sem);
        return $res;
    }*/

    public function actionUpdateVvmp()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $value = Yii::app()->request->getParam('value', null);
        $field = Yii::app()->request->getParam('field', null);
        $vvmp1 = Yii::app()->request->getParam('vvmp1', null);

        $whiteList = array(
            'vvmp10', 'vvmp11', 'vvmp12', 'vvmp13', 'vvmp14', 'vvmp15', 'vvmp16',
            'vvmp17', 'vvmp18', 'vvmp19', 'vvmp20', 'vvmp21', 'vvmp22', 'vvmp23',
        );
        if (in_array($field, $whiteList))
            $attr = array(
                $field => $value
            );

        $criteria = new CDbCriteria();
        $criteria->compare('vvmp1', $vvmp1);

        $model = Vvmp::model()->find($criteria);
        if (empty($model))
            $error = true;
        else
            $error = !$model->saveAttributes($attr);

        Yii::app()->end(CJSON::encode(array('error' => $error, 'errors' => $model->getErrors())));
    }

    public function actionInsertVmpMark()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $vmp1  = Yii::app()->request->getParam('vvmp1', null);
        $vmp2  = Yii::app()->request->getParam('st1', null);
        $vmp3  = Yii::app()->request->getParam('module', null);
        $field = Yii::app()->request->getParam('field', null);
        $value = Yii::app()->request->getParam('value', null);

        $whiteList = array('vmp4', 'vmp5', 'vmp6', 'vmp7');
        if (in_array($field, $whiteList))
            $attr = array(
                $field => $value
            );


        $criteria = new CDbCriteria();
        $criteria->compare('vmp1', $vmp1);
        $criteria->compare('vmp2', $vmp2);
        $criteria->compare('vmp3', $vmp3);

        $model = Vmp::model()->find($criteria);
        if (empty($model))
            $error = true;
        else
            $error = !$model->saveAttributes($attr);

        // recalculate vmp4 if vmp5, vmp6 or vmp7 were changed
        if (! $error && in_array($field, array('vmp5', 'vmp6', 'vmp7')))
            $model->recalculateVmp4();

        Yii::app()->end(CJSON::encode(array('error' => $error, 'errors' => $model->getErrors())));
    }

    public function actionUpdateStus()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $value = Yii::app()->request->getParam('value', null);
        $field = Yii::app()->request->getParam('field', null);
        $vvmp1 = Yii::app()->request->getParam('vvmp1', null);
        $st1   = Yii::app()->request->getParam('st1', null);

        $whiteList = array(
            'stus3'
        );
        if (in_array($field, $whiteList))
            $attr = array(
                $field => $value
            );

        $vvmp = Vvmp::model()->findByPk($vvmp1);

        $criteria = new CDbCriteria();
        $criteria->compare('stus1',  $st1);
        $criteria->compare('stus18', $vvmp->vvmp3);
        $criteria->compare('stus19', 8);
        $criteria->compare('stus20', $vvmp->vvmp4);
        $criteria->compare('stus21', $vvmp->vvmp5);

        $model = Stus::model()->find($criteria);
        if (empty($model))
            $error = true;
        else
            $error = !$model->saveAttributes($attr);

        $res = array(
            'error' => $error
        );

        if (! empty($model))
            $res += array('errors' => $model->getErrors());

        Yii::app()->end(CJSON::encode($res));
    }

    public function actionCloseModule()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $vvmp1 = Yii::app()->request->getParam('vvmp1', null);

        if (empty($vvmp1))
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $vvmp = Vvmp::model()->findByPk($vvmp1);

        $res = $vvmp->saveAttributes(array(
            'vvmp7' => date('Y-m-d H:i:s')
        ));

        Yii::app()->end(CJSON::encode(array('res' => $res)));
    }

    public function actionRenderExtendedModule()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $uo1  = Yii::app()->request->getParam('uo1', null);
        $gr1  = Yii::app()->request->getParam('gr1', null);
        $d1   = Yii::app()->request->getParam('d1', null);
        $module_num = Yii::app()->request->getParam('module_num', null);


        $model = new FilterForm;
        $model->scenario = 'modules';
        $model->group = $gr1;
        $model->discipline = $d1;
        $moduleInfo = $this->fillModulesFor($model);


        if (empty($uo1) || empty($gr1) || empty($d1) || empty($module_num))
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $students = St::model()->getStudentsForJournal($gr1, $uo1);

        $this->renderPartial('modules/_extended_module', array(
            'students'   => $students,
            'moduleInfo' => $moduleInfo,
            'module_num' => $module_num
        ));
    }

    public function actionExamSession()
    {
        $type = 0; // own disciplines

        $grants = Yii::app()->user->dbModel->grants;
        if (! empty($grants))
            $type = $grants->getGrantsFor(Grants::EXAM_SESSION);

        $model = new FilterForm;
        $model->scenario = 'exam-session';
        if (isset($_REQUEST['FilterForm']))
            $model->attributes=$_REQUEST['FilterForm'];

        $this->render('examSession', array(
            'model' => $model,
            'type' => $type,
        ));

    }

    public function actionInsertStus()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $st1    = Yii::app()->request->getParam('st1', null);
        $stus0  = Yii::app()->request->getParam('stus0', null);
        $stusp5 = Yii::app()->request->getParam('stusp5', null);
        $k1     = Yii::app()->request->getParam('k1', null);
        $field  = Yii::app()->request->getParam('field', null);
        $value  = Yii::app()->request->getParam('value', null);
        $stus6  = Yii::app()->request->getParam('stus6', null);
        $stus7  = Yii::app()->request->getParam('stus7', null);
        $cxmb0  = Yii::app()->request->getParam('cxmb0', null);

        $stus  = array('stus8', 'stus6', 'stus7', 'stus3');
        $stusp = array('stusp8', 'stusp6', 'stusp7', 'stusp3');
        $whiteList = array_merge($stus, $stusp);

        if (in_array($field, $whiteList))
            $attr = array(
                $field => $value
            );

        $error = true;

        $criteria = new CDbCriteria();
        $criteria->compare('stus0', $stus0);
        $modelS = Stus::model()->find($criteria);
        $modelS->saveAttributes(array('stus4' => Yii::app()->user->dbModel->getPd1ByK1($k1)));

        if (in_array($field, $stus)) {

            if (! empty($modelS)) {
                if ($field == 'stus3') {
                    $cxmb = Cxmb::model()->getExtraMarks($cxmb0, $value);
                    if (! empty($cxmb)) {
                        $attr['stus11'] = $cxmb['cxmb3'];
                        $attr['stus8']  = $cxmb['cxmb2'];
                    }
                }
                $error = ! $modelS->saveAttributes($attr + array('stus6'=>$stus6, 'stus7'=>$stus7));
            }

        } elseif (in_array($field, $stusp)) {

                $criteria = new CDbCriteria();
                $criteria->compare('stusp0', $stus0);
                $criteria->compare('stusp5', $stusp5);

                if ($field == 'stusp3') {
                    $cxmb = Cxmb::model()->getExtraMarks($cxmb0, $value);
                    if (! empty($cxmb)) {
                        $attr['stusp11'] = $cxmb['cxmb3'];
                        $attr['stusp8']  = $cxmb['cxmb2'];
                    }
                }

                $model = Stusp::model()->find($criteria);
                if (empty($model)) {
                    $model = new Stusp();
                    $model->stusp0 = $stus0;
                    $model->stusp2 = 0;
                    $model->stusp5 = $stusp5;
                    $model->stusp6 = $stus6;
                    $model->stusp7 = $stus7;
                    $model->stusp12 = '';

                    $model->$field = $value;
                    if (isset($attr['stusp11'])) {
                        $model->stusp11 = $attr['stusp11'];
                        $model->stusp8  = $attr['stusp8'];
                    }

                    $error = ! $model->save();
                } else
                    $error = ! $model->customSave($attr + array('stusp6'=>$stus6, 'stusp7'=>$stus7));
            }

        Yii::app()->end(CJSON::encode(array('error' => $error)));
    }


    /*-------------------------------Modules----------------------------------*/
    public function actionModules()
    {
        $model = new FilterForm;
        $model->scenario = 'modules';
        if (isset($_REQUEST['FilterForm']))
            $model->attributes=$_REQUEST['FilterForm'];

        $this->render('modules', array(
            'model'      => $model,
        ));
    }

    public function actionInsertJpvd()
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $error =false;

        $jpv1  = Yii::app()->request->getParam('jpv1', null);
        $st1  = Yii::app()->request->getParam('st1', null);
        $value = Yii::app()->request->getParam('value', null);

        if(empty($jpv1)||empty($st1)||$value<0||$value>200||$value===null)
            $error=true;
        else
        {
            $jpv = Jpv::model()->findByPk($jpv1);
            if($jpv==null)
                $error=true;
            else{
                $jpvp = Jpvp::model()->findByAttributes(array('jpvp1'=>$jpv1,'jpvp2'=>Yii::app()->user->dbModel->p1));
                if($jpvp==null)
                    $error=true;
                else
                {
                    $jpvd = Jpvd::model()->findByAttributes(array('jpvd1'=>$jpv1,'jpvd2'=>$st1));
                    if($jpvd==null)
                    {
                        $jpvd= new Jpvd();
                        $jpvd->jpvd0=new CDbExpression('GEN_ID(GEN_JPVD, 1)');
                        $jpvd->jpvd1=$jpv1;
                        $jpvd->jpvd2=$st1;
                    }
                    $jpvd->jpvd3=$value;
                    $jpvd->jpvd4=date('Y-m-d H:i:s');
                    $jpvd->jpvd5=0;
                    $jpvd->jpvd6=Yii::app()->user->dbModel->p1;
                    $error=!$jpvd->save();
                }
            }
        }


        Yii::app()->end(CJSON::encode(array('error' => $error)));
    }

    public function actionGetCxmb()
    {
        //if (! Yii::app()->request->isAjaxRequest)
            //throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $error =false;
        $ects = "-";
        $bal5 = "-";

        $bal  = Yii::app()->request->getParam('bal', null);

        if(!empty($bal))
        {
            $sql = <<<SQL
              SELECT * FROM cxmb WHERE cxmb4<={$bal} AND cxmb5>={$bal}
SQL;
            $el = Cxmb::model()->findBySql($sql);
            if(!empty($el))
            {
                $ects =$el->cxmb3;
                $bal5 =$el->cxmb2;
            }
            else
                $error = true;
        }else
            $error = true;

        Yii::app()->end(CJSON::encode(array('error' => $error,'ects'=>$ects,'bal5'=>$bal5)));
    }
    /*------------------------------------------------------------------------*/
}