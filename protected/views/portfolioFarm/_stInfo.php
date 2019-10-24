<?php
/**
 * Created by PhpStorm.
 * User: Lesha
 * Date: 04.09.2019
 * Time: 17:08
 */

/**
 * @var St $student
 * @var TimeTableForm $model
 */

Yii::app()->clientScript->registerCss('stInfo', <<<CSS
    .student-info td {
        font-size: 1.2em;
        color: #0A246A;
    }
CSS
    );

$studentInfo = $student->getStudentInfoForPortfolio();
$url = $this->createUrl('/site/userPhoto', array('_id' => $student->st1, 'type' => Users::FOTO_ST1));
$class='img-circle';
$infoHtml = <<<HTML
    <div class="student-info row">
        <div class="student-photo span3">
             <img alt="photo" src="{$url}" class="{$class}">
        </div>
        <table class="table span9">
            <tbody>
                <tr>
                    <th>%s</th>
                    <td>%s</td>
                </tr>
                <tr>
                    <th>%s</th>
                    <td>%s</td>
                </tr>
                <tr>
                    <th>%s</th>
                    <td>%s</td>
                </tr>
                <tr>
                    <th>%s</th>
                    <td>%s</td>
                </tr>
            </tbody>
        </table>
    </div>
HTML;

echo sprintf($infoHtml,
    tt('ФИО'),$student->fullName,
    tt('Дата рождения'),date( 'd.m.Y', strtotime($student->st7)),
    tt('Образовательная программа'),  $studentInfo['spc4'],
    tt('Специальность'), $studentInfo['pnsp2']
);
