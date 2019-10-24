<?php
/**
 * Created by PhpStorm.
 * User: Lesha
 * Date: 11.09.2019
 * Time: 12:42
 */


/**
 * @var PortfolioFarmController $this
 * @var Stpwork $model
 */

/**
 * @var $form TbActiveForm
 */

$form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array());
echo CHtml::errorSummary($model);
echo $form->textFieldRow($model, 'stpwork3');
echo $form->textFieldRow($model, 'stpwork4');
echo $form->textFieldRow($model, 'stpwork5');
echo $form->textFieldRow($model, 'stpwork6');
?>
<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? tt('Добавить') : tt('Изменить'), array(
		        'class' => 'btn btn-small btn-success'
        )); ?>
</div>
<?php
$this->endWidget();