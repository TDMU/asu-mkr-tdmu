<?php

    $getGroupUrl = Yii::app()->createAbsoluteUrl('/progress/getGroups');
    Yii::app()->clientScript->registerScript('getGroupUrl', <<<JS
        getGroupsUrl = "{$getGroupUrl}"
JS
    , CClientScript::POS_READY);

    $form=$this->beginWidget('CActiveForm', array(
        'id'=>'filter-form',
        'htmlOptions' => array('class' => 'form-horizontal row')
    ));
        $disciplines = CHtml::listData(D::model()->getDisciplinesForJournalPermition(), 'd1', 'd2');
        $type_lesson=SH::getTypesForJournal();
        $groups = CHtml::listData(Gr::model()->getGroupsForJournalPermition($model->discipline,$model->type_lesson), 'group', 'name');

        $options =  array('class'=>'cs-select cs-skin-elastic', 'autocomplete' => 'off', 'empty' => '&nbsp;');

        echo '<div>'.
            '<fieldset>'.
                '<div class="select-group col-xs-12">'.
                $form->label($model, 'discipline').
                $form->dropDownList($model, 'discipline', $disciplines,$options).
                '</div>'.
                '<div class="select-group col-xs-6">'.
                $form->label($model, 'type_lesson').
                $form->dropDownList($model, 'type_lesson', $type_lesson, array('class'=>'cs-select cs-skin-elastic', 'autocomplete' => 'off', 'empty' => '&nbsp;')).
                '</div>'.
                '<div class="select-group col-xs-6">'.
                $form->label($model, 'group').
                $form->dropDownList($model, 'group', $groups,$options).
                '</div>'.
             '</fieldset>'.
            '</div>';

    $this->endWidget();

echo <<<HTML
<span id="spinner1"></span>
HTML;
