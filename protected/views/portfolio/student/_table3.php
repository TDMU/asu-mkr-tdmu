<?php
/**
 * Created by PhpStorm.
 * User: neffa
 * Date: 04.02.2019
 * Time: 13:57
 */


$dataProvider=new CArrayDataProvider($data = Zrst::model()->getTableData($student->st1, 2),array(
    'sort'=>false,
    'pagination'=>false,
    'keyField' => 'zrst1'
));

$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'table2-list',
    'dataProvider' => $dataProvider,
    'filter' => null,
    'type' => 'striped bordered',
    'template' => '{items}',
    'columns' => array(
        array(
            'header'=>tt('Пояснение'),
            'value'=>'$data["zrst7"]'
        ),
        array(
            'header'=>tt('Подтверждение'),
            'type' => 'raw',
            'value'=>function($data){
                return CHtml::link('просмотр', Yii::app()->createUrl('/portfolio/showFile', array('id'=> $data['zrst1'])),array('target'=>'_blank'));
            },
        ),
        array(
            'header'=>tt('Файл'),
            'type' => 'raw',
            'value'=>function($data){
                return CHtml::link('<i class="icon-trash"></i>',
                    Yii::app()->createUrl('/portfolio/removeFile', array('id'=> $data['zrst1'])),
                    array(
                        'class' => 'btn btn-danger btn-mini'
                    )
                );
            },
        ),
    ),
));