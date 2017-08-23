<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 09.04.2017
 * Time: 14:44
 */

$this->pageHeader=tt('История блокировки неоплативших студентов');
$this->breadcrumbs=array(
    tt('История блокировки неоплативших студентов'),
);

?>
    <form id="excel-form" type="get">
        <button id="excel-import" class="btn btn-info"><i class="icon-print"></i> Excel</button>
    </form>

<?php
$pageSize=Yii::app()->user->getState('pageSize',10);
$this->widget('bootstrap.widgets.TbGridView',array(
    'id'=>'grid',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'type'=>'striped hover bordered',
    'columns'=>array(
        array(
            'name'=>'st',
            'value'=>'SH::getShortName($data->st_lname, $data->st_fname, $data->st_sname)',
        ),
        'gr_name',
        'course',
        'stbl3',
        'stbl5',
        array(
            'name'=>'tch',
            'value'=>'SH::getShortName($data->tch_lname, $data->tch_fname, $data->tch_sname)',
        ),
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'',
            'buttons'=>array(
            ),
            'header'=>CHtml::dropDownList(
                'pageSize',
                $pageSize,
                $this->getPageSizeArray(),
                array('class'=>'change-pageSize','style'=>'max-width:70px')
            ),
        ),
    ),
));
Yii::app()->clientScript->registerScript('initPageSize',"
	   $(document).on('change','.change-pageSize', function() {
	        $.fn.yiiGridView.update('grid',{ data:{ pageSize: $(this).val() }})
	    });",CClientScript::POS_READY);

$url = Yii::app()->createUrl('/journal/stFinBlockStatisticExcel');
Yii::app()->clientScript->registerScript('excelPrint',"
    $(function(){
        var url = '$url';

        $('body').on('click','#excel-import', function() {
           //var data = $('#user-history-grid .filters input,#user-history-grid .filters select').serialize();
           //alert(data);
             $('#excel-form').attr('action', url);
             //alert( url+'/?'+data);
             $('#excel-form').submit();
             document.location.reload();
        });
    });
", CClientScript::POS_READY);