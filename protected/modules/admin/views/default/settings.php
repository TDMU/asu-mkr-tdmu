<?php
	$this->pageHeader=tt('Настройки');
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
			
			<?php //echo $form->DropDownListRow($model, 'attendanceStatistic',array('0'=>tt('По электронному журналу'),'1'=>tt('По деканату'))); ?>
			
			<?php echo $form->DropDownListRow($model, 'timeTable',array('0'=>tt('Таблица'),'1'=>tt('Календарь'))); ?>
			
			<?php echo $form->checkBoxRow($model, 'fixedCountLesson'); ?>
			
			<?php 
			$style='';
			if($model->fixedCountLesson!=1)
				$style='display:none';
			?>
			<div id="countLesson" style="<?=$style?>">
				<label for="ConfigForm_countLesson" class="required"><?=$model->getAttributeLabel('countLesson')?> <span class="required">*</span></label>
				<?php
				echo $form->numberField($model, 'countLesson',array('min'=>'1','max'=>'8')); ?>
			</div>
			<?php 
				Yii::app()->clientScript->registerScript('fixedCountLesson',"
					$('#ConfigForm_fixedCountLesson').change(function() {
						if(this.checked)
							$('#countLesson').show();
						else
							$('#countLesson').hide();
					});
				");
			?>
    
                        <?php echo $form->labelEx($model,'month'); ?>
			<?php echo $form->numberField($model, 'month',array('min'=>'1','max'=>'12')); ?>
    
			<?php echo $form->textAreaRow($model,'analytics',array('rows'=>6, 'cols'=>50)); ?>

			<?php echo $form->textAreaRow($model,'analyticsYandex',array('rows'=>6, 'cols'=>50)); ?>
			
                        <?php echo $form->labelEx($model,'banner'); ?>
                        <?php $this->widget('application.extensions.elFinderTinyMce.TinyMce',
                            array(
                                'model'=>$model,
                                'attribute'=>'banner',
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
                        <?php echo $form->error($model,'banner'); ?>
                        </br>
                        
                        <?php echo $form->labelEx($model,'top1'); ?>
                        <?php $this->widget('application.extensions.elFinderTinyMce.TinyMce',
                            array(
                                'model'=>$model,
                                'attribute'=>'top1',
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
                        <?php echo $form->error($model,'top1'); ?>
                        </br>
                        
                        <?php echo $form->labelEx($model,'top2'); ?>
                        <?php $this->widget('application.extensions.elFinderTinyMce.TinyMce',
                            array(
                                'model'=>$model,
                                'attribute'=>'top2',
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
                        <?php echo $form->error($model,'top2'); ?>
                        </br>

            <?php echo $form->textField($model, 'loginKey'); ?>

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

