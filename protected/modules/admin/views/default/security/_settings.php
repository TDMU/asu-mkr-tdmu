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
    <?=CHtml::checkBox('', PortalSettings::model()->findByPk(114)->ps2, $htmlOptions2)?>
    <span class="lbl"> <?=tt('Выключить одновременные сессии (Вы можете одновременно войти в учетную запись только с одного устройства или браузера. При этом другие активные сессии будут удалены.)')?></span>
    <?=CHtml::hiddenField('settings[114]', PortalSettings::model()->findByPk(114)->ps2)?>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-info btn-small">
        <i class="icon-ok bigger-110"></i>
        <?=tt('Сохранить')?>
    </button>
</div>

<?php $this->endWidget();?>
