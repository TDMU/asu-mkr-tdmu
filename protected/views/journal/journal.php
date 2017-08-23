<?php
/* @var $this ProgressController
 * @var $model FilterForm
 */
    $this->pageHeader=tt('Электронный журнал');
    $this->breadcrumbs=array(
        tt('Электронный журнал'),
    );

    Yii::app()->clientScript->registerPackage('gritter');
    Yii::app()->clientScript->registerPackage('jquery.ui');
    Yii::app()->clientScript->registerPackage('datepicker');
    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/journal/journal.js', CClientScript::POS_HEAD);

    $error       = tt('Ошибка! Проверьте правильность вводимых данных или доступ для ввода!');
    $success     = tt('Cохранено!');
    $minMaxError = tt('Оценка за пределами допустимого интервала!');
    $access= tt('Ошибка! Нет доступа!');
    $st= tt('Ошибка! Студент заблокирован!');
    $error2= tt('Ошибка! Не найдены входящие данные!');
    $min=Elgzst::model()->getMin();
    $ps44 = PortalSettings::model()->getSettingFor(44);
    $ps55 = PortalSettings::model()->getSettingFor(55);
    $ps84 = PortalSettings::model()->getSettingFor(84);
    $ps88 = PortalSettings::model()->getSettingFor(88);
    $timeAccess = tt('Ошибка! Доступ на редактирование закрыт!');
    $notFoundVmpv = tt('Ошибка! Ведомость не найдена или закрыта!');
    $stusvError = tt('Предупреждение! Оценка сохранена, но ведомость не пересчитана');

//errorType=0 error
//errorType=4 minMaxError
//errorType=3 access
//errorType=5 st
//errorType=2 error2
//errorType=6 timeAccess
//errorType=9 Not Found vmpv
    Yii::app()->clientScript->registerScript('translations', <<<JS
        minBal = {$min}
        tt.error       = "{$error}";
        tt.success     = "{$success}";
        tt.minMaxError = "{$minMaxError}";
        tt.timeAccess = "{$timeAccess}";
        tt.access = "{$access}";
        tt.st = "{$st}";
        tt.error2 = "{$error2}";
        tt.notFoundVmpv = "{$notFoundVmpv}";
        tt.stusvError = "{$stusvError}";

        ps44 = {$ps44};
        ps55 = {$ps55};
        ps84 = {$ps84};
        ps88 = {$ps88};
        isStd = false;
JS
    , CClientScript::POS_READY);

?>

<?php
    $this->renderPartial('/filter_form/default/year_sem');

    $this->renderPartial('/filter_form/default/discipline_group_type', array(
        'model' => $model,
    ));
    
    
   $this->renderPartial('journal/_bottom', array('model' => $model,'read_only' => $read_only,'ps44'=>$ps44,'ps55'=>$ps55,'ps88'=>$ps88));


$this->beginWidget(
    'bootstrap.widgets.TbModal',
    array(
        'id' => 'modalBlock',
        'htmlOptions'=>array(
            'class'=>'full-modal'
        )
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
                'label' => tt('Закрыть'),
                'url' => '#',
                'htmlOptions' => array('data-dismiss' => 'modal'),
            )
        ); ?>
    </div>

<?php $this->endWidget();