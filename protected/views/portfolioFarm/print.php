<?php
/**
 * Created by PhpStorm.
 * User: Lesha
 * Date: 27.09.2019
 * Time: 10:40
 */


/**
 * @param $fields
 * @param $st1
 * @param $id
 * @param string $code
 * @return string
 * @throws CException
 */
function printFieldValue($fields, $st1, $id, $code = ''){
    $value = Stportfolio::model()->getPrintValue($st1, $id);
    if(empty($value))
        $value = '-';
    $label = CHtml::tag('strong' ,array(),$code.$fields[$id]['text'].': ');
    return $label.$value;
}

/**
 * @var PortfolioFarmController $this
 * @var St $student
 */
$studentInfo = $student->getStudentInfoForPortfolio();
$fieldList = Stportfolio::model()->getFieldsList();
echo '<h2>МІНІСТЕРСТВО  ОХОРОНИ  ЗДОРОВ’Я  УКРАЇНИ<br>
НАЦІОНАЛЬНИЙ ФАРМАЦЕВТИЧНИЙ УНІВЕРСИТЕТ
</h2>';
echo CHtml::image('data:image/png;base64,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', '', array(
    'style'=>'height:100px; margin-left:40%'
));
echo '<h2>'.tt('Портфолио').'</h2>';
echo '<h3 style="text-align: center">1. '.tt('Резюме').'</h3>';

echo CHtml::image(Yii::app()->createUrl('site/userPhoto',array( '_id'=>$student->st1, 'type'=>1)), $student->getShortName(), array(
    'style' => 'float:right;height: 200px;'
));
echo CHtml::openTag('div', array(
    'class' => 'ul-fields',
    'style' => 'margin-top:200px'
));

echo CHtml::openTag('div');
echo CHtml::tag('strong', array('class' =>'label-field'),tt('Фамилия, имя, отчество: ')).$student->fullName;
echo CHtml::closeTag('div');
echo CHtml::openTag('div');
echo CHtml::tag('strong', array('class' =>'label-field'),tt('Дата рождения: ')).date('d.m.Y', strtotime($student->st7));
echo CHtml::closeTag('div');
echo CHtml::openTag('div');
echo printFieldValue($fieldList, $student->st1, Stportfolio::FIELD_EDUCATION_SCHOOL);
echo CHtml::closeTag('div');
echo CHtml::openTag('div');
echo CHtml::tag('strong', array('class' =>'label-field'),tt('Специальность, которую получает в ЗВО: ')).$studentInfo['pnsp2'];
echo CHtml::closeTag('div');
echo CHtml::openTag('div');
echo CHtml::tag('strong', array('class' =>'label-field'),tt('Образовательная программа: ')). $studentInfo['spc4'];
echo CHtml::closeTag('div');
echo CHtml::openTag('div');
echo printFieldValue($fieldList, $student->st1, Stportfolio::FIELD_EXTRA_EDUCATION);
echo CHtml::closeTag('div');
echo CHtml::openTag('div');
echo printFieldValue($fieldList, $student->st1, Stportfolio::FIELD_WORK_EXPERIENCE);
echo CHtml::closeTag('div');
echo CHtml::openTag('div');
echo printFieldValue($fieldList, $student->st1, Stportfolio::FIELD_PHONE);
echo CHtml::closeTag('div');
echo CHtml::openTag('div');
echo printFieldValue($fieldList, $student->st1, Stportfolio::FIELD_EMAIL);
echo CHtml::closeTag('div');

echo CHtml::closeTag('div');

echo '<h3>2. '.tt('Портфолио достижений').'</h3>';

echo CHtml::openTag('div', array(
    'class' => 'ul-fields'
));

echo CHtml::openTag('div');
echo CHtml::tag('strong', array(), '2.1'.'&nbsp;'.tt('Учебно-профессиональная деятельность'));

$dataProvider3 = new CArrayDataProvider(
    Stpwork::model()->findAll(
        'stpwork2 = :stpwork2 and stpwork9 is not null',
        array(
            ':stpwork2' => $student->st1,
        )
    ),
    array(
        'keyField'=>'stpwork1',
        'sort'=>false,
        'pagination'=>false
    )
);
echo Yii::app()->controller->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $dataProvider3,
    'filter' => null,
    'template'=>'{items}',
    'columns' => array(
        array(
            'name' => 'stpwork3',
            'header' => Stpwork::model()->getAttributeLabel('stpwork3'),
            'value' => '$data->stpwork3',
        ),
        array(
            'name' => 'stpwork4',
            'header' => Stpwork::model()->getAttributeLabel('stpwork4'),
            'value' => '$data->stpwork4',
        ),
        array(
            'name' => 'stpwork5',
            'header' => Stpwork::model()->getAttributeLabel('stpwork5'),
            'value' => '$data->stpwork5',
        ),
        array(
            'name' => 'stpwork6',
            'header' => Stpwork::model()->getAttributeLabel('stpwork6'),
            'value' => '$data->stpwork6',
        ),
    )
), true);
echo CHtml::closeTag('div');
echo CHtml::openTag('div');
echo printFieldValue($fieldList, $student->st1, Stportfolio::FIELD_EXTRA_COURSES, '2.2&nbsp;');
echo CHtml::closeTag('div');
echo CHtml::openTag('div');
echo CHtml::tag('strong', array(), '2.3'.'&nbsp;'.tt('Данные об участии в мероприятиях'));

