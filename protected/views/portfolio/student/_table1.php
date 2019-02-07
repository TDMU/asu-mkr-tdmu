<?php
/**
 * Created by PhpStorm.
 * User: neffa
 * Date: 04.02.2019
 * Time: 13:56
 */

/**
 * @var St $student
 * @var TimeTableForm $model
 */

$dataProvider=new CArrayDataProvider(Zrst::model()->getTable1Data($student->st1),array(
    'sort'=>false,
    'pagination'=>false,
    'keyField' => false
));

$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'table1-list',
    'dataProvider' => $dataProvider,
    'filter' => null,
    'type' => 'striped bordered',
    'template' => '{items}',
    'columns' => array(
        array(
            'header'=>tt('Уч. год, семестр'),
            'value'=>function($data){
                return sprintf('%d/%d %s', $data['sem3'], $data['sem3'] + 1, SH::convertSem5($data['sem5']));
            },
        ),
        array(
            'header'=>tt('Дисциплина'),
            'value'=>'$data["d2"]'
        ),
        array(
            'header'=>tt('Вид работы'),
            'value'=>'$data["vid"]'
        ),
        array(
            'header'=>tt('Оценка'),
            'value'=>'$data["ocenka"] == -1 ? "зач.": $data["ocenka"] '
        ),
        array(
            'header'=>tt('Работа'),
            'type' => 'raw',
            'value'=>function($data){
                if(empty($data['rabota']))
                    return '';
                return CHtml::link('просмотр', Yii::app()->createUrl('/portfolio/showFile', array('id'=> $data['rabota'])),array('target'=>'_blank'));
            },
        ),
        array(
            'header'=>tt('Рецензия'),
            'type' => 'raw',
            'value'=>function($data){
                if(empty($data['recenziya']))
                    return '';
                return CHtml::link('просмотр', Yii::app()->createUrl('/portfolio/showFile', array('id'=> $data['recenziya'])),array('target'=>'_blank'));
            },
        ),
        array(
            'header'=>tt('Файл'),
            'type' => 'raw',
            'value'=>function($data) use (&$model){
                if(empty($data['rabota']))
                    return CHtml::link('<i class="icon-plus"></i>',
                        Yii::app()->createUrl('/portfolio/uploadFile',
                            array(
                                'us1'=> $data['us1'],
                                'type' => CreateZrstForm::TYPE_TABLE1,
                                'id' =>  $model->student
                            )
                        ),
                        array(
                            'class' => 'btn btn-success btn-mini'
                        )
                    );
                else
                    return CHtml::link('<i class="icon-trash"></i>',
                        Yii::app()->createUrl('/portfolio/removeFile', array('id'=> $data['rabota'])),
                        array(
                            'class' => 'btn btn-danger btn-mini'
                        )
                    );
                },
        ),
    ),
));