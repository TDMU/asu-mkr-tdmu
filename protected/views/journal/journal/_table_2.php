<?php
/**
 * @var $this JournalController
 */

/**
 * @param $uo1
 * @param $gr1
 * @param $st1
 * @return string
 */

function table2TrItog($uo1, $gr1, $st1){
    $vmp = Vmp::model()->getVedItog($uo1, $gr1, 98, $st1);

    if(!empty($vmp)){
        $ind = round($vmp['vmp6'],2);
        $pot = round($vmp['vmp5'],2);
        $itog = round($vmp['vmp4'],2);
        $pmk = round($vmp['vmp7'],2);

        switch($pmk){
            case '-4':
                $pmk = tt('н/я');
                break;
            case '-3':
                $pmk = '0';
                break;
            case '-2':
                $pmk = tt('неув');
                break;
            case '-1':
                $pmk = tt('уваж');
                break;
        }

        return sprintf('<td>%s</td><td>%s</td><td>%s</td><td>%s</td>',$pot,$ind,$pmk,$itog);

    }else{
        return '<td colspan="4"></td>';
    }
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
function table2Tr($date,$gr1,$st,$marks,$permLesson,$read_only,$type_lesson,$ps20,$ps55,$ps56,$sem7,$ps60,$min,$ps65,$show,$moduleNom,$ps88, $isStd)
{
    if (($st['st71']!=$sem7&&$st['st71']!=$sem7+1) &&$ps60==1) {
        $read_only = true;
    }

    if ($st['st167']==1) {
        $read_only = true;
    }
        //return '<td colspan="2"></td>';

    if (stripos($date['r2'], '11.11.1111')!==false )
        return '<td colspan="2"></td>';

    $dateCurrLesson = strtotime($date['r2']);
    if ($dateCurrLesson > strtotime('now')) {
        if(!empty($st['elgvst2'])&&!empty($st['elgvst3']))
        {
            if(!($st['elgvst2']<=$dateCurrLesson&&$st['elgvst3']>=$dateCurrLesson)){
                return '<td colspan="2"></td>';
            }
        }
        else
            return '<td colspan="2"></td>';
    }

    if ($ps56 == 1 && $date['elgz4']>0)
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

    $ps78 = PortalSettings::model()->getSettingFor(78);
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

        $ps79 = PortalSettings::model()->getSettingFor(79);
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

    /*if($st['st167']==1)
    {
        if($ps65==1&&$date2<=$date1&&!isset($marks[$nom]))
            Elgzst::model()->nbSt45($st,$elgz1);
    }*/

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
        ? round($marks[$key]['elgzst4'], 2)
        : '';

    $isset = isset($marks[$key]);
    $elgzst5 = $isset && $marks[$key]['elgzst5'] != 0 && $marks[$key]['elgzst5']!=-1
        ?
            round($marks[$key]['elgzst5'], 2)
        :
            (
                $isset && $marks[$key]['elgzst5']==-1
                ?
                tt('Отработано'):
                    (
                        $isset&&isset($marks[$key]['elgotr0'])&&$marks[$key]['elgotr0']>0?round($marks[$key]['elgzst5'], 1):''
                    )
            );




    $class_1='';
    $class_2='';
    $disabled_input=$disabled;
    $disabled_input_1=$disabled;
    if($disabled != 'disabled="disabled"')
    {
        if($elgzst4=='')
            $class_1 = 'class="not-value"';
        if($elgzst5==='')
            $class_2 = 'class="not-value"';
    }


    $ps29 = PortalSettings::model()->getSettingFor(29);
    if($ps29 == 1)
        $disabled_input_1 = 'disabled="disabled"';


    if($elgzst5!=='')
        $disabled_input = 'disabled="disabled"';

    if($typeCheck == 0)
    {
        $tooltip=tt('Отсутсвует');
        $disabled_input = 'disabled="disabled"';
        //$disabled_input_1 = 'disabled="disabled"';
    }else
    {
        $tooltip=tt('Присутсвует');
        if(!empty($elgzst4)&&$elgzst4!=''&&$elgzst4!=0)
            $disabled = 'disabled="disabled"';

        if($ps55==1&&$elgzst4==''&&$elgzst3==''&&$type_lesson==1)
        {
            if($date1>=$date2&&isset($marks[$key]['elgzst4'])) {
                $elgzst4 = round($marks[$key]['elgzst4']);
                $class_1 = '';
                $disabled = 'disabled="disabled"';
            }
        }
    }

    if(!$read_only)
        $elgzst3_input='<input type="checkbox" data-toggle="tooltip" data-module-nom="'.$moduleNom.'-'.$st['st1'].'" data-placement="right" data-original-title="'.$tooltip.'" '.$elgzst3.' data-name="elgzst3" '.$disabled.'>';
    else
    {
        if($typeCheck == 0)
            $elgzst3='-';
        else
            $elgzst3='+';
        $elgzst3_input='<label class="label label-warning">'.$elgzst3.'</label>';
    }
    /*костыль для блокировки ввода оценок по занятимя*/
    if($isStd)
        $read_only = true;

    if($type_lesson==1)
    {
        if(!$read_only){

            if($disabled_input_1 != 'disabled="disabled"') {
                if (($typeCheck == 0)||($elgzst4 <= $min && ($elgzst4 != 0 || ($ps55 == 1 && $elgzst4 == 0 && isset($marks[$key]['elgzst4']))))) {
                    $disabled_input_1 = '';
                }
                else
                    $disabled_input_1 = 'disabled="disabled"';
            }
            $elgzst4_input='<input value="'.$elgzst4.'" '.$class_1.' maxlength="5" data-module-nom="'.$moduleNom.'-'.$st['st1'].'" data-name="elgzst4" '.$disabled_input.'>';
            $elgzst5_input='<input value="'.$elgzst5.'" '.$class_2.' maxlength="5" data-module-nom="'.$moduleNom.'-'.$st['st1'].'" data-name="elgzst5" '.$disabled_input_1.'>';
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
                $elgzst5_input = '<input type="checkbox" '.$val.' data-module-nom="'.$moduleNom.'-'.$st['st1'].'" data-name="elgzst5" '.$disabled_input_1.'>';
            }else
            {
                $elgzst5_input='<label class="label label-warning">'.$elgzst5.'</label>';
            }
        }
        else
            $elgzst5_input='<label class="label label-warning">'.$elgzst5.'</label>';
    }
    if($show==0||$isStd)
        $button='';
    else {
        $button = CHtml::htmlButton('<i class="icon-tag"></i>', array('class' => 'btn btn-mini btn-info btn-retake', 'data-module-nom'=>$moduleNom.'-'.$st['st1'], 'style' => 'display:none'));
        $min = Elgzst::model()->getMin();
        if (!$read_only && ($typeCheck == 0 || ($elgzst4 <= $min && ($elgzst4 != 0|| ($ps55 == 1 && ($elgzst4 == 0 && isset($marks[$key]['elgzst4'])&& $type_lesson!=0)))))) {
            if (($elgzst5 <= $min&& $type_lesson==1) || ($marks[$key]['elgzst5'] != -1 && $type_lesson==0))
                $button = CHtml::htmlButton('<i class="icon-tag"></i>', array('class' => 'btn btn-mini btn-info btn-retake', 'data-module-nom'=>$moduleNom.'-'.$st['st1'],'style' => 'display:inline'));
        }
    }
    //$show=Yii::app()->user->getState('showRetake',0);
    //if($show==0)
        //$button='';
    $pattern = <<<HTML
    <td colspan="2" data-nom="{$nom}" data-priz="{$type}"  data-elgz1="{$elgz1}" data-type-lesson="{$type_lesson}" data-r1="{$r1}" data-date="{$date_lesson}" data-gr1="{$gr1}">
        %s %s %s %s
    </td>
HTML;

    return sprintf($pattern, $elgzst3_input, $elgzst4_input, $elgzst5_input, $button);

}

