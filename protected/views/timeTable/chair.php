<?php

/**
 * @var $model TimeTableForm
 * @var $this TimeTableController
 */

$this->pageHeader=tt('Расписание кафедры');
$this->breadcrumbs=array(
    tt('Расписание'),
);

Yii::app()->clientScript->registerPackage('jquery.ui');
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/timetable/timetable.js', CClientScript::POS_HEAD);

$this->renderPartial('/filter_form/timeTable/chair', array(
    'model' => $model,
));

echo <<<HTML
    <span id="spinner1"></span>
HTML;

if (! empty($model->chair)) {
    echo '<div id="time-table-chair">';
    $teachers = P::model()->getTeachersForTimeTableChair($model->chair);
    if(!empty($teachers)){
        $this->renderPartial('chair/_table1', array(
                'model'      => $model,
                'teachers'      => $teachers
        ));
        $this->renderPartial('chair/_table2', array(
            'model'      => $model,
            'teachers'      => $teachers
        ));
    }
    echo '</div>';
}