<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 26.11.2015
 * Time: 15:31
 */

/**
 * @var $this PortfolioFarmController
 * @var TimeTableForm $model
 */

$this->pageHeader=tt('Портфолио');
$this->breadcrumbs=array(
    tt('Портфолио'),
);
Yii::app()->clientScript->registerPackage('gritter');
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/timetable/timetable.js', CClientScript::POS_HEAD);

if(Yii::app()->user->isTch) {
    $this->renderPartial('_student', array(
        'model' => $model
    ));
}


echo <<<HTML
    <span id="spinner1"></span>
HTML;
if ($model->student) :
    $this->renderPartial('_bottom', array(
        'student' => St::model()->findByPk($model->student),
        'model' => $model,
    ));
endif;
