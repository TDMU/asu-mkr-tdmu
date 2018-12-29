<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 02.12.2016
 * Time: 22:25
 */

/** @var $model FilterForm */
$this->pageHeader=tt('Export of list of students for Moodle');
$this->breadcrumbs=array(
    tt('Export of list of students for Moodle'),
);

Yii::app()->clientScript->registerPackage('dataTables');
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/list/students2moodle.js', CClientScript::POS_HEAD);

$this->renderPartial('/filter_form/list/students2moodle', array(
    'model' => $model,
));


echo <<<HTML
    <span id="spinner1"></span>
HTML;


if (! empty($model->stream))
    $this->renderPartial('students2moodle/_bottom', array(
        'model' => $model,
    ));
