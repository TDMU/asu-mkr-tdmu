<?php
$pattern = <<<HTML
<div class="control-group">
    %s
    <div class="controls">
        %s
    </div>
</div>
HTML;

$options = array(
    'class' => 'control-label'
);

   
    $form=$this->beginWidget('CActiveForm', array(
        'id'=>'retake-form',
        'htmlOptions' => array('class' => 'form-horizontal')
    ));
    $html = '<div>';
    $html .= $form->errorSummary($model, null, null, array('class' => 'alert alert-error'));
    
    $input = $form->hiddenField($model, 'stego1');
    $html .= $input;

    $label = $form->label($model, 'stego2', $options);
    $input = $form->textField($model, 'stego2');
    $html .= sprintf($pattern, $label, $input);

    $label = $form->label($model, 'stego3', $options);
    $input = $form->textField($model, 'stego3',array('class' => 'datepicker'));
    $html .= sprintf($pattern, $label, $input);
    
    //$options_select = array('class'=>'chosen-select', 'autocomplete' => 'off', 'empty' => '&nbsp;', 'style' => 'width:200px');
    $teacher = CHtml::listData(Stego::model()->getTeacher($us1), 'p1', 'name');
    $label = $form->label($model, 'stego4', $options);
    $input = $form->dropDownList($model, 'stego4',$teacher);
    $html .= sprintf($pattern, $label, $input);
    $html .= '</div>';
    echo $html;

    $this->endWidget();
?>