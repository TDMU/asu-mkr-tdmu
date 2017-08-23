<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 21.09.2016
 * Time: 22:00
 */

$this->pageHeader=tt('Обшая информация').' '. $model->getFullName();
$this->breadcrumbs=array(
    tt('Студенты') => array('/admin/default/students'),
    tt('Обшая информация'),
);

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'st-inforrm',
    'type' => 'horizontal',
    //'action' => ''//$this->createUrl('/admin/prospect/scheduleProspect')
));?>


    <div class="control-group">
        <span class="lbl"> <?=tt('Общая информация')?>:</span>
        <?php $this->widget('application.extensions.elFinderTinyMce.TinyMce',
            array(
                'model'=>$model,
                'attribute'=>'st165',
                'htmlOptions'=>array('rows'=>6, 'cols'=>50, 'class'=>'tinymce'),
                'fileManager' => array(
                    'class' => 'ext.elFinder.TinyMceElFinder',
                    'connectorRoute'=>'/admin/default/connector',
                ),
                /*'settings'=>array(
                    'theme' => "advanced",
                    'skin' => 'default',
                    'language' => Yii::app()->language,
                ),*/
            )); ?>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-info">
            <i class="icon-ok bigger-110"></i>
            <?=tt('Сохранить')?>
        </button>
    </div>

<?php $this->endWidget();