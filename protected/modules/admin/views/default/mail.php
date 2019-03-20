<?php
	$this->pageHeader=tt('Настройки Почты');
	$this->breadcrumbs=array(
		tt('Админ. панель'),
	);
?>
<div class="form">
 <?php if(Yii::app()->user->hasFlash('config'))
		{ ?>
        <div class="alert in fade alert-success">
            <?php echo Yii::app()->user->getFlash('config'); ?>
        </div>
    <?php
	}else
	if(Yii::app()->user->hasFlash('config_error'))
		{ ?>
        <div class="alert in fade alert-danger">
            <?php echo Yii::app()->user->getFlash('config_error'); ?>
        </div>
    <?php
	}else
	{ 
		$form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
			'id'=>'config-form',
			'enableAjaxValidation'=>false,
			'htmlOptions' => array('class' => 'well','enctype'=>'multipart/form-data'),
		)); ?>
		
			<?php echo $form->errorSummary($model); ?>

			<?php echo $form->textFieldRow($model, 'Host'); ?>

			<?php echo $form->textFieldRow($model, 'Username'); ?>

			<?php echo $form->passwordFieldRow($model, 'Password'); ?>

			<?php echo $form->labelEx($model,'Port'); ?>
			<?php echo $form->numberField($model, 'Port',array('min'=>'1','max'=>'65535')); ?>
			<?php //echo $form->textFieldRow($model, 'Port',array('type'=>'number')); ?>

			<?php echo $form->dropDownListRow($model, 'SMTPSecure',array(''=>'none','tls'=>'tls','ssl'=>'ssl')); ?>

			<div class="form-actions">
				<?php $this->widget('bootstrap.widgets.TbButton', array(
					'buttonType'=>'submit',
					'type'=>'primary',
					'label'=>tt('Сохранить'),
				)); ?>
			</div>

		<?php $this->endWidget(); 
	}
?>
</div>

