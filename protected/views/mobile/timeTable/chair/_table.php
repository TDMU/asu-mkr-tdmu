<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 24.07.2016
 * Time: 14:27
 */
 ?>
    <div class="panel-actions row">
        <div class="form-actions col-xs-12">
            <button id="lesson-left" class="btn col-xs-2" type="button"><i class="arrow glyphicon glyphicon-triangle-left"></i></i></button>
            <div class="form-group col-xs-8">
                <div class='input-group datepicker'  id="lesson-datepicker">
                    <?= CHtml::textField('TimeTableForm[dateLesson]',$model->dateLesson, array('class'=>'form-control'));?>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar">
                        </span>
                    </span>
                </div>
            </div>
            <button id="lesson-right" class="btn col-xs-2" type="button"><i class="arrow glyphicon glyphicon-triangle-right"></i></button>
        </div>
    </div>
    <div id="table-time" class="col-xs-12 table-time">
    <?php
    $lang = Yii::app()->language;
    $js =<<<JS
         $('#lesson-datepicker .form-control').datepicker({
            todayBtn: "linked",
            format: "dd.mm.yyyy",
            language: '{$lang}',
            daysOfWeekHighlighted: "0,6",
            calendarWeeks: false,
            autoclose: true,
            todayHighlight: true,
            toggleActive: true
        });
JS;
    Yii::app()->clientScript->registerScript('lesson-datepicker',$js,CClientScript::POS_END);

    if(empty($teachers)):
        echo '<div class="alert alert-danger" role="alert"><h3>'.Yii::t('main','Преподаватели не найдены!').'</h3></div>';
    else:
        $pattern = <<<HTML
            <td>%s</td>
HTML;
        $columnName = tt('Преподаватель');

        $th = $tr = '';
            $table = <<<HTML
            <div class="table-responsive fixed-column-table">
                <table class="table table-striped table-bordered table-hover table-condensed" >
                    <thead>
                        <tr>
                            %s
                        </tr>
                    </thead>
                    <tbody>
                        %s
                    </tbody>
                </table>
            </div>
HTML;

        $th .= '<th>'.$columnName.'</th>';
        for($i=1;$i<=8;$i++){
            $th .= "<th>{$i}</th>";
        }

        $class = tt('ауд');
        $text  = tt('Добавлено');
        $now = new DateTime('NOW');

        foreach($teachers as $teacher) {
            $tr .='<tr>';
            $timTable=Gr::getTimeTable($teacher['p1'], $model->dateLesson, $model->dateLesson, 3);
            reset($timTable);
            $tr .= sprintf($pattern, $teacher['name']);

            for($i=1;$i<=8;$i++)
            {
                $num = date('w', strtotime($model->dateLesson))*8;

                $class_day="";
                if(((int)floor($num/8)==0)||((int)floor($num/8)==6))
                {
                    $class_day="weekends";
                }
                $cur=current($timTable);

                if($cur!=false&&$cur['colonka']==$i)
                {
                    $name=$cur['d2'];

                    $name = str_replace('"','&quot;',$name);
                    $group=$cur['gr3'];
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
                    $next=next($timTable);
                    if($next!=false)
                    {
                        while($r2==$next['r2']&&$r3==$next['r3'])
                        {
                            $group.=', '.$next['gr3'];
                            $r2=$next['r2'];
                            $r3=$next['r3'];
                            $next=next($timTable);
                        }
                    }
                    /*$pattern = <<<HTML
                            {$name}[{$type_str}]<br>
                            {$group}<br>
                            {$class}. {$a2}<br>
                            {$text}: {$added}
HTML;
                    $fullText=trim($pattern);*/
                    //$tr .='<td class="'.$class_day.' '.$class_interval.'" style="background-color:'.SH::getLessonColor($cur['tip']).'!important"><div data-rel="popover" data-placement="right" data-content="'.$fullText.'">X</label></td>';
                    $tr .='<td class="'.$class_day.' '.$class_interval.'" style="background-color:'.SH::getLessonColor($cur['tip']).'!important">X</td>';
                }  else {
                    $tr .='<td class="empty-day '.$class_day.'">&nbsp;</td>';
                }
            }
            $tr .='</tr>';
        }

        echo sprintf($table, $th, $tr);

        $js =<<<JS
       $('.fixed-column-table table').each(function(){
            var table = $(this),
                fixedCol = table.clone(true),
                fixedWidth = table.find('th').eq(0).width(),
                tablePos = table.position();

            // Remove all but the first column from the cloned table
            fixedCol.find('th').not(':eq(0)').remove();
            fixedCol.find('tbody tr').each(function(){
                $(this).find('td').not(':eq(0)').remove();
            });

            // Set positioning so that cloned table overlays
            // first column of original table
            fixedCol.addClass('fixedCol');
            fixedCol.css({
                left: tablePos.left,
                top: tablePos.top
            });

            // Match column width with that of original table
            fixedCol.find('th,td').css('width',fixedWidth+'px');

            $(this).parents('.fixed-column-table').append(fixedCol);
        });
JS;
        Yii::app()->clientScript->registerScript('fixed-column',$js,CClientScript::POS_END);

    endif;
?>
    </div>
<?php