function table2TrModule2($date,$gr1,$st,$ps56,$nom,$modules,$sem7,$ps60, $total, $_count)
{
    if (($st['st71']!=$sem7&&$st['st71']!=$sem7+1) && $ps60 ==1)
        return '<td colspan="2"></td>';

    if (stripos($date['r2'], '11.11.1111')!==false )
        return '<td colspan="2"></td>';

    /*if (strtotime($date['r2']) > strtotime('now'))
        return '<td colspan="4"></td>';*/

    if ($ps56 == 1 && $date['elgz4']>0)
        return '<td colspan="2"></td>';

    if(!isset($modules[(int)$nom-1]))
        return '<td colspan="2">'.tt('Нет ведомости, обращайтесь в деканат!').'</td>';
    else{
        $mark = Vmp::model()->getMarks($modules[(int)$nom-1]['vvmp1'],$st['st1'],$gr1);
        if(empty($mark))
            return '<td colspan="2">'.tt('Нет ведомости, обращайтесь в деканат!').'</td>';
        $_itog = round($mark['vmp7'],2);
        $itog = round($mark['vmp4'],2);
        $vmpv6 = $mark['vmpv6'];
        $vvmp1 = $modules[(int)$nom-1]['vvmp1'];

        /*if($date['elgz4']==3&&SH::getUniversityCod()==32){
            $total = $total/$_count;
            $total = round($total,2);
            $total= ($total*200)/5;
        }*/

        if(!empty($vmpv6)) {
            $js=<<<JS
                        $('*[data-module-nom="{$nom}-{$st['st1']}"]').prop('disabled',true);
JS;
            Yii::app()->clientScript->registerScript('module-nom'.$nom.'-'.$st['st1'], $js, CClientScript::POS_END);

        }

        $vmpv1 = $mark['vmpv1'];
        $nom_=$date['elgz3'];
        $elgz1=$date['elgz1'];
        $date_lesson=$date['r2'];
        $r1=$date['r1'];

        $vmpv6 = $mark['vmpv6'];
        if(!empty($vmpv6)) {
            $js=<<<JS
                        $('*[data-module-nom="{$nom}-{$st['st1']}"]').prop('disabled',true);
JS;
            Yii::app()->clientScript->registerScript('module-nom'.$nom.'-'.$st['st1'], $js, CClientScript::POS_END);
        }

        $elgpmkst = Elgpmkst::model()->findByAttributes(
            array(
                'elgpmkst2'=> $date['elgz2'],
                'elgpmkst3' =>$st['st1'],
                'elgpmkst4' =>$vmpv1,
            )
        );

        $total=0;

        if($elgpmkst!=null)
            $total = round($elgpmkst->elgpmkst5,2);

        return sprintf(<<<HTML
                    <td class="module-tr">%s  <a class="btn btn-info btn-mini btn-show-marks" data-vmpv1="{$vmpv1}" data-nom-module="{$nom}" data-nom="{$nom_}" data-elgz1="{$elgz1}" data-r1="{$r1}" data-date="{$date_lesson}" data-gr1="{$gr1}"><i class="icon-eye-open"></i></a></td>
                    <td class="module-tr">%s</td>
HTML
            ,$total,($total!=round($itog,2))?'<label class="label label-warning">'.$itog.'</label>':$itog);
    }
}

