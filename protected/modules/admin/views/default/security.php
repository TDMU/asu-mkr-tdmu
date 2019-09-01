<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 09.02.2017
 * Time: 17:49
 */

$this->pageHeader=tt('Настройки безопасности');
$this->breadcrumbs=array(
    tt('Админ. панель'),
);

Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/admin/journal.js');

$widget = <<<HTML
<div class="span4">
    <div class="widget-box">
        <div class="widget-header">
            <h4>%s</h4>
            <span class="widget-toolbar">
                <a data-action="collapse" href="#">
                    <i class="icon-chevron-up"></i>
                </a>
            </span>
        </div>
        <div class="widget-body">
            <div class="widget-main">
            %s
            </div>
        </div>
    </div>
</div>
HTML;


$title = tt('Блокировка уч. записей');
$html  = $this->renderPartial('security/_accounts', array(), true);
echo sprintf($widget, $title, $html);

$title = tt('Остальные настройки');
$html  = $this->renderPartial('security/_settings', array(), true);
echo sprintf($widget, $title, $html);
