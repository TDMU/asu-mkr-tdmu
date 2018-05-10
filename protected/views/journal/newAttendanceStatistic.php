<?php
/**
 *
 * @var ProgressController $this
 * @var FilterForm $model
 */

/** @var $model AttendanceStatisticForm */

$this->pageHeader=tt('Статистика посещаемости (н.)');
$this->breadcrumbs=array(
    tt('Статистика посещаемости (н.)'),
);

Yii::app()->clientScript->registerPackage('dataTables');
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/journal/newAttendanceStatistic.js', CClientScript::POS_HEAD);

$this->renderPartial('newAttendanceStatistic/_type', array(
    'model' => $model
));

$this->renderPartial('newAttendanceStatistic/_filter', array(
    'model' => $model
));
echo <<<HTML
    <span id="spinner1"></span>
HTML;

$this->renderPartial('newAttendanceStatistic/_bottom', array(
    'model' => $model
));
