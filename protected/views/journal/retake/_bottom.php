<?php
/**
 *
 * @var ProgressController $this
 * @var $model FilterForm
 */
/**
 * @var $retake Elgzst
 */
$options = array('class'=>'chosen-select', 'autocomplete' => 'off', 'empty' => '&nbsp;', 'style' => 'width:200px');
$form=$this->beginWidget('CActiveForm', array(
    'id'=>'filter-form',
    'htmlOptions' => array('class' => 'form-inline')
));

$html  ='<div class="row-fluid">';
$disciplines = CHtml::listData(D::model()->getDisciplinesForRetakePermition(), 'd1', 'd2');
$html .= '<div class="span2 ace-select">';
$html .= $form->label($model, 'discipline');
$html .= $form->dropDownList($model, 'discipline', $disciplines, $options);
$html .= '</div>';
$groups = CHtml::listData(D::model()->getUоFromDiscipline($model->discipline), 'key', 'name');
$html .= '<div class="span2 ace-select">';
$html .= $form->label($model, 'group',array('label' => tt( 'Специальность' )));
$html .= $form->dropDownList($model, 'group', $groups, $options);
$html .= '</div>';
echo $html;

$this->endWidget();

if (!empty($model->group)) {
    $this->renderPartial('/filter_form/default/_refresh_filter_form_button');
    list($uo1,$us1)=explode('/',$model->group);
    $retake->uo1=$uo1;
    $us=Us::model()->findByPk($us1);
    $retake->type_lesson=$us->us4;
    $this->renderPartial('retake/_grid', array(
        'model' =>  $retake,
        'us1'=>$us1
    ));
} ?>