function table2TrModule($date,$gr1,$st,$ps20,$ps55,$ps56,$nom,$uo1,$modules,$potoch,$sem7,$ps60)
{
    if (($st['st71']!=$sem7&&$st['st71']!=$sem7+1) && $ps60 ==1)
        return '<td colspan="4"></td>';

    if (stripos($date['r2'], '11.11.1111')!==false )
        return '<td colspan="4"></td>';

    /*if (strtotime($date['r2']) > strtotime('now'))
        return '<td colspan="4"></td>';*/

    if ($ps56 == 1 && $date['elgz4']>0)
        return '<td colspan="4"></td>';

    switch($date['elgz4']){
        case 2:

            if(!isset($modules[(int)$nom-1]))
                return '<td colspan="4">'.tt('Нет ведомости, обращайтесь в деканат!').'</td>';
            else{
                $mark = Vmp::model()->getMarks($modules[(int)$nom-1]['vvmp1'],$st['st1'],$gr1);
                if(empty($mark))
                    return '<td colspan="4">'.tt('Нет ведомости, обращайтесь в деканат!').'</td>';

                $ind = round($mark['vmp6'],2);
                $pot = round($mark['vmp5'],2);
                $itog = round($mark['vmp4'],2);
                $pmk = round($mark['vmp7'],2);

                switch($pmk){
                    case '-4':
                        $pmk = tt('н/я');
                        break;
                    case '-3':
                        $pmk = '0';
                        break;
                    case '-2':
                        $pmk = tt('неув');
                        break;
                    case '-1':
                        $pmk = tt('уваж');
                        break;
                }
                $vmpv1 = $mark['vmpv1'];

                $nom_=$date['elgz3'];
                $elgz1=$date['elgz1'];
                $date_lesson=$date['r2'];
                $r1=$date['r1'];
                $vmpv6 = $mark['vmpv6'];
                $disabled = '';
                if(!empty($vmpv6)) {
                    $disabled = 'disabled="disabled"';
                    $js=<<<JS
                        $('*[data-module-nom="{$nom}-{$st['st1']}"]').prop('disabled',true);
JS;
                    Yii::app()->clientScript->registerScript('module-nom'.$nom.'-'.$st['st1'], $js, CClientScript::POS_END);

                }

                $elgpmkst = Elgpmkst::model()->findByAttributes(
                    array(
                        'elgpmkst2'=> $date['elgz2'],
                        'elgpmkst3' =>$st['st1'],
                        'elgpmkst4' =>$vmpv1,
                    )
                );

                if(!empty($elgpmkst)){
                    if($elgpmkst->elgpmkst5!=$mark['vmp5']) {
                        $pot = '<label class="label label-warning">' . $pot .' / ' .round($elgpmkst->elgpmkst5, 2) .   '</label>';
                    }
                }

                $st1 = $st['st1'];
                return sprintf(<<<HTML
                    <td class="module-tr module{$vmpv1}{$st1}">%s <a class="btn btn-info btn-mini btn-show-marks" data-vmpv1="{$vmpv1}" data-nom-module="{$nom}" data-nom="{$nom_}" data-elgz1="{$elgz1}" data-r1="{$r1}" data-date="{$date_lesson}" data-gr1="{$gr1}"><i class="icon-eye-open"></i></a></td>
                    <td class="module-tr module{$vmpv1}{$st1}" data-nom-module="{$nom}" data-nom="{$nom_}" {$disabled} data-elgz1="{$elgz1}" data-r1="{$r1}" data-date="{$date_lesson}" data-gr1="{$gr1}"><input value="%s" class="module-ind"  maxlength="5" data-vmpv1="{$vmpv1}"/></td>
                    <td class="module-tr module{$vmpv1}{$st1}">%s</td>
                    <td class="module-tr module{$vmpv1}{$st1}">%s</td>
HTML
                ,$pot,$ind,$pmk,$itog);
            }
            break;
    }
    return '<td colspan="4"></td>';
}

