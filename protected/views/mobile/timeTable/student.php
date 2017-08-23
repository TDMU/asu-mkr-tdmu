<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 09.02.2016
 * Time: 11:00
 */
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/mobile/script/timetable/script.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerPackage('datepicker-mobile');
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/mobile/datepicker/locales/bootstrap-datepicker.'.Yii::app()->language.'.min.js', CClientScript::POS_END);

echo '<div class="pull-right">';
echo CHtml::link('<i class="glyphicon glyphicon-hand-right"></i>'.' '.tt('Общий вид'), array('/timeTable/student'));
echo '</div>';
echo '<div class="clearfix"></div>';

$this->renderPartial('/filter_form/default/mobile/_accordion_select', array(
    'render' => '/filter_form/mobile/timeTableStudent',
    'arr' => array('model' => $model)
));?>

<?php

if (! empty($model->student)) {
    $this->renderPartial('timeTable/schedule', array(
        'model' => $model,
        'timeTable' => $timeTable,
        'rz' => $rz,
    ));
}else{
    $this->pageHeader=tt('Расписание студента');
    $this->breadcrumbs=array(
        tt('Расписание'),
    );
}