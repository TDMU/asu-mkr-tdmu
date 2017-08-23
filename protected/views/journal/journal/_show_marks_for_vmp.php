<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 24.02.2017
 * Time: 15:16
 */
/**
 * @param $marks array оценки
 * @param $year int год
 * @param $sem int семетр
 * @return string
 */
function fillMarks($marks, $year, $sem){
    $html = '';
    $html.='<tr>';
    $html.='<th colspan="6" style="align-content: center; vertical-align: middle; text-align: center"><label class="label label-info">'.$year.' '.SH::convertSem5($sem).'</label></th>';
    $html.='</tr>';

    foreach($marks as $mark){
        $html.='<tr>';
            $html.='<td>'.$mark['elgz3'].'</td>';
            $html.='<td>'.date('Y-m-d',strtotime( $mark['r2'])).'</td>';
            $html.='<td>'.SH::convertUS4($mark['us4']).'</td>';
            //$html.='<td>'.Ustem::model()->getUstem6Value($mark['elgz4']).'</td>';

                $elgzst3 = $mark['elgzst3'] != 0
                    ? 'checked'
                    : '';

                $elgzst4 = round($mark['elgzst4'], 1);

                $ps55 = PortalSettings::model()->getSettingFor(55);

                if(($elgzst4==0&&$ps55!=1)||($elgzst3=='checked')){
                    $elgzst4='';
                }
                $elgzst5 = $mark['elgzst5'] != 0 && $mark['elgzst5']!=-1
                    ? round($mark['elgzst5'], 1)
                    :($mark['elgzst5']==-1?tt('Отработано'):'');

                if($elgzst3=='checked')
                    $elgzst3='-';
                else
                    $elgzst3='+';

                $elgzst3_input='<label class="label label-warning">'.$elgzst3.'</label>';

                if($mark['us4']!=1)
                {
                    $elgzst4_input='<label class="label label-success">'.$elgzst4.'</label>';
                    $elgzst5_input='<label class="label label-inverse">'.$elgzst5.'</label>';
                }else
                {
                    $elgzst4_input='';
                    $elgzst5_input = '<label class="label label-inverse">' . $elgzst5 . '</label>';
                }

            $html.='<td>'.$elgzst3_input.'</td>';
            $html.='<td>'.$elgzst4_input.'</td>';
            $html.='<td>'.$elgzst5_input.'</td>';
        $html.='</tr>';
    }

    return $html;
}

$thead = $tbody = '';

$table = <<<HTML
    <table class="table table-hover table-condensed">
        <thead>
            %s
        </thead>
        <tbody>
            %s
        </tbody>
    </table>
HTML;

$nomLessonStr =tt('Номер занятия');
$dateLessonStr =tt('Дата занятия');
$typeLessonStr =tt('Тип занятия');
//$typeLessonStr =tt('Тип');
$elgzst3Str =tt('Посещение');
$elgzst4Str =tt('Оценка');
$elgzst5Str =tt('Отработка');

$thead = <<<HTML
    <tr>
        <th>{$nomLessonStr}</th>
        <th>{$dateLessonStr}</th>
        <th>{$typeLessonStr}</th>
        <th>{$elgzst3Str}</th>
        <th>{$elgzst4Str}</th>
        <th>{$elgzst5Str}</th>
    </tr>
HTML;

if(isset($marksArray['prev']))
{
    $prev = $marksArray['prev'];
    $tbody.= fillMarks($prev['marks'],$prev['year'],$prev['sem']);
}

$current = $marksArray['current'];
$tbody.= fillMarks($current['marks'],$current['year'],$current['sem']);
$title = tt('Просмотр оценок для расчета ПМК');
echo sprintf($table, $thead, $tbody);