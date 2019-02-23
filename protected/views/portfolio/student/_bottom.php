<?php
/**
 * Created by PhpStorm.
 * User: neffa
 * Date: 04.02.2019
 * Time: 13:22
 */

/**
 * @var St $student
 * @var TimeTableForm $model
 */

$studentInfo = $student->getStudentInfoForPortfolio();

$infoHtml = <<<HTML
    <div class="student-info">
        <table class="table">
            <tbody>
                <tr>
                    <th>%s</th>
                    <td>%s</td>
                    <th>%s</th>
                    <td>%s</td>
                </tr>
                <tr>
                    <th>%s</th>
                    <td>%s</td>
                    <th>%s</th>
                    <td>%s</td>
                </tr>
                <tr>
                    <th>%s</th>
                    <td>%s</td>
                    <th>%s</th>
                    <td>%s</td>
                </tr>
                <tr>
                    <th>%s</th>
                    <td>%s</td>
                    <th>%s</th>
                    <td>%s</td>
                </tr>
            </tbody>
        </table>
    </div>
HTML;

echo sprintf($infoHtml,
    tt('ФИО'),$student->fullName,
    tt('Средний балл'), 0,

    tt('Факультет'), $studentInfo['f3'],
    tt('Специальность, шифр'), $studentInfo['sp2'] . ', '. $studentInfo['sp4'],

    tt('Форма обучения'),SH::convertEducationType($studentInfo['sg4']),
    tt('Курс'), $studentInfo['sem4'],

    tt('Группа'), Gr::model()->getGroupName($studentInfo['sem4'], $studentInfo),
    tt('Профиль'), $studentInfo['spc4']
);

echo '<div class="page-header">';
echo CHtml::tag('h3', array(), tt('Дисциплины'));
echo '</div>';
$this->renderPartial('student/_table1', array(
    'student' => $student,
    'model' => $model,
));


echo '<div class="page-header">';
echo CHtml::tag('h3', array(), tt('Документы подтверждающие в научно-иследовательской деятельности'));
echo '</div>';
$this->widget(
    'bootstrap.widgets.TbButton',
    array(
        'label' => tt('Добавить'),
        'url' => Yii::app()->createUrl('/portfolio/uploadFile',
            array(
                'type' => CreateZrstForm::TYPE_TABLE2,
                'id' =>  $model->student
            )
        ),
        'type' => 'success',
        'size' => 'mini',
        'icon' => 'plus'
    )
);
$this->renderPartial('student/_table2', array(
    'student' => $student
));


echo '<div class="page-header">';
echo CHtml::tag('h3', array(), tt('Документы подтверждающие участие в общественной деятельности'));
echo '</div>';
$this->widget(
    'bootstrap.widgets.TbButton',
    array(
        'label' => tt('Добавить'),
        'url' => Yii::app()->createUrl('/portfolio/uploadFile',
            array(
                'type' => CreateZrstForm::TYPE_TABLE3,
                'id' =>  $model->student
            )
        ),
        'type' => 'success',
        'size' => 'mini',
        'icon' => 'plus'
    )
);
$this->renderPartial('student/_table3', array(
    'student' => $student
));

echo '<div class="page-header">';
echo CHtml::tag('h3', array(), tt('Публикации в журналах, сборниках'));
echo '</div>';
$this->widget(
    'bootstrap.widgets.TbButton',
    array(
        'label' => tt('Добавить'),
        'url' => Yii::app()->createUrl('/portfolio/uploadFile',
            array(
                'type' => CreateZrstForm::TYPE_TABLE4,
                'id' =>  $model->student
            )
        ),
        'type' => 'success',
        'size' => 'mini',
        'icon' => 'plus'
    )
);
$this->renderPartial('student/_table4', array(
    'student' => $student
));


echo '<div class="page-header">';
echo CHtml::tag('h3', array(), tt('Участие в спортивных, творческих и культурно-массовых мероприятиях'));
echo '</div>';
$this->widget(
    'bootstrap.widgets.TbButton',
    array(
        'label' => tt('Добавить'),
        'url' => Yii::app()->createUrl('/portfolio/uploadFile',
            array(
                'type' => CreateZrstForm::TYPE_TABLE5,
                'id' =>  $model->student
            )
        ),
        'type' => 'success',
        'size' => 'mini',
        'icon' => 'plus'
    )
);
$this->renderPartial('student/_table5', array(
    'student' => $student
));
