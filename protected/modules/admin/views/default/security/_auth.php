<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 10.02.2017
 * Time: 18:18
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
    <?=CHtml::checkBox('', PortalSettings::model()->findByPk(115)->ps2, $htmlOptions2)?>
    <span class="lbl"> <?=tt('Защита авторизационной сессии')?></span>
    <?=CHtml::hiddenField('settings[115]', PortalSettings::model()->findByPk(115)->ps2)?>
</div>

<div class="control-group">
    <?=CHtml::checkBox('', PortalSettings::model()->findByPk(116)->ps2, $htmlOptions2)?>
    <span class="lbl"> <?=tt('Привязка к REMOTE_ADR')?></span>
    <?=CHtml::hiddenField('settings[116]', PortalSettings::model()->findByPk(116)->ps2)?>
</div>

<div class="control-group">
    <?=CHtml::checkBox('', PortalSettings::model()->findByPk(117)->ps2, $htmlOptions2)?>
    <span class="lbl"> <?=tt('Привязка к USER AGENT')?></span>
    <?=CHtml::hiddenField('settings[117]', PortalSettings::model()->findByPk(117)->ps2)?>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-info btn-small">
        <i class="icon-ok bigger-110"></i>
        <?=tt('Сохранить')?>
    </button>
</div>

<?php $this->endWidget();?>
