<?php

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'ps-appearance',
    'htmlOptions' => array('class' => 'form-horizontal'),
    'action' => '#'
));
?>
    <div class="control-group">
        <span class="lbl"> <?=tt('Количество дней для индикации (по умолчанию)')?>:</span>
        <?=CHtml::numberField('settings[108]', PortalSettings::model()->findByPk(108)->ps2)?>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-info btn-small">
            <i class="icon-ok bigger-110"></i>
            <?=tt('Сохранить')?>
        </button>
    </div>

<?php $this->endWidget();?>