function generateTh2($ps9,$date,$type_lesson,$ps57, $enableMinMax = true)
{
    if($date['elgz4']==2&&$ps57==1) {
        return sprintf('<th>%s</th><th>%s</th><th>%s</th><th>%s</th>',tt('Тек.'),tt('Инд.р.'),tt('ПМК'),tt('Итог.'));
    }elseif(($date['elgz4']==3||$date['elgz4']==4||$date['elgz4']==5)&&$ps57==1){
        return '<th></th><th></th>';
    }
    if ($type_lesson == '0')
        return '<th></th><th></th>';
        //return '<th>'.tt('Посещение').'</th><th>'.tt('Отработка').'</th>';
    if ($ps9 == '0')
        return '<th></th><th></th>';
    $elgz5='';
    $elgz6='';
    if($date['elgz5']>0)
        $elgz5=round($date['elgz5'],1);
    if($date['elgz6']>0)
        $elgz6=round($date['elgz6'],1);
    return $enableMinMax ?
        <<<HTML
<th><input value="{$elgz5}" maxlength="3" placeholder="min" data-name="elgz5" data-elgz1="{$date['elgz1']}"></th>
<th><input value="{$elgz6}" maxlength="3" placeholder="max" data-name="elgz6" data-elgz1="{$date['elgz1']}"></th>
HTML
     :
        <<<HTML
            <th><label>{$elgz5}</label></th>
            <th><label>{$elgz6}</label></th>
HTML;
}

