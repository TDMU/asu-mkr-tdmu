<?php
$this->pageHeader=tt('Настройки портала');
$this->breadcrumbs=array(
tt('Настройки портала'),
);
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/admin/journal.js');
Yii::app()->clientScript->registerPackage('jquery.ui');
Yii::app()->clientScript->registerPackage('datepicker');
//Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/admin/psettings.js');

$htmlOptions2 = array(
    'class'=>'ace',
);

$options = array(
    'uk'=>tt('Ua'),
    'ru'=>tt('Ru'),
    'en'=>tt('en'),
);


$js =<<<JS
    $('.sem-start').datepicker({
        format: 'mm-dd',
        language: 'ru',
        maxViewMode:1,
        minViewMode:0
    })
    .on('changeDate', function (ev) {
        $(this).datepicker('hide');
    })
    //.focus();
JS;

Yii::app()->clientScript->registerScript('sem-start', $js);
?>
<div class="span6">
    <div class="widget-box">
        <div class="widget-header">
            <h4><?=tt('Настройки закрытия портала')?></h4>
            <span class="widget-toolbar">
                <a data-action="collapse" href="#">
                    <i class="icon-chevron-up"></i>
                </a>
            </span>
        </div>
        <div class="widget-body">
            <div class="widget-main">
                <?php
                $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                    'id'=>'ps-appearance',
                    'htmlOptions' => array('class' => 'form-horizontal'),
                    'action' => '#'
                ));
                $checkboxStyle = array('class' => 'ace ace-switch ace-switch-4');
                $htmlOptions2 = array(
                    'class'=>'ace',
                );
                ?>

                <?php
                    $errorSubcriptionMessage = PortalSettings::ERROR_SUBCRIPTION_MESSAGE;
                ?>
                <div class="control-group">
                    <span class="lbl"> <?=tt('Сообщение при ошибке записи на дисциплины')?>:</span>
                    <?=CHtml::textField('settings['.$errorSubcriptionMessage.']', PortalSettings::model()->findByPk($errorSubcriptionMessage)->ps2)?>
                </div>

                <div class="control-group">
                    <?=CHtml::checkBox('', PortalSettings::model()->findByPk(107)->ps2, $checkboxStyle)?>
                    <span class="lbl"> <?=tt('Использовать стусв')?></span>
                    <?=CHtml::hiddenField('settings[107]', PortalSettings::model()->findByPk(107)->ps2)?>
                </div>

                <div class="control-group">
                    <?=CHtml::checkBox('', PortalSettings::model()->findByPk(80)->ps2, $checkboxStyle)?>
                    <span class="lbl"> <?=tt('Скрывать баннер "Плаймаркет"')?></span>
                    <?=CHtml::hiddenField('settings[80]', PortalSettings::model()->findByPk(80)->ps2)?>
                </div>

                <div class="control-group">
                    <span class="lbl"> <?=tt('Язык по умолчанию')?>:</span>
                    <?=CHtml::dropDownList('settings[58]', PortalSettings::model()->findByPk(58)->ps2, $options, $htmlOptions2)?>
                </div>

                <div class="control-group">
                    <span class="lbl"> <?=tt('Название ВУЗа')?>:</span>
                    <?=CHtml::textField('settings[45]', PortalSettings::model()->findByPk(45)->ps2)?>
                </div>

                <div class="control-group">
                    <span class="lbl"> <?=tt('Название министерсва')?>:</span>
                    <?=CHtml::textField('settings[46]', PortalSettings::model()->findByPk(46)->ps2)?>
                </div>

                <div class="control-group">
                    <?=CHtml::checkBox('', PortalSettings::model()->findByPk(98)->ps2, $checkboxStyle)?>
                    <span class="lbl"> <?=tt('Скрывать регистрацию для иностранцев')?></span>
                    <?=CHtml::hiddenField('settings[98]', PortalSettings::model()->findByPk(98)->ps2)?>
                </div>

                <div class="control-group">
                    <?=CHtml::checkBox('', PortalSettings::model()->findByPk(102)->ps2, $checkboxStyle)?>
                    <span class="lbl"> <?=tt('Скрывать регистрацию')?></span>
                    <?=CHtml::hiddenField('settings[102]', PortalSettings::model()->findByPk(102)->ps2)?>
                </div>

                <div class="control-group">
                    <?=CHtml::checkBox('', PortalSettings::model()->findByPk(103)->ps2, $checkboxStyle)?>
                    <span class="lbl"> <?=tt('Скрывать забыль пароль')?></span>
                    <?=CHtml::hiddenField('settings[103]', PortalSettings::model()->findByPk(103)->ps2)?>
                </div>

                <div class="control-group">
                    <?=CHtml::checkBox('', PortalSettings::model()->findByPk(101)->ps2, $checkboxStyle)?>
                    <span class="lbl"> <?=tt('Скрывать выбор языка')?></span>
                    <?=CHtml::hiddenField('settings[101]', PortalSettings::model()->findByPk(101)->ps2)?>
                </div>

                <div class="control-group">
                    <?=CHtml::checkBox('', PortalSettings::model()->findByPk(104)->ps2, $checkboxStyle)?>
                    <span class="lbl"> <?=tt('в футере mkr текстом')?></span>
                    <?=CHtml::hiddenField('settings[104]', PortalSettings::model()->findByPk(104)->ps2)?>
                </div>

                <div class="control-group">
                    <?=CHtml::checkBox('', PortalSettings::model()->findByPk(105)->ps2, $checkboxStyle)?>
                    <span class="lbl"> <?=tt('Скрывть хлебные крошки')?></span>
                    <?=CHtml::hiddenField('settings[105]', PortalSettings::model()->findByPk(105)->ps2)?>
                </div>

                <div class="control-group">
                    <span class="lbl"> <?=tt('Текст в футер')?>:</span>
                    <?=CHtml::textField('settings[99]', PortalSettings::model()->findByPk(99)->ps2)?>
                </div>

                <div class="control-group">
                    <span class="lbl"> <?=tt('Начало Весеннего семестра')?>:</span>
                    <?=CHtml::textField('settings[53]', PortalSettings::model()->findByPk(53)->ps2,array('class' => 'sem-start datepicker','date-format'=>'mm-dd'))?>
                </div>

                <div class="control-group">
                    <span class="lbl"> <?=tt('Текст на главной(Ua)')?>:</span>
                    <?php $this->widget('application.extensions.elFinderTinyMce.TinyMce',
                        array(
                            'name'=>'settings[61]',
                            'value'=>PortalSettings::model()->findByPk(61)->ps2,
                            'htmlOptions'=>array('rows'=>6, 'cols'=>50, 'class'=>'tinymce'),
                            'fileManager' => array(
                                'class' => 'ext.elFinder.TinyMceElFinder',
                                'connectorRoute'=>'/admin/default/connector',
                            ),
                            /*'settings'=>array(
                                'theme' => "advanced",
                                'skin' => 'default',
                                'language' => Yii::app()->language,
                            ),*/
                        )); ?>
                </div>

                <div class="control-group">
                    <span class="lbl"> <?=tt('Текст на главной(Ru)')?>:</span>
                    <?php $this->widget('application.extensions.elFinderTinyMce.TinyMce',
                        array(
                            'name'=>'settings[62]',
                            'value'=>PortalSettings::model()->findByPk(62)->ps2,
                            'htmlOptions'=>array('rows'=>6, 'cols'=>50, 'class'=>'tinymce'),
                            'fileManager' => array(
                                'class' => 'ext.elFinder.TinyMceElFinder',
                                'connectorRoute'=>'/admin/default/connector',
                            ),
                            /*'settings'=>array(
                                'theme' => "advanced",
                                'skin' => 'default',
                                'language' => Yii::app()->language,
                            ),*/
                        )); ?>
                </div>

                <div class="control-group">
                    <span class="lbl"> <?=tt('Текст на главной(En)')?>:</span>
                    <?php $this->widget('application.extensions.elFinderTinyMce.TinyMce',
                        array(
                            'name'=>'settings[63]',
                            'value'=>PortalSettings::model()->findByPk(63)->ps2,
                            'htmlOptions'=>array('rows'=>6, 'cols'=>50, 'class'=>'tinymce'),
                            'fileManager' => array(
                                'class' => 'ext.elFinder.TinyMceElFinder',
                                'connectorRoute'=>'/admin/default/connector',
                            ),
                            /*'settings'=>array(
                                'theme' => "advanced",
                                'skin' => 'default',
                                'language' => Yii::app()->language,
                            ),*/
                        )); ?>
                </div>

                <div class="control-group">
                    <span class="lbl"> <?=tt('Письмо для смены пароля')?>:</span><br>
                    <span class="blue">{username}-логин, {link}-ссылка для смены пароля</span>
                    <?php $this->widget('application.extensions.elFinderTinyMce.TinyMce',
                        array(
                            'name'=>'settings[86]',
                            'value'=>PortalSettings::model()->findByPk(86)->ps2,
                            'htmlOptions'=>array('rows'=>6, 'cols'=>50, 'class'=>'tinymce'),
                            'fileManager' => array(
                                'class' => 'ext.elFinder.TinyMceElFinder',
                                'connectorRoute'=>'/admin/default/connector',
                            ),
                            /*'settings'=>array(
                                'theme' => "advanced",
                                'skin' => 'default',
                                'language' => Yii::app()->language,
                            ),*/
                        )); ?>
                </div>

                <div class="control-group">
                    <span class="lbl"> <?=tt('Письмо о смене пароля')?>:</span><br>
                    <span class="blue">{username}-логин, {password}-пароль</span>
                    <?php $this->widget('application.extensions.elFinderTinyMce.TinyMce',
                        array(
                            'name'=>'settings[87]',
                            'value'=>PortalSettings::model()->findByPk(87)->ps2,
                            'htmlOptions'=>array('rows'=>6, 'cols'=>50, 'class'=>'tinymce'),
                            'fileManager' => array(
                                'class' => 'ext.elFinder.TinyMceElFinder',
                                'connectorRoute'=>'/admin/default/connector',
                            ),
                            /*'settings'=>array(
                                'theme' => "advanced",
                                'skin' => 'default',
                                'language' => Yii::app()->language,
                            ),*/
                        )); ?>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-info btn-small">
                        <i class="icon-ok bigger-110"></i>
                        <?=tt('Сохранить')?>
                    </button>
                </div>

                <?php $this->endWidget();?>
            </div>
        </div>
    </div>
