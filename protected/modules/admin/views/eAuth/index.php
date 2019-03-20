<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 13.09.2017
 * Time: 12:00
 */

/**
 * @var $this EAuthController
 * @var $form TbActiveForm
 * @var $model ConfigEAuthForm
 */

	$this->pageHeader=tt('Настройки EAuth');
	$this->breadcrumbs=array(
        tt('Админ. панель'),
    );
?>
<div class="form">

    <?php
        $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
            'id'=>'config-eauth-form',
            'enableAjaxValidation'=>false,
        )); ?>

        <?php echo $form->errorSummary($model); ?>

        <?= $form->checkBoxRow($model,'enable')?>

        <?php /*$form->radioButtonListInlineRow($model, 'popup', $model->getAuthPopupType())*/?>

        <div class="row-fluid">
            <?= $this->renderPartial('_panel',array(
                'form' => $form,
                'model' => $model,
                'serviceTitle'=>'Twitter',
                'serviceFileName' => '_twitter',
                'infoUrl' => 'https://dev.twitter.com/apps/new'
            ))?>

            <?= $this->renderPartial('_panel',array(
                'form' => $form,
                'model' => $model,
                'serviceTitle'=>'Facebook',
                'serviceFileName' => '_facebook',
                'infoUrl' => 'https://developers.facebook.com/apps/'
            ))?>

            <?= $this->renderPartial('_panel',array(
                'form' => $form,
                'model' => $model,
                'serviceTitle'=>'Dropbox',
                'serviceFileName' => '_dropbox',
                'infoUrl' => 'https://www.dropbox.com/developers/apps/create'
            ))?>

        </div>

        <div class="row-fluid">

            <?= $this->renderPartial('_panel',array(
                'form' => $form,
                'model' => $model,
                'serviceTitle'=>'Github',
                'serviceFileName' => '_github',
                'infoUrl' => 'https://github.com/settings/applications'
            ))?>

            <?= $this->renderPartial('_panel',array(
                'form' => $form,
                'model' => $model,
                'serviceTitle'=>'Google',
                'serviceFileName' => '_google',
                'infoUrl' => 'https://code.google.com/apis/console/'
            ))?>


            <?= $this->renderPartial('_panel',array(
                'form' => $form,
                'model' => $model,
                'serviceTitle'=>'Linkedin',
                'serviceFileName' => '_linkedin',
                'infoUrl' => 'https://www.linkedin.com/secure/developer'
            ))?>
        </div>

        <div class="row-fluid">
            <?= $this->renderPartial('_panel',array(
                'form' => $form,
                'model' => $model,
                'serviceTitle'=>'Yandex',
                'serviceFileName' => '_yandex',
                'infoUrl' => 'https://oauth.yandex.ru/client/my'
            ))?>

            <?= $this->renderPartial('_panel',array(
                'form' => $form,
                'model' => $model,
                'serviceTitle'=>'Vkontakte',
                'serviceFileName' => '_vkontakte',
                'infoUrl' => 'https://vk.com/editapp?act=create&site=1'
            ))?>

            <?= $this->renderPartial('_panel',array(
                'form' => $form,
                'model' => $model,
                'serviceTitle'=>'Mailru',
                'serviceFileName' => '_mailru',
                'infoUrl' => 'http://api.mail.ru/sites/my/add'
            ))?>
        </div>

        <div class="form-actions">
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType'=>'submit',
                'type'=>'primary',
                'label'=>tt('Сохранить'),
            )); ?>
        </div>

        <?php $this->endWidget();?>
</div>
