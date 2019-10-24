<?php
/**
 *
 * @var DefaultController $this
 * @var St $model
 *
 */
$this->pageHeader=tt('Родители');
$this->breadcrumbs=array(
    tt('Админ. панель'),
);
?>

<?php
$provider = $model->getParentsForAdmin();
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'parents',
    'dataProvider' => $provider,
    'filter' => $model,
    'type' => 'striped bordered',
    'ajaxUrl' => Yii::app()->createAbsoluteUrl('/admin/default/parents'),
    'columns' => array(
        'st2',
        'st3',
        'st4',
        array(
            'header' => 'Login',
            'filter' => CHtml::textField('login', Yii::app()->request->getParam('login')),
            'name'   => 'parentsAccount.u2',
            'value'  => '! empty($data->parentsAccount)
                                ? $data->parentsAccount->u2
                                : ""',
        ),
        array(
            'class'=>'CButtonColumn',
            'template'=>'{grants} {enter} {delete}',
            'header' => tt('Настройки'),
            'buttons'=>array
            (
                'grants' => array(
                    'label'=>'<i class="icon-check bigger-120"></i>',
                    'imageUrl'=>false,
                    'url'=>'Yii::app()->createAbsoluteUrl("/admin/default/prntGrants", array("id" => $data->st1))',
                    'options' => array(
                        'class' => 'btn btn-mini btn-success',
                        'title'=>tt('Редактировать'),
                    ),
                ),
                'enter' => array(
                    'label'=>'<i class="icon-share bigger-120"></i>',
                    'imageUrl'=>false,
                    'url'=>'Yii::app()->createAbsoluteUrl("/admin/default/enter", array("id" => !empty($data->parentsAccount)? $data->parentsAccount->u1: "-1"))',
                    'options' => array(
                        'class' => 'btn btn-mini btn-primary',
                        'title'=>tt('Авторизироваться'),
                    ),
                    'visible'=>'!empty($data->parentsAccount)'
                ),
                'delete' => array(
                    'label'=>'<i class="icon-trash bigger-120"></i>',
                    'imageUrl'=>false,
                    'url'=>'Yii::app()->createAbsoluteUrl("/admin/default/deleteUser", array("id" => !empty($data->parentsAccount)? $data->parentsAccount->u1: "-1"))',
                    'options' => array(
                        'class' => 'btn btn-mini btn-danger',
                        'title'=>tt('Удалить'),
                    ),
                    'visible'=>'!empty($data->parentsAccount)'
                ),
            ),
        ),
    ),
));