</div>

<div class="span6">
    <div class="widget-box">
        <div class="widget-header">
            <h4><?=tt('Антиплагиат')?></h4>
            <span class="widget-toolbar">
                <a data-action="collapse" href="#">
                    <i class="icon-chevron-up"></i>
                </a>
            </span>
        </div>
        <div class="widget-body">
            <div class="widget-main">
                <?php
                $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                    'id'=>'ps-antiplagiat',
                    'htmlOptions' => array('class' => 'form-horizontal'),
                    'action' => '#'
                ));
                ?>

                <div class="control-group">
                    <span class="lbl"> <?=tt('LOGIN')?>:</span>
                    <?=CHtml::textField('settings[68]', PortalSettings::model()->findByPk(68)->ps2)?>
                </div>

                <div class="control-group">
                    <span class="lbl"> <?=tt('PASSWORD')?>:</span>
                    <?=CHtml::textField('settings[69]', PortalSettings::model()->findByPk(69)->ps2)?>
                </div>

                <div class="control-group">
                    <span class="lbl"> <?=tt('COMPANY_NAME')?>:</span>
                    <?=CHtml::textField('settings[70]', PortalSettings::model()->findByPk(70)->ps2)?>
                </div>

                <div class="control-group">
                    <span class="lbl"> <?=tt('APICORP_ADDRESS')?>:</span>
                    <?=CHtml::textField('settings[71]', PortalSettings::model()->findByPk(71)->ps2)?>
                </div>

                <div class="control-group">
                    <?=CHtml::checkBox('', PortalSettings::model()->findByPk(121)->ps2, $checkboxStyle)?>
                    <span class="lbl"> <?=tt('Отправлять отчет руководителю на почту')?></span>
                    <?=CHtml::hiddenField('settings[121]', PortalSettings::model()->findByPk(121)->ps2)?>
                </div>

                <span class="lbl"> <?=tt('Шаблон письма с отчетом')?>:</span><br>
                <span class="blue">{username}-логин, {name}- имя, {student}-имя студента, {link}-ссылка на отчет</span>
                <?php $this->widget('application.extensions.elFinderTinyMce.TinyMce',
                    array(
                        'name'=>'settings[118]',
                        'value'=>PortalSettings::model()->findByPk(118)->ps2,
                        'htmlOptions'=>array('rows'=>6, 'cols'=>50, 'class'=>'tinymce'),
                        'fileManager' => array(
                            'class' => 'ext.elFinder.TinyMceElFinder',
                            'connectorRoute'=>'/admin/default/connector',
                        ),
                    )); ?>

                <div class="form-actions">
                    <button type="submit" class="btn btn-info btn-small">
                        <i class="icon-ok bigger-110"></i>
                        <?=tt('Сохранить')?>
                    </button>
                </div>

                <?php $this->endWidget();?>
            </div>
        </div>
    </div>
