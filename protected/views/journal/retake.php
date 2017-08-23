<?php
/**
 * @var $retake Elgzst
 */
$this->pageHeader=tt('Отработка');
    $this->breadcrumbs=array(
        tt('Электронный журнал'),
    );
Yii::app()->clientScript->registerPackage('gritter');
Yii::app()->clientScript->registerPackage('jquery.ui');
Yii::app()->clientScript->registerPackage('datepicker');
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/journal/retake.js', CClientScript::POS_HEAD);

$error       = tt('Ошибка! Проверьте правильность вводимых данных!');
$error_load  = tt('Ошибка! Ошибка загрузки данных!');
$success     = tt('Cохранено!');
$minMaxError = tt('Оценка за пределами допустимого интервала!');
$access= tt('Ошибка! Нет доступа!');

Yii::app()->clientScript->registerScript('translations', <<<JS
    tt.error       = "{$error}"
    tt.error_load       = "{$error_load}"
    tt.success     = "{$success}"
    tt.minMaxError = "{$minMaxError}"
    tt.access = "{$access}";
JS
, CClientScript::POS_READY);
        
$this->renderPartial('/filter_form/default/year_sem');

echo <<<HTML
    <span id="spinner1"></span>
HTML;

$this->renderPartial('retake/_bottom', array(
        'model' => $model,
        'retake'      => $retake
    ));

