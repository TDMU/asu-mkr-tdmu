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
                    'module', 'changeField', 'changeMark', 'autocomplete', 'changeTeacher'
                ),
                'expression' => 'Yii::app()->user->isAdmin || Yii::app()->user->isTch',
            ),

            array('allow',
                'actions' => array('rating','ratingExcel', 'ratingStudent')
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }
    
    public function actionRating()
    {
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

        $objPHPExcel=new PHPExcel();
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

    /**
     *
     */
    public function actionModule(){
        $form = new ModuleForm(Yii::app()->user->dbModel->p1);
        if (isset($_REQUEST['ModuleForm']))
            $form->attributes=$_REQUEST['ModuleForm'];

        if($form->validate()){
            if(!$form->checkAccess())
                throw new CHttpException(403, tt('Доступ запрещен'));

            $count = $form->getCountModules();
            if($count > 0)
                $form->countModules = $count;
            else if($form->countModules > 0) {
                $form->createModules();
            }
        }

        $this->render('module', array(
            'model' => $form,
        ));
    }

    /**
     * Изменение Оценки
     * @throws CHttpException
     * @throws CException
     * @throws Exception
     */
    public function actionChangeMark()
    {
        if (!Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $st1 = Yii::app()->request->getParam('st1', null);
        $mark = Yii::app()->request->getParam('mark', null);
        $module = Yii::app()->request->getParam('module', null);

        if($st1==null || $module==null || $mark == null)
            throw new CHttpException(400, tt('Не все данные переданы'));

        $moduleModel = Modgr::model()->findByPk($module);
        if(empty($moduleModel))
            throw new CHttpException(400, tt('Не найден модуль'));

        //TODO: добавить проврку что студент есть в данной группе

        $form = new ModuleForm(Yii::app()->user->dbModel->p1);
        if(!$form->checkAccessForModule($module))
            throw new CHttpException(403, tt('Доступ запрещен'));

        if($mark < 0)
            throw new CHttpException(400, tt('Оценка должна быть больше 0'));

        $mod = $moduleModel->module;
        if($mod->mod6 > 0 && $mark > $mod->mod6)
            throw new CHttpException(400, tt('Оценка должна быть ниже максимального балла'));

        $currentDate = new DateTime();
        if(!empty($mod->mod7)){
            $date = new DateTime($mod->mod7);
            if($date>$currentDate)
                throw new CHttpException(400, tt('Дата начала модуля еще не наступила'));
        }
        if(!empty($mod->mod8)){
            $date = new DateTime($mod->mod8);
            if($date<$currentDate)
                throw new CHttpException(400, tt('Дата конца модуля уже прошла'));
        }

        $markModel = Mods::model()->findByAttributes(array('mods1' => $module, 'mods2' => $st1));
        if(empty($markModel))
        {
            $markModel = new Mods();
            $markModel->mods1 = $module;
            $markModel->mods2 = $st1;
        }

        $markModel->mods3 = $mark;
        $markModel->mods4 = date('Y-m-d H:i:s');
        $markModel->mods5 = Yii::app()->user->id;

        if(!$markModel->save())
            throw new CHttpException(500, tt('Ошибка сохранения оценки'));

        Yii::app()->end(CJSON::encode(array('message' => 'OK')));
    }

    /**
     * Изменение параметров модуля
     * @throws CHttpException
     * @throws CException
     */
    public function actionChangeField(){
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $field = Yii::app()->request->getParam('field', null);
        $id = Yii::app()->request->getParam('id', null);
        $value = Yii::app()->request->getParam('value', null);

        if($id==null || $field==null)
            throw new CHttpException(400, tt('Не все данные переданы'));

        if(!in_array($field, array('mod5', 'mod6')) || !is_string($value))
            throw new CHttpException(400, tt('Ошибка входящих данных'));

        $module = Mod::model()->findByPk($id);
        if(empty($module))
            throw new CHttpException(400, tt('Не найден модуль'));

        $form = new ModuleForm(Yii::app()->user->dbModel->p1);
        if(!$form->checkAccessForMod($module->mod1))
            throw new CHttpException(403, tt('Доступ запрещен'));

        if($module->checkMaxBall($value))
            throw new CHttpException(400, tt('Сумма баллов по модулям не должна быть больше 100'));

        $module->$field = $value;

        if(!$module->save())
            throw new CHttpException(500, tt('Ошибка сохранения'));

       Yii::app()->end(CJSON::encode(array(
           'message' => 'ok'
       )));
    }

    /**
     * Изменение параметров модуля
     * @throws CHttpException
     * @throws CException
     */
    public function actionChangeTeacher(){
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $id = Yii::app()->request->getParam('id', null);
        $value = Yii::app()->request->getParam('value', null);

        if($id==null || $value==null)
            throw new CHttpException(400, tt('Не все данные переданы'));

        $module = Modgr::model()->findByPk($id);
        if(empty($module))
            throw new CHttpException(400, tt('Не найден модуль'));

        $form = new ModuleForm(Yii::app()->user->dbModel->p1);
        if(!$form->checkAccessForMod($module->module->mod1))
            throw new CHttpException(403, tt('Доступ запрещен'));

        $module->modgr5 = $value;

        if(!$module->save())
            throw new CHttpException(500, tt('Ошибка сохранения'));

        Yii::app()->end(CJSON::encode(array(
            'message' => 'ok'
        )));
    }

    /**
     * Поиск преподователя
     * @throws CHttpException
     */
    public function actionAutocomplete($k1)
    {
        if (! Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404, 'Invalid request. Please do not repeat this request again.');

        $query = Yii::app()->request->getParam('query', null);

        $suggestions = array();

        $teachers = P::model()->findTeacherChairByName($k1,$query);

        foreach($teachers as $tch)
        {
            $suggestions[] = array(
                'value' => SH::getShortName($tch['p3'], $tch['p4'], $tch['p5']). ' '.$tch['dol2']. ' '.round($tch['pd6'], 2).' '.tt('ст.'),
                'id'    => $tch['pd1']
            );
        }

        $res = array(
            'query'       => $query,
            'suggestions' => $suggestions,
        );

        Yii::app()->end(CJSON::encode($res));
    }
}