<?php
/**
 * Created by PhpStorm.
 * User: neffa
 * Date: 18.03.2019
 * Time: 16:41
 */

/**
 * @var $st St
 */

$dataProvider=new CArrayDataProvider($st->getGostem(),array(
    'sort'=>false,
    'pagination'=>false,
    'keyField' => 'stusvst1'
));

$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'gostem-list',
    'dataProvider' => $dataProvider,
    'filter' => null,
    'type' => 'striped bordered',
    'columns' => array(
        array(
            'header'=>tt('Год'),
            'value'=>'$data["sem3"]',
        ),
        array(
            'header'=>tt('Семестр'),
            'value'=>'SH::convertSem5($data["sem5"])',
        ),
        array(
            'header'=>tt('Номер семестра'),
            'value'=>'$data["sem7"]',
        ),
        array(
            'header'=>tt('Курс'),
            'value'=>'$data["sem4"]',
        ),
        array(
            'header'=>tt('Гос.экзамен'),
            'value'=>'$data["d2"]',
        ),
        array(
            'header'=>tt('Оценка'),
            'value'=>'round($data["stusvst6"],2)',
        )
    ),
));