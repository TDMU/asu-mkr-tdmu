<?php
/**
 * @var ListController $this
 * @var FilterForm $model
 */
	$students=St::model()->getListStream($model->stream);
	Yii::app()->clientScript->registerScript('list-stream', <<<JS
        initDataTableOprions('list-stream',{
            aaSorting: [],
            "iDisplayLength": 50,
            "aLengthMenu": [[25, 50, 100, 200, -1], [25, 50, 100,200, "Все"]],
            bPaginate: true,
            "bFilter": true,
            oLanguage: {
                sSearch: 'Поиск',
                oPaginate: {
                    sNext: 'След',
                    sPrevious: 'Пред'
                },
                sLengthMenu: 'Показать _MENU_ записей',
                sInfo: 'Общее кол-во записей _TOTAL_ отображено (_START_ - _END_)',
                sInfoEmpty: 'Ничего не найдено',
                sInfoFiltered: ' - отсортировано _MAX_ записей',
                sZeroRecords: 'Ничего не найдено',
                responsive: true,
                columnDefs: [
                    { targets: [-1, -3], className: 'dt-body-right' }
                ]
            }
        });
JS
    );
    $ps34=PortalSettings::model()->findByPk(34)->ps2;

    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=>'button',
        'type'=>'primary',

        'icon'=>'print',
        'label'=>tt('Печать'),
        'htmlOptions'=>array(
            'class'=>'btn-small',
            'data-url'=>Yii::app()->createUrl('/list/streamExcel'),
            'id'=>'btn-print-excel',
        )
    ));

?>
<table id="list-stream" class="table table-striped table-hover">
<thead>
	<tr>
		<th style="width:40px">№</th>
        <?php if($ps34==1):?>
		<th style="width:90px"><?=tt('Вид фин.')?></th>
        <?php endif;?>
		<th><?=tt('ФИО студента')?></th>
        <th style="width:200px">№ <?=tt('зач. книжки')?></th>
        <th><?=tt('Академ. группа')?></th>
	</tr>
</thead>
<tbody>
<?php
	$type=array(
		0=>tt('бюджет'),
		1=>tt('контракт')
	);
	$i=1;
	foreach($students as $student)
	{
        if($ps34==1){
            if($student['sk3']==0){
                echo '<tr class="success">';
            }else
            {
                echo '<tr class="warning">';
            }
        }else
            echo '<tr class="info">';
		echo '<td>'.$i.'</td>';
        if($ps34==1)
		    echo '<td>'.$type[$student['sk3']].'</td>';

        $name = $student['st2'].' '.$student['st3'].' '.$student['st4'];

        if(Yii::app()->language == 'en' && !empty($student['st74']))
            $name = $student['st74'].' '.$student['st75'].' '.$student['st76'];

		echo '<td>'.$name.'</td>';
        echo '<td>'.$student['st5'].'</td>';
        echo '<td>'.Gr::model()->getGroupName($model->course, $student).'</td>';
		echo '</tr>';
		$i++;
	}
?>
</tbody>
</table>
