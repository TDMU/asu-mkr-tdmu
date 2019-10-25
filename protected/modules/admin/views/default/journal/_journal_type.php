<?php

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'ps-appearance',
    'htmlOptions' => array('class' => 'form-horizontal'),
    'action' => '#'
));

    $options = array(
        ' '.tt('Стандартный (сума балов)'),
        ' '.tt('Вариант 1 (ср. бал по занятиям * 12 + доп. балы)'),
        ' '.tt('Вариант 2 (ср. бал по занятиям + доп. балы)'),
        ' '.tt('Одувс (сума балов(переводится в 40 бальную систему)  + доп. балы)'),
    );

    $options2 = array(
        ' '.tt('Количество дней на введение оценок'). ' '.tt('(Тип 1)'),
        ' '.tt('10 минут до занятия и определенное кол-во минут после занятия').' '.tt('(Тип 2)'),
    );

    $options4 = array(
        ' -',
        ' '.tt('Запись и чтение итоговой оценки'),
        ' '.tt('Чтение итоговой оценки'),
    );

    $options3 = array(
        ' '.tt('Сумма балов по занятиям'),
        ' '.tt('Среднее по занятиям'),
        ' '.tt('Перевод среднего в 200-бальную систему'),
    );

    $htmlOptions = array(
        'class'=>'ace',
        'labelOptions' => array(
            'class' => 'lbl'
        )
    );


    $htmlOptions2 = array(
        'class'=>'ace',
    );