function generateColumnName($date,$type_lesson,$ps57,$ps59, $ps90,$permLesson, $date1, $ps78,$ps27)
{
    if($date['elgz4']==2&&$ps57==1) {
        $pattern = <<<HTML
	<th colspan="4">
	    <i class="icon-hand-right icon-animated-hand-pointer blue"></i>
        <span data-rel="popover" class="nom%s" data-placement="top" data-content="%s" class="green">%s</span>
        %s
    </th>
HTML;
    }else {
        $pattern = <<<HTML
	<th colspan="2">
	    <i class="icon-hand-right icon-animated-hand-pointer blue"></i>
        <span data-rel="popover" class="nom%s" data-placement="top" data-content="%s" class="green">%s</span>
        %s
    </th>
HTML;
    }

    /*switch($date['elgz4'])
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
    }*/

    $type = Ustem::model()->getUstem6Value($date['elgz4']);

    $type=' '.$type;
    $us4=SH::convertUS4(1);
    if($type_lesson!=0)
    $us4=SH::convertUS4($date['us4']);

    $time = sprintf('%02d-%02d:%02d-%02d',$date['rz9'],$date['rz10'],$date['rz11'],$date['rz12']);

    $name = '№'.$date['elgz3'].' '.$date['formatted_date'].' '.$us4.$type;
    if($ps59==1)
        $name.= ' '.$date['k2'];

    $ps100 = PortalSettings::model()->getSettingFor(100);
    if($ps100==1)
        $name.= ' '.$time;


    $disabled = Elgz::model()->checkLesson($date,$permLesson,$ps78,$date1,$ps27);

    $a = '';
    if($ps90==1&&!$disabled){
        $a = CHtml::link('<i class="icon-pencil"></i>','#',array(
            'data-nom'=>  $date['elgz1'],
            'data-r1'=>  $date['r1'],
            'class'=>'change-theme'
        ));
    }

    return sprintf($pattern,$date['elgz3'],$time.'<br>'.$date['k2'].'<br>'.$date['ustem5'], $name, $a);
}

function countMarkTotal($marks)
{
    $total = 0;
    $count = 0;
    foreach ($marks as $mark) {
        $m = $mark['elgzst5'] != 0
            ? $mark['elgzst5']
            : $mark['elgzst4'];
        $total += $m;
        if($m>0)
            $count++;
    }
    return array($total,$count);
}

function getMarkByLesson($marks, $nom){
    if(isset($marks[$nom])){
        $mark = $marks[$nom];
        $m = $mark['elgzst5'] != 0
            ? $mark['elgzst5']
            : $mark['elgzst4'];

        return $m;
    }
    return 0;
}


    $url       = Yii::app()->createUrl('/journal/insertStMark');
    $url_check = Yii::app()->createUrl('/journal/checkCountRetake');
    $minMaxUrl = Yii::app()->createUrl('/journal/insertMinMaxMark');
    $urlRetake = Yii::app()->createUrl('/journal/journalRetake');
    $urlModule= Yii::app()->createUrl('/journal/updateVmp');
    $urlShowMarks= Yii::app()->createUrl('/journal/ShowMarksForRecalculate');

    $changeTheme= Yii::app()->createUrl('/journal/changeTheme');

    $urlStFinBlock= Yii::app()->createUrl('/journal/insertStFinPriznak');

    $table = <<<HTML
<div class="{$classTable2}" data-ps33="{$ps33}" data-gr1="{$gr1}" data-url-st-fin-block="{$urlStFinBlock}" data-url-show-marks="{$urlShowMarks}" data-url-module="{$urlModule}" data-url="{$url}" data-url-change-theme="{$changeTheme}" data-url-retake="{$urlRetake}" data-url-check="{$url_check}">
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

