<?php
/**
 * Created by PhpStorm.
 * User: neffa
 * Date: 15.02.2019
 * Time: 12:57
 */

/**
 * @var $this AlertController
 * @var $model Users
 */

$this->pageHeader=tt('Оповещение');
$this->breadcrumbs=array(
    tt('Сообщения'),
);
?>
    <div class="widget-box">
        <div class="widget-header">
            <h5><?=tt('Входящие сообщения')?></h5>

            <div class="widget-toolbar">
                <a data-action="collapse" href="#">
                    <i class="icon-chevron-down"></i>
                </a>
            </div>
        </div>

        <div class="widget-body">
            <div class="widget-main">
                <?=$this->renderPartial('_input', array(
                    'model' => $model
                ));?>
            </div>
        </div>
    </div>
<?php

if($model->isTeacher || ($model->isStudent && PortalSettings::model()->getSettingFor(PortalSettings::STUDENT_SEND_IN_ALERT) == 1)){

    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=>'button',
        'type'=>'success',

        'icon'=>'plus',
        'label'=>tt('Написать сообщение'),
        'htmlOptions'=>array(
            'class'=>'btn-small',
            'id'=>'btn-new-message',
            'data-toggle' => 'modal',
            'data-target' => '#myModal',
        )
    ));

    $this->beginWidget(
        'bootstrap.widgets.TbModal',
        array('id' => 'myModal')
    ); ?>

    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4><?=tt('Написать новое сообщение')?></h4>
    </div>

    <div class="modal-body">
        <?php
        $formModel = new CreateMessageForm();
        /**
         * @var $form TbActiveForm
         */
        $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id'=>'create-message-form',
            'htmlOptions' => array('class' => ''),
            'method'=>'post',
            'action'=> array('alert/send-message'),
        ));

        echo $form->textAreaRow($formModel,'body',array('rows'=>6, 'cols'=>50));

        echo $form->checkBoxRow($formModel, 'notification');

        $this->endWidget();
        ?>
    </div>

    <div class="modal-footer">
        <?php $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'type' => 'primary',
                'label' => tt('Отправить'),
                'url' => '#',
                'htmlOptions' => array(
                    'id'=>'btn-send-new-message',
                ),
            )
        ); ?>
        <?php $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'label' => tt('Отмена'),
                'url' => '#',
                'htmlOptions' => array('data-dismiss' => 'modal'),
            )
        ); ?>
    </div>

    <?php $this->endWidget();

?>

<div class="widget-box">
    <div class="widget-header">
        <h5><?=tt('Отправленные сообщения')?></h5>

        <div class="widget-toolbar">
            <a data-action="collapse" href="#">
                <i class="icon-chevron-down"></i>
            </a>
        </div>
    </div>

    <div class="widget-body">
        <div class="widget-main">
            <?=$this->renderPartial('_output', array(
                'model' => $model
            ))?>
        </div>
    </div>
</div>

<?php
}
