<?php
/* @var $this SiteController */
/* @var $model RegistrationForm */
/* @var $form CActiveForm  */

?>

<div class="login-container ">
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
                            'id'=>'registration-form',
                            'action' => Yii::app()->createUrl("site/registration")
                        )); ?>
                            <fieldset>
                                <label>
                                    <span class="block input-icon input-icon-right">
                                        <?=$form->textField($model, 'identityCode', array('class' => 'span12', 'placeholder' => tt('Идентификационный код')))?>
                                        <?=$form->error($model, 'identityCode')?>
                                        <i class="icon-barcode"></i>
                                    </span>
                                </label>

                                <label>
                                    <span class="block input-icon input-icon-right">
                                        <?=$form->textField($model, 'email', array('class' => 'span12', 'placeholder' => 'Email', 'type' => 'email'))?>
                                        <?=$form->error($model, 'email')?>
                                        <i class="icon-envelope"></i>
                                    </span>
                                </label>

                                <label>
                                    <span class="block input-icon input-icon-right">
                                        <?=$form->textField($model, 'username', array('class' => 'span12', 'placeholder' => tt('Имя пользователя')))?>
                                        <?=$form->error($model, 'username')?>
                                        <i class="icon-user"></i>
                                    </span>
                                </label>

                                <label>
                                    <span class="block input-icon input-icon-right">
                                        <?=$form->passwordField($model, 'password', array('class' => 'span12', 'placeholder' => tt('Пароль')))?>
                                        <?=$form->error($model, 'password')?>
                                        <i class="icon-lock"></i>
                                    </span>
                                </label>

                                <label>
                                    <span class="block input-icon input-icon-right">
                                        <?=$form->passwordField($model, 'password2', array('class' => 'span12', 'placeholder' => tt('Повторите пароль')))?>
                                        <?=$form->error($model, 'password2')?>
                                        <i class="icon-retweet"></i>
                                    </span>
                                </label>

                                <div class="space-24"></div>

                                <div class="clearfix">
                                    <button class="width-30 pull-left btn btn-small" type="reset">
                                        <i class="icon-refresh"></i>
                                        Reset
                                    </button>

                                    <button data-loading-text="Loading..." class="width-65 pull-right btn btn-small btn-success" type="submit">
                                        <?=tt('Зарегистрироваться')?>
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
