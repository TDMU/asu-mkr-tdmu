<?php
/**
 *
 * @var DefaultController $this
 */
    $this->pageHeader=tt('Настройки сервиса Расписание');
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

    $title = tt('Обшие настройки');
    $html  = $this->renderPartial('timeTable/_general', array(), true);
    echo sprintf($widget, $title, $html);

    $title = tt('Расписание преподователя');
    $html  = $this->renderPartial('timeTable/_teacher', array(), true);
    echo sprintf($widget, $title, $html);

    $title = tt('Расписание студента');
    $html  = $this->renderPartial('timeTable/_student', array(), true);
    echo sprintf($widget, $title, $html);


