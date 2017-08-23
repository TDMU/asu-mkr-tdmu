<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 13.03.2017
 * Time: 12:41
 */

$this->pageHeader=tt('Блокировка смены тем курсовых');
$this->breadcrumbs=array(
    tt('Админ. панель'),
);

Yii::app()->clientScript->registerPackage('gritter');
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/admin/courseWorkBlocker.js', CClientScript::POS_HEAD);

$error       = tt('Ошибка! Проверьте правильность вводимых данных или доступ для ввода!');
$success     = tt('Cохранено!');
Yii::app()->clientScript->registerScript('translations', <<<JS
        tt.error       = "{$error}";
        tt.success     = "{$success}";
JS
    , CClientScript::POS_READY);


echo <<<HTML
<span id="spinner1"></span>
HTML;


$url       = $this->createUrl('save');
echo <<<HTML
    <ul class="nav nav-tabs nav-stacked" data-url="{$url}" >
HTML;

$pattern = <<<HTML
        <li><a>%s <span>%s (%s - %s)</span></a></li>
HTML;

    foreach ($models as $item)
    {
        echo sprintf(
            $pattern,
            CHtml::checkBox('checkbox-'.$item['sg1'],$item['sg1']==$item['cwb1'], array(
                    'data-sg1'=>$item['sg1'],
                    'class'=>'checkbox-sg1'
                )
            ),
            $item['sp2'],
            $item['sg3'],
            SH::convertEducationType($item['sg4'])
        );
    }

echo '</ul>'
?>