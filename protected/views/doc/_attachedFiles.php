<?php
/**
 *
 * @var DocsController $this
 * @var Tddo $model
 * @var Fpdd $fpdd
 */

$attachedFiles = $model->getFiles();
?>


<table class="table table-striped table-bordered table-hover">
    <thead>
    <tr>
        <th><?=tt('Прикрепленные файлы')?></th>
    </tr>
    </thead>

    <tbody>
    <?php
        $html = '';
        foreach ($attachedFiles as $file) {
            $link = CHtml::link($file->fpdd5, Yii::app()->baseUrl.'/uploads/docs/'.$file->fpdd4, array('target' => '_blank'));
            $html .= '<tr>
                        <td>'.$link.'</td>
                      </tr>';
        }
        echo $html;
    ?>

    </tbody>
</table>

<?php
/*

<div class="widget-box no-margin span6">
    <div class="widget-header">
        <h4>Custom File Input</h4>
    </div>

    <div class="widget-body">
        <div class="widget-main">
            <?php
                $form=$this->beginWidget('CActiveForm', array(
                    'id'=>'attach-file-form',
                    'htmlOptions' => array(
                        'enctype' => 'multipart/form-data',
                        'class' => 'form-horizontal'
                    )
                ));
            ?>

            <?php echo CHtml::fileField('files[]', '', array('id' => 'id-input-file-3', 'multiple' => 'true')) ?>


            <div class="form-actions">
                <button type="submit" class="btn btn-info">
                    <i class="icon-ok bigger-110"></i>
                    <?=tt('Сохранить')?>
                </button>
            </div>

            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>*/