</div>

<div class="span6">
    <div class="widget-box">
        <div class="widget-header">
            <h4><?=tt('Данные студентов')?></h4>
            <span class="widget-toolbar">
                <a data-action="collapse" href="#">
                    <i class="icon-chevron-up"></i>
                </a>
            </span>
        </div>
        <div class="widget-body">
            <div class="widget-main">
                <?php
                $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                    'id'=>'ps-student-info',
                    'htmlOptions' => array('class' => 'form-horizontal'),
                    'action' => '#'
                ));
                ?>

                <div class="control-group">
                    <?=CHtml::checkBox('', PortalSettings::model()->findByPk(72)->ps2, $checkboxStyle)?>
                    <span class="lbl"> <?=tt('Блокировка изменения темы курсовой')?></span>
                    <?=CHtml::hiddenField('settings[72]', PortalSettings::model()->findByPk(72)->ps2)?>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-info btn-small">
                        <i class="icon-ok bigger-110"></i>
                        <?=tt('Сохранить')?>
                    </button>
                </div>

                <?php $this->endWidget();?>
            </div>
        </div>
    </div>
</div>

<div class="span6">
    <div class="widget-box">
        <div class="widget-header">
            <h4><?=tt('Сообщения для пользователей после аторизации')?></h4>
            <span class="widget-toolbar">
                <a data-action="collapse" href="#">
                    <i class="icon-chevron-up"></i>
                </a>
            </span>
        </div>
        <div class="widget-body">
            <div class="widget-main">
                <?php
                $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                    'id'=>'ps-message-info',
                    'htmlOptions' => array('class' => 'form-horizontal'),
                    'action' => '#'
                ));
                ?>

                <div class="control-group">
                    <span class="lbl"> <?=tt('Для студентов')?>:</span>
                    <?php $this->widget('application.extensions.elFinderTinyMce.TinyMce',
                        array(
                            'name'=>'settings[92]',
                            'value'=>PortalSettings::model()->findByPk(92)->ps2,
                            'htmlOptions'=>array('rows'=>6, 'cols'=>50, 'class'=>'tinymce'),
                            'fileManager' => array(
                                'class' => 'ext.elFinder.TinyMceElFinder',
                                'connectorRoute'=>'/admin/default/connector',
                            ),
                        )); ?>
                </div>

                <div class="control-group">
                    <span class="lbl"> <?=tt('Для преподавателей')?>:</span>
                    <?php $this->widget('application.extensions.elFinderTinyMce.TinyMce',
                        array(
                            'name'=>'settings[93]',
                            'value'=>PortalSettings::model()->findByPk(93)->ps2,
                            'htmlOptions'=>array('rows'=>6, 'cols'=>50, 'class'=>'tinymce'),
                            'fileManager' => array(
                                'class' => 'ext.elFinder.TinyMceElFinder',
                                'connectorRoute'=>'/admin/default/connector',
                            ),
                        )); ?>
                </div>

                <div class="control-group">
                    <span class="lbl"> <?=tt('Для родителей')?>:</span>
                    <?php $this->widget('application.extensions.elFinderTinyMce.TinyMce',
                        array(
                            'name'=>'settings[94]',
                            'value'=>PortalSettings::model()->findByPk(94)->ps2,
                            'htmlOptions'=>array('rows'=>6, 'cols'=>50, 'class'=>'tinymce'),
                            'fileManager' => array(
                                'class' => 'ext.elFinder.TinyMceElFinder',
                                'connectorRoute'=>'/admin/default/connector',
                            ),
                        )); ?>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-info btn-small">
                        <i class="icon-ok bigger-110"></i>
                        <?=tt('Сохранить')?>
                    </button>
                </div>

                <?php $this->endWidget();?>
            </div>
        </div>
    </div>
