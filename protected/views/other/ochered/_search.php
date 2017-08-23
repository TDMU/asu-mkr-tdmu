<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 18.07.2017
 * Time: 14:50
 */

/**
 * @var $model Abtmpi
 */
$form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
    'action'=>Yii::app()->createUrl($this->route),
    'method'=>'get',
)); ?>
    <div class="span4">
        <?php echo $form->textField($model, 'abtmpi7',array('class' => 'datepicker form-control'));?>
    </div>

    <div class="span4">
        <?php echo $form->checkboxList($model,'abtmpi5',Abtmpi::getTypes(),array('class'=>'')); ?>
    </div>

    <div class="span4">
        <?php echo $form->checkboxList($model,'abtmpci2',Abtmpci::getTypes(),array('class'=>'')); ?>
    </div>

    <div class="span12">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType'=>'submit',
            'type'=>'primary',
            'size'=> 'mini',
            'label'=>tt('Обновить'),
        )); ?>
    </div>

<?php $this->endWidget(); ?>