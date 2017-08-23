<?php
/**
 *
 * @var TimeTableForm || FilterForm $model
 * @var CActiveForm $form
 */
$options = array('class'=>'chosen-select', 'autocomplete' => 'off', 'empty' => '&nbsp;');
$form=$this->beginWidget('CActiveForm', array(
    'id'=>'filter-form',
    'htmlOptions' => array('class' => 'form-inline')
));

    $html = '<div>';
    $html .= '<fieldset>';

    $groups = CHtml::listData(Gr::model()->getGroupsForCurator(Yii::app()->user->dbModel->p1), 'gr1', 'name');
    if(count($groups)==1)
        $model->group = key($groups);
    $html .= '<div class="span2 ace-select">';
    $html .= $form->label($model, 'group');
    $html .= $form->dropDownList($model, 'group', $groups, $options);
    $html .= '</div>';


    $students = CHtml::listData(St::model()->getStudentsOfGroupForPayment($model->group, $type), 'st1', 'name');
    $html .= '<div class="span2 ace-select">';
    $html .= $form->label($model, 'student');
    $html .= $form->dropDownList($model, 'student', $students, $options);
    $html .= '</div>';
    $html .= '</fieldset>';

    $html .= '<fieldset style="margin-top:1%;">';

    $html .= '</div>';

    echo $html;

$this->endWidget();