$dataProvider2 = new CArrayDataProvider(
    Stppart::model()->findAll(
        'stppart2 = :stppart2 and stppart12 is not null',
        array(
            ':stppart2' => $student->st1,
        )
    ),
    array(
        'keyField'=>'stppart1',
        'sort'=>false,
        'pagination'=>false
    )
);
echo Yii::app()->controller->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $dataProvider2,
    'filter' => null,
    'template'=>'{items}',
    'columns' => array(
        array(
            'name' => 'stppart3',
            'header' => Stppart::model()->getAttributeLabel('stppart3'),
            'value' => '$data->getStppart3Type()',
        ),
        array(
            'name' => 'stppart4',
            'header' => Stppart::model()->getAttributeLabel('stppart4'),
            'value' => '$data->stppart4',
        ),
        array(
            'name' => 'stppart5',
            'header' => Stppart::model()->getAttributeLabel('stppart5'),
            'value' => '$data->stppart5',
        ),
        array(
            'name' => 'stppart6',
            'header' => Stppart::model()->getAttributeLabel('stppart6'),
            'value' => '$data->getStppart6Type()',
        ),
        array(
            'name' => 'stppart7',
            'header' => Stppart::model()->getAttributeLabel('stppart7'),
            'value' => '$data->getStppart7Type()',
        ),
        array(
            'name' => 'stppart8',
            'header' => Stppart::model()->getAttributeLabel('stppart8'),
            'value' => '$data->getStppart8Type()',
        )
    )
), true);
echo CHtml::closeTag('div');
echo CHtml::openTag('div');
echo printFieldValue($fieldList, $student->st1, Stportfolio::FIELD_OLIMPIADS, '2.4&nbsp;');
echo CHtml::closeTag('div');
echo CHtml::openTag('div');
echo printFieldValue($fieldList, $student->st1, Stportfolio::FIELD_SPORTS, '2.5&nbsp;');
echo CHtml::closeTag('div');
echo CHtml::openTag('div');
echo printFieldValue($fieldList, $student->st1, Stportfolio::FIELD_SCIENCES, '2.6&nbsp;');
echo CHtml::closeTag('div');
echo CHtml::openTag('div');
echo printFieldValue($fieldList, $student->st1, Stportfolio::FIELD_STUD_ORGS, '2.7&nbsp;');
echo CHtml::closeTag('div');
echo CHtml::openTag('div');
echo printFieldValue($fieldList, $student->st1, Stportfolio::FIELD_VOLONTER, '2.8&nbsp;');
echo CHtml::closeTag('div');
echo CHtml::openTag('div');
echo printFieldValue($fieldList, $student->st1, Stportfolio::FIELD_GROMADSKE, '2.9&nbsp;');
echo CHtml::closeTag('div');

echo CHtml::closeTag('div');

echo '<h3>3. '.tt('Портфолио работ').'</h3>';

$dataProvider1 = new CArrayDataProvider(
    Stpeduwork::model()->findAll(
        'stpeduwork2 = :stpeduwork2 and stpeduwork8 is not null',
        array(
            ':stpeduwork2' => $student->st1,
        )
    ),
    array(
        'keyField'=>'stpeduwork1',
        'sort'=>false,
        'pagination'=>false
    )
);
echo Yii::app()->controller->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' =>$dataProvider1,
    'template'=>'{items}',
    'filter' => null,
    'columns' => array(
        array(
            'name' => 'stpeduwork3',
            'header' => Stpeduwork::model()->getAttributeLabel('stpeduwork3'),
            'value' => '$data->getStpeduwork3Type()',
        ),
        array(
            'name' => 'stpeduwork4',
            'header' => Stpeduwork::model()->getAttributeLabel('stpeduwork4'),
            'value' => '$data->stpeduwork4',
        ),
        array(
            'name' => 'stpeduwork5',
            'header' => Stpeduwork::model()->getAttributeLabel('stpeduwork5'),
            'value' => '$data->stpeduwork5',
        ),
    )
), true);

echo '<h3>4. '.tt('Портфолио отзывов').'</h3>';

$dataProvider = new CArrayDataProvider(
    Stpfile::model()->findAllByAttributes(
        array(
            'stpfile6' => -1,
            'stpfile5' => $student->st1
        )
    ),
    array(
        'keyField'=>'stpfile1',
        'sort'=>false,
        'pagination'=>false
    )
);

echo Yii::app()->controller->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $dataProvider,
    'filter' => null,
    'template'=>'{items}',
    'columns' => array(
        array(
            'name' => 'stpfile2',
            'header' => tt('Файл'),
            'type' => 'raw',
            'value' => function($data){
                return $data->stpfile2;
            }
        )
    )
), true);

echo '<h3>5. '.tt('Портфолио профессиональной реализации').'</h3>';

$stpfwork = $student->getStpfwork();

if(!empty($stpfwork->stpfwork2)) {
    echo CHtml::openTag('div');
    echo CHtml::tag('strong', array('class' => 'label-field'), $stpfwork->getAttributeLabel('stpfwork2')) .': ' . $stpfwork->stpfwork2;
    echo CHtml::closeTag('div');
}
if(!empty($stpfwork->stpfwork3)) {
    echo CHtml::openTag('div');
    echo CHtml::tag('strong', array('class' => 'label-field'), $stpfwork->getAttributeLabel('stpfwork3')) .': ' . $stpfwork->stpfwork3;
    echo CHtml::closeTag('div');
}