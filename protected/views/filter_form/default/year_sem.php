
<form class="form-inline" id="filter-year-sem-form" method="POST">
    <?php
        $previousYear2 = date('Y', strtotime('-3 year'));
        $previousYear1 = date('Y', strtotime('-2 year'));
        $previousYear = date('Y', strtotime('-1 year'));
        $currentYear  = date('Y');
        $nextYear     = date('Y', strtotime('+1 year'));
		$nextYear1     = date('Y', strtotime('+2 year'));
		
        $options = array(
            $previousYear2 => $previousYear2.'/'.$previousYear1,
            $previousYear1 => $previousYear1.'/'.$previousYear,
            $previousYear => $previousYear.'/'.$currentYear,
            $currentYear  => $currentYear.'/'.$nextYear,
            $nextYear     => $nextYear.'/'.$nextYear1,
        );
		
        echo CHtml::dropDownList('year', Yii::app()->session['year'], $options, array('class'=>'input-small','style'=>'width:130px'));
	
		//echo CHtml::dropDownList('year', '2014', $options, array('class'=>'input-small'));
        $options = array(
            tt('Осенний'),
            tt('Весенний')
        );
        echo CHtml::dropDownList('sem', Yii::app()->session['sem'], $options, array('class'=>'input-medium'));

    /*<button class="btn btn-info btn-small" type="submit">
        <i class="icon-ok bigger-110"></i>
        <?=tt('Ок')?>
    </button>*/
    ?>
</form>