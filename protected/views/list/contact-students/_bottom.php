<?php
/**
 * @var ListController $this
 * @var FilterForm $model
 */

    $dataProvider=new CArrayDataProvider(St::model()->getListGroup($model->group),array(
        'sort'=>false,
        'pagination'=>false,
        'keyField' => 'st1'
    ));

$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'students-list',
    'dataProvider' => $dataProvider,
    'filter' => null,
    'type' => 'striped bordered',
    'columns' => array(
        array(
            'header'=>tt('ФИО'),
            'value'=>function($data){
                return SH::getShortName($data['st2'], $data['st3'], $data['st4']);
            },
        ),
        array(
            'header'=>tt('Логин Skype'),
            'type' => 'raw',
            'value'=>function($data){
                if(empty($data['st107']))
                    return '';
                return CHtml::link(
                    $data['st107'],
                    'skype:'.$data['st107'].'?chat'
                );
            },
        )
    ),
));
