<?php
/**
 * Created by PhpStorm.
 * User: neffa
 * Date: 07.12.2018
 * Time: 17:17
 */

/**
 * @var $st St
 */

$references = $st->getReferences();

$dataProvider=new CArrayDataProvider($references,array(
    'sort'=>false,
    'pagination'=>false,
    'keyField' => 'rpspr0'
));

$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'references-list',
    'dataProvider' => $dataProvider,
    'filter' => null,
    'type' => 'striped bordered',
    'columns' => array(
        array(
            'header' => Rpspr::model()->getAttributeLabel('rpspr4'),
            'value'=>'$data->rpspr4'
        ),
        array(
            'header' => Rpspr::model()->getAttributeLabel('rpspr2'),
            'value'=>'$data->rpspr2'
        ),
        array(
            'header' => Rpspr::model()->getAttributeLabel('rpspr1'),
            'value'=>'$data->getType()'
        ),
        array(
            'header' => Rpspr::model()->getAttributeLabel('rpspr5'),
            'value'=>'$data->rpspr5'
        ),
        array(
            'header' => Rpspr::model()->getAttributeLabel('rpspr6'),
            'value'=>'$data->rpspr6'
        ),
        array(
            'header' => Rpspr::model()->getAttributeLabel('rpspr7'),
            'value'=>'$data->rpspr7'
        ),
    ),
));