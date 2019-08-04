<?php
$this->pageHeader=tt('Пункты меню (доп.)');
$this->breadcrumbs=array(
	tt('Пункты меню (доп.)'),
);

$this->menu=array(
	array('label'=>tt('Создать'),'icon'=>'plus','url'=>array('create')),
);

if(!empty($this->menu))
{
    echo $this->renderPartial('/default/_menu');
}
$vis_arr=Pm::getPm7Array();
$visible=$vis_arr[1];
$notVisible=$vis_arr[0];
$pageSize=Yii::app()->user->getState('pageSize',10);
$this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'pm-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
        'type'=>'striped hover bordered',
	'columns'=>array(
		//'pm1',
		'pm2',
		/*'pm3',
		'pm4',
		'pm5',
		'pm6',*/
		'pm7'=>array(
                    'name'=>'pm7',
                    'type'=>'html',
                    'value'=>function($data) use ($visible,$notVisible) {
                        return ($data->pm7==0)?"<a class=\"visible-off\" href=\"".$data->pm1."\"><i class=\"glyphicon glyphicon-remove-circle\"></i>".$notVisible."</a>":"<a class=\"visible-on\"  href=\"".$data->pm1."\"><i class=\"glyphicon glyphicon-ok-circle\"></i>".$visible."</a>";
                    },
                    'filter'=>Pm::getPm7Array(),
                ),
		/*'pm8'=>array(
                    'name'=>'pm8',
                    'value'=>'$data->getPm8()',
                    'filter'=>Pm::getPm8Array(),
                ),*/
		/*'pm9',*/
        'pm10'=>array(
            'name'=>'pm10',
            'value'=>'$data->getPm10()',
            'filter'=>Pm::getPm10Array(),
        ),
        'pm11'=>array(
            'name'=>'pm11',
            'value'=>'$data->getPm11()',
            'filter'=>Pm::getPm11Array(),
        ),
        array(
            'header'=>  Pmc::model()->getAttributeLabel('pmc1'),
            'value'=>'$data->getParentTitle()',
            'filter'=>''
        ),
		array(
                    'class'=>'bootstrap.widgets.TbButtonColumn',
                    'template'=>'{update} {view} {delete}',
                    'header'=>CHtml::dropDownList(
                        'pageSize',
                        $pageSize,
                        SH::getPageSizeArray(),
                        array('class'=>'change-pageSize','style'=>'max-width:70px')
                    ),
		),
	),
));
Yii::app()->clientScript->registerScript('initPageSize',"
	   $(document).on('change','.change-pageSize', function() {
	        $.fn.yiiGridView.update('pm-grid',{ data:{ pageSize: $(this).val() }})
	    });",CClientScript::POS_READY);

Yii::app()->clientScript->registerScript('change-visible', "
    $(document).on('click', '.visible-on',function(event) {
        var id = $(this).attr('href');
        event.preventDefault();
        changeVisible(id,0);
    });

    $(document).on('click', '.visible-off', function(event) {
        var id = $(this).attr('href');
        event.preventDefault();
        changeVisible(id,1);
    });

    function changeVisible(id,type)
    {
            var url_str='".Yii::app()->request->baseUrl."/".Yii::app()->controller->module->id."/".Yii::app()->controller->id."/changevisible/id/'+id+'/type/'+type;
            $.ajax({
                success:function(html){
                    updateGrid();
                },
                type:'get',
                url:url_str,
                cashe:false,
                dataType:'html'
            });
    }

    function updateGrid()
    {
        jQuery('#pm-grid').yiiGridView('update', {
		type: 'POST',
		url: jQuery(this).attr('href'),
		data:{ '".Yii::app()->request->csrfTokenName."':'".Yii::app()->request->csrfToken."' },
		success: function(data) {
			jQuery('#pm-grid').yiiGridView('update');
		},
	});
    }
");
