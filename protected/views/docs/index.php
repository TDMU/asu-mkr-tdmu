<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 20.06.2016
 * Time: 16:24
 */

$this->pageHeader=tt('Документооборот');
$this->breadcrumbs=array(
    tt('Документооборот'),
);

Yii::app()->clientScript->registerPackage('autosize');
Yii::app()->clientScript->registerPackage('jquery.ui');
Yii::app()->clientScript->registerPackage('datepicker');

Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/docs/tddo.js', CClientScript::POS_HEAD);


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

    //$addUrl   = Yii::app()->createUrl('docs/tddoCreate', array('docType' => $docType));
    $printUrl = Yii::app()->createUrl('docs/tddoPrint', array('docType' => $docType));

    $pager = <<<HTML
        <div>
            <button class="print-doc btn btn-info" data-href="{$printUrl}">
                %s
                <i class="icon-print  bigger-125 icon-on-right"></i>
            </button>
            <div class="pull-right">{pager}</div>
        </div>
HTML;

    $items = array();

    $pageSize=Yii::app()->user->getState('pageSize',10);
        if($pageSize==0)
            $pageSize=10;

    $this->widget('bootstrap.widgets.TbGridView', array(
        'id' => 'docs-list',
        'dataProvider' => $provider,
        'type' => 'striped bordered',
        'template' => '{items} '.sprintf($pager, tt('Распечатать')),
        'htmlOptions' => array(
            'class' => 'span12',
            'style' => ''
        ),
        'rowHtmlOptionsExpression' => 'array("data-id" => $data->tddo1)',
        'filter' => $model,
        'ajaxUrl' => Yii::app()->createAbsoluteUrl('/docs/index',array('docType'=>$docType)),
        'columns' =>
            array_merge(
                array(
                    array(
                    'name' => 'tddo3',
                    'value' => '$data->tddo3',
                    //'visible' => $docType != 1,
                    'headerHtmlOptions' => array('style' => 'width:7%'),
                    'filter' => CHtml::textField('Tddo[tddo3]', $model->tddo3, array('class' => 'span12'))
                    )
                ), $items,
                array(
                    array(
                        'class'=>'CButtonColumn',
                        'template'=>'{view}',
                        'header'=>CHtml::dropDownList(
                            'pageSize',
                            $pageSize,
                            array(5=>5,10=>10,20=>20,50=>50,100=>100),
                            array('class'=>'change-pageSize')
                        ),
                        'buttons'=>array(
                            'edit'=>array(
                                'url'=>'Yii::app()->controller->createAbsoluteUrl("docs/tddoViewt", array("tddo1" => $data->tddo1))',
                                'click' => 'function(){}',
                                'options' => array('class' => 'view btn btn-mini btn-info'),
                                'imageUrl' => false,
                                'label' => '<i class="icon-eye bigger-120"></i>'
                            ),
                        ),
                    )
                )
            ),
    ));

        Yii::app()->clientScript->registerScript('initPageSize',"
	    $.fn.yiiGridView.update('docs-list',{ data:{ pageSize: $(this).val() }})

            $(document).on('change','.change-pageSize', function() {
                $.fn.yiiGridView.update('docs-list',{ data:{ pageSize: $(this).val() }})
	    });",CClientScript::POS_READY);

}
    ?>
</div>