<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 09.12.2015
 * Time: 8:48
 */

echo "<style>
        #studentCardRetake th{
            text-align: center;
        }
        #studentCardRetake td{
            text-align: center;
        }
        #studentCardRetake .text-left{
            text-align: left;
        }
</style>";
 $disciplines = Elg::model()->getDispBySt($st->st1);

 $table = <<<HTML
    <table id="studentCardRetake" class="table table-bordered table-hover table-condensed">
        <thead>
                %s
        </thead>
        <tbody>
            %s
        </tbody>
    </table>
HTML;

    $th = $tr = '';
        $th.='<tr>';
        $th.='<th rowspan="2">'.tt('№ пп').'</th>';
        $th.='<th rowspan="2">'.tt('Кафедра').'</th>';
        $th.='<th rowspan="2">'.tt('Дисциплина').'</th>';
        $th.='<th rowspan="2">'.tt('Тип занятий').'</th>';
        //$th.='<th rowspan="2">'.tt('Общее к-во часов').'</th>';
        $th.='<th rowspan="2">'.tt('Общее к-во занятий').'</th>';
        $th.='<th colspan="2">'.tt('Количество пропусков').'</th>';
        $th.='<th rowspan="2">'.tt('К-во "2"').'</th>';
        $th.='<th colspan="2">'.tt('К-во отработанных занятий').'</th>';
        $th.='<th rowspan="2">'.tt('% задолжености').'</th>';
    $th.='</tr>';
    $th.='<tr>';
        $th.='<th>'.tt('Уваж.').'</th>';
        $th.='<th>'.tt('Неув.').'</th>';
        $th.='<th>'.tt('"нб"').'</th>';
        $th.='<th>'.tt('"2"').'</th>';
    $th.='</tr>';

    $i=1;
    foreach($disciplines as $discipline)
    {
        $type=0;
        if($discipline['us4']>1)
            $type=1;
        list($respectful,$disrespectful,$f,$nbretake,$fretake,$count) = Elg::model()->getRetakeInfo($discipline['uo1'],$discipline['sem1'],$type,$st->st1);
        $tr.='<tr>';
            $tr.='<td>'.$i.'</td>';
            $tr.='<td class="text-left">'.$discipline['k2'].'</td>';
            if(!empty($discipline['d27'])&&Yii::app()->language=="en")
                $d2=$discipline['d27'];
            else
                $d2=$discipline['d2'];
            $tr.='<td class="text-left">'.$d2.'</td>';
            $tr.='<td>'.SH::convertUS4($discipline['us4']).'</td>';
            //$tr.='<td>'.round($discipline['us6'],2).'</td>';
            $tr.='<td>'.$count.'</td>';
            $tr.='<td>'.$respectful.'</td>';
            $tr.='<td>'.$disrespectful.'</td>';
            $tr.='<td>'.$f.'</td>';
            $tr.='<td>'.$nbretake.'</td>';
            $tr.='<td>'.$fretake.'</td>';
            if($count!=0)
                $proc = round((($respectful+$disrespectful-$nbretake)+($f-$fretake))/$count*100);
            else
                $proc=0;
            $tr.='<td>'.$proc.'</td>';
        $tr.='</tr>';
        $i++;
    }

    echo sprintf($table,$th,$tr);
