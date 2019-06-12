<?php
/* @var $this ProgressController
 * @var $model FilterForm
 */
if(!empty($model->group)):
list($uo1,$gr1) = explode("/", $model->group);

if(!isset($isStd))
    $isStd = false;

$sem1List =  Sem::model()->getSem1List($uo1);
if(count($sem1List)!=1) {
    $options =  array('class'=>'chosen-select', 'autocomplete' => 'off', 'empty' => '&nbsp;');

    $html  = '<div class="span2 ace-select" style="width:100%;margin-left: 0px;margin-bottom: 5px;">';
    $html .= CHtml::label($model->getAttributeLabel('sem1'), 'FilterForm_sem1');
    $html .= CHtml::dropDownList('FilterForm[sem1]', $model->sem1, CHtml::listData($sem1List, 'sem1', 'name'), $options);
    $html .= '</div>';

    echo $html;
}else
{
    $elem =  current($sem1List);
    $model->sem1 = $elem['sem1'];

    //var_dump($model->sem1);
}

if (!empty($model->sem1)):

    $dates = R::model()->getDatesForJournal(
            $uo1,
            $gr1,
            $model->type_lesson,
            $model->sem1
    );

    $us = Us::model()->findByAttributes(array('us2'=>$uo1,'us3'=>$model->sem1));

    if(count($dates)==0)
        throw new CHttpException(404, tt('Не найдены занятия.'));

    $ps57 = PortalSettings::model()->getSettingFor(57);//
    $modules = null;

    $elg1=Elg::getElg1($uo1,$model->type_lesson,$model->sem1);
    $elg = Elg::model()->findByPk($elg1);
    if(empty($elg))
        throw new CHttpException(404, tt('Не задана структура журнала. Обратитесь к Администратору системы').'.');


    echo '<div class="container">';

    if(!$isStd){
        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType'=>'button',
            'type'=>'primary',

            'icon'=>'print',
            'label'=>tt('Печать'),
            'htmlOptions'=>array(
                'class'=>'btn-small',
                'data-url'=>Yii::app()->createUrl('/journal/journalExcel',array('sem1'=>$model->sem1)),
                'id'=>'journal-print',
            )
        ));

        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType'=>'button',
            'type'=>'success',

            'icon'=>'print',
            'label'=>tt('Печать Итог'),
            'htmlOptions'=>array(
                'class'=>'btn-small',
                'data-url'=>Yii::app()->createUrl('/journal/journalExcelItog',array('sem1'=>$model->sem1)),
                'id'=>'journal-print-itog',
            )
        ));

        $this->renderPartial('/filter_form/default/_refresh_filter_form_button');

        if($ps57==1)
            $modules = Vvmp::model()->getModule($uo1,$gr1);
        if(!empty($modules)) {
            $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'button',
                'type' => 'danger',

                'icon' => 'refresh',
                'label' => tt('Пересчитать ведомости'),
                'htmlOptions' => array(
                    'class' => 'btn-small',
                    'data-url' => Yii::app()->createUrl('/journal/recalculateVmp', array('uo1' => $uo1, 'gr1' => $gr1, 'sem1' => $model->sem1, 'type' => $model->type_lesson)),
                    'id' => 'recalculate-vmp',
                )
            ));
        }

        $ps84 = PortalSettings::model()->findByPk(84)->ps2;
        if ($ps84 == 1) {
            $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'button',
                'type' => 'inverse',

                'icon' => 'refresh',
                'label' => tt('Пересчитать ведомости(и.)'),
                'htmlOptions' => array(
                    'class' => 'btn-small',
                    'data-url' => Yii::app()->createUrl('/journal/recalculateStus', array('uo1' => $uo1, 'gr1' => $gr1, 'sem1' => $model->sem1, 'type' => $model->type_lesson)),
                    'id' => 'recalculate-stus',
                )
            ));
        }

        if($elg->elg20->uo6==3){
            $sem1End = Vmp::model()->getEndSem1($elg->elg2);

            if($elg->elg3==$sem1End){

                $vedItog = Vvmp::model()->checkModul($elg->elg2, $gr1, 98);

                if(!empty($vedItog))
                    $this->widget('bootstrap.widgets.TbButton', array(
                        'buttonType' => 'button',
                        'type' => 'inverse',

                        'icon' => 'refresh',
                        'label' => tt('Пересчитать итоговую накопительную ведомость'),
                        'htmlOptions' => array(
                            'class' => 'btn-small',
                            'data-url' => Yii::app()->createUrl('/journal/recalculateVmpItog', array('uo1' => $uo1, 'gr1' => $gr1, 'sem1' => $model->sem1, 'type' => $model->type_lesson)),
                            'id' => 'recalculate-vmp-itog',
                        )
                    ));
            }
        }

        ?>
            <span><label class="label label-warning">&nbsp;&nbsp;</label> - <?=tt('Информация требует обновления страницы')?></span>
            <div class="span-12">
                <?= tt("Режим чтения")?>:
                <label class="label label-warning">+</label> - <?= tt("Присутствовал на занятии")?>
                <label class="label label-warning">-</label> - <?= tt("Отсутствовал на занятии")?>
                <label class="label label-success">5</label> - <?= tt("Оценка за занятие")?>
                <label class="label label-inverse">5</label> - <?= tt("Отработка занятия")?>
            </div>
        <?php
    }


    $ps9  = PortalSettings::model()->getSettingFor(9);
    $ps20 = PortalSettings::model()->getSettingFor(20);// use sub modules
    $ps56 = PortalSettings::model()->getSettingFor(56);//
    $ps33=PortalSettings::model()->getSettingFor(33);

    $students = St::model()->getStudentsForJournal($gr1, $uo1);
    echo '</div>';
    echo '<div class="journal-bottom">';

    $this->renderPartial('journal/_table_1', array(
        'students' => $students
    ));

    if($model->type_lesson!=0) {
        //доп колонки
        Elgd::checkEmptyElgd($elg1);
    }

    $elgd = array();
    $ps97 = PortalSettings::model()->findByPk(97)->ps2;
    $ps85 = PortalSettings::model()->findByPk(85)->ps2;
    $ps83 = PortalSettings::model()->findByPk(83)->ps2;
    if($ps97==0)
        $elgd=Elgd::model()->getDop($elg1);

    $exam = null;
    /*if($ps57==1)
        $xam = Stus::model()->getExam($uo1,$gr1,$model->sem1,$model->discipline);*/

    $classTable2='journal_div_table2';
    if($model->type_lesson==0 || (empty($elgd)&&$ps85==1&&$ps83==1))
        $classTable2='journal_div_table2 journal_div_table2_1';

    $this->renderPartial('journal/_table_2', array(
        'students' => $students,
        'dates'=>$dates,
        'elg'=>$elg,
        'uo1'=>$uo1,
        'gr1'=>$gr1,
        'ps20'  => $ps20,
        'ps33'  => $ps33,
        'ps55'  => $ps55,
        'ps56'  => $ps56,
        'ps57'  => $ps57,
        'ps88'  => $ps88,
        'ps9'  => $ps9,
        'modules'=>$modules,
        'read_only'=>$read_only,
        'model' => $model,
        'classTable2'=>$classTable2,
        'isStd'=>$isStd
    ));


    if($model->type_lesson!=0&&!(empty($elgd)&&$ps85==1&&$ps83==1))
    {
        $this->renderPartial('journal/_table_3', array(
            'students' => $students,
            'elgd'=>$elgd,
            'elg'=>$elg,
            'read_only'=>$read_only,
            'ps44'=>$ps44,
            'us'=>$us,
            'gr1'=>$gr1,
            'model'=>$model
        ));
    }

    echo '</div>';

    Yii::app()->clientScript->registerScript('journal-vars', <<<JS
        ps9  = {$ps9};
JS
        , CClientScript::POS_HEAD);

    endif;
 endif;
