
<form class="form-horizontal row" method="POST">
    <?php
        $previousYear = date('Y', strtotime('-1 year'));
        $currentYear  = date('Y');
        $nextYear     = date('Y', strtotime('+1 year'));
		$nextYear1     = date('Y', strtotime('+2 year'));
		
        $options = array(
            $previousYear => $previousYear.'/'.$currentYear,
            $currentYear  => $currentYear.'/'.$nextYear,
            $nextYear     => $nextYear.'/'.$nextYear1,
        );
		?>
        <div class="select-group col-xs-6">
        <?php
            echo CHtml::dropDownList('year', Yii::app()->session['year'], $options, array('class'=>'cs-select cs-skin-elastic'));
        ?>
        </div>
        <div class="select-group col-xs-6">
        <?php
            $options = array(
                tt('Осенний'),
                tt('Весенний')
            );
            echo CHtml::dropDownList('sem', Yii::app()->session['sem'], $options, array('class'=>'cs-select cs-skin-elastic'));
        ?>
        </div>
    <div class="col-xs-6 form-actions">
        <button class="btn btn-info btn-small" type="submit"><i class="glyphicon glyphicon-ok"></i> <?=tt('Выбрать')?></button>
    </div>
    <div class="col-xs-6 form-actions" id="btn-refresh">
        <button class="btn btn-primary btn-refresh btn-small" type="submit"><i class="glyphicon glyphicon-refresh"></i><?=tt('Обновить')?></button>
    </div>
</form>