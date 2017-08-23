<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 12.04.2017
 * Time: 13:28
 */

$options = array('class'=>'chosen-select', 'autocomplete' => 'off', 'empty' => '&nbsp;');
$form=$this->beginWidget('CActiveForm', array(
    'id'=>'filter-form',
    'htmlOptions' => array('class' => 'form-inline')
));

$html = '<div>';
$html .= '<fieldset>';

$filials = Ks::getListDataForKsFilter();
if (count($filials) > 1) {
    $html .= '<div class="span2 ace-select">';
    $html .= $form->label($model, 'filial');
    $html .= $form->dropDownList($model, 'filial', $filials, $options);
    $html .= '</div>';
}else{
    $model->filial = key($filials);
}

//$faculties = CHtml::listData(F::model()->getFacultiesFor($model->filial), 'f1', 'f3');
$faculties = F::model()->getFacultiesFor($model->filial);
if(count($faculties)==1)
    $model->faculty = key($faculties);
$html .= '<div class="span2 ace-select">';
$html .= $form->label($model, 'faculty');
$html .= $form->dropDownList($model, 'faculty', $faculties, $options);
$html .= '</div>';

$specialities = CHtml::listData(Pnsp::model()->getSpFor($model->faculty), 'sp1', 'name');
$html .= '<div class="span2 ace-select">';
$html .= $form->label($model, 'speciality');
$html .= $form->dropDownList($model, 'speciality', $specialities, $options);
$html .= '</div>';

$courses = Sp::model()->getCoursesForSp($model->faculty, $model->speciality);
$html .= '<div class="span2 ace-select">';
$html .= $form->label($model, 'course');
$html .= $form->dropDownList($model, 'course', $courses, $options);
$html .= '</div>';

$html .= '</fieldset>';

$html .= '<fieldset>';

$html .= '<div class="span2  ace-select">';
$html .= $form->label($model, 'date1');
$html .= $form->textField($model, 'date1',array('class' => 'datepicker form-control'));
$html .= '</div>';

$html .= '<div class="span2  ace-select">';
$html .= $form->label($model, 'date2');
$html .= $form->textField($model, 'date2',array('class' => 'datepicker form-control'));
$html .= '</div>';

$html .= '</fieldset>';
$html .= '</div>';

echo $html;

$this->endWidget();