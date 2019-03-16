<?php
/**
 * Created by PhpStorm.
 * User: neffa
 * Date: 15.02.2019
 * Time: 12:57
 */

/**
 * @var $this AlertController
 * @var $model Users
 * @var $period string
 */

$this->pageHeader=tt('Оповещение');
$this->breadcrumbs=array(
    tt('Сообщения'),
);

Yii::app()->clientScript->registerCss('style-alert', <<<CSS
    #alert-service .well{
        margin-bottom: 2px;
        padding: 3px;
    }
    #alert-service .media{
        margin-top: 5px;
    }
    #alert-service img{
        max-height: 50px;
    }
    #period-type label{
        display: inline;
    }
    #alert-service .badge>small {
        color:#eeeeee;
    }
CSS
);
echo '<div class="row-fluid">';
$isOutputEnabled = $model->isTeacher || ($model->isStudent && PortalSettings::model()->getSettingFor(PortalSettings::STUDENT_SEND_IN_ALERT) == 1);
if($isOutputEnabled){
    echo '<div class="pull-left">';
    echo $this->renderPartial('_form');
    echo '</div>';
}

echo '<div class="pull-right">';
echo CHtml::radioButtonList('period-type', $period,
    array(
        Um::TIME_PERIOD_MONTH => tt('Месяц'),
        Um::TIME_PERIOD_YEAR => tt('Год')
    ),
    array(
        'separator' => " | ",
    )
);
echo '</div>';
echo '</div>';
$url = Yii::app()->createUrl("/alert/index");
Yii::app()->clientScript->registerScript('change-periodType', <<<JS
     $("#period-type").on("change", function(){
         var period = $('input[name="period-type"]:checked').val();
         window.location = "{$url}?period=" + period;
     });
JS
    );
?>
<div class="row-fluid" id="alert-service">
    <div class="span6">
        <div class="widget-box">
            <div class="widget-header">
                <h5><?=tt('Входящие сообщения')?></h5>

                <div class="widget-toolbar">
                    <a data-action="collapse" href="#">
                        <i class="icon-chevron-down"></i>
                    </a>
                </div>
            </div>

            <div class="widget-body">
                <div class="widget-main">
                    <?=$this->renderPartial('_input', array(
                        'model' => $model,
                        'period' => $period,
                        'isOutputEnabled' => $isOutputEnabled
                    ));?>
                </div>
            </div>
        </div>
    </div>
<?php

if($isOutputEnabled):
?>

    <div class="span6">
        <div class="widget-box">
            <div class="widget-header">
                <h5><?=tt('Отправленные сообщения')?></h5>

                <div class="widget-toolbar">
                    <a data-action="collapse" href="#">
                        <i class="icon-chevron-down"></i>
                    </a>
                </div>
            </div>

            <div class="widget-body">
                <div class="widget-main">
                    <?=$this->renderPartial('_output', array(
                        'model' => $model,
                        'period' => $period
                    ))?>
                </div>
            </div>
        </div>
    </div>
<?php
endif;
echo '</div>';