/**
 * @var $elg Elg
 * @var $stusv Stusv
 */
    /*$ps107 = PortalSettings::model()->getSettingFor(107);
    if($ps107==1)
        $stusv = Stusv::model()->getStusvByJournal($elg, $gr1);
    else
        $stusv=null;*/


    $sem7 = Gr::model()->getSem7ByGr1ByDate($gr1,date('d.m.Y'));
    $ps59 = PortalSettings::model()->getSettingFor(59);
    $ps60 = PortalSettings::model()->getSettingFor(60);
    $ps65 = PortalSettings::model()->getSettingFor(65);
    $ps66 = PortalSettings::model()->getSettingFor(66);

    //$ps107 = PortalSettings::model()->getSettingFor(107);

    $min = Elgzst::model()->getMin();
    $elgz1_arr=array();
    $th = $th2 = $tr = '';

    global $total_1, $total_count_1, $minBalForAllLessons, $maxBalForAllLessons;
    global $count_dates;
    global $readOnlyByStudents;

    $readOnlyByStudents = array();
    $count_dates=0;
    //$column = 1;
    $elgz3Nom = 1;
    $dateDiff = -1;
    $date1 = new DateTime(date('Y-m-d H:i:s'));
    /*добавлять ли не проставленые занятия*/
    $ps89 = PortalSettings::model()->getSettingFor(89);
    /*Разрешено ли менять тему занятий*/
    $ps90 = PortalSettings::model()->getSettingFor(90);

    $ps78 = PortalSettings::model()->getSettingFor(78);
    $ps27 = PortalSettings::model()->getSettingFor(27);

    foreach($dates as $date) {
        array_push($elgz1_arr, $date['elgz1']);
    }

    $permLesson=Elgr::model()->getList($gr1,$elgz1_arr);

    /*количество занятий для пересчета оценки дифзачета запорожье*/
    $countDivZacvmarks =0;

    //проверка на редактирование минмакс для ирпени
    $enableMimMax  = true;
    if($this->universityCode == U_IRPEN&& count($elgz1_arr)>0 && $ps9 == 1){
        $firstElgz = Elgz::model()->findByPk(reset($elgz1_arr));
        $enableMimMax =  $firstElgz == null ? false : $firstElgz->checkAccessMinMixIrpen($gr1);
    }

    $sem1End = null;
    if($elg->elg20->uo6==3) {
        $sem1End = Vmp::model()->getEndSem1($elg->elg2);
    }

    foreach($dates as $date) {
        $date2  = new DateTime($date['r2']);

        $th .= generateColumnName($date, $model->type_lesson,$ps57,$ps59, $ps90, $permLesson,$date1, $ps78,$ps27);
        $th2 .= generateTh2($ps9, $date, $model->type_lesson,$ps57, $enableMimMax);
        //$column++;

        $date2  = new DateTime($date['r2']);
        $diff = $date1->diff($date2)->days;
        if ($diff <= $dateDiff || $dateDiff==-1)
        {
            $dateDiff = $diff;
            $elgz3Nom = $date['elgz3'];
        }
        $count_dates++;

        if($date['elgz4']==0)
            $countDivZacvmarks++;

        if($ps9==1){
            $elgz5=0;
            $elgz6=0;

            if($date['elgz5']>0)
                $elgz5=round($date['elgz5'],1);
            if($date['elgz6']>0)
                $elgz6=round($date['elgz6'],1);

            $minBalForAllLessons += $elgz5;
            $maxBalForAllLessons += $elgz6;
        }
    }


    if($elg->elg20->uo6==3){
        if($elg->elg3==$sem1End){
            $th2.=sprintf('<th>%s</th><th>%s</th><th>%s</th><th>%s</th>',tt('Тек.'),tt('Инд.р.'),tt('ПМК'),tt('Итог.'));
            $th.= '<th colspan="4">'.tt('Итоговая накопительная ведомость').'</th>';
        }
    }

    foreach($students as $st) {
        if(!empty( $st['elgvst2']))
            $st['elgvst2']=strtotime($st['elgvst2']);
        if(!empty( $st['elgvst3']))
            $st['elgvst3']=strtotime($st['elgvst3']);
        //для дивзачета сумарная
        $divZachTotal = 0;
        $potoch = 0;
        $moduleNom=1;
        $st1 = $st['st1'];
        $class= '';
        if( $st['st167']==1)//заблокирвоан по фин признакку
            $class = 'error-tr';
        $marks = $elg->getMarksForStudent($st1);
        $tr .= '<tr data-st1="'.$st1.'" class="'.$class.'">';
        list($total_1[$st1], $total_count_1[$st1]) = countMarkTotal($marks);
        //проверка есть ли итоговая оценка, тогда бдлокируем ввод оценко
        $readOnlySt = false;
        //if($ps107==1) {
        $readOnlyByStudents[$st1] = false;
        $stusv = Stusv::model()->getStusvByJournalAndStudent($elg, $st1);
        if (!empty($stusv)) {

            $stusvst = $stusv->getMarkForStudent($st1);
            if (!empty($stusvst)) {
                $readOnlySt = !empty($stusv['stusv15']);

                if(!$readOnlySt){
                    $stusv12 = $stusv['stusv12'];
                    $stusv11 = $stusv['stusv11'];
                    if($stusv12!=0&&!empty($stusv11)){
                        $date = date('Y-m-d H:i:s');
                        $date2 = new DateTime($stusv11);
                        $date1 = new DateTime($date);

                        $date2->modify('+'.$stusv12.' days');

                        if($date1>$date2) {
                            $readOnlySt=true;
                        }
                    }
                }

                if($stusvst->stusvst6==0 || $stusvst->stusvst6==2)
                    $readOnlySt = false;

                $readOnlyByStudents[$st1] = $readOnlySt;
                /*if ($stusvst->stusvst4 > 0 || $stusvst->stusvst6 > 0)
                    $readOnlySt = true;*/
            }
        }
        //}

        foreach($dates as $key => $date) {
            $_nom=$date['elgz3'];
            $date2 = new DateTime($date['r2']);
            //если нет оценки добавляем ее ps89
            //костыль баг если выставить настроку ставить ноль
            if(!isset($marks[$_nom])&&$date1>$date2&&$ps89==1){
                $elgzst = Elg::model()->addRowMark($st1,$date['elgz1'], $elg->elg4);
                $marks[$_nom] = array(
                    'elgz3'=>$_nom,
                    'elgzst3'=>$elgzst->elgzst3,
                    'elgzst4'=>$elgzst->elgzst4,
                    'elgzst5'=>$elgzst->elgzst5,
                );
            }

            if($date['elgz4']==2&&$ps57==1)
            {
                $tr .= table2TrModule($date,$gr1,$st,$ps20,$ps55,$ps56,$moduleNom,$uo1,$modules,$potoch,$sem7,$ps60);
                $potoch = 0;
                $moduleNom++;
            }elseif(($date['elgz4']==3||$date['elgz4']==4||$date['elgz4']==5)&&$ps57==1){
                if($date['elgz4']==3){
                    $tr .= table2TrModule2($date,$gr1,$st,$ps56,$moduleNom,$modules,$sem7,$ps60,$divZachTotal,$countDivZacvmarks);
                    $divZachTotal = 0;
                }else
                    $tr.='<th></th><th></th>';
                $moduleNom++;
            }else {
                $tr .= table2Tr($date, $gr1, $st, $marks, $permLesson, ($readOnlySt)?$readOnlySt:$read_only, $model->type_lesson, $ps20, $ps55, $ps56,$sem7,$ps60,$min,$ps65,$ps66,$moduleNom, $ps88, $isStd);
                $potoch+=getMarsForElgz3($date['elgz3'],$marks);

                if($ps57==1)
                    $divZachTotal+=getMarkByLesson($marks,$date['elgz3']);
            }
        }

        if($elg->elg20->uo6==3){
            if($elg->elg3==$sem1End){
                $tr .= table2TrItog($elg->elg2, $gr1, $st1 );
            }
        }

        $tr .= '</tr>';
    }

    echo sprintf($table, $th, $th2, $tr); // 2 table
    if($elgz3Nom>1)
    Yii::app()->clientScript->registerScript('journal-scroll', <<<JS
        /*alert($('.journal_div_table2 .nom{$elgz3Nom}').position().left);
        alert($('.journal_div_table2').position().left);*/
        $(".journal_div_table2 ").animate({
            scrollLeft:$('.journal_div_table2 .nom{$elgz3Nom}').position().left - $('.journal_div_table2').position().left
        }, 750);

JS
    , CClientScript::POS_END);


