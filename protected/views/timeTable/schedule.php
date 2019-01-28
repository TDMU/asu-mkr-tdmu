<?php

function countHeight($maxLessons, $dayOfWeek, $lesson)
{
    $height = isset($maxLessons[$dayOfWeek][$lesson])
                ? 50*$maxLessons[$dayOfWeek][$lesson]
                : 50;

    return $height;
}

$timestamps    = array_keys($timeTable);
$amountOfWeeks =  ceil(((current($timestamps) - end($timestamps))/86400) / -7);
reset($timestamps);

$html = '<table class="timeTable" id="timeTableGroup">';

foreach(range(1,7) as $dayOfWeek) {// дни недели 1-пн

    $html .= '<tr>';

    // первая колонка - название дня + пары 1-7
    $name = $minMax['names'][$dayOfWeek];
	if(Yii::app()->params['fixedCountLesson']!=1)
	{
		$min  = $minMax['min'][$dayOfWeek];
		$max  = $minMax['max'][$dayOfWeek];
	}else
	{
		$min  = 1;
		$max  = Yii::app()->params['countLesson'];
	}
	



    $html .= '<td>
                  <div>'.$name.'</div>';
    foreach (range($min, $max) as $lesson) {
        $interval = isset($rz[$lesson]) ? $rz[$lesson]['rz2'].' - '.$rz[$lesson]['rz3'] : null;
		$start=isset($rz[$lesson]) ? $rz[$lesson]['rz2']: null;
		$finish=isset($rz[$lesson]) ? $rz[$lesson]['rz3']: null;
        $h = countHeight($maxLessons, $dayOfWeek, $lesson);
		$tmp='<span class="lesson">'.$lesson.' '.tt('пара').'</span><span class="start">'.$start.'</span><span class="finish">'.$finish.'</span>';
        /*$html .= <<<HTML
<div class="lh-{$h} mh-{$h} cell tooltip-info" data-rel="tooltip" data-placement="left" data-original-title="{$interval}">{$lesson}</div>
HTML;

    */
$html .= <<<HTML
<div class="mh-{$h} cell cell-vertical">{$tmp}</div>
HTML;
	}
    $html .= '</td>';
    //Сообщение для тултипа для закрытых дней
    $closedMessage = tt('Выберите необходимый диапазон дат');
    // колонки с занятиями
    $firstTs = $timestamps[0];
    for($week = 0; $week < $amountOfWeeks; $week++) {

        $day  = $firstTs + $week*7*86400; // timestamp of current day
        $tt   = $timeTable[$day]['timeTable'];

        $date = $timeTable[$day]['date'];

        $isClosed = $day < strtotime($model->date1) ||
                    $day > strtotime($model->date2);
        $closedClass = $isClosed ? 'class="closed"' : null;

        $html .= '<td '.$closedClass.'>
                    <div>'.$date.'</div>';

        foreach (range($min, $max) as $lesson) {

            $mh = countHeight($maxLessons, $dayOfWeek, $lesson);

            $shortText = $fullText = $params = '';
            if (isset($tt[$lesson])) {
                $shortText = $tt[$lesson]['shortText'];
                $fullText  = $tt[$lesson]['fullText'];
                /*$fullText  = trim($tt[$lesson]['fullText'], '<br>');
                if(isset($tt[$lesson]['gr3']))
                    $fullText  =str_replace('{$gr3}',$tt[$lesson]['gr3'],$fullText);*/
            }else{
                if($isClosed){
                    $html .= <<<HTML
<div class="cell mh-{$mh}"  data-toggle="tooltip" title="{$closedMessage}">{$shortText}</div>
HTML;
                    continue;
                }
            }

            $html .= <<<HTML
<div class="cell mh-{$mh}"  data-rel="popover" data-placement="right" data-content="{$fullText}">{$shortText}</div>
HTML;

        }

        $html .= '</td>';
    }

    array_shift($timestamps);

    $html .= '</tr>';
}
$html .= '</table>';
/*$class="print-btn";
if('teacherExcel'==$action||'classroomExcel'==$action)
    $class="print-btn-tch";

if(isset(Yii::app()->session['printAttr']))
    $model->printAttr = Yii::app()->session['printAttr'];*/

	/*<a id="print-table" data-url="<?=Yii::app()->createUrl('/timeTable/'.$action.'?type=%type%')?>" class="btn btn-info btn-small <?=$class?>">
        <i class="icon-print bigger-110"></i>
    </a>
    <label class="inline">
        <?php
        echo CHtml::activeCheckBox($model, "printAttr");
        ?>
        <?=$model->getAttributeLabel("printAttr")
    </label>*/?>
    <h3 class="red header lighter tooltip-info noprint">
        <i class="icon-info-sign show-info" style="cursor:pointer"></i>
        <small>
            <i class="icon-double-angle-right"></i> <?= tt('Дисциплины с (!) - Нажмите на дисциплину для вывода времени занятия')?>
        </small>
    </h3>
<?php

echo $html;

