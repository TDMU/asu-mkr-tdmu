<?php

class PaymentController extends Controller
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
                    'hostel',
                    'education'
                ),
                'expression' => 'Yii::app()->user->isStd',
            ),
            array('allow',
                'actions' => array(
                    'hostelCurator',
                    'educationCurator'
                ),
                'expression' => 'Yii::app()->user->isAdmin || Yii::app()->user->isTch',
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }



    public function actionHostel()
    {
        $plans    = Spob::model()->getPlan(Yii::app()->user->dbModel->st1);
        $payments = Sob::model()->getPayments(Yii::app()->user->dbModel->st1);

        $this->render('hostel', array(
            'plans' => $plans,
            'payments' => $payments,
        ));
    }

    public function actionEducation()
    {
        $plans    = Spo::model()->getPlan(Yii::app()->user->dbModel->st1);
        $payments = So::model()->getPayments(Yii::app()->user->dbModel->st1);

        $this->render('education', array(
            'plans' => $plans,
            'payments' => $payments,
        ));
    }

    /**
     * проверка есть ли дорступ у куратора для просомтра этого студента
     * @param $st1
     */
    private function _checkAccessCuratorForStudent($st1){
        if(empty($st1))
            return;
    }

    public function actionEducationCurator()
    {
        $model = new FilterForm();
        $model->scenario = 'payment';
        if (isset($_REQUEST['FilterForm']))
            $model->attributes=$_REQUEST['FilterForm'];

        $this->_checkAccessCuratorForStudent($model->student);

        $this->render('education-curator', array(
            'model' => $model
        ));
    }

    public function actionHostelCurator()
    {
        $model = new FilterForm();
        $model->scenario = 'payment';
        if (isset($_REQUEST['FilterForm']))
            $model->attributes=$_REQUEST['FilterForm'];

        $this->_checkAccessCuratorForStudent($model->student);

        $this->render('hostel-curator', array(
            'model' => $model
        ));
    }

}