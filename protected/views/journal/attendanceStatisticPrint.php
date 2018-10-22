<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 12.04.2017
 * Time: 14:19
 */

$this->pageHeader=tt('Статистика посещаемости на поток');
$this->breadcrumbs=array(
    tt('Статистика посещаемости на поток'),
);
Yii::app()->clientScript->registerPackage('datepicker');
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/journal/attendanceStatisticPrint.js', CClientScript::POS_HEAD);

if($type_statistic!=0)
    throw new Exception(tt("Печать статистики только для настройки 'Статистика по электроному журналу'"));

$this->renderPartial('attendanceStatistic/_print', array(
    'model' => $model,
));

echo <<<HTML
    <span id="spinner1"></span>
HTML;

$str = tt('Печать');

if(!empty($model->course))
    echo <<<HTML
        <button class="btn btn-primary btn-small" id="print-excel"><i class="icon-print"></i> $str </button>
HTML;

$url = Yii::app()->createUrl('/journal/attendanceStatisticPrintExcel');

Yii::app()->clientScript->registerScript('print-excel',<<<JS
    $(document).on('click','#print-excel',
        function(){
            var action=$("#filter-form").attr("action");
            var url = $(this).data('url');
            $("#filter-form").attr("action", '$url');
            $("#filter-form").submit();
            $("#filter-form").attr("action", action);
        }
    );

    
    $('.datepicker').datepicker({
        format: 'dd.mm.yyyy',
        language:'ru'
    });
JS
    , CClientScript::POS_READY);
