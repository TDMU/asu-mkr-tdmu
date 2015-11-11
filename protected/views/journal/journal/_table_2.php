<?php
function table2Tr($date,$gr1,$st,$marks,$permLesson,$read_only,$type_lesson)
{
    if (stripos($date['r2'], '11.11.1111')!==false )
        return '<td colspan="2"></td>';

    if (strtotime($date['r2']) > strtotime('now'))
        return '<td colspan="2"></td>';

    $r1  = $date['r1'];
    $ps2 = PortalSettings::model()->getSettingFor(27);
    $nom=$date['elgz3'];
    $elgz1=$date['elgz1'];
    $date_lesson=$date['r2'];
    $type=$date['elgz4'];

    $disabled = null;

    if(isset($permLesson[$elgz1]))
        if(strtotime($permLesson[$elgz1]) <= strtotime('yesterday'))
            $disabled = 'disabled="disabled"';
        else
            $disabled = '';

    if (! empty($ps2)&&!isset($permLesson[$elgz1])) {
        $date1  = new DateTime(date('Y-m-d H:i:s'));
        $date2  = new DateTime($date_lesson);
        $diff = $date1->diff($date2)->days;
        if ($diff > $ps2)
        {

            $disabled = 'disabled="disabled"';
        }
    }

    if($st['st45']==1)
        $disabled = 'disabled="disabled"';

    $key = $nom; // 0 - r3

    $elgzst3 = isset($marks[$key]) && $marks[$key]['elgzst3'] != 0
        ? 'checked'
        : '';

    $elgzst4 = isset($marks[$key]) && $marks[$key]['elgzst4'] != 0
        ? round($marks[$key]['elgzst4'], 1)
        : '';

    $elgzst5 = isset($marks[$key]) && $marks[$key]['elgzst5'] != 0 && $marks[$key]['elgzst5']!=-1
        ? round($marks[$key]['elgzst5'], 1)
        :( isset($marks[$key]) && $marks[$key]['elgzst5']==-1?tt('Отработано'):'');

    $class_1='';
    $class_2='';
    $disabled_input=$disabled;
    $disabled_input_1=$disabled;
    if($disabled != 'disabled="disabled"')
    {
        if($elgzst4=='')
            $class_1 = 'class="not-value"';
        if($elgzst5=='')
            $class_2 = 'class="not-value"';
    }
    if($elgzst3=='checked')
    {
        $tooltip=tt('Отсутсвует');
        $disabled_input = 'disabled="disabled"';
        $disabled_input_1 = 'disabled="disabled"';
    }else
    {
        $tooltip=tt('Присутсвует');
        if(!empty($elgzst4)&&$elgzst4!=''&&$elgzst4!=0)
            $disabled = 'disabled="disabled"';
    }

    if(PortalSettings::model()->findByPk(29)->ps2==1)
        $disabled_input_1 = 'disabled="disabled"';

    if($elgzst5!='')
        $disabled_input = 'disabled="disabled"';

    if(!$read_only)
        $elgzst3_input='<input type="checkbox" data-toggle="tooltip" data-placement="right" data-original-title="'.$tooltip.'" '.$elgzst3.' data-name="elgzst3" '.$disabled.'>';
    else
    {
        if($elgzst3=='checked')
            $elgzst3='-';
        else
            $elgzst3='+';
        $elgzst3_input='<label class="label label-warning">'.$elgzst3.'</label>';
    }
    if($type_lesson==1)
    {
        if(!$read_only){
            $elgzst4_input='<input value="'.$elgzst4.'" '.$class_1.' maxlength="3" data-name="elgzst4" '.$disabled_input.'>';
            $elgzst5_input='<input value="'.$elgzst5.'" '.$class_2.' maxlength="3" data-name="elgzst5" '.$disabled_input_1.'>';
        }else
        {
            $elgzst4_input='<label class="label label-success">'.$elgzst4.'</label>';
            $elgzst5_input='<label class="label label-inverse">'.$elgzst5.'</label>';
        }
    }else
    {
        $elgzst4_input='';
        $elgzst5_input='<label class="label label-warning">'.$elgzst5.'</label>';
    }

    $button=CHtml::htmlButton('<i class="icon-tag"></i>',array('class'=>'btn btn-mini btn-info btn-retake','style'=>'display:none'));
    $min =Elgzst::model()->getMin();
    if(!$read_only&&($elgzst3=='checked'||$elgzst4<=$min&&$elgzst4!=0))
    {
        if($elgzst5<=$min&&$elgzst5!=-1)
            $button=CHtml::htmlButton('<i class="icon-tag"></i>',array('class'=>'btn btn-mini btn-info btn-retake','style'=>'display:inline'));
    }
    $show=Yii::app()->user->getState('showRetake',0);
    if($show==0)
        $button='';
    $pattern= <<<HTML
        <td colspan="2" data-nom="{$nom}" data-priz="{$type}"  data-elgz1="{$elgz1}" data-type-lesson="{$type_lesson}" data-r1="{$r1}" data-date="{$date_lesson}" data-gr1="{$gr1}">
            %s %s %s %s
        </td>
HTML;

    return sprintf($pattern, $elgzst3_input,$elgzst4_input,$elgzst5_input,$button);

}

