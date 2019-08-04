<?php
    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/filter_form/default/discipline_group_type.js', CClientScript::POS_HEAD);

    $getGroupUrl = Yii::app()->createAbsoluteUrl('/progress/getGroups');
    Yii::app()->clientScript->registerScript('getGroupUrl', <<<JS
        getGroupsUrl = "{$getGroupUrl}"
JS
    , CClientScript::POS_READY);

    $form=$this->beginWidget('CActiveForm', array(
        'id'=>'filter-form',
        'htmlOptions' => array('class' => 'form-inline row-fluid')
    ));
        $disciplines = CHtml::listData(D::model()->getDisciplinesForJournalPermition(), 'd1', 'd2');
        $type_lesson=SH::getTypesForJournal();
        $groups = CHtml::listData(Gr::model()->getGroupsForJournalPermition($model->discipline,$model->type_lesson), 'group', 'name');


        $options =  array('class'=>'chosen-select', 'autocomplete' => 'off', 'empty' => '&nbsp;');
        echo '<div>'.
                '<div class="span2 ace-select">'.
                $form->label($model, 'discipline').
                $form->dropDownList($model, 'discipline', $disciplines,$options).
                '</div>'.
                '<div class="span2 ace-select">'.
                $form->label($model, 'type_lesson').
                $form->dropDownList($model, 'type_lesson', $type_lesson, array('class'=>'chosen-select', 'autocomplete' => 'off', 'empty' => '&nbsp;')).
                '</div>'.
                '<div class="span2 ace-select">'.
                $form->label($model, 'group').
                $form->dropDownList($model, 'group', $groups,$options).
                '</div>'.
            '</div>';

    $this->endWidget();

echo <<<HTML
<span id="spinner1"></span>
HTML;
