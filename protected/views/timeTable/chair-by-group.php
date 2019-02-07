<?php

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
    $groups = Gr::model()->getGroupsByChair($model->chair, $model->date1, $model->date2);
    if(!empty($groups)){
        $this->renderPartial('chair/_table1_group', array(
                'model'      => $model,
                'groups'      => $groups
        ));
        $this->renderPartial('chair/_table2_group', array(
            'model'      => $model,
            'groups'      => $groups
        ));
    }
    echo '</div>';
}