<?php
/**
 * Created by PhpStorm.
 * User: neffa
 * Date: 04.02.2019
 * Time: 13:22
 */

/**
 * @var P $teacher
 * @var FilterForm $model
 */

$dataProvider=new CArrayDataProvider(Zrst::model()->getTable1DataTeacher($teacher->p1, $model->discipline),array(
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
            'header'=>tt('Студент'),
            'value'=>function($data){
                return sprintf('%s %s %s', $data['st2'], $data['st3'], $data['st4']);
            },
        ),
        array(
            'header'=>tt('Группа'),
            'value'=>'Gr::model()->getGroupName($data["sem4"], $data)'
        ),
        array(
            'header'=>tt('Отзыв'),
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
                if(empty($data['recenziya']))
                    return CHtml::link('<i class="icon-plus"></i>',
                        Yii::app()->createUrl('/portfolio/uploadFile',
                            array(
                                'us1'=> $model->discipline,
                                'type' => CreateZrstForm::TYPE_TEACHER,
                                'id' =>  $data['st1']
                            )
                        ),
                        array(
                            'class' => 'btn btn-success btn-mini'
                        )
                    );
                else
                    return CHtml::link('<i class="icon-trash"></i>',
                        Yii::app()->createUrl('/portfolio/removeFile', array('id'=> $data['recenziya'])),
                        array(
                            'class' => 'btn btn-danger btn-mini'
                        )
                    );
            },
        ),
    ),
));
