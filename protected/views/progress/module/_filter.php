<?php
/**
 * @var ProgressController $this
 * @var ModuleForm $model
 * @var $form CActiveForm
 */

$options = array('class'=>'chosen-select', 'autocomplete' => 'off', 'empty' => '&nbsp;');
$form=$this->beginWidget('CActiveForm', array(
    'id'=>'filter-form',
    'htmlOptions' => array('class' => 'form-inline')
));

$html = '<div>';
$html .= '<fieldset>';

$disciplines = CHtml::listData($model->getDisciplines(), 'd1', 'd2');
$html .= '<div class="span2 ace-select">';
$html .= $form->label($model, 'discipline');
$html .= $form->dropDownList($model, 'discipline', $disciplines, $options);
$html .= '</div>';

$groups = $model->getGroups($model->discipline);
$html .= '<div class="span2 ace-select">';
$html .= $form->label($model, 'group');
$html .= $form->dropDownList($model, 'group', $groups, $options);
$html .= '</div>';

if(!empty($model->group)) {
    $html .= '<div class="span2 ace-select">';
    $html .= $form->label($model, 'countModules');
    $html .= $form->numberField($model, 'countModules', [
        'disabled' => $model->countModules > 0,
        'style' => 'width:60px'
    ]);
    $html .= '</div>';
}

$html .= '</fieldset>';

$html .= '</div>';

echo $html;
$this->endWidget();