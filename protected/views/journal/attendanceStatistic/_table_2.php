<?php
/**
 * @var ProgressController $this
 * @var FilterForm $model
 */
$th  = '<th class="center" colspan="3">%s</th>';
$th2 = '<th class="center">Зн</th><th class="center">Ну</th><th class="center">Ув</th>';

function tds($start, $attendance, $monthStatistic)
{
    $statistic = $attendance[$start];

    $td1 = $statistic['td1'] ? $statistic['td1'] : '&nbsp';
    $td2 = $statistic['td2'] ? $statistic['td2'] : '';
    $td3 = $statistic['td3'] ? $statistic['td3'] : '';

    // percents {{{
    $td2P = $td3P = '';
    if ($statistic['td1']) {
        $td2P = round(($statistic['td2']/$statistic['td1'])*100);
        $td3P = round(($statistic['td3']/$statistic['td1'])*100);
        $td2P = $td2P ? $td2P : '';
        $td3P = $td3P ? $td3P : '';
    }
    // }}}

    $weekend = null;
    $showWeekends = $monthStatistic && $start != 'summary';
    if ($showWeekends) {
        $dayOfWeek = date('N', $start);
        $weekend   = in_array($dayOfWeek, array(7)) ? 'weekend': '';
    }

    return <<<HTML
<td class="center {$weekend}" style="background-color: #e1e9da">{$td1}</td>
<td class="center {$weekend}"><span>{$td2}</span><span class="hide">{$td2P}</span></td>
<td class="center {$weekend}"><span>{$td3}</span><span class="hide">{$td3P}</span></td>

HTML;
}

function getFirstAndLastDays($model) {

    list($firstDay, $lastDay) = Sem::model()->getSemesterStartAndEnd($model->semester);

    if (! empty($model->month)) {

        $firstDayOfMonth = strtotime($model->month); // beginning of month
        $lastDayOfMonth  = date('Y-m-t', $firstDayOfMonth);

        $firstDay = $model->month;
        $lastDay  = strtotime($lastDay) > strtotime($lastDayOfMonth)
                        ? $lastDayOfMonth
                        : $lastDay;
    }
    return array($firstDay, $lastDay);
}

list($firstDay, $lastDay) = getFirstAndLastDays($model);

$monthStatistic = ! empty($model->month);

$tooltip = '';

if (! empty($firstDay) && !empty($lastDay)) :
    $start = strtotime($firstDay);
    $end   = strtotime($lastDay);
?>
<div style="overflow: scroll;">
    <table class="table table-striped table-bordered table-hover small-rows attendance-statistic-table-2">
        <thead>
            <tr>
                <?php
                    $columns = array();
                    $columns['summary'] = $monthStatistic
                                            ? SH::russianMonthName(date('m', $start))
                                            : tt('Семестр');
                    while($start <= $end) {

                        $name = $monthStatistic
                                    ? date('d.m.Y', $start)
                                    : SH::russianMonthName(date('m', $start));

                        $columns[$start] = $name;

                        $condition = $monthStatistic
                                        ? 'next day'
                                        : 'first day of next month';
                        $start = strtotime($condition, $start);
                    }
                    foreach ($columns as $name)
                        echo sprintf($th, $name);
                ?>
            </tr>
            <tr class="second-row">
                <?php
                    foreach ($columns as $name)
                        echo sprintf($th2);
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
                $html = '';
                foreach ($students as $student) {

                    $attendance = Elgzst::model()->getAttendanceStatisticFor($student['st1'], $firstDay, $lastDay, $monthStatistic, $model->discipline);
                    //$attendance['summary']['td1'] = Elgzst::getCountLesson($model->group, $model->semester, $firstDay, $lastDay);

                    $html .= '<tr>';
                    foreach ($columns as $start => $name) {
                        $html .= tds($start, $attendance, $monthStatistic);
                    }
                    $html .= '</tr>';
                }
                echo $html;
            ?>
        </tbody>
    </table>
</div>
<?php endif;