<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 11.02.2016
 * Time: 10:45
 */

function getCellStName($st)
{
    $cell = <<<HTML
        <td  data-container="body" data-toggle="popover" data-placement="top" data-content="%s">%s</td>
HTML;
    return sprintf($cell,$st['st2'].' '.$st['st3'].' '.$st['st4'],ShortCodes::getShortName($st['st2'], $st['st3'], $st['st4']));
}
function table2Tr($date,$gr1,$st,$marks,$permLesson,$read_only,$type_lesson,$sem7,$min,$moduleNom)
{
    $ps20= PortalSettings::model()->getSettingFor(20);
    $ps55= PortalSettings::model()->getSettingFor(55);
    $ps56= PortalSettings::model()->getSettingFor(56);
    $ps60= PortalSettings::model()->getSettingFor(60);
    $ps65= PortalSettings::model()->getSettingFor(65);
    $ps88= PortalSettings::model()->getSettingFor(88);
    $show= PortalSettings::model()->getSettingFor(66);
    $ps78 = PortalSettings::model()->getSettingFor(78);

    $nom=$date['elgz3'];
    if ($st['st71']!=$sem7 &&$ps60==1)
        return '<td colspan="4"></td>';

    if (stripos($date['r2'], '11.11.1111')!==false )
        return '<td colspan="4"></td>';

    if (strtotime($date['r2']) > strtotime('now'))
        return '<td colspan="4"></td>';

    if ($ps56 == 1 && $date['elgz4']>0)
        return '<td colspan="4"></td>';

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


    if($ps78==0) {
        $date1 = new DateTime(date('Y-m-d H:i:s'));
        $date2 = new DateTime($date_lesson);
        if (!empty($ps2) && !isset($permLesson[$elgz1])) {
            $diff = $date1->diff($date2)->days;
            if ($diff > $ps2) {
                $disabled = 'disabled="disabled"';
            }
        }
    }else{

        $ps79 = PortalSettings::model()->getSettingFor(78);
        $date2 = new DateTime($date_lesson/*.' '.$rz->rz9.':'.$rz->rz10*/);
        $date2->modify('+'.$date['rz9'].' hours');
        $date2->modify('+'.$date['rz10'].' minutes');

        $date2->modify('-10 minutes');

        $date3 = new DateTime($date_lesson);
        $date3->modify('+'.$date['rz9'].' hours');
        $date3->modify('+'.$date['rz10'].' minutes');
        $date3->modify('+' . $ps79 . ' minutes');

        $date1 = new DateTime(date('Y-m-d H:i:s'));
        if ($date1 < $date2 || $date1 > $date3) {
            $disabled = 'disabled="disabled"';
        }
    }

    if($st['st45']==1) {
        $disabled = 'disabled="disabled"';
        $dateClose = new DateTime($st['st163']);
        if($dateClose<=$date2&&$ps65==1&&$date2<=$date1&&!isset($marks[$nom]))
            Elgzst::model()->nbSt45($st,$elgz1);
    }

    $key = $nom; // 0 - r3

    if($ps88==0){
        $elgzst3 = isset($marks[$key]) && $marks[$key]['elgzst3'] != 0
            ? 'checked'
            : '';

        if($elgzst3=='checked')
            $typeCheck = 0;//небыл
        else
            $typeCheck = 1;//был
    }else{
        $elgzst3 = isset($marks[$key]) && $marks[$key]['elgzst3'] != 0
            ? ''
            : 'checked';
        if($elgzst3!='checked') {
            $typeCheck = 0;//небыл
        }
        else {
            $typeCheck = 1;//был
        }
    }

    $elgzst4 = isset($marks[$key]) && $marks[$key]['elgzst4'] != 0
        ? round($marks[$key]['elgzst4'], 1)
        : '';

    $elgzst5 = isset($marks[$key]) && $marks[$key]['elgzst5'] != 0 && $marks[$key]['elgzst5']!=-1
        ? round($marks[$key]['elgzst5'], 1)
        :( isset($marks[$key]) && $marks[$key]['elgzst5']==-1?tt('Отработано'):'');

    $disabled_input=$disabled;
    $disabled_input_1=$disabled;

    $ps29 = PortalSettings::model()->getSettingFor(29);
    if($ps29 == 1)
        $disabled_input_1 = 'disabled="disabled"';

    if($elgzst5!='')
        $disabled_input = 'disabled="disabled"';

    if($typeCheck == 0)
    {
        $disabled_input = 'disabled="disabled"';
        //$disabled_input_1 = 'disabled="disabled"';
    }else
    {
        if(!empty($elgzst4)&&$elgzst4!=''&&$elgzst4!=0)
            $disabled = 'disabled="disabled"';

        if($ps55==1&&$elgzst4==''&&$elgzst3==''&&$type_lesson==1)
        {
            if($date1>=$date2&&isset($marks[$key]['elgzst4'])) {
                $elgzst4 = round($marks[$key]['elgzst4']);
                $disabled = 'disabled="disabled"';
            }
        }
    }

    if(!$read_only)
        $elgzst3_input='<input type="checkbox" id="checkbox-'.$elgz1.'-'.$st['st1'].'-0" class="checkbox-custom" data-name="elgzst3" '.$elgzst3.' '.$disabled.'>
                    <label for="checkbox-'.$elgz1.'-'.$st['st1'].'-0" class="checkbox-custom-label"> </label>';
    else
    {
        if($typeCheck == 0)
            $elgzst3='-';
        else
            $elgzst3='+';
        $elgzst3_input='<label class="label label-warning">'.$elgzst3.'</label>';
    }
     $class_td_elgzst5 ='input-td';
    if($type_lesson==1)
    {
        if(!$read_only) {
            if ($disabled_input_1 != 'disabled="disabled"') {
                if (($typeCheck == 0) || ($elgzst4 <= $min && ($elgzst4 != 0 || ($ps55 == 1 && $elgzst4 == 0 && isset($marks[$key]['elgzst4']))))) {
                    $disabled_input_1 = '';
                } else
                    $disabled_input_1 = 'disabled="disabled"';
            }
            $elgzst4_input = '<input value="' . $elgzst4 . '" maxlength="3" type="number" data-name="elgzst4" ' . $disabled_input . '>';
            $elgzst5_input = '<input value="' . $elgzst5 . '" maxlength="3" type="number" data-name="elgzst5" ' . $disabled_input_1 . '>';
        }else
        {
            $elgzst4_input='<label class="label label-success">'.$elgzst4.'</label>';
            $elgzst5_input='<label class="label label-inverse">'.$elgzst5.'</label>';
        }
    }else
    {
        $elgzst4_input='';
        if(!$read_only) {
            if($ps29!=1) {
                if($disabled == 'disabled="disabled"') {
                    $disabled_input_1 = 'disabled="disabled"';
                }else
                    $disabled_input_1 = '';
                if($disabled_input_1 != 'disabled="disabled"') {
                    if ($typeCheck == 0)
                        $disabled_input_1 = '';
                    else
                        $disabled_input_1 = 'disabled="disabled"';
                }
                $val = $elgzst5;
                if($elgzst5!='')
                    $val = 'checked';
                $elgzst5_input = '<input type="checkbox" id="checkbox-'.$elgz1.'-'.$st['st1'].'-1" class="checkbox-custom" name="elgzst5" '.$disabled_input_1.' '.$val.'>
                                    <label for="checkbox-'.$elgz1.'-'.$st['st1'].'-1" class="checkbox-custom-label"> </label>';
                $class_td_elgzst5 ='checkbox-td';
            }else
            {
                $elgzst5_input='<label class="label label-warning">'.$elgzst5.'</label>';
            }
        }
        else
            $elgzst5_input='<label class="label label-warning">'.$elgzst5.'</label>';
    }
    if($show==0)
        $button='';
    else {
        $button = CHtml::htmlButton('<i class="glyphicon glyphicon-tag"></i>', array('class' => 'btn btn-mini btn-info btn-retake', 'style' => 'display:none'));
        $min = Elgzst::model()->getMin();
        if (!$read_only && ($typeCheck == 0|| ($elgzst4 <= $min && ($elgzst4 != 0|| ($ps55 == 1 && ($elgzst4 == 0 && isset($marks[$key]['elgzst4'])&& $type_lesson!=0)))))) {
            if (($elgzst5 <= $min&& $type_lesson==1) || ($marks[$key]['elgzst5'] != -1 && $type_lesson==0))
                $button = CHtml::htmlButton('<i class="glyphicon glyphicon-tag"></i>', array('class' => 'btn btn-mini btn-info btn-retake', 'style' => 'display:inline'));
        }
    }
    //show=Yii::app()->user->getState('showRetake',0);

    $cell = <<<HTML
    <td class="%s" data-number="{$nom}" data-priz="{$type}"  data-elgz1="{$elgz1}" data-type-lesson="{$type_lesson}" data-r1="{$r1}" data-date="{$date_lesson}">
        %s
    </td>
HTML;
    $pattern = <<<HTML
        %s %s %s %s
HTML;
    $elgzst3_input = sprintf($cell,'checkbox-td',$elgzst3_input);
    $elgzst4_input = sprintf($cell,'input-td',$elgzst4_input);
    $elgzst5_input = sprintf($cell,$class_td_elgzst5,$elgzst5_input);
    $button = sprintf($cell,'action-td',$button);
    return sprintf($pattern, $elgzst3_input, $elgzst4_input, $elgzst5_input, $button);

}

