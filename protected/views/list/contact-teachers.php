<?php
/**
 *
 * @var WorkPlanController $this
 * @var FilterForm $model
 */

$this->pageHeader=tt('Контакты кафедры');
$this->breadcrumbs=array(
    tt('Контакты кафедры'),
);

Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/list/chair.js', CClientScript::POS_HEAD);

$attr = array('class'=>'chosen-select', 'autocomplete' => 'off', 'empty' => '&nbsp;');
$form=$this->beginWidget('CActiveForm', array(
    'id'=>'timeTable-form',
    'htmlOptions' => array('class' => 'form-inline noprint')
));

$html = '<div>';
    $html .= '<fieldset>';
    $filials = Ks::getListDataForKsFilter();
    if (count($filials) > 1) {
        $html .= '<div class="span2 ace-select">';
        $html .= $form->label($model, 'filial');
        $html .= $form->dropDownList($model, 'filial', $filials, $attr);
        $html .= '</div>';
    }

    $chairs = K::model()->getOnlyChairsFor($model->filial);
    $html .= '<div class="span2 ace-select">';
    $html .= $form->label($model, 'chair');
    $html .= $form->dropDownList($model, 'chair', $chairs, $attr);
    $html .= '</div>';

    $html .= '</fieldset>';
    $html .= '</div>';

    echo $html;

$this->endWidget();


echo <<<HTML
    <span id="spinner1"></span>
HTML;


if (! empty($model->chair))
    $this->renderPartial('contact-teachers/_bottom', array(
        'model' => $model,
    ));
