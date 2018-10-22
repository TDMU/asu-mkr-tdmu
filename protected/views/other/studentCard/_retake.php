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

 $urlShow = Yii::app()->createUrl('/other/showRetake');
 $table = <<<HTML
    <table id="studentCardRetake" data-url-retake="{$urlShow}" class="table table-bordered table-hover table-condensed">
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
        $th.='<th rowspan="2">'.tt('% задолженности').'</th>';
        $th.='<th rowspan="2">'.tt('').'</th>';
    $th.='</tr>';
    $th.='<tr>';
        $th.='<th>'.tt('Уваж.').'</th>';
        $th.='<th>'.tt('Неув.').'</th>';
        $th.='<th>'.tt('"нб"').'</th>';
        $th.='<th>'.tt('"2"').'</th>';
    $th.='</tr>';

    $i=1;

    $ps55=PortalSettings::model()->findByPk(55)->ps2;
    foreach($disciplines as $discipline)
    {
        $type=$discipline['type_journal'];
        list($respectful,$disrespectful,$f,$nbretake,$fretake,$count) = Elg::model()->getRetakeInfo($discipline['uo1'],$discipline['sem1'],$type,$st->st1,$ps55);
        $tr.='<tr>';
            $tr.='<td>'.$i.'</td>';
            if(!empty($discipline['k15'])&&Yii::app()->language=="en")
                $k2=$discipline['k15'];
            else
                $k2=$discipline['k2'];
            $tr.='<td class="text-left">'.$k2.'</td>';

            if(!empty($discipline['d27'])&&Yii::app()->language=="en")
                $d2=$discipline['d27'];
            else
                $d2=$discipline['d2'];

            $tr.='<td class="text-left">'.$d2.' | '.tt("Семестр").' №'.$discipline['sem7'].'</td>';
            $tr.='<td>'.SH::convertTypeJournal($discipline['type_journal']).'</td>';
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
            $tr.='<td>'.
                sprintf(
                '<a class="btn btn-minier btn-info tooltip-info btn-retake"
                        data-rel="tooltip"
                        data-placement="bottom"
                        data-original-title="Просмотр"
                        data-uo1="%s"
                        data-sem1="%s"
                        data-type="%s"
                        data-st1="%s"
                        data-gr1="%s">
                    <i class="icon-eye-open"></i>
                </a>',$discipline['uo1'],$discipline['sem1'],$type,$st->st1,$gr1).'</td>';
        $tr.='</tr>';
        $i++;
    }

    echo sprintf($table,$th,$tr);