?>

<div id="dialog-confirm" class="hide" title="Empty the recycle bin?">
    <div class="alert alert-info bigger-110">
        <?=tt('По данному пропуску есть отработки. Все отработки будут удалены!')?>
    </div>

    <div class="space-6"></div>

    <p class="bigger-110 bolder center grey">
        <i class="icon-hand-right blue bigger-120"></i>
        <?=tt('Вы уверены?')?>
    </p>
</div><!-- #dialog-confirm -->

    <div id="dialog-confirm-fin-block" class="hide" title="Empty the recycle bin?">
        <div class="alert alert-info bigger-110">
            <?=tt('Проставление отметки о извещении студента о задолженности по оплате!')?>
        </div>

        <div class="space-6"></div>

        <p class="bigger-110 bolder center grey">
            <i class="icon-hand-right blue bigger-120"></i>
            <?=tt('Вы уверены?')?>
        </p>
    </div><!-- #dialog-confirm -->

<?php  $this->beginWidget(
        'bootstrap.widgets.TbModal',
        array(
            'id' => 'modalRetake',
            'htmlOptions'=>array('data-url'=>Yii::app()->createUrl('/journal/saveJournalRetake'))
        )
    ); ?>

    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4></h4>
    </div>

    <div class="modal-body">
        <div id="modal-content">

        </div>
    </div>

    <div class="modal-footer">
        <?php $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'label' => tt('Сохранить'),
                'type'=>'info',
                'url' => '#',
                'htmlOptions' => array('id' => 'save-retake-journal'),
            )
        ); ?>
        <?php $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'label' => tt('Отмена'),
                'url' => '#',
                'htmlOptions' => array('data-dismiss' => 'modal'),
            )
        ); ?>
    </div>

<?php $this->endWidget();


$this->beginWidget(
    'bootstrap.widgets.TbModal',
    array(
        'id' => 'modalChangeTheme',
        'htmlOptions'=>array('data-url'=>Yii::app()->createUrl('/journal/saveChangeTheme'))
    )
); ?>

    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4></h4>
    </div>

    <div class="modal-body">
        <div id="modal-content">

        </div>
    </div>

    <div class="modal-footer">
        <?php $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'label' => tt('Сохранить'),
                'type'=>'info',
                'url' => '#',
                'htmlOptions' => array('id' => 'change-theme-journal'),
            )
        ); ?>
        <?php $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'label' => tt('Отмена'),
                'url' => '#',
                'htmlOptions' => array('data-dismiss' => 'modal'),
            )
        ); ?>
    </div>

<?php $this->endWidget();