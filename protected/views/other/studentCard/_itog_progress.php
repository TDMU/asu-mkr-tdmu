<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 07.02.2017
 * Time: 20:07
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

$urlShow = Yii::app()->createUrl('/other/showRetake');

$_infoText = tt('Лекционные занятий не учитываються!');
echo <<<HTML
    <h3 class="blue header lighter tooltip-info noprint">
    <i class="icon-info-sign show-info" style="cursor:pointer"></i>
    <small>
        <i class="icon-double-angle-right"></i> $_infoText
    </small>
</h3>
HTML;

$table = <<<HTML
    <table id="studentCardItogProgress" data-url-retake="{$urlShow}" class="table table-bordered table-hover table-condensed">
        <thead>
                %s
        </thead>
        <tbody>
            %s
        </tbody>
    </table>
HTML;

/*Учитовать мин-макс или нет*/
$ps9 = PortalSettings::model()->findByPk(9)->ps2;

$th = $tr = '';
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
$th.='<th>'.tt('Количество уваж. пропусков').'</th>';
$th.='</tr>';
$i=1;
foreach($disciplines as $discipline)
{
    if($discipline['type_journal']==0)
        continue;

    $type=1;

    $tr.='<tr>';
        $tr.='<td>'.$i.'</td>';

        if(!empty($discipline['d27'])&&Yii::app()->language=="en")
            $d2=$discipline['d27'];
        else
            $d2=$discipline['d2'];

        $tr.='<td class="text-left">'.$d2.'</td>';

        $statistic = Elg::model()->getItogProgressInfo($discipline['uo1'],$discipline['sem1'],$type,$st->st1, $discipline['ucgn2']);

        if($ps9) {
            $tr .= '<td>' . $statistic['min'] . '</td>';
            $tr .= '<td>' . $statistic['max'] . '</td>';
        }
        $tr.='<td>'. $statistic['bal'].'</td>';
        $tr.='<td>'. $statistic['countLesson'].'</td>';
        $tr.='<td>'.$statistic['countOmissions'].'</td>';
        $tr.='<td>'.$statistic['countOmissions1'].'</td>';
    $tr.='</tr>';
    $i++;

}

echo sprintf($table,$th,$tr);
