<?php
    Yii::app()->clientScript->registerPackage('daterangepicker');
    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/timetable/_date_interval.js', CClientScript::POS_HEAD);

    $startDate = $date1;
    $endDate   = $date2;
    Yii::app()->clientScript->registerScript('date-range', <<<JS
        startDate = "{$startDate}";
        endDate   = "{$endDate}";
JS
    , CClientScript::POS_HEAD);

?>


<div class="span3 ace-block ace-datepicker-block">
    <label for="id-date-range-picker-1"><?=tt('Отображаемый диапазон дат:')?></label>
    <div class="input-prepend">
        <span class="add-on">
            <i class="icon-calendar"></i>
        </span>
        <input  type="text" id="id-date-range-picker-1" value="<?=$date1.' - '.$date2?>"/>
    </div>
	
    <button id="addNewModule" class="btn btn-info btn-small" type="submit">
        <i class="icon-plus bigger-110"></i>
        <?=tt('Изменить')?>
    </button>
	<?php if(isset($showSem)&&($showSem))
	{
		if(!empty($gr1))
		{
			$sem=Sem::model()->getSemestrForGroup($gr1);
			if (empty($sem[0]) || empty($sem[1]))
			{
			
			}else
				echo CHtml::link(tt('Семестр'),'#',array('class'=>'btn btn-info btn-small','id'=>'sem-date','data-date1'=>date('d.m.Y',strtotime($sem[0])),'data-date2'=>date('d.m.Y',strtotime($sem[1]))));
			
		}
		if(!empty($teacher)){
		
		}
	}
	?>
</div>