<?php
/**
 * @var ProgressController $this
 * @var ModuleForm $model
 */

$this->pageHeader=tt('Ведение модулей');
$this->breadcrumbs=array(
    tt('Ведение модулей'),
);

Yii::app()->clientScript->registerPackage('gritter');
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/module/main.js', CClientScript::POS_HEAD);

$this->renderPartial('/filter_form/default/year_sem');

$this->renderPartial('module/_filter', array(
    'model' => $model
));


echo <<<HTML
    <span id="spinner1"></span>
HTML;


if ($model->validate())
    $this->renderPartial('module/_bottom', array(
        'model' => $model
    ));