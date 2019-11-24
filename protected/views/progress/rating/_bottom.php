<?php
/**
 * @var $this ProgressController
 * @var $model RatingForm
 */

$options = array('class'=>'chosen-select', 'autocomplete' => 'off', 'empty' => '&nbsp;');

$data = $model->getSemestersForFilter();

$html  = '<div class="row-fluid" style="margin-bottom:2%">';
$html .= '<div class="span3 ace-select">';
$html .= CHtml::activeLabel($model, 'semStart');
$html .= CHtml::activeDropDownList($model, 'semStart', $data,$options);
$html .= '</div>';
$html .= '<div class="span3 ace-select">';
$html .= CHtml::activeLabel($model, 'semEnd');
$html .= CHtml::activeDropDownList($model, 'semEnd', $data,$options);
$html .= '</div>';
$html .= '<div class="span3 ace-select">';
$html .= CHtml::activeLabel($model, 'stType');
$html .= CHtml::activeDropDownList($model, 'stType',RatingForm::getStudentsTypes(),array('class'=>'chosen-select', 'autocomplete' => 'off'));
$html .= '</div>';
$html .= '<div class="span3">';
$html .= CHtml::activeCheckBox($model, 'ratingType',array('style'=>'float:left'));
$html .= CHtml::activeLabel($model, 'ratingType');
$html .= '</div>';
$html .= '</div>';


echo $html;


if (!empty($model->semStart)&&!empty($model->semEnd))
    $this->renderPartial('rating/_table', array(
        'model' => $model
    ));

