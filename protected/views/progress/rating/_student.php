<?php
/**
 * Created by PhpStorm.
 * User: NEFF
 * Date: 05.02.2018
 * Time: 8:41
 */

/**
 * @var $this ProgressController
 * @var $model RatingForm
 * @var St $student
 */


$marks = $model->getRating(RatingForm::STUDENT);

$thead = '<tr>'.
            '<th>№</th>'.
            '<th>'.tt('Дисциплина').'</th>'.
            '<th>'.tt('Тип').'</th>'.
            '<th>'.tt('Семестр').'</th>'.
            '<th>'.tt('Оценка').'</th>'.
         '</tr>';

$tbody = '';

$ps81 = PortalSettings::model()->getSettingFor(81);
$is5 = $ps81==0;

$i=1;
foreach ($marks as $mark){
    $tbody.=sprintf(
        '<tr><td>%d</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>',
        $i,
        $mark['d2'],
        $mark['tip'],
        $mark['sem7'],
        $mark[$is5 ? 'bal_5' : 'bal_100']
    );
    $i++;
}

echo <<<HTML
    <table class="table table-striped table-hover table-condensed">
        <thead>
            {$thead}
        </thead>
        <tbody>
            {$tbody}
        </tbody>
HTML;
