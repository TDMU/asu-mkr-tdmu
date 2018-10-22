<?php
/**
 * Created by PhpStorm.
 * User: NEFF
 * Date: 21.11.2017
 * Time: 14:34
 */

$this->pageTitle=tt('Регистрация в системе дистанционного обучения');
$this->pageHeader=tt('Регистрация в системе дистанционного обучения: существующая учетная запись');

/**
 * @var $form TbActiveForm
 * @var $model SingUpOldDistEducationForm
 */
?>

<div class="row-fluid">
    <div class="position-relative">
        <div id="changePassword-box" class="visible widget-box no-border">
            <div class="widget-body">
                <div class="widget-main">

                    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                    )); ?>

                        <fieldset>

                        <?php echo $form->errorSummary($model); ?>

                        <?=$model->getFormHtml($form)?>

                        <?php /*if(CCaptcha::checkRequirements()): ?>
                            <div>
                                <?php echo $form->labelEx($model,'verifyCode'); ?>
                                <div>
                                    <?php $this->widget('CCaptcha'); ?>
                                    <?php echo $form->textField($model,'verifyCode'); ?>
                                </div>
                                <div class="hint"><?=tt('Пожалуйста, введите буквы, как показано на изображении выше.
                                    <br/> Буквы не чувствительны к регистру.')?></div>
                                <?php echo $form->error($model,'verifyCode'); ?>
                            </div>
                        <?php endif;*/ ?>

                        <div class="space"></div>

                        <div class="clearfix">
                            <button  data-loading-text="Loading..." class="width-35 pull-right btn btn-small btn-primary" type="submit">
                                <i class="icon-ok"></i>
                                <?=tt('Сохранить')?>
                            </button>
                        </div>
                        </fieldset>
                    <?php $this->endWidget();?>

                </div>
            </div><!-- /widget-main -->

        </div><!-- /widget-body -->
    </div><!-- /login-box -->
</div>