function table2TrModule($date,$gr1,$st,$ps20,$ps55,$ps56,$moduleNom,$uo1,$modules,$potoch,$sem7,$ps60)
{
    $nom=$date['elgz3'];
    if ($st['st71']!=$sem7 && $ps60 ==1)
        return '<td data-number="'.$nom.'" colspan="4"></td>';

    if (stripos($date['r2'], '11.11.1111')!==false )
        return '<td data-number="'.$nom.'" colspan="4"></td>';

    if ($ps56 == 1 && $date['elgz4']>0)
        return '<td data-number="'.$nom.'" colspan="4"></td>';

    switch($date['elgz4']){
        case 2:

            if(!isset($modules[(int)$moduleNom-1]))
                return '<td colspan="4">'.tt('Модуль не найден!').'</td>';
            else{
                $mark = Vmp::model()->getMarks($modules[(int)$nom-1]['vvmp1'],$st['st1'],$gr1);
                if(empty($mark))
                    return '<td colspan="4">'.tt('Модуль не найден!').'</td>';
                $ind = !empty($mark)?$mark['vmp6']:'';
                $itog = !empty($mark)?$mark['vmp4']:'';
                $pmk = !empty($mark)?$mark['vmp7']:'';
                return sprintf('<td  data-number="'.$nom.'">%s</td><td  data-number="'.$nom.'">%s</td><td  data-number="'.$nom.'">%s</td><td  data-number="'.$nom.'">%s</td>',$potoch,$ind,$pmk,$itog);
            }
            break;
    }
    return '<td data-number="'.$nom.'" colspan="4"></td>';
}

