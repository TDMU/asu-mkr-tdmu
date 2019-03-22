<?php

class MobileController extends Controller
{
    public $layout='/layouts/mobile';

    public function filters() {

        return array(
            'accessControl',
        );
    }

    public function accessRules() {

        return array(
            array('allow',
                'actions' => array(
                    'journal',
                ),
                'expression' => 'Yii::app()->user->isTch',
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    /**
     *  Мобильній Журнал
     */
    public function actionJournal()
    {
        if($this->universityCode != U_ZSMU)
            throw new CHttpException(403, tt('Доступ запрещен'));

        $model = new FilterForm;
        $model->scenario = 'journal';

        if (isset($_REQUEST['FilterForm']))
            $model->attributes=$_REQUEST['FilterForm'];

        $read_only=false;

        $this->render('journal', array(
            'model' => $model,
            'read_only' => $read_only,
        ));
    }

}