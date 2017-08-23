<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/filter_form/default/discipline_sst.js', CClientScript::POS_HEAD);

    $form=$this->beginWidget('CActiveForm', array(
        'id'=>'filter-form',
        'htmlOptions' => array('class' => 'form-inline row-fluid')
    ));
        $groups = CHtml::listData(Gr::model()->getGroupsForJournalPermitionSst(Yii::app()->user->dbModel->st1), 'group', 'name');
        $disciplines = CHtml::listData(D::model()->getDisciplinesForSstPermition($model->group), 'discipline', 'd2');
        $type_lesson=FilterForm::getTypesForJournal();

        $options =  array('class'=>'chosen-select', 'autocomplete' => 'off', 'empty' => '&nbsp;');
        echo '<div>'.
                '<div class="span2 ace-select">'.
                $form->label($model, 'group').
                $form->dropDownList($model, 'group', $groups,$options).
                '</div>'.
                '<div class="span2 ace-select">'.
                $form->label($model, 'discipline').
                $form->dropDownList($model, 'discipline', $disciplines,$options).
                '</div>'.
                '<div class="span2 ace-select">'.
                $form->label($model, 'type_lesson').
                $form->dropDownList($model, 'type_lesson', $type_lesson, array('class'=>'chosen-select', 'autocomplete' => 'off')).
                '</div>'.
            '</div>';

    $this->endWidget();

echo <<<HTML
<span id="spinner1"></span>
HTML;