function getMarsForElgz3($nom,$marks){
    if(isset($marks[$nom])) {
        $m = $marks[$nom]['elgzst5'] != 0
            ? $marks[$nom]['elgzst5']
            : $marks[$nom]['elgzst4'];
        return  $m;
    }
    return 0;
}

function generateTh2($ps9,$date,$type_lesson,$ps57)
{
    $nom = $date['elgz3'];
    if($date['elgz4']>1&&$ps57==1) {
        return sprintf('
            <th data-number="'.$nom.'">%s</th>
            <th data-number="'.$nom.'">%s</th>
            <th data-number="'.$nom.'">%s</th>
            <th data-number="'.$nom.'">%s</th>',tt('Тек.'),tt('Инд.р.'),tt('ПМК'),tt('Итог.'));
    }
    return '<th data-number="'.$nom.'"></th><th data-number="'.$nom.'"></th><th data-number="'.$nom.'"></th><th data-number="'.$nom.'"></th>';
}

function generateColumnName($date,$type_lesson,$ps57, $ps59)
{
    $pattern = <<<HTML
	<th data-number="%s" data-date="%s" colspan="4" data-container="body" data-toggle="popover" data-placement="top" data-content="%s">
	    %s
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
    if($ps59==1)
        $name.= ' '.$date['k2'];
    return sprintf($pattern,$date['elgz3'],$date['formatted_date'],$date['ustem5'], $name);
}

$url       = Yii::app()->createUrl('/journal/insertStMark');
$url_check = Yii::app()->createUrl('/journal/checkCountRetake');
$minMaxUrl = Yii::app()->createUrl('/journal/insertMinMaxMark');
$urlRetake = Yii::app()->createUrl('/journal/journalRetake');

$table = <<<HTML
    <table class="table table-bordered table-condensed journal-table" data-gr1="{$gr1}" data-url="{$url}" data-url-retake="{$urlRetake}" data-url-check="{$url_check}">
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
HTML;

$sem7 = Gr::model()->getSem7ByGr1ByDate($gr1,date('d.m.Y'));
$ps59 = PortalSettings::model()->getSettingFor(59);
$ps60 = PortalSettings::model()->getSettingFor(60);
$ps65 = PortalSettings::model()->getSettingFor(65);
$ps66 = PortalSettings::model()->getSettingFor(66);

$min = Elgzst::model()->getMin();
$elgz1_arr=array();
$th = $th2 = $tr = '';
$th = '<th></th>';
$th2 ='<th></th>';

$date1 = new DateTime(date('Y-m-d H:i:s'));

/*добавлять ли не проставленые занятия*/
$ps89 = PortalSettings::model()->getSettingFor(89);

$ps78 = PortalSettings::model()->getSettingFor(78);
$ps27 = PortalSettings::model()->getSettingFor(27);

foreach($dates as $date) {
    $th .= generateColumnName($date, $model->type_lesson,$ps57,$ps59);
    $th2 .= generateTh2($ps9, $date, $model->type_lesson,$ps57);

    array_push($elgz1_arr, $date['elgz1']);
}

$permLesson=Elgr::model()->getList($gr1,$elgz1_arr);

foreach($students as $st) {
    $potoch = 0;
    $moduleNom=1;
    $st1 = $st['st1'];
    $marks = $elg->getMarksForStudent($st1);
    $tr .= '<tr data-st1="'.$st1.'" data-gr1="'.$gr1.'">';
    $tr .= getCellStName($st);
    foreach($dates as $key => $date) {

        $_nom=$date['elgz3'];
        $date2 = new DateTime($date['r2']);
        //если нет оценки добавляем ее ps89
        //костыль баг если выставить настроку ставить ноль
        if(!isset($marks[$_nom])&&$date1>=$date2&&$ps89==1){
            $elgzst = Elg::model()->addRowMark($st1,$date['elgz1'], $elg->elg4);
            $marks[$_nom] = array(
                'elgz3'=>$_nom,
                'elgzst3'=>$elgzst->elgzst3,
                'elgzst4'=>$elgzst->elgzst4,
                'elgzst5'=>$elgzst->elgzst5,
            );
        }

        if($date['elgz4']>1&&$ps57==1)
        {
            $tr .= table2TrModule($date,$gr1,$st,$ps20,$ps55,$ps56,$moduleNom,$uo1,$modules,$potoch,$sem7,$ps60);
            $potoch = 0;
            $moduleNom++;
        }else {
            $tr .= table2Tr($date, $gr1, $st, $marks, $permLesson, $read_only, $model->type_lesson,$sem7,$min,$moduleNom);
            $potoch+=getMarsForElgz3($date['elgz3'],$marks);
        }
    }
    $tr .= '</tr>';
}

echo sprintf($table, $th, $th2, $tr); // 2 table