function generateTh2($ps9,$date,$type_lesson)
{
    if ($ps9 == '0')
        return '<th></th><th></th>';
    if ($type_lesson == '0')
        return '<th></th><th></th>';
    $elgz5='';
    $elgz6='';
    if($date['elgz5']>0)
        $elgz5=round($date['elgz5'],1);
    if($date['elgz6']>0)
        $elgz6=round($date['elgz6'],1);

    $pattern = <<<HTML
<th><input value="{$elgz5}" maxlength="3" placeholder="min" data-name="elgz5" data-elgz1="{$date['elgz1']}"></th>
<th><input value="{$elgz6}" maxlength="3" placeholder="max" data-name="elgz6" data-elgz1="{$date['elgz1']}"></th>
HTML;

    return sprintf($pattern);
}

function generateColumnName($date,$type_lesson)
{
    $pattern = <<<HTML
	<th colspan="2">
	    <i class="icon-hand-right icon-animated-hand-pointer blue"></i>
        <span data-rel="popover" data-placement="top" data-content="%s" class="green">%s</span>
    </th>
HTML;

    switch($date['elgz4'])
    {
        case '0':
            $type='';
            break;
        case '1':
            $type=tt('Субмодуль');
            break;
        case '2':
            $type=tt('ПМК');
            break;
        default:
            $type='';
    }
    $type=' '.$type;
    $us4=SH::convertUS4(1);
    if($type_lesson!=0)
    $us4=SH::convertUS4($date['us4']);
    $name = '№'.$date['elgz3'].' '.$date['formatted_date'].' '.$us4.$type;

    return sprintf($pattern,$date['ustem5'], $name);
}

function countMarkTotal($marks)
{
    $total = 0;
    foreach ($marks as $mark) {
        $total += $mark['elgzst5'] != 0
            ? $mark['elgzst5']
            : $mark['elgzst4'];
    }
    return $total;
}


    $url       = Yii::app()->createUrl('/journal/insertStMark');
    $url_check = Yii::app()->createUrl('/journal/checkCountRetake');
    $minMaxUrl = Yii::app()->createUrl('/journal/insertMinMaxMark');
    $urlRetake = Yii::app()->createUrl('/journal/journalRetake');

    $table = <<<HTML
<div class="{$classTable2}" data-ps33="{$ps33}" data-gr1="{$gr1}" data-url="{$url}" data-url-retake="{$urlRetake}" data-url-check="{$url_check}">
    <table class="table table-striped table-bordered table-hover journal_table">
        <thead>
            <tr>
                %s
            </tr>
            <tr class="min-max" data-url="{$minMaxUrl}">
                %s
            </tr>
        </thead>
        <tbody>
            %s
        </tbody>
    </table>
</div>
HTML;



    $elgz1_arr=array();
    $th = $th2 = $tr = '';

    global $total_1;
    global $count_dates;
    $count_dates=0;
    //$column = 1;
    foreach($dates as $date) {
        $th    .= generateColumnName($date,$model->type_lesson);
        $th2   .= generateTh2($ps9,$date,$model->type_lesson);
        array_push($elgz1_arr,$date['elgz1']);
        //$column++;
        $count_dates++;
    }

    $permLesson=Elgr::model()->getList($gr1,$elgz1_arr);

    foreach($students as $st) {
        $st1 = $st['st1'];
        $marks = $elg->getMarksForStudent($st1);
        $tr .= '<tr data-st1="'.$st1.'">';
        $total_1[$st1] = countMarkTotal($marks);
        foreach($dates as $key => $date) {
            $tr .= table2Tr($date,$gr1,$st,$marks,$permLesson,$read_only,$model->type_lesson);
        }
        $tr .= '</tr>';
    }

    echo sprintf($table, $th, $th2, $tr); // 2 table

