<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 14.12.2016
 * Time: 20:46
 */

class AlertController extends Controller
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
                    'send'
                ),
                'expression' => 'Yii::app()->user->isAdmin || Yii::app()->user->isTch',
            ),
            array('allow',
                'actions' => array(
                    'index'
                ),
                'expression' => 'Yii::app()->user->isAdmin || Yii::app()->user->isTch || Yii::app()->user->isStd',
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    /**
     * Индесная страница
     */
    public function actionIndex()
    {
        if(Yii::app()->user->isGuest)
            throw new CHttpException(403, tt('Доступ запрещен'));

        $this->render('index', array(
            'model'   => Yii::app()->user->model
        ));
    }
}