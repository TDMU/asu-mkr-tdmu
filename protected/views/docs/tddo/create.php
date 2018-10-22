<?php
/**
 *
 * @var DocsController $this
 */
$docType = $model->tddo2;

$this->pageHeader=tt('Добавить документ');
$this->breadcrumbs=array(
    tt('Документооборот') => Yii::app()->createUrl('docs/tddo', array('docType' => $docType)),
    Ddo::model()->findByPk($docType)->getAttribute('ddo2')
);

$this->renderPartial('tddo/_form', array(
    'model' => $model
));

