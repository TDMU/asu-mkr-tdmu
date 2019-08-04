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
    $filials = Ks::getListDataForKsFilter();
    if (count($filials) > 1) {
        $html .= '<div class="span2 ace-select">';
        $html .= $form->label($model, 'filial');
        $html .= $form->dropDownList($model, 'filial', $filials, $options);
        $html .= '</div>';
    }else{
        $model->filial = key($filials);
    }

    $faculties = F::model()->getFacultiesFor($model->filial,1);
    if(count($faculties)==1)
        $model->faculty = key($faculties);

    if($model->faculty==5&&Yii::app()->core->universityCode==U_NULAU)
        $model->faculty = 1;

    $html .= '<div class="span2 ace-select">';
    $html .= $form->label($model, 'faculty');
    $html .= $form->dropDownList($model, 'faculty', $faculties, $options);
    $html .= '</div>';


    $courses = Sp::model()->getCoursesFor($model->faculty);
    $html .= '<div class="span2 ace-select">';
    $html .= $form->label($model, 'course');
    $html .= $form->dropDownList($model, 'course', $courses, $options);
    $html .= '</div>';


    $groups = CHtml::listData(Gr::model()->getGroupsForTimeTableForPortfolio($model->faculty, $model->course), 'gr1', 'name');
    $html .= '<div class="span2 ace-select">';
    $html .= $form->label($model, 'group');
    $html .= $form->dropDownList($model, 'group', $groups, $options);
    $html .= '</div>';


    $students = CHtml::listData(St::model()->getStudentsOfGroupForPortfolio($model->group), 'st1', 'name');
    $html .= '<div class="span2 ace-select">';
    $html .= $form->label($model, 'student');
    $html .= $form->dropDownList($model, 'student', $students, $options);
    $html .= '</div>';
    $html .= '</fieldset>';

    $html .= '</div>';

    echo $html;

$this->endWidget();