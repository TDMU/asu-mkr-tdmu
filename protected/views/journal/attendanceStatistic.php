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
    'showDateRangePicker' => false
));


echo <<<HTML
    <span id="spinner1"></span>
HTML;

if (! empty($model->group))
{
    if($type_statistic==0)
		$this->renderPartial('attendanceStatistic/_bottom', array(
			'model' => $model,
			'type_statistic'=>$type_statistic,
		));
	else
		$this->renderPartial('attendanceStatistic/_bottomOld', array(
			'model' => $model,
			'type_statistic'=>$type_statistic,
		));	
}
