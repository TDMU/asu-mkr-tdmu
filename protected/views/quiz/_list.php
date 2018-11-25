<?php
/**
 * Created by PhpStorm.
 * User: neffa
 * Date: 17.11.2018
 * Time: 13:17
 */

/**
 * @var QuizController $this
 * @var TimeTableForm $model
 * @var Gr $group
 */
$dataProvider=new CArrayDataProvider(St::model()->getOpprezByGroup($group->gr1),array(
    'sort'=>false,
    'pagination'=>false,
    'keyField' => 'st1'
));

$list=CHtml::listData(Opr::model()->findAll(), 'opr1', 'opr2');

$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'oprrez-list',
    'dataProvider' => $dataProvider,
    'filter' => null,
    'type' => 'striped bordered',
    'htmlOptions'=>array(
        'data-url' => Yii::app()->createUrl('quiz/create')
    ),
    'columns' => array(
        array(
            'header'=>Oprrez::model()->getAttributeLabel('oprrez2'),
            'value'=>'$data["st2"] . " " . $data["st3"] . " " . $data["st4"]',
        ),
        array(
            'header'=>Oprrez::model()->getAttributeLabel('oprrez3'),
            'value'=>function($data) use (&$list){
                $select =  empty($data["oprrez"])? null : $data["oprrez"]->oprrez30->opr1;

                return CHtml::dropDownList('oprrez-student-'.$data['st1'], $select, $list, array(
                    'data-st1'=>$data['st1'],
                    'class' => 'oprrez-select',
                    'prompt' => tt('--Выберите вариант--')
                ));
            },
            'type'=>'raw',
        ),
        array(
            'header'=>Oprrez::model()->getAttributeLabel('oprrez4'),
            'value'=>function($data){
                if(empty($data["oprrez"]))
                    return '';
                return date("d.m.Y H:i:s", strtotime($data["oprrez"]->oprrez4));
            },
        ),
        array(
            'header'=>Oprrez::model()->getAttributeLabel('oprrez5'),
            'value'=>function($data){
                if(empty($data["oprrez"]))
                    return '';
                return $data["oprrez"]->oprrez50->name;
            },
        ),
    ),
));

Yii::app()->clientScript->registerCss('oprrez-select', <<<CSS
    .oprrez-select{
        margin-bottom: 0px;
        width: 100%;
    }
CSS
);