</div>

    <div class="span6">
        <div class="widget-box">
            <div class="widget-header">
                <h4><?=tt('Портфолио')?></h4>
                <span class="widget-toolbar">
                <a data-action="collapse" href="#">
                    <i class="icon-chevron-up"></i>
                </a>
            </span>
            </div>
            <div class="widget-body">
                <div class="widget-main">
                    <?php
                    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                        'id'=>'ps-portfolio',
                        'htmlOptions' => array('class' => 'form-horizontal'),
                        'action' => '#'
                    ));
                    ?>

                    <div class="control-group">
                        <?=CHtml::checkBox('', PortalSettings::model()->getSettingFor(PortalSettings::USE_PORTFOLIO), $checkboxStyle)?>
                        <span class="lbl"> <?=tt('Использовать портфолио')?></span>
                        <?=CHtml::hiddenField('settings['.PortalSettings::USE_PORTFOLIO.']', PortalSettings::model()->getSettingFor(PortalSettings::USE_PORTFOLIO))?>
                    </div>

                    <div class="control-group">
                        <span class="lbl"> <?=tt('Путь к папке с файлами')?>:</span>
                        <?=CHtml::textField('settings['.PortalSettings::PORTFOLIO_PATH.']', PortalSettings::model()->getSettingFor(PortalSettings::PORTFOLIO_PATH))?>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-info btn-small">
                            <i class="icon-ok bigger-110"></i>
                            <?=tt('Сохранить')?>
                        </button>
                    </div>

                    <?php $this->endWidget();?>
                </div>
            </div>
        </div>
    </div>

    <div class="span6">
        <div class="widget-box">
            <div class="widget-header">
                <h4><?=tt('Оповещение')?></h4>
                <span class="widget-toolbar">
                <a data-action="collapse" href="#">
                    <i class="icon-chevron-up"></i>
                </a>
            </span>
            </div>
            <div class="widget-body">
                <div class="widget-main">
                    <?php
                    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                        'id'=>'ps-alert',
                        'htmlOptions' => array('class' => 'form-horizontal'),
                        'action' => '#'
                    ));
                    ?>

                    <div class="control-group">
                        <?=CHtml::checkBox('', PortalSettings::model()->getSettingFor(PortalSettings::STUDENT_SEND_IN_ALERT), $checkboxStyle)?>
                        <span class="lbl"> <?=tt('Отправка сообщений студентами')?></span>
                        <?=CHtml::hiddenField('settings['.PortalSettings::STUDENT_SEND_IN_ALERT.']', PortalSettings::model()->getSettingFor(PortalSettings::STUDENT_SEND_IN_ALERT))?>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-info btn-small">
                            <i class="icon-ok bigger-110"></i>
                            <?=tt('Сохранить')?>
                        </button>
                    </div>

                    <?php $this->endWidget();?>
                </div>
            </div>
        </div>
    </div>

<?php

