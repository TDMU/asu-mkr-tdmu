<style>
    .ui-autocomplete {
        max-height: 200px;
        overflow-y: auto;
        /* prevent horizontal scrollbar */
        overflow-x: hidden;
    }
    /* IE 6 doesn't support max-height
    * we use height instead, but this forces the menu to always be this tall
    */
    * html .ui-autocomplete {
        height: 200px;
    }
    .ui-autocomplete-loading {
        background: white url('/images/ui-anim_basic_16x16.gif') right center no-repeat;
    }
</style>
<?php
/**
 *
 * @var DocsController $this
 * @var Tddo $model
 */

$this->pageHeader=tt('Документооборот');
$this->breadcrumbs=array(
    tt('Документооборот'),
);
Yii::app()->clientScript->registerPackage('autosize');
Yii::app()->clientScript->registerPackage('jquery.ui');
Yii::app()->clientScript->registerPackage('datepicker');

Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/docs/tddo.js', CClientScript::POS_HEAD);

$confirmDeleteMsg = tt('Вы уверены, что хотите удалить документ?');
Yii::app()->clientScript->registerScript('variables', <<<JS
   tt.confirmDeleteMsg  = '{$confirmDeleteMsg}';
JS
    ,CClientScript::POS_READY);

?>
<form class="form-inline">
    <?php
        $docTypes = CHtml::listData(Ddo::model()->findAll(), 'ddo1', 'ddo2');
        echo CHtml::dropDownList('docType', $docType, $docTypes, array('empty' => ''));
    ?>
</form>

