<?php
/**
 * Created by PhpStorm.
 * User: NEFF
 * Date: 12.01.2018
 * Time: 15:54
 */

/* @var $this DistEducationController */
/* @var $model DistEducationFilterForm */

$table = <<<HTML
        <table class="table table-bordered table-condensed table-hover">
            <thead>
               %s     
            </thead>
            <tbody>
                %s
            </tbody>
        </table> 
HTML;

$thead = <<<HTML
    <tr>
        <th>№</th>
        <th>ФИО</th>
        <th>Группа</th>
        <th>Email</th>
    </tr>
HTML;

$tbody = '';

$students = St::model()->getStudentsForDistEducationCourse($model->discipline);
$i=1;
foreach ($students as $student){
    $name = SH::getShortName($student['st2'], $student['st3'], $student['st4']);

    $nameGroup = Gr::model()->getGroupName($student['sem4'], $student);

    $tbody.=sprintf('<tr><td>%d</td><td>%s</td><td>%s</td><td>%s</td></tr>', $i, $name, $nameGroup, $student['stdist2']);
    $i++;
}

echo sprintf($table,$thead, $tbody);
