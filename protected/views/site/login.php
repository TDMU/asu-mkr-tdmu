<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form TbActiveForm  */

$this->pageTitle = 'Авторизация';
$this->pageHeader=$this->pageTitle;
?>

<div class="login-container ">
    <div class="row-fluid">
        <div class="position-relative">
            <div id="login-box" class="login-box visible widget-box no-border">
                <div class="widget-body">
                    <div class="widget-main">
                        <h5 class="header blue lighter bigger">
                            <i class="icon-coffee green"></i>
                            <?=tt('Укажите необходимую информацию')?>
                        </h5>

                        <div class="space-6"></div>
                        <div id="replace-there">
                        <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                            'id'=>'login-form-new',
                            'type' => 'horizontal',
                            'action' => Yii::app()->createUrl("site/login")
                        )); ?>
                            <fieldset>
                                <?php
                                /*$url = Yii::app()->request->urlReferrer;
                                $host = Yii::app()->request->hostInfo;
                                echo $url.' '.$host;*/
                                    //$model->setNewValidationKey();
                                    //echo $form->hiddenField($model, 'validationKey');
                                ?>
                                <label>
                                    <span class="block input-icon input-icon-right">
                                        <?=$form->textFieldRow($model, 'username', array('class' => 'span12', 'placeholder' => tt('Логин')))?>
                                        <i class="icon-user"></i>
                                    </span>
                                </label>

                                <label>
                                    <span class="block input-icon input-icon-right">
                                        <?=$form->passwordFieldRow($model, 'password', array('class' => 'span12', 'placeholder' => tt('Пароль')))?>
                                        <i class="icon-lock"></i>
                                    </span>
                                </label>

                                <?php
                               /* $criteria = new CDbCriteria;
                                //$criteria->addCondition('LOWER(U2)=:USERNAME OR LOWER(U4)=:EMAIL');
                                $criteria->addCondition('u2=\'leshamkr\'');

                                $setting = PortalSettings::model()->getSettingFor(113);
                                var_dump($setting);

                                $user = Users::model()->find($criteria);
                                var_dump($user->getCountFail($setting));*/
                                /*if(CCaptcha::checkRequirements()): ?>
                                    <div class="form-group">
                                        <?php //$form->labelEx($model,'verifyCode'); ?>
                                        <div>
                                            <?php $this->widget('CCaptcha'); ?>
                                            <?php echo $form->textField($model,'verifyCode'); ?>
                                        </div>
                                        <?php echo $form->error($model,'verifyCode'); ?>
                                    </div>
                                <?php endif; */?>

                                <div class="space"></div>

                                <div class="clearfix">
                                    <!--<label class="inline">
                                        <input type="checkbox" class="ace" />
                                        <span class="lbl"> Remember Me</span>
                                    </label>-->
                                    <button id="login-button"  data-loading-text="Loading..." class="width-35 pull-right btn btn-small btn-primary" type="submit">
                                        <i class="icon-key"></i>
                                        <?=tt('Войти')?>
                                    </button>
                                </div>

                                <div class="space-4"></div>
                            </fieldset>
                        <?php $this->endWidget(); ?>
                        <?php
                        if(Yii::app()->params['enableEAuth']===true)
                            $this->widget('ext.eauth.EAuthWidget', array('action' => 'site/login'));
                        ?>
                        </div>
                    </div><!-- /widget-main -->

                </div><!-- /widget-body -->
            </div><!-- /login-box -->
        </div><!-- /position-relative -->
    </div>
</div>

<?php

Yii::app()->clientScript->registerScript('loginbutton click', <<<JS
     $("#login-button").on('click', function() {
            $(this).button('loading');
     });

JS
    , CClientScript::POS_READY);