?>
    <?php /*<div class="control-group">
        <?=CHtml::radioButtonList('settings[8]', PortalSettings::model()->findByPk(8)->ps2, $options, $htmlOptions)?>
    </div>*/ ?>

    <div class="control-group">
        <?=CHtml::checkBox('', PortalSettings::model()->getSettingFor(PortalSettings::ENABLE_REGISTRATION_PASS), $htmlOptions2)?>
        <span class="lbl"> <?=tt('Расширенная "Регистрация пропусков"')?></span>
        <?=CHtml::hiddenField('settings['.PortalSettings::ENABLE_REGISTRATION_PASS.']', PortalSettings::model()->getSettingFor(PortalSettings::ENABLE_REGISTRATION_PASS))?>
    </div>

    <div class="control-group">
        <?=CHtml::checkBox('', PortalSettings::model()->findByPk(60)->ps2, $htmlOptions2)?>
        <span class="lbl"> <?=tt('Блокировать непереведенных студентов')?></span>
        <?=CHtml::hiddenField('settings[60]', PortalSettings::model()->findByPk(60)->ps2)?>
    </div>

    <div class="control-group">
        <?=CHtml::checkBox('', PortalSettings::model()->findByPk(65)->ps2, $htmlOptions2)?>
        <span class="lbl"> <?=tt('Автопроставлени пропусков заблокированым студентам')?></span>
        <?=CHtml::hiddenField('settings[65]', PortalSettings::model()->findByPk(65)->ps2)?>
    </div>


    <div class="control-group">
        <?=CHtml::radioButtonList('settings[44]', PortalSettings::model()->getSettingFor(44), $options, $htmlOptions)?>
    </div>

    <div class="control-group">
        <?=CHtml::checkBox('', PortalSettings::model()->findByPk(9)->ps2, $htmlOptions2)?>
        <span class="lbl"> <?=tt('Учитывать min max')?></span>
        <?=CHtml::hiddenField('settings[9]', PortalSettings::model()->findByPk(9)->ps2)?>
    </div>

    <div class="control-group">
        <?=CHtml::checkBox('', PortalSettings::model()->findByPk(20)->ps2, $htmlOptions2)?>
        <span class="lbl"> <?=tt('Использовать субмодули')?></span>
        <?=CHtml::hiddenField('settings[20]', PortalSettings::model()->findByPk(20)->ps2)?>
    </div>

    <div class="control-group">
        <?=CHtml::checkBox('', PortalSettings::model()->findByPk(56)->ps2, $htmlOptions2)?>
        <span class="lbl"> <?=tt('Ввод оценок только типа "занятие"')?></span>
        <?=CHtml::hiddenField('settings[56]', PortalSettings::model()->findByPk(56)->ps2)?>
    </div>

    <div class="control-group">
        <?=CHtml::checkBox('', PortalSettings::model()->findByPk(57)->ps2, $htmlOptions2)?>
        <span class="lbl"> <?=tt('Обьединение с модулями')?></span>
        <?=CHtml::hiddenField('settings[57]', PortalSettings::model()->findByPk(57)->ps2)?>
    </div>

    <div class="control-group">
        <?=CHtml::checkBox('', PortalSettings::model()->findByPk(106)->ps2, $htmlOptions2)?>
        <span class="lbl"> <?=tt('Ввод старостами посещаемости')?></span>
        <?=CHtml::hiddenField('settings[106]', PortalSettings::model()->findByPk(106)->ps2)?>
    </div>

    <div class="control-group">
        <?=CHtml::checkBox('', PortalSettings::model()->findByPk(83)->ps2, $htmlOptions2)?>
        <span class="lbl"> <?=tt('Скрывать столбец "Итог"')?></span>
        <?=CHtml::hiddenField('settings[83]', PortalSettings::model()->findByPk(83)->ps2)?>
    </div>

    <div class="control-group">
        <?=CHtml::checkBox('', PortalSettings::model()->findByPk(85)->ps2, $htmlOptions2)?>
        <span class="lbl"> <?=tt('Скрывать столбец "Всего"')?></span>
        <?=CHtml::hiddenField('settings[85]', PortalSettings::model()->findByPk(85)->ps2)?>
    </div>

    <div class="control-group">
        <?=CHtml::checkBox('', PortalSettings::model()->findByPk(97)->ps2, $htmlOptions2)?>
        <span class="lbl"> <?=tt('Скрывать столбец Доп колонки')?></span>
        <?=CHtml::hiddenField('settings[97]', PortalSettings::model()->findByPk(97)->ps2)?>
    </div>

    <?php /*<div class="control-group">
        <?=CHtml::checkBox('', PortalSettings::model()->findByPk(84)->ps2, $htmlOptions2)?>
        <span class="lbl"> <?=tt('Запись Итоговой оценки')?></span>
        <?=CHtml::hiddenField('settings[84]', PortalSettings::model()->findByPk(84)->ps2)?>
    </div>*/?>

    <div class="control-group">
        <?=CHtml::radioButtonList('settings[84]', PortalSettings::model()->findByPk(84)->ps2, $options4, $htmlOptions)?>
    </div>

    <div class="control-group">
        <?=CHtml::checkBox('', PortalSettings::model()->findByPk(59)->ps2, $htmlOptions2)?>
        <span class="lbl"> <?=tt('Показывать кафедру')?></span>
        <?=CHtml::hiddenField('settings[59]', PortalSettings::model()->findByPk(59)->ps2)?>
    </div>

    <div class="control-group">
        <?=CHtml::checkBox('', PortalSettings::model()->findByPk(100)->ps2, $htmlOptions2)?>
        <span class="lbl"> <?=tt('Показывать время занятия')?></span>
        <?=CHtml::hiddenField('settings[100]', PortalSettings::model()->findByPk(100)->ps2)?>
    </div>

    <div class="control-group">
        <?=CHtml::checkBox('', PortalSettings::model()->findByPk(66)->ps2, $htmlOptions2)?>
        <span class="lbl"> <?=tt('Проставление отработок из журнала')?></span>
        <?=CHtml::hiddenField('settings[66]', PortalSettings::model()->findByPk(66)->ps2)?>
    </div>

    <div class="control-group">
        <?=CHtml::checkBox('', PortalSettings::model()->findByPk(67)->ps2, $htmlOptions2)?>
        <span class="lbl"> <?=tt('Регистрация пропусков из журнала')?></span>
        <?=CHtml::hiddenField('settings[67]', PortalSettings::model()->findByPk(67)->ps2)?>
    </div>

    <div class="control-group">
        <?=CHtml::checkBox('', PortalSettings::model()->findByPk(29)->ps2, $htmlOptions2)?>
        <span class="lbl"> <?=tt('Блокировать поле пересдач')?></span>
        <?=CHtml::hiddenField('settings[29]', PortalSettings::model()->findByPk(29)->ps2)?>
    </div>
    <?php /*
    <div class="control-group">
        <?=CHtml::checkBox('', PortalSettings::model()->findByPk(33)->ps2, $htmlOptions2)?>
        <span class="lbl"> <?=tt('Переводить в 200-бальную систему')?></span>
        <?=CHtml::hiddenField('settings[33]', PortalSettings::model()->findByPk(33)->ps2)?>
    </div> */?>

    <div class="control-group">
        <span class="lbl"> <?=tt('Максимальный бал')?>:</span>
        <?=CHtml::numberField('settings[36]', PortalSettings::model()->findByPk(36)->ps2)?>
    </div>

    <div class="control-group">
        <span class="lbl"> <?=tt('Максимальный неудов. бал')?>:</span>
        <?=CHtml::numberField('settings[37]', PortalSettings::model()->findByPk(37)->ps2)?>
    </div>

    <div class="control-group">
        <?=CHtml::checkBox('', PortalSettings::model()->findByPk(55)->ps2, $htmlOptions2)?>
        <span class="lbl"> <?=tt('Вводить 0')?></span>
        <?=CHtml::hiddenField('settings[55]', PortalSettings::model()->findByPk(55)->ps2)?>
    </div>

    <div class="control-group">
        <?=CHtml::radioButtonList('settings[78]', PortalSettings::model()->findByPk(78)->ps2, $options2, $htmlOptions)?>
    </div>

    <div class="control-group">
        <span class="lbl"> <?=tt('Количество дней на редактирование оценок (Тип 1)')?>:</span>
        <?=CHtml::textField('settings[27]', PortalSettings::model()->findByPk(27)->ps2)?>
    </div>

    <div class="control-group">
        <span class="lbl"> <?=tt('Количество минут на редактирование оценок после начала занятия (Тип 2)')?>:</span>
        <?=CHtml::textField('settings[79]', PortalSettings::model()->findByPk(79)->ps2)?>
    </div>

    <div class="control-group">
        <?=CHtml::radioButtonList('settings[82]', PortalSettings::model()->findByPk(82)->ps2, $options3, $htmlOptions)?>
    </div>

    <div class="control-group">
        <?=CHtml::checkBox('', PortalSettings::model()->findByPk(88)->ps2, $htmlOptions2)?>
        <span class="lbl"> <?=tt('Галочка-присутствие')?></span>
        <?=CHtml::hiddenField('settings[88]', PortalSettings::model()->findByPk(88)->ps2)?>
    </div>

    <div class="control-group">
        <?=CHtml::checkBox('', PortalSettings::model()->findByPk(89)->ps2, $htmlOptions2)?>
        <span class="lbl"> <?=tt('Добавлять ли не проставленые занятия')?></span>
        <?=CHtml::hiddenField('settings[89]', PortalSettings::model()->findByPk(89)->ps2)?>
    </div>

    <div class="control-group">
        <?=CHtml::checkBox('', PortalSettings::model()->findByPk(119)->ps2, $htmlOptions2)?>
        <span class="lbl"> <?=tt('Проставлять в не проставленые занятия(пз) пропуски ')?></span>
        <?=CHtml::hiddenField('settings[119]', PortalSettings::model()->findByPk(119)->ps2)?>
    </div>

    <div class="control-group">
        <?=CHtml::checkBox('', PortalSettings::model()->findByPk(120)->ps2, $htmlOptions2)?>
        <span class="lbl"> <?=tt('Проставлять в не проставленые занятия(лк) пропуски ')?></span>
        <?=CHtml::hiddenField('settings[120]', PortalSettings::model()->findByPk(120)->ps2)?>
    </div>

    <div class="control-group">
        <?=CHtml::checkBox('', PortalSettings::model()->findByPk(90)->ps2, $htmlOptions2)?>
        <span class="lbl"> <?=tt('Разрешить менять тему занятий из журнала')?></span>
        <?=CHtml::hiddenField('settings[90]', PortalSettings::model()->findByPk(90)->ps2)?>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-info btn-small">
            <i class="icon-ok bigger-110"></i>
            <?=tt('Сохранить')?>
        </button>
    </div>

<?php $this->endWidget();?>