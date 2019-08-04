<?php
    $this->pageHeader=tt('Группы пунктов меню (доп.)');
    $this->breadcrumbs=array(
        tt('Группы пунктов меню (доп.)'),
    );

    $this->menu=array(
        array('label'=>tt('Создать'),'icon'=>'plus','url'=>array('create')),
    );

    if(!empty($this->menu))
    {
        echo $this->renderPartial('/default/_menu');
    }
?>

<?php
$vis_arr=Pmg::getPmg7Array();
$visible=$vis_arr[1];
$notVisible=$vis_arr[0];
$pageSize=Yii::app()->user->getState('pageSize',10);
$this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'pmg-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
    'type'=>'striped hover bordered',
	'columns'=>array(
		//'pmg1',
		'pmg2',
		'pmg3',
		'pmg4',
		'pmg5',
        'pmg7'=>array(
            'name'=>'pmg7',
            'type'=>'html',
            'value'=>function($data) use ($visible,$notVisible) {
                    return ($data->pmg7==0)?"<a class=\"visible-off\" href=\"".$data->pmg1."\"><i class=\"glyphicon glyphicon-remove-circle\"></i>".$notVisible."</a>":"<a class=\"visible-on\"  href=\"".$data->pmg1."\"><i class=\"glyphicon glyphicon-ok-circle\"></i>".$visible."</a>";
                },
            'filter'=>Pmg::getPmg7Array(),
        ),
        'pmg6'=>array(
            'name'=>'pmg6',
            'value'=>'$data->getPmg6()',
            'filter'=>Pmg::getPmg6Array(),
        ),
		'pmg8',
		'pmg9',
		array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{update} {delete}',
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
	        $.fn.yiiGridView.update('pmg-grid',{ data:{ pageSize: $(this).val() }})
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
        jQuery('#pmg-grid').yiiGridView('update', {
		type: 'POST',
		url: jQuery(this).attr('href'),
		data:{ '".Yii::app()->request->csrfTokenName."':'".Yii::app()->request->csrfToken."' },
		success: function(data) {
			jQuery('#pmg-grid').yiiGridView('update');
		},
	});
    }
");
?>
