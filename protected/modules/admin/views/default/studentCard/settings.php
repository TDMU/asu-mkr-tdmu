<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 25.12.2015
 * Time: 10:11
 */

$htmlOptions2 = array(
    'class'=>'ace',
);

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'ps-appearance',
    'htmlOptions' => array('class' => 'form-horizontal form-settings'),
    'action' => '#'
));

$options = array(
    ' '.tt('Успеваемость'),
    ' '.tt('Поточная задолженость'),
    ' '.tt('Ведение модулей'),
    ' '.tt('Екзаменационная сессия'),
    ' '.tt('Общая успеваемость'),
    ' '.tt('Общая информация'),
    ' '.tt('Итоговая успеваемость (журнал)'),
);

$htmlOptions = array(
    'class'=>'ace',
    'labelOptions' => array(
        'class' => 'lbl'
    )
);

?>
    <div class="control-group">
        <span class="lbl"> <?=tt('Активный таб (по умолчанию):')?></span><br>
        <?=CHtml::radioButtonList('settings[50]', PortalSettings::model()->findByPk(50)->ps2, $options, $htmlOptions)?>
    </div>

    <div class="control-group">
        <?=CHtml::checkBox('settings[47]', PortalSettings::model()->findByPk(47)->ps2, $htmlOptions2)?>
        <span class="lbl"> <?=tt('Показывать таб Успеваемость (журнал)')?></span>
        <?=CHtml::hiddenField('settings[47]', PortalSettings::model()->findByPk(47)->ps2)?>
    </div>

    <div class="control-group">
        <?=CHtml::checkBox('settings[48]', PortalSettings::model()->findByPk(48)->ps2, $htmlOptions2)?>
        <span class="lbl"> <?=tt('Показывать таб Поточная задолженость (журнал)')?></span>
        <?=CHtml::hiddenField('settings[48]', PortalSettings::model()->findByPk(48)->ps2)?>
    </div>

    <div class="control-group">
        <?=CHtml::checkBox('settings[49]', PortalSettings::model()->findByPk(49)->ps2, $htmlOptions2)?>
        <span class="lbl"> <?=tt('Показывать таб ведение модулей')?></span>
        <?=CHtml::hiddenField('settings[49]', PortalSettings::model()->findByPk(49)->ps2)?>
    </div>

    <div class="control-group">
        <?=CHtml::checkBox('settings[51]', PortalSettings::model()->findByPk(51)->ps2, $htmlOptions2)?>
        <span class="lbl"> <?=tt('Показывать таб екзаменационная сессия')?></span>
        <?=CHtml::hiddenField('settings[51]', PortalSettings::model()->findByPk(51)->ps2)?>
    </div>

    <div class="control-group">
        <?=CHtml::checkBox('settings[52]', PortalSettings::model()->findByPk(52)->ps2, $htmlOptions2)?>
        <span class="lbl"> <?=tt('Показывать таб общая успеваемость')?></span>
        <?=CHtml::hiddenField('settings[52]', PortalSettings::model()->findByPk(52)->ps2)?>
    </div>

    <div class="control-group">
        <?=CHtml::checkBox('settings[91]', PortalSettings::model()->findByPk(91)->ps2, $htmlOptions2)?>
        <span class="lbl"> <?=tt('Показывать таб общая информация')?></span>
        <?=CHtml::hiddenField('settings[91]', PortalSettings::model()->findByPk(91)->ps2)?>
    </div>

    <div class="control-group">
        <?=CHtml::checkBox('settings[109]', PortalSettings::model()->findByPk(109)->ps2, $htmlOptions2)?>
        <span class="lbl"> <?=tt('Показывать таб "Итоговая успеваемость (журнал)"')?></span>
        <?=CHtml::hiddenField('settings[109]', PortalSettings::model()->findByPk(109)->ps2)?>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-info btn-small">
            <i class="icon-ok bigger-110"></i>
            <?=tt('Сохранить')?>
        </button>
    </div>

<?php $this->endWidget();?>