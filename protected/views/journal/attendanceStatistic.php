<?php
/**
 *
 * @var ProgressController $this
 * @var FilterForm $model
 */

$this->pageHeader=tt('Статистика посещаемости');
$this->breadcrumbs=array(
    tt('Статистика посещаемости'),
);

Yii::app()->clientScript->registerPackage('dataTables');
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/journal/attendanceStatistic.js', CClientScript::POS_HEAD);

$this->renderPartial('/filter_form/timeTable/group', array(
    'model' => $model,
    'type'=>1,//тип для юрки показывать или нет факультет 5
    'showDateRangePicker' => false
));


echo <<<HTML
    <span id="spinner1"></span>
HTML;

if (! empty($model->group))
{
    $this->renderPartial('attendanceStatistic/_bottom', array(
        'model' => $model,
    ));
}
