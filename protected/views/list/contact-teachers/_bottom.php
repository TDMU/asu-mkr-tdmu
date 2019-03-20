<?php
/**
 * @var ListController $this
 * @var FilterForm $model
 */

    $dataProvider=new CArrayDataProvider(P::model()->getTeachersForContactTeachers($model->chair),array(
        'sort'=>false,
        'pagination'=>false,
        'keyField' => 'p1'
    ));

$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'teachers-list',
    'dataProvider' => $dataProvider,
    'filter' => null,
    'type' => 'striped bordered',
    'columns' => array(
        array(
            'header'=>tt('ФИО'),
            'value'=>function($data){
                return SH::getShortName($data['p3'], $data['p4'], $data['p5']);
            },
        ),
        array(
            'header'=>tt('Логин Skype'),
            'type' => 'raw',
            'value'=>function($data){
                if(empty($data['p81']))
                    return '';
                return CHtml::link(
                    $data['p81'],
                    'skype:'.$data['p81'].'?chat'
                );
            },
        )
    ),
));
