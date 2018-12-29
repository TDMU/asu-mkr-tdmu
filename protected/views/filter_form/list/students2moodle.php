<?php
/**
 *
 * @var FilterForm $model
 * @var CActiveForm $form
 */

?>
<?php
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
    $faculties = F::model()->getFacultiesFor($model->filial, 1);
    if(count($faculties)==1)
        $model->faculty = key($faculties);
    $html .= '<div class="span2 ace-select">';
    $html .= $form->label($model, 'faculty');
    $html .= $form->dropDownList($model, 'faculty', $faculties, $options);
    $html .= '</div>';

    $specialities = CHtml::listData(Sp::model()->getSpecialitiesForFaculty($model->faculty), 'sp1', 'name');
    $html .= '<div class="span2 ace-select">';
    $html .= $form->label($model, 'speciality');
    $html .= $form->dropDownList($model, 'speciality', $specialities, $options);
    $html .= '</div>';

    $courses = Sp::model()->getCoursesForSp($model->faculty, $model->speciality);
    $html .= '<div class="span2 ace-select">';
    $html .= $form->label($model, 'course');
    $html .= $form->dropDownList($model, 'course', $courses, $options);
    $html .= '</div>';
    
    $streams = Gr::model()->getStreamForSpec($model->faculty, $model->speciality, $model->course);
    $html .= '<div class="span2 ace-select">';
    $html .= $form->label($model, 'stream');
    $html .= $form->dropDownList($model, 'stream', $streams, $options);
    $html .= '</div>';

    $html .= '</fieldset>';
    $html .= '</div>';
    
	
	echo $html;
$this->endWidget();