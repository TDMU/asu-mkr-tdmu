<?php
/**
 * @var ProgressController $this
 * @var RatingForm $model
 */

$this->pageHeader=tt('Рейтинг группы');
$this->breadcrumbs=array(
    tt('Рейтинг'),
);

Yii::app()->clientScript->registerPackage('dataTables');
Yii::app()->clientScript->registerPackage('gritter');
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/rating/main.js', CClientScript::POS_HEAD);

$this->renderPartial('rating/_group', array(
    'model' => $model
));


echo <<<HTML
    <span id="spinner1"></span>
HTML;


if (! empty($model->group))
    $this->renderPartial('rating/_bottom', array(
        'model' => $model
    ));
