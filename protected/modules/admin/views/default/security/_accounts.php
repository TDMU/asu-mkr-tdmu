<?php

/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 09.02.2017
 * Time: 19:15
 */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'ps-appearance',
    'htmlOptions' => array('class' => 'form-horizontal'),
    'action' => '#'
));

$htmlOptions2 = array(
    'class'=>'ace',
);
?>

    <div class="control-group">
        <?=CHtml::checkBox('', PortalSettings::model()->findByPk(110)->ps2, $htmlOptions2)?>
        <span class="lbl"> <?=tt('Блокирововать уч. записи (блокировка на время)')?></span>
        <?=CHtml::hiddenField('settings[110]', PortalSettings::model()->findByPk(110)->ps2)?>
    </div>

    <div class="control-group">
        <span class="lbl"> <?=tt('Количетсво попыток (после чего будет блокировка)')?>:</span>
        <?=CHtml::numberField('settings[111]', PortalSettings::model()->findByPk(111)->ps2, array('min'=>1))?>
    </div>

    <div class="control-group">
        <span class="lbl"> <?=tt('Количетсво минут (в течении которых считаютсья попытки)')?>:</span>
        <?=CHtml::numberField('settings[113]', PortalSettings::model()->findByPk(113)->ps2, array('min'=>1))?>
    </div>

    <div class="control-group">
        <span class="lbl"> <?=tt('Количетсво минут (на сколько блокировать)')?>:</span>
        <?=CHtml::numberField('settings[112]', PortalSettings::model()->findByPk(112)->ps2, array('min'=>10))?>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-info btn-small">
            <i class="icon-ok bigger-110"></i>
            <?=tt('Сохранить')?>
        </button>
    </div>

<?php $this->endWidget();?>