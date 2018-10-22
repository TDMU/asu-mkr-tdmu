<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 07.02.2017
 * Time: 20:07
 */

/**
 * @var $this OtherController
 */
echo "<style>
        #studentCardItogProgress th{
            text-align: center;
        }
        #studentCardItogProgress td{
            text-align: center;
        }
        #studentCardItogProgress .text-left{
            text-align: left;
        }
</style>";

$table = <<<HTML
    <h3 class="blue header lighter tooltip-info noprint">
        <i class="icon-info-sign show-info" style="cursor:pointer"></i>
        <small>
            <i class="icon-double-angle-right"></i> %s
        </small>
    </h3>
    <table id="studentCardItogProgress%s"  class="table table-bordered table-hover table-condensed">
        <thead>
                %s
        </thead>
        <tbody>
            %s
        </tbody>
    </table>
HTML;

$isFarm = $this->universityCode == U_FARM;

/*Учитовать мин-макс или нет*/
$ps9 = PortalSettings::model()->findByPk(9)->ps2;

$th = $tr = $th2 = $tr2 = '';
//заголовок для таблицы с пз
$th.='<tr>';
$th.='<th>'.tt('№ пп').'</th>';
$th.='<th>'.tt('Дисциплина').'</th>';
if($ps9) {
    $th .= '<th>' . tt('Мин. бал') . '</th>';
    $th .= '<th>' . tt('Макс. бал') . '</th>';
}
$th.='<th>'.tt('Количество балов').'</th>';
$th.='<th>'.tt('Количество прошедших занятий').'</th>';
$th.='<th>'.tt('Количество пропусков').'</th>';
if(!$isFarm)
    $th.='<th>'.tt('Количество уваж. пропусков').'</th>';
$th.='</tr>';
//Загловок для таблицы с лк
$th2.='<tr>';
$th2.='<th>'.tt('№ пп').'</th>';
$th2.='<th>'.tt('Дисциплина').'</th>';
$th2.='<th>'.tt('Количество прошедших занятий').'</th>';
$th2.='<th>'.tt('Количество пропусков').'</th>';
if(!$isFarm)
    $th2.='<th>'.tt('Количество уваж. пропусков').'</th>';
$th2.='</tr>';

$i = $i2 =1;
foreach($disciplines as $discipline)
{
    if($discipline['type_journal']==0)
        $type = 0;
    else
        $type = 1;

    $statistic = Elg::model()->getItogProgressInfo($discipline['uo1'],$discipline['sem1'],$type,$st->st1, $discipline['ucgn2']);

    $error = '';
    if($type == 1 && $ps9){
        if($statistic['min']>=$statistic['bal']){
            $error = 'class="error"';
        }
    }

    $row="<tr {$error}>";
    $row.='<td>'.($type == 1 ? $i : $i2).'</td>';

        if(!empty($discipline['d27'])&&Yii::app()->language=="en")
            $d2=$discipline['d27'];
        else
            $d2=$discipline['d2'];


        $row.='<td class="text-left">'.$d2.'</td>';

        if($type == 1) {
            if ($ps9) {
                $row .= '<td>' . $statistic['min'] . '</td>';
                $row .= '<td>' . $statistic['max'] . '</td>';
            }
            $row .= '<td>' . $statistic['bal'] . '</td>';
        }
        $row.='<td>'. $statistic['countLesson'].'</td>';
        $row.='<td>'.$statistic['countOmissions'].'</td>';
        if(!$isFarm)
            $row.='<td>'.$statistic['countOmissions1'].'</td>';

    $row.='</tr>';


    if($type==1) {
        $i++;
        $tr.=$row;
    }else{
        $i2++;
        $tr2.=$row;
    }

}


$_infoText = tt('Практические занятия');

echo sprintf($table, $_infoText,'PZ',$th,$tr);

$_infoText = tt('Лекционные занятий');

echo sprintf($table, $_infoText,'LK',$th2,$tr2);