<div class="row-fluid" >
    <?php
    if (! empty($docType)) {
        $provider = $model->getDocsFor($docType);

        $addUrl   = Yii::app()->createUrl('docs/tddoCreate', array('docType' => $docType));
        $printUrl = Yii::app()->createUrl('docs/tddoPrint', array('docType' => $docType));

        $pager = <<<HTML
    <div>
        <button class="add-doc btn btn-pink btn-small" data-href="{$addUrl}">
            <i class="icon-share-alt bigger-200"></i>%s
        </button>
        <button class="print-doc btn btn-info" data-href="{$printUrl}">
            %s
            <i class="icon-print  bigger-125 icon-on-right"></i>
        </button>
        <div class="pull-right">{pager}</div>
    </div>
HTML;
        if ($docType == 2) {
            $values = array(Tddo::ONLY_INDEXES => tt('Индексы').' ');
        } else {
            $values = array(Tddo::ONLY_TEACHERS => tt('Препод.').' ', Tddo::ONLY_CHAIRS => tt('Каф.').' / '.tt('Отдел'));
        }

        $executorType = CHtml::radioButtonList('Tddo[executorType]', $model->executorType, $values, array('separator' => '', 'labelOptions' => array('style' => 'display:inline')));

        $name = '';
        $id = Yii::app()->request->getParam('executor', null);
        if (! empty($id)) {
            if ($model->executorType == Tddo::ONLY_TEACHERS)
                $name = P::model()->getTeacherNameByPd1($id);
            elseif ($model->executorType == Tddo::ONLY_CHAIRS)
                $name = K::model()->findByPk($id)->getAttribute('k3');
            else
                $name = Innf::model()->findByPk($id)->getAttribute('innf3');
        }

        $executorFilter = $executorType .
                          CHtml::textField('', $name, array(
                            'placeholder' => tt('исполнитель'),
                            'class' => 'autocomplete',
                            'data-type' => $model->executorType,
                            'autocomplete' => 'off'
                          )) .
                          CHtml::hiddenField('executor', $id);


        $this->widget('bootstrap.widgets.TbGridView', array(
            'id' => 'docs-list',
            'dataProvider' => $provider,
            'type' => 'striped bordered',
            'template' => '{items} '.sprintf($pager, tt('Добавить'), tt('Распечатать')),
            'htmlOptions' => array(
                'class' => 'span12',
                'style' => ''
            ),
            'rowHtmlOptionsExpression' => 'array("data-id" => $data->tddo1)',
            'filter' => $model,
            'afterAjaxUpdate' => <<<JS
                function(id, data){
                    initExecutorTypeFilter();
                    initAutoComplete();
                }
JS
            , 'columns' => array(
                array(
                    'name' => 'tddo7',
                    'value' => '$data->tddo7',
                    'visible' => $docType == 1,
                    'headerHtmlOptions' => array('style' => 'width:10%'),
                    'cssClassExpression' => '$data->isOnControl() ? "isOnControl" : ""',
                    'filter' => CHtml::textField('Tddo[tddo7]', $model->tddo7, array('class' => 'span12'))
                ),
                array(
                    'name' => 'tddo3',
                    'value' => '$data->tddo3',
                    'visible' => $docType != 1,
                    'headerHtmlOptions' => array('style' => 'width:5%'),
                    'filter' => CHtml::textField('Tddo[tddo3]', $model->tddo3, array('class' => 'span12'))
                ),
                array(
                    'name' => 'tddo4',
                    'value' => 'SH::formatDate("Y-m-d H:i:s", "d.m.Y", $data->tddo4)',
                    'headerHtmlOptions' => array('style' => 'width:5%'),
                    'cssClassExpression' => '$data->hasAttachedFiles() ? "hasAttachesFiles" : ""',
                    'filter' => CHtml::textField('Tddo[tddo4]', $model->tddo4, array('class' => 'span12'))
                ),
                array(
                    'name' => 'tddo8',
                    'value' => '$data->tddo8',
                    'visible' => $docType == 1,
                    'headerHtmlOptions' => array('style' => 'width:10%'),
                    'filter' => CHtml::textField('Tddo[tddo8]', $model->tddo8, array('class' => 'span12'))
                ),
                array(
                    'name' => 'tddo9',
                    'value' => 'SH::formatDate("Y-m-d H:i:s", "d.m.Y", $data->tddo9)',
                    'visible' => $docType == 1,
                    'headerHtmlOptions' => array('style' => 'width:5%'),
                    'filter' => CHtml::textField('Tddo[tddo9]', $model->tddo9, array('class' => 'span12'))
                ),
                array(
                    'name' => 'tddo5',
                    'header' => Tddo::getTddo5Header($docType),
                    'value' => '$data->tddo5',
                    'headerHtmlOptions' => array('style' => 'width:25%'),
                    'filter' => CHtml::textField('Tddo[tddo5]', $model->tddo5, array('class' => 'span12'))
                ),
                array(
                    'name' => 'tddo6',
                    'value' => '$data->tddo6',
                    'headerHtmlOptions' => array('style' => 'width:25%'),
                    'filter' => CHtml::textField('Tddo[tddo6]', $model->tddo6, array('class' => 'span12'))
                ),
                array(
                    'name' => 'executor',
                    'value' => '$data->getExecutorNames()',
                    'visible' => Tddo::showExecutorFor($docType),
                    'type' => 'raw',
                    'cssClassExpression' => '($data->executorType == Tddo::ONLY_TEACHERS
                                                ? "only-teachers"
                                                : ($data->executorType == Tddo::ONLY_INDEXES
                                                    ? "only-indexes"
                                                    : ($data->executorType == Tddo::ONLY_CHAIRS
                                                        ? "only-chairs"
                                                        : "")));',
                    'filter' => $executorFilter
                ),
                array(
                    'name' => 'tddo10',
                    'value' => 'CHtml::checkBox("", $data->tddo10 == 2, array("disabled" => "disabled"))',
                    'visible' => $docType >= 5,
                    'type' => 'raw',
                ),
                array(
                    'class'=>'CButtonColumn',
                    'template'=>'{edit} {delete} {attach}',
                    'buttons'=>array(
                        'edit'=>array(
                            'url'=>'Yii::app()->controller->createAbsoluteUrl("docs/tddoEdit", array("tddo1" => $data->tddo1))',
                            'click' => 'function(){}',
                            'options' => array('class' => 'edit btn btn-mini btn-info'),
                            'imageUrl' => false,
                            'label' => '<i class="icon-edit bigger-120"></i>'
                        ),
                        'delete'=>array(
                            'url'=>'Yii::app()->controller->createAbsoluteUrl("docs/deleteTddo", array("tddo1" => $data->tddo1))',
                            'click' => 'function(){}',
                            'options' => array('class' => 'delete btn btn-mini btn-danger'),
                            'imageUrl' => false,
                            'label' => '<i class="icon-trash bigger-120"></i>'
                        ),
                        'attach'=>array(
                            'url'=>'Yii::app()->controller->createAbsoluteUrl("docs/attachFileTddo", array("tddo1" => $data->tddo1))',
                            'click' => 'function(){}',
                            'options' => array('class' => 'attachFile btn btn-mini btn-grey'),
                            'imageUrl' => false,
                            'label' => '<i class="icon-cloud-download bigger-120"></i>'
                        ),
                    ),
                ),
            ),
        ));
    }
    ?>
</div>