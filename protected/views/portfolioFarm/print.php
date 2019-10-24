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
    $label = CHtml::label($code.$fields[$id]['text'].': ', '', array(
        'class' => 'label-field'
    ));
    return CHtml::tag('p', array(),$label.$value);
}

/**
 * @var PortfolioFarmController $this
 * @var St $student
 */


$studentInfo = $student->getStudentInfoForPortfolio();
$fieldList = Stportfolio::model()->getFieldsList();

echo '<h3>1. РЕЗЮМЕ</h3>';

echo CHtml::image(Yii::app()->createUrl('site/userPhoto',array( '_id'=>$student->st1, 'type'=>1)), $student->getShortName(), array(
    'style' => 'float:right;height: 200px;'
));
echo CHtml::openTag('ul', array(
    'class' => 'ul-fields',
    'style' => 'margin-top:200px'
));

echo CHtml::openTag('ol');
echo CHtml::tag('p', array(),CHtml::label('Прізвище, ім`я, по батькові: ', '', array(
    'class' => 'label-field'
)).$student->fullName);
echo CHtml::closeTag('ol');
echo CHtml::openTag('ol');
echo CHtml::tag('p', array(),CHtml::label('Дата народження: ', '', array(
    'class' => 'label-field'
)).date('d.m.Y', strtotime($student->st7)));
echo CHtml::closeTag('ol');
echo CHtml::openTag('ol');
echo printFieldValue($fieldList, $student->st1, Stportfolio::FIELD_EDUCATION_SCHOOL);
echo CHtml::closeTag('ol');
echo CHtml::openTag('ol');
echo CHtml::tag('p', array(),CHtml::label('Спеціальність, яку отримує у ЗВО: ', '', array(
    'class' => 'label-field'
)).$studentInfo['pnsp2']);
echo CHtml::closeTag('ol');
echo CHtml::openTag('ol');
echo CHtml::tag('p', array(),CHtml::label('Освітня програма: ', '', array(
    'class' => 'label-field'
)). $studentInfo['spc4']);
echo CHtml::closeTag('ol');
echo CHtml::openTag('ol');
echo printFieldValue($fieldList, $student->st1, Stportfolio::FIELD_EXTRA_EDUCATION);
echo CHtml::closeTag('ol');
echo CHtml::openTag('ol');
echo printFieldValue($fieldList, $student->st1, Stportfolio::FIELD_WORK_EXPERIENCE);
echo CHtml::closeTag('ol');
echo CHtml::openTag('ol');
echo printFieldValue($fieldList, $student->st1, Stportfolio::FIELD_PHONE);
echo CHtml::closeTag('ol');
echo CHtml::openTag('ol');
echo printFieldValue($fieldList, $student->st1, Stportfolio::FIELD_EMAIL);
echo CHtml::closeTag('ol');

echo CHtml::closeTag('ul');

echo '<h3>2. ПОРТФОЛІО ДОСЯГНЕНЬ</h3>';

echo CHtml::openTag('ul', array(
    'class' => 'ul-fields'
));

echo CHtml::openTag('ol');
echo CHtml::label('2.1'.'&nbsp;'.'Навчально-професійна діяльність', '', array(
'class' => 'label-field'
));

$dataProvider3 = new CArrayDataProvider(
    Stpwork::model()->findAll(
        'stpwork2 = :stpwork2 and stpwork7 is not null',
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
Yii::app()->controller->widget('zii.widgets.grid.CGridView', array(
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
echo CHtml::closeTag('ol');
echo CHtml::openTag('ol');
echo printFieldValue($fieldList, $student->st1, Stportfolio::FIELD_EXTRA_COURSES, '2.2&nbsp;');
echo CHtml::closeTag('ol');
echo CHtml::openTag('ol');
echo CHtml::label('2.3'.'&nbsp;'.'Дані щодо участі у заходах', '', array(
    'class' => 'label-field'
));

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
echo CHtml::closeTag('ol');
echo CHtml::openTag('ol');
echo printFieldValue($fieldList, $student->st1, Stportfolio::FIELD_OLIMPIADS, '2.4&nbsp;');
echo CHtml::closeTag('ol');
echo CHtml::openTag('ol');
echo printFieldValue($fieldList, $student->st1, Stportfolio::FIELD_SPORTS, '2.5&nbsp;');
echo CHtml::closeTag('ol');
echo CHtml::openTag('ol');
echo printFieldValue($fieldList, $student->st1, Stportfolio::FIELD_SCIENCES, '2.6&nbsp;');
echo CHtml::closeTag('ol');
echo CHtml::openTag('ol');
echo printFieldValue($fieldList, $student->st1, Stportfolio::FIELD_STUD_ORGS, '2.7&nbsp;');
echo CHtml::closeTag('ol');
echo CHtml::openTag('ol');
echo printFieldValue($fieldList, $student->st1, Stportfolio::FIELD_VOLONTER, '2.8&nbsp;');
echo CHtml::closeTag('ol');
echo CHtml::openTag('ol');
echo printFieldValue($fieldList, $student->st1, Stportfolio::FIELD_GROMADSKE, '2.9&nbsp;');
echo CHtml::closeTag('ol');

echo CHtml::closeTag('ul');

echo '<h3>3. ПОРТФОЛІО РОБІТ</h3>';

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

echo '<h3>4. ПОРТФОЛІО ВІДГУКІВ</h3>';

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