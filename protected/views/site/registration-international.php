<?php
/* @var $this SiteController */
/* @var $model RegistrationForm */
/* @var $form CActiveForm  */
$this->pageTitle=tt('Регистрация иностранных граждан');
$this->pageHeader=$this->pageTitle;
?>

<div class="regisrtation-container ">
    <div class="row-fluid">
        <div class="position-relative">
            <div class="signup-box widget-box no-border visible" id="signup-box">
                <div class="widget-body">
                    <div class="widget-main">
                        <h4 class="header green lighter bigger">
                            <i class="icon-group blue"></i>
                            <?=tt('Укажите необходимую информацию')?>
                        </h4>

                        <div class="space-6"></div>
                        <div id="replace-there">
                        <?php $form=$this->beginWidget('CActiveForm', array(
                            'id'=>'registration-international-form',
                            //'action' => Yii::app()->createUrl("site/registrationInternational")
                        )); ?>
                            <fieldset>
                                <?=$form->errorSummary($model)?>

                                <?php
                                if($model->scenario == 'step-1'){?>
                                    <label>
                                        <span class="block input-icon input-icon-right">
                                            <?=$form->textField($model, 'number', array('class' => 'span12', 'placeholder' => $model->getAttributeLabel('number')))?>
                                            <?=$form->error($model, 'number')?>
                                            <i class="icon-barcode"></i>
                                        </span>
                                    </label>
                                <?php
                                }else{
                                    echo $form->hiddenField($model, 'number');
                                }

                                if($model->scenario != 'step-1'){
                                    if($model->scenario == 'step-2'){?>
                                        <label>
                                            <span class="block input-icon input-icon-right">
                                                <?=$form->textField($model, 'serial', array('class' => 'span12', 'placeholder' => $model->getAttributeLabel('serial')))?>
                                                <?=$form->error($model, 'serial')?>
                                                <i class="icon-barcode"></i>
                                            </span>
                                        </label>
                                        <?php
                                    }else{
                                        echo $form->hiddenField($model, 'serial');
                                    }
                                }

                                if($model->scenario == 'step-3'){?>
                                <label>
                                    <span class="block input-icon input-icon-right">
                                        <?=$form->textField($model, 'email', array('class' => 'span12', 'placeholder' => $model->getAttributeLabel('email'), 'type' => 'email'))?>
                                        <?=$form->error($model, 'email')?>
                                        <i class="icon-envelope"></i>
                                    </span>
                                </label>

                                <label>
                                    <span class="block input-icon input-icon-right">
                                        <?=$form->textField($model, 'username', array('class' => 'span12', 'placeholder' =>$model->getAttributeLabel('username')))?>
                                        <?=$form->error($model, 'username')?>
                                        <i class="icon-user"></i>
                                    </span>
                                </label>

                                <label>
                                    <span class="block input-icon input-icon-right">
                                        <?=$form->passwordField($model, 'password', array('class' => 'span12', 'placeholder' => $model->getAttributeLabel('password')))?>
                                        <?=$form->error($model, 'password')?>
                                        <i class="icon-lock"></i>
                                    </span>
                                </label>

                                <label>
                                    <span class="block input-icon input-icon-right">
                                        <?=$form->passwordField($model, 'password2', array('class' => 'span12', 'placeholder' => $model->getAttributeLabel('password2')))?>
                                        <?=$form->error($model, 'password2')?>
                                        <i class="icon-retweet"></i>
                                    </span>
                                </label>
                                <?php
                                /*if(CCaptcha::checkRequirements()): ?>
                                    <div class="form-group">
                                        <?php $form->labelEx($model,'verifyCode'); ?>
                                        <div>
                                            <?php $this->widget('CCaptcha'); ?>
                                            <?php echo $form->textField($model,'verifyCode'); ?>
                                        </div>
                                        <?php echo $form->error($model,'verifyCode'); ?>
                                    </div>
                                <?php endif;*/
                                } ?>

                                <div class="space-24"></div>

                                <div class="clearfix">
                                    <input id="reset" class="width-30 pull-left btn btn-small" type="reset">
                                    </input>

                                    <button data-loading-text="Loading..." class="width-65 pull-right btn btn-small btn-success" type="submit">
                                        <?php if($model->scenario == 'step-3') {
                                            echo tt('Зарегистрироваться');
                                        }else{
                                            echo tt('Следующий шаг');
                                        }
                                        ?>
                                        <i class="icon-arrow-right icon-on-right"></i>
                                    </button>
                                </div>
                            </fieldset>
                        <?php $this->endWidget(); ?>
                        </div>
                    </div>

                </div><!-- /widget-body -->
            </div>
        </div><!-- /position-relative -->
    </div>
</div>
