<?php
/**
 * Created by PhpStorm.
 * User: Lesha
 * Date: 11.09.2019
 * Time: 12:42
 */


/**
 * @var PortfolioFarmController $this
 * @var Stpfwork $model
 */

/**
 * @var $form TbActiveForm
 */

$form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array());
echo CHtml::errorSummary($model);

echo $form->textFieldRow($model, 'stpfwork3');
echo $form->textFieldRow($model, 'stpfwork4');
echo $form->textFieldRow($model, 'stpfwork5');

?>
<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? tt('Добавить') : tt('Изменить'), array(
		        'class' => 'btn btn-small btn-success'
        )); ?>
</div>
<?php
$this->endWidget();