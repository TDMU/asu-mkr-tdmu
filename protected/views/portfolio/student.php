<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 26.11.2015
 * Time: 15:31
 */


$this->pageHeader=tt('Студент');
$this->breadcrumbs=array(
    tt('Портфолио'),
);

Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/timetable/timetable.js', CClientScript::POS_HEAD);

if(Yii::app()->user->isAdmin) {
    $this->renderPartial('/filter_form/default/year_sem');

    $this->renderPartial('student/_student', array(
        'model' => $model
    ));
}


echo <<<HTML
    <span id="spinner1"></span>
HTML;
if ($model->student) :
    $this->renderPartial('student/_bottom', array(
        'student' => St::model()->findByPk($model->student),
        'model' => $model,
    ));
endif;
