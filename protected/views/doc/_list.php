<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 23.12.2016
 * Time: 14:57
 */

/**
 * @var $model Tddo
 * @var $form CActiveForm
 * @var $docTypeModel Ddo
 */

$action = Yii::app()->controller->action->id;

Yii::app()->clientScript->registerPackage('autosize');
Yii::app()->clientScript->registerPackage('jquery.ui');
Yii::app()->clientScript->registerPackage('datepicker');

Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/doc/main.js', CClientScript::POS_HEAD);

$options = array('class'=>'chosen-select', 'autocomplete' => 'off', 'empty' => '&nbsp;', 'style' => 'width:200px');

$form=$this->beginWidget('CActiveForm', array(
    'id'=>'filter-form',
    'method'=>'get',
    'action'=> Yii::app()->createUrl('doc/'.$action),
    'htmlOptions' => array('class' => 'form-inline')
));
$html  = '<div>';
$html .= '<fieldset>';

$docTypes = CHtml::listData(Ddo::model()->findAll('ddo1>0'), 'ddo1', 'ddo2');
$html .= '<div class="span2 ace-select">';
$html .= $form->label($model, 'tddo2');
$html .= CHtml::dropDownList('docType', $model->tddo2, $docTypes,$options);
//$html .= $form->dropDownList($model, 'tddo2', $docTypes, $options);
$html .= '</div>';

$previousYear = date('Y', strtotime('-1 year'));
$previousYear2 = date('Y', strtotime('-2 year'));
$currentYear  = date('Y');

$years = array(
    $currentYear  => $currentYear,
    $previousYear => $previousYear,
    $previousYear2 => $previousYear2
);

$html .= '<div class="span2 ace-select">';
$html .= $form->label($model, 'tddo23');
//$html .= $form->dropDownList($model, 'tddo23', $years);
$html .= CHtml::dropDownList('docYear', $model->tddo23, $years);
$html .= '</div>';

$html .= '</fieldset>';
$html .= '</div>';
echo $html;
$this->endWidget();
?>

<div class="row-fluid" >
    <?php
    if (! empty($model->tddo2)) {
        $docTypeModel = Ddo::model()->findByPk($model->tddo2);
        if(empty($docTypeModel))
            throw new Exception("docTypeModel");

        $items = $docTypeModel->generateColumnsGrid($this, $model);

        $printUrl = Yii::app()->createUrl('doc/tddoPrint', array('docType' => $model->tddo2));

        $pageTop = <<<HTML
        <div>
            <div class="pull-left">{summary}</div>
            <div class="pull-right">{pager}</div>
        </div>
HTML;
        $pager = <<<HTML
        <div>
            <!--<button class="print-doc btn btn-info" data-href="{$printUrl}">
                %s
                <i class="icon-print  bigger-125 icon-on-right"></i>
            </button>-->
            <div class="pull-right">{pager}</div>
        </div>
HTML;


        $items = array_merge(
            $items,
            array(
                'tddo21'=>array(
                    'name'=>'tddo21',
                    'header'=>$model->getAttributeLabel('tddo21'),
                    'value'=>'$data->tddo21',
                    /*'value'=>function($data) {
                        return date_format(date_create_from_format('Y-m-d H:i:s', $data['tddo21']), 'd-m-Y H:i:s');
                    },*/
                    'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'model'=>$model,
                        'attribute'=>'tddo21',
                        'language' => Yii::app()->language,
                        // 'i18nScriptFile' => 'jquery.ui.datepicker-ja.js', (#2)
                        'htmlOptions' => array(
                            'id' => 'datepicker-for-tddo21',
                            'size' => '10',
                        ),
                        'options' => array(
                            'dateFormat' => 'yy-mm-dd'
                        ),
                        'defaultOptions' => array(  // (#3)
                            'showOn' => 'focus',
                            'dateFormat' => 'yy-mm-dd',
                            'showOtherMonths' => true,
                            'selectOtherMonths' => true,
                            'changeMonth' => true,
                            'changeYear' => true,
                            'showButtonPanel' => true,
                        )
                    ),
                        true)
                )
            )
        );

        $pageSize=Yii::app()->user->getState('pageSize',10);

        $this->widget('bootstrap.widgets.TbGridView', array(
            'id' => 'docs-list',
            'dataProvider' => $dataProvider,
            'type'=>'striped hover bordered',
            'afterAjaxUpdate' => 'reinstallDatePicker',
            'template' => $pageTop.'{items} '.sprintf($pager, tt('Распечатать')),
            'rowHtmlOptionsExpression' => 'array("data-id" => $data->tddo1, "class"=>$data->isControl()?"warning":"")',
            'filter' => $model,
            'ajaxUrl' => Yii::app()->createAbsoluteUrl('/doc/'.$action,array('docType'=>$model->tddo2,'docYear'=>$model->tddo23)),
            'columns' =>
                array_merge(
                /*array(
                    array(
                    'name' => 'tddo3',
                    'value' => '$data->tddo3',
                    //'visible' => $docType != 1,
                    'headerHtmlOptions' => array('style' => 'width:7%'),
                    'filter' => CHtml::textField('Tddo[tddo3]', $model->tddo3, array('class' => 'span12'))
                    )
                )*/
                    array(
                        'tddo3'=>array(
                            'name' => 'tddo3',
                            'header'=>$model->getAttributeLabel('tddo3'),
                            'headerHtmlOptions' => array('style' => 'width:7%'),
                        )
                    ),
                    $items,
                    array(
                        array(
                            'class'=>'CButtonColumn',
                            'template'=>'{view}',
                            'headerHtmlOptions' => array('style' => 'width:7%'),
                            'header'=>CHtml::dropDownList(
                                'pageSize',
                                $pageSize,
                                SH::getPageSizeArray(),
                                array('class'=>'change-pageSize','style'=>'width:60px')
                            ),
                            /*'buttons'=>array(
                                'edit'=>array(
                                    'url'=>'Yii::app()->controller->createAbsoluteUrl("doc/tddoView", array("tddo1" => $data->tddo1))',
                                    'click' => 'function(){}',
                                    'options' => array('class' => 'view btn btn-mini btn-info'),
                                    'imageUrl' => false,
                                    'label' => '<i class="icon-eye bigger-120"></i>'
                                ),
                            ),*/
                        )
                    )
                ),
        ));

        Yii::app()->clientScript->registerScript('initPageSize',"
           $(document).on('change','.change-pageSize', function() {
                $.fn.yiiGridView.update('docs-list',{ data:{ pageSize: $(this).val() }})
            });",CClientScript::POS_READY);

        $language = Yii::app()->language;
        Yii::app()->clientScript->registerScript('re-install-date-picker', "
        function reinstallDatePicker(id, data) {
            $('#datepicker-for-tddo21,#datepicker-for-tddo4,#datepicker-for-tddo9').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['{$language}'],{'dateFormat':'yy-mm-dd'}));
        }
    ");

    }
    ?>
</div>