<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 19.12.2016
 * Time: 20:40
 */
/**
 * @var $model Tddo
 * @var $docTypeModel Ddo
 * */
    $this->pageHeader=tt('Документооборот: №').$model->tddo3;
    $this->breadcrumbs=array(
        tt('Документооборот: №').$model->tddo3,
    );
?>
    <div class="row-fluid" >

<?php
    $docTypeModel = Ddo::model()->findByPk($model->tddo2);
    if(empty($docTypeModel))
        throw new Exception("docTypeModel");

    $link = CHtml::link(sprintf('<<< %s (%s)', tt('На список документов'), $docTypeModel->ddo2),Yii::app()->createAbsoluteUrl('/doc/index',array('docType'=>$model->tddo2,'docYear'=>$model->tddo23)));
    echo <<<HTML
        <div>
            $link
        </div>
HTML;


    $items = $docTypeModel->generateAttributesView($this,$model);

    $items = array_merge(
        $items,
        array(
            'tddo21'=>array(
                'name'=>'tddo21',
                'header'=>$model->getAttributeLabel('tddo21'),
                'value'=>$model->tddo21,
            )
        )
    );

    echo '<h5>',$docTypeModel->ddo2,'</h5>';


    $this->widget('bootstrap.widgets.TbDetailView',array(
        'data'=>$model,
        'attributes'=>
            array_merge(
                array(
                    'tddo3'
                ),
                $items
            )
    ));

    $this->renderPartial('_attachedFiles', array(
        'model' => $model
    ));
?>
</div>
