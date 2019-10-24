<?php
/**
 * Created by PhpStorm.
 * User: Lesha
 * Date: 11.09.2019
 * Time: 12:42
 */


/**
 * @var PortfolioFarmController $this
 * @var Stpeduwork $model
 */

/**
 * @var $form TbActiveForm
 */

$form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array());
echo CHtml::errorSummary($model);
echo $form->dropDownListRow($model, 'stpeduwork3', $model->getStpeduwork3Types());

echo $form->textField($model, 'stpeduwork11', array(
    'style' => $model->stpeduwork3 != 4 ? 'display:none' : '',
));
echo $form->textFieldRow($model, 'stpeduwork4');
echo $form->dropDownListRow($model, 'stpeduwork5', Stportfolio::getYears());

Yii::app()->clientScript->registerScript('stpeduwork11', <<<JS
    $('#Stpeduwork_stpeduwork3').change(function() {
        var val = $(this).val();
        if(val==4)
            $('#Stpeduwork_stpeduwork11').show();
        else
            $('#Stpeduwork_stpeduwork11').hide();
    })
JS
    );
?>
<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? tt('Добавить') : tt('Изменить'), array(
		        'class' => 'btn btn-small btn-success'
        )); ?>
</div>
<?php
$this->endWidget();