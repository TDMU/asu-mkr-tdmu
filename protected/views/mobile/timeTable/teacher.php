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
echo CHtml::link('<i class="glyphicon glyphicon-hand-right"></i>'.' '.tt('Общий вид'), array('/timeTable/teacher'));
echo '</div>';
echo '<div class="clearfix"></div>';

$this->renderPartial('/filter_form/default/mobile/_accordion_select', array(
    'render' => '/filter_form/mobile/timeTableTeacher',
    'arr' => array('model' => $model)
));?>

<?php

if (! empty($model->teacher)) {
    $this->renderPartial('timeTable/schedule', array(
        'model' => $model,
        'timeTable' => $timeTable,
        'rz' => $rz,
    ));
}else{
    $this->pageHeader=tt('Расписание преподавателя');
    $this->breadcrumbs=array(
        tt('Расписание'),
    );
}