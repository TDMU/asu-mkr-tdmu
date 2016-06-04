<?php
function getTotal1($total_1,$count_dates,$ps44){
    $value = '';
    switch($ps44){
        case 0:
            $value = $total_1;
            break;
        case 1:
            if($count_dates!=0)
                $value = round($total_1/$count_dates * 12);
            else
                $value=0;
            break;
        case 2:
            if($count_dates!=0)
                $value = round($total_1/$count_dates);
            else
                $value=0;
            break;
    }
    return $value;
}

function table3Tr($column, $marks,$st1,$elg1,$gr1)
{
    $tr= <<<HTML
<td><input class="elgdst-input" value="%s" data-name="%s" data-gr1="%s" maxlength="3"></td>
HTML;

    $tr1 = <<<HTML
        <td>%s</td>
HTML;
    //list($field, $name) = $column;
    $key=$column['elgd0'];

    $mark = isset($marks[$key]) && $marks[$key] != 0
        ? round($marks[$key], 1)
        : '';
    $edit = true;

    switch($column['elgsd4']){
        case 3:case 4:case 5:
            $edit = false;
            break;
        default:
            $edit = true;
            break;
    }

    if($edit)
        return sprintf($tr, $mark, $key,$gr1);
    else
        return sprintf($tr1, $mark);
}
function generate3Th($key)
{
    $pattern_tr = <<<HTML
	<th>
        <span class="blue">%s</span>
    </th>
HTML;
    return sprintf($pattern_tr,$key['elgsd2']);
}

function generate3Th2($key)
{
    return '<th></th>';
}

function generateTotal1Header()
{
    $pattern = <<<HTML
        <th colspan="2" class="blue">%s</th>
HTML;
    $name = tt('Итого');

    return sprintf($pattern, $name);
}

function countDopTotal($marks)
{
    $total = 0;
    foreach ($marks as $mark) {
        $total += $mark!=0?$mark:0;
    }

    return $total;
}

    $elg1=$elg->elg1;
    $url = Yii::app()->createUrl('/journal/insertDopMark');

    $table = <<<HTML
<div class="journal_div_table3">
    <table class="table table-striped table-bordered table-hover journal_table"  data-url="{$url}" data-elg1="{$elg1}">
        <thead>
            <tr>
                %s
            </tr>
            <tr class="min-max">
                %s
            </tr>
        </thead>
        <tbody>
            %s
        </tbody>
    </table>
</div>
HTML;

$th=$th2=$tr='';

global $total_1, $total_count_1;// calculating in journal/table_2
global $count_dates;

$total_2 =array();

$ps83 = PortalSettings::model()->findByPk(83)->ps2;
$ps85 = PortalSettings::model()->findByPk(85)->ps2;
if($ps83==0) {
    $th  .= generateTotal1Header();
    $th2.='<th colspan="2"></th>';
}

foreach($elgd as $key)
{
    $th.=generate3Th($key);
    $th2.='<th></th>';
}
if($ps85==0) {
    $th .= '<th>' . tt('Всего') . '</th>';
    $th2 .= '<th></th>';
}

$ps84 = PortalSettings::model()->findByPk(84)->ps2;
$sem = Sem::model()->findByPk($model->sem1);

foreach ($students as $st) {
    $st1 = $st['st1'];
    $val=  getTotal1($total_1[$st1],$total_count_1[$st1],$ps44);
    $marks=Elgdst::model()->getMarksForStudent($st1,$elg1);
    $total_2[$st1] = $val+countDopTotal($marks);
    $tr.='<tr data-st1="'.$st1.'">';
    $bal='';
    if($ps44==1){
        if($total_count_1[$st1]!=0)
            $bal = round($total_1[$st1]/$total_count_1[$st1],2);
        else
            $bal=0;
    }
    if($ps44==2){
        if($total_count_1[$st1]!=0)
            $bal = round($total_1[$st1]/$total_count_1[$st1],2);
        else
            $bal=0;
    }
    $sr = ($bal!='')?'('.$bal.')':'';
    if($ps83==0) {
        $tr .= '<td data-total=1 colspan="2">' . $val . $sr . '</td>'; // total 1
    }
    foreach($elgd as $key)
    {
        $tr .= table3Tr($key, $marks, $st1,$elg1,$gr1);
    }
    if($ps85==0) {
        if ($ps84 == 0)
            $tr .= '<td data-total=2>' . $total_2[$st1] . '</td>'; // total 2
        else {
            $mark = Stus::model()->getMarkForDisp($st1, $model->discipline, $sem->sem7);
            if (!empty($mark)) {
                $bal = '';
                $bal = $mark['stus3'];
                $tr .= '<td data-stus="' . $mark['stus0'] . '" data-total=2>' . $bal . '</td>';
            } else {
                $tr .= '<td data-total=2></td>'; // total 2
            }
        }
    }
    $tr .= '</tr>';
}
echo sprintf($table,$th,$th2,$tr); // 3 table