<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 15.10.15
 * Time: 13:54
 */

$this->pageTitle=tt('Администраторы');
$this->pageHeader=tt('Администраторы');

$this->breadcrumbs=array(
    tt('Администраторы')
);

$this->menu=array(
    array('label'=>tt('Создать'),'icon'=>'plus', 'url'=>array('adminCreate')),
);
$pageSize=Yii::app()->user->getState('pageSize',10);
if(!empty($this->menu))
{
    echo $this->renderPartial('/default/_menu');
}
?>
<div class="showback">
    <?php
    $this->widget('bootstrap.widgets.TbGridView',array(
        'id'=>'user-grid',
        'dataProvider'=>$model->search(),
        'filter'=>$model,
        //'responsiveTable' => true,
        'type'=>'striped condensed hover',
        'columns'=>array(
            'u2',
            'u4',
            array(
                'name'=>'u8',
                'value'=>'$data->getU8Type()',
                'filter'=>false
            ),
            array(
                'class'=>'bootstrap.widgets.TbButtonColumn',
                'template'=>'{update} {delete}',
                'buttons'=>array(
                    'delete' => array
                    (
                        'url'=>'array("adminDelete","id"=>$data->u1)',
                    ),
                    'update' => array
                    (
                        'url'=>'array("adminUpdate","id"=>$data->u1)',
                    ),
                ),
                'header'=>CHtml::dropDownList(
                        'pageSize',
                        $pageSize,
                        SH::getPageSizeArray(),
                        array('class'=>'change-pageSize')
                    ),
            ),
        ),
    ));
    Yii::app()->clientScript->registerScript('initPageSize',"
	   $(document).on('change','.change-pageSize', function() {
	        $.fn.yiiGridView.update('user-grid',{ data:{ pageSize: $(this).val() }})
	    });",CClientScript::POS_READY);
    ?>
</div>