<?php if (Yii::app()->user->hasFlash('success')) : ?>
    <div class="alert alert-success">
        <button data-dismiss="alert" class="close" type="button">
            <i class="icon-remove"></i>
        </button>
        <?=Yii::app()->user->getFlash('success')?>
    </div>
<?php endif; ?>

<?php if (Yii::app()->user->hasFlash('info')) : ?>
    <div class="alert alert-info">
        <button data-dismiss="alert" class="close" type="button">
            <i class="icon-remove"></i>
        </button>
        <?=Yii::app()->user->getFlash('info')?>
    </div>
<?php endif; ?>

<?php if (Yii::app()->user->hasFlash('error')) : ?>
    <div class="alert alert-error">
        <button data-dismiss="alert" class="close" type="button">
            <i class="icon-remove"></i>
        </button>
        <?=Yii::app()->user->getFlash('error')?>
    </div>
<?php endif; ?>

<?php if (Yii::app()->user->hasFlash('warning')) : ?>
    <div class="alert alert-warning">
        <button data-dismiss="alert" class="close" type="button">
            <i class="icon-remove"></i>
        </button>
        <?=Yii::app()->user->getFlash('warning')?>
    </div>
<?php endif; ?>

<?php /*-----------------------state----------------------------*/?>

<?php if (Yii::app()->user->hasState('success')) : ?>
    <div class="alert alert-success">
        <?=Yii::app()->user->getState('success')?>
    </div>
<?php endif; ?>

<?php if (Yii::app()->user->hasState('info')) : ?>
    <div class="alert alert-info">
        <?=Yii::app()->user->getState('info')?>
    </div>
<?php endif; ?>

<?php if (Yii::app()->user->hasState('error')) : ?>
    <div class="alert alert-error">
        <?=Yii::app()->user->getState('error')?>
    </div>
<?php endif; ?>

<?php if (Yii::app()->user->hasState('warning')) : ?>
    <div class="alert alert-warning">
        <?=Yii::app()->user->getState('warning')?>
    </div>
<?php endif; ?>

