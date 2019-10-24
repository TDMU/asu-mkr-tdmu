<?php
/**
 * Created by PhpStorm.
 * User: neffa
 * Date: 05.02.2019
 * Time: 10:09
 */

/**
 * @var $model CreateZrstForm
 */

$this->pageTitle=tt('Добавление файла');
$this->pageHeader=tt('Добавление файла');
?>

<div class="span4">
    <div class="widget-box">
        <div class="widget-header">
            <h4><?=tt('Добавление')?></h4>
        </div>

        <div class="widget-body">
            <div class="widget-main no-padding">
                    <?php
                    /**
                     * @var $form TbActiveForm
                     */
                    $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                        'id'=>'attach-file-form',
                        'htmlOptions' => array(
                            'enctype' => 'multipart/form-data',
                        )
                    ));
                    ?>

                    <fieldset>
                        <?= $form->fileFieldRow($model,'file') ?>
                    </fieldset>

                    <fieldset>
                        <?= $form->textFieldRow($model,'note') ?>
                    </fieldset>

                    <?php
                        if($model->scenario == CreateZrstForm::TYPE_TABLE2):
                   ?>
                        <fieldset>
                            <?= $form->dropDownListRow($model,'zrst5', CreateZrstForm::getZrst5Types()) ?>
                        </fieldset>
                    <?php endif;?>

                    <?php if(CCaptcha::checkRequirements()): ?>
                        <?php /*$form->labelEx($model,'verifyCode'); */?>
                        <fieldset>
                            <?php $this->widget('CCaptcha',array(
                                    'captchaAction' => 'site/captcha'
                            )); ?>
                            <?php echo $form->textFieldRow($model,'verifyCode'); ?>
                        </fieldset>
                    <?php endif; ?>

                    <div class="form-actions center">
                        <button class="btn btn-small btn-success">
                            <?=tt('Отправить')?>
                            <i class="icon-arrow-right icon-on-right bigger-110"></i>
                        </button>
                    </div>
                    <?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
</div>