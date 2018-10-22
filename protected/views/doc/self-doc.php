<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 20.06.2016
 * Time: 16:24
 */

/**
 * @var $model Tddo
 * @var $form CActiveForm
 * @var $docTypeModel Ddo
 * @var $this DocController
 */
$this->pageHeader=tt('Документооборот: К исполнению');
$this->breadcrumbs=array(
    tt('Документооборот: К исполнению'),
);

$this->renderPartial(
    '_list',
array(
    'dataProvider'=>$model->searchSelf(),
    'model'=>$model
));