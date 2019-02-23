<?php
function getTh($val)
{
    if($val>0)
    {
        return '<span data-toggle="tooltip" data-placement="top" data-original-title="'.tt('Пересдача').' '.$val.'">'.tt('П').' '.$val.'</span>';
        //return tt('П').' '.$val;
    }
    else
    {
        return tt('Тест');
    }
}
function generateColumnName($i)
{
    $num = date('w', strtotime($i));
    $pattern = <<<HTML
	<th colspan="8"><span class="green">%s %s</span></th>
HTML;
    $name = $i;

    return sprintf($pattern, $name,SH::russianDayName($num));
}

$table = <<<HTML
    <div class="div-time-table-chair-2">
        <table class="table table-bordered table-hover time-table-chair-2">
            <thead>
                <tr>
                    %s
                </tr>
                <tr class="per-name">

                    %s
                </tr>
            </thead>
            <tbody>
                %s
            </tbody>
        </table>
    </div>
HTML;
    
    $th_head='<th>1</th>
        <th>2</th>
        <th>3</th>
        <th>4</th>
        <th>5</th>
        <th>6</th>
        <th>7</th>
        <th>8</th>';
    
    $pattern = <<<HTML
<td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td>
HTML;
    
    /*** 2 table ***/
    $th = $th2 = $tr = '';
    $date=$model->date1;
    $day=0;
    while ((strtotime($date)<=strtotime($model->date2))) {
        $th    .= generateColumnName($date);
        $th2   .= $th_head;
        $date=date('d.m.Y',strtotime($date . "+1 days"));
        $day++;
    }
    
    $i=0;
    $class = tt('ауд');
    $text  = tt('Добавлено');
    $now = new DateTime('NOW');
    foreach($groups as $group) {
        $tr .='<tr>';
        $timTable=Gr::getTimeTable($group['gr1'], $model->date1, $model->date2, 4);
        reset($timTable);
        $num = date('w', strtotime($model->date1))*8;
        for($i=1;$i<=$day*8;$i++)
        {
            $class_day="";
            if(((int)floor($num/8)==0)||((int)floor($num/8)==6))
            {
                $class_day="weekends";
            }
            $cur=current($timTable);
            
            if($cur!=false&&$cur['colonka']==$i)
            {
                $name=$cur['d2'];

                $group=$cur['grupfull'];
                $a2=$cur['a2'];
                $added = date('d.m.Y H:i', strtotime($cur['r11']));
                $datetime2 = new DateTime($cur['r11']);
                $interval_added = $now->diff($datetime2);
                $class_interval='';
                if($interval_added->days<=$model->r11)
                   $class_interval='new'; 
                $type_str=$cur['tip'];
                $r2=$cur['r2'];
                $r3=$cur['r3'];
                $teacher = $cur['fio'];
                $next=next($timTable);

                $pattern = <<<HTML
                    {$name}[{$type_str}]<br>
                    {$teacher}<br>
                    {$class}. {$a2}<br>
                    {$text}: {$added}
HTML;
                $fullText=CHtml::encode(trim($pattern));
                $tr .='<td class="'.$class_day.' '.$class_interval.'" style="background-color:'.SH::getLessonColor($cur['tip']).'!important"><div data-rel="popover" data-placement="right" data-content="'.$fullText.'">X</div></td>';
            }  else {
                $tr .='<td class="empty-day '.$class_day.'">&nbsp;</td>';
            }
            if($num!=55)
            {
                $num++;
            }  else {
                $num=0;
            }
        }
        $tr .='</tr>';
        /*if($i==2)
            break;*/
        $i++;
    }

    echo sprintf($table, $th, $th2, $tr); // 2 table

