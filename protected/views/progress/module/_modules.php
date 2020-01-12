<?php
/**
 * @var ProgressController $this
 * @var ModuleForm $model
 * @var Modgr[] $modules
 */

$students = $model->getStudents();

echo CHtml::openTag('table', array(
    'id' => 'marks',
    'class' => 'table table-hover table-condensed table-bordered',
    'data-url' => Yii::app()->createUrl('progress/changeMark'),
    'style' => 'width: 900px'
));

$itog = null;
echo CHtml::openTag('thead');
echo CHtml::openTag('tr');
echo '<th style="width: 25px">'.'№'.'</th>';
echo '<th style="width: 200px">'.tt('ФИО').'</th>';
echo '<th style="width: 90px">'.tt('Номер зачетки').'</th>';
foreach ($modules as $module) {
    if ($module->module->mod3 == 0)
        echo '<th style="width: 70px">' . $module->module->mod5 . '</th>';
    else
        $itog = $module;
}
echo '<th style="width: 70px">'.tt('Общее количество').'</th>';
echo '<th style="width: 70px">'.(empty($itog) ? tt('Экзамен') : $itog->module->mod5).'</th>';
echo '<th style="width: 70px">'.tt('Итог').'</th>';
echo CHtml::closeTag('tr');
echo CHtml::closeTag('thead');
echo CHtml::openTag('tbody');
$i = 1;
foreach ($students as $student){
    echo CHtml::openTag('tr');
        echo '<td>'.$i.'</td>';
        echo '<td>'.$student->fullName.'</td>';
        echo '<td>'.$student->st5.'</td>';
        $markAll = 0;
        $marks = $model->getModuleMarks($student->st1);
        foreach ($modules as $module) {
            if($module->module->mod3 == 1){
                $itog = $module;
                continue;
            }

            if(isset($marks[$module->modgr1])) {
                $mark = $marks[$module->modgr1]->mods3;
                $markAll+=$mark;
            }
            else
                $mark = '';

            echo '<td>' . CHtml::numberField('mark-' . $student->st1 . '-' . $module->modgr1, $mark, array(
                    'data-st1' => $student->st1,
                    'data-module' => $module->modgr1,
                    'style' => 'margin-bottom:0px',
                    'class' => 'input-mark'
                )) . '</td>';
        }
        echo '<td class="summ" data-st1="'.$student->st1.'">'.$markAll.'</td>';
        if(!empty($itog)){
            if(isset($marks[$itog->modgr1])) {
                $mark = $marks[$itog->modgr1]->mods3;
                $markAll+=$mark;
            }
            else
                $mark = '';

            echo '<td>' . CHtml::numberField('mark-' . $student->st1 . '-' . $itog->modgr1, $mark, array(
                    'data-st1' => $student->st1,
                    'data-module' => $itog->modgr1,
                    'style' => 'margin-bottom:0px',
                    'class' => 'input-exam-mark'
                )) . '</td>';
        }else
            echo '<td></td>';
        echo '<td class="itog-summ" data-st1="'.$student->st1.'">'.$markAll.'</td>';
    echo CHtml::closeTag('tr');
    $i++;
}
echo CHtml::closeTag('tbody');
echo CHtml::closeTag('table');

Yii::app()->clientScript->registerCss('style', <<<CSS
    .input-mark, .input-exam-mark {
        width: 60px;
    }
    #marks thead>tr {
        background-image: linear-gradient(to bottom,#f8f8f8,#dddcdc);
    }
    .summ {
        background-color: #fcf8e3;
    }
    .itog-summ{
        background-color: #dff0d8;
    }
CSS
);
