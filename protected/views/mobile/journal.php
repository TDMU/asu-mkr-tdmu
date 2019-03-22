<?php
/* @var $this ProgressController
 * @var $model FilterForm
 */

$this->pageHeader=tt('Журнал');
$this->breadcrumbs=array(
    tt('Журнал'),
);
    Yii::app()->clientScript->registerPackage('noty');
    Yii::app()->clientScript->registerPackage('jquery.ui');
    Yii::app()->clientScript->registerPackage('datepicker-mobile');

    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/mobile/script/journal/journal.js', CClientScript::POS_HEAD);

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

    Yii::app()->clientScript->registerScript('translations', <<<JS
        minBal = {$min}
        _error       = "{$error}" //errorType=0
        _success     = "{$success}"
        _minMaxError = "{$minMaxError}" //errorType=4
        _access = "{$access}" //errorType=3
        _st = "{$st}" //errorType=5
        _error2 = "{$error2}" //errorType=2
         ps44 = {$ps44};
        ps55 = {$ps55};
        ps84 = {$ps84};
        ps88 = {$ps88};
JS
    , CClientScript::POS_READY);
?>

<?php
$this->renderPartial('/filter_form/mobile/_accordion_select', array(
    'render' => '/filter_form/mobile/journal',
    'arr' => array('model' => $model)
));

$this->renderPartial('journal/_bottom', array('model' => $model,'read_only' => $read_only,'ps44'=>$ps44,'ps55'=>$ps55));
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

<div id="modalRetake" data-url="<?=Yii::app()->createUrl('/journal/saveJournalRetake')?>" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">

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
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->