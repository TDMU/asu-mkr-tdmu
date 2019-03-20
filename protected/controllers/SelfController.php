<?php
/**
 * Created by PhpStorm.
 * User: neffa
 * Date: 16.05.2018
 * Time: 10:33
 */

class SelfController extends Controller
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
                    'workLoad'
                ),
                'expression' => 'Yii::app()->user->isTch',
            ),
            array('allow',
                'actions' => array(
                    'subscription',
                ),
                'expression' => 'Yii::app()->user->isStd',
            ),
            array('allow',
                'actions' => array(
                    'studentInfo',
                    'studentCard',
                ),
            ),
            array('allow',
                'actions' => array(
                    'timeTable',
                ),
                'users' => array('@'),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    public function actionWorkLoad()
    {
        $this->forward('workLoad/self');
    }

    public function actionTimeTable()
    {
        $this->forward('timeTable/self');
    }

    public function actionStudentCard()
    {
        $this->forward('other/studentCard');
    }

    public function actionSubscription()
    {
        $this->forward('other/subscription');
    }

    public function actionStudentInfo()
    {
        $this->forward('other/studentInfo');
    }
}