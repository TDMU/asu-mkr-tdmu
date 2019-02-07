<?php
/**
 * Created by PhpStorm.
 * User: neffa
 * Date: 04.02.2019
 * Time: 13:22
 */

/**
 * @var $model FilterForm
 */

$this->pageHeader=tt('Преподаватель');
$this->breadcrumbs=array(
    tt('Портфолио'),
);

Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/timetable/timetable.js', CClientScript::POS_HEAD);

$attr = array('class'=>'chosen-select', 'autocomplete' => 'off', 'empty' => '&nbsp;');
$form=$this->beginWidget('CActiveForm', array(
    'id'=>'filter-form',
    'action'=>array('teacher'),
    'htmlOptions' => array('class' => 'form-inline')
));

$html = '<div>';
$html .= '<fieldset>';

if(Yii::app()->user->isAdmin) {

    $filials = Ks::getListDataForKsFilter();
    if (count($filials) > 1) {
        $html .= '<div class="span2 ace-select">';
        $html .= $form->label($model, 'filial');
        $html .= $form->dropDownList($model, 'filial', $filials, $attr);
        $html .= '</div>';
    }else{
        $model->filial = key($filials);
    }

    $chairs = K::model()->getOnlyChairsFor($model->filial);
    $html .= '<div class="span2 ace-select">';
    $html .= $form->label($model, 'chair');
    $html .= $form->dropDownList($model, 'chair', $chairs, $attr);
    $html .= '</div>';

    $teachers = P::model()->getTeachersForTimeTable($model->chair);
    $html .= '<div class="span2 ace-select">';
    $html .= $form->label($model, 'teacher');
    $html .= $form->dropDownList($model, 'teacher', $teachers, $attr);
    $html .= '</div>';
}

$semesters = Zrst::model()->getSemesterData($model->teacher);
$html .= '<div class="span2 ace-select">';
$html .= $form->label($model, 'semester');
$html .= $form->dropDownList($model, 'semester', $semesters, $attr);
$html .= '</div>';

$disciplines = Zrst::model()->getDisciplinesData($model->teacher, $model->semester);
$html .= '<div class="span2 ace-select">';
$html .= $form->label($model, 'discipline');
$html .= $form->dropDownList($model, 'discipline', $disciplines, $attr);
$html .= '</div>';

$html .= '</fieldset>';
$html .= '</div>';

echo $html;

$this->endWidget();


echo <<<HTML
    <span id="spinner1"></span>
HTML;
if ($model->discipline) :
    $this->renderPartial('teacher/_bottom', array(
        'teacher' => P::model()->findByPk($model->teacher),
        'model' => $model
    ));
endif;