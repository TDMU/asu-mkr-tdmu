<?php
/**
 * Created by PhpStorm.
 * User: neffa
 * Date: 17.11.2018
 * Time: 13:17
 *
 * Не используеться
 *
 */

/**
 * @var QuizController $this
 * @var TimeTableForm $model
 */
/**
 * @var CActiveForm $form
 */

$formModel = new CreateOprrezForm();
$formModel->st1 = $model->student;

$form=$this->beginWidget('CActiveForm', array(
    'id'=>'create-oprrez-form',
    'action' => Yii::app()->createUrl('quiz/create'),
    'htmlOptions' => array(
        'class' => 'well'
    )
)); ?>
    <fieldset>
        <?=$form->errorSummary($formModel)?>
        <?php echo $form->hiddenField($formModel,'st1', array()); ?>
        <?php echo $form->radioButtonList($formModel,'opr1',CHtml::listData(Opr::model()->findAll(), 'opr1', 'opr2'), array(
            'labelOptions'=>array('style'=>'display:inline'),
        )); ?>

        <div class="space-24"></div>

        <div class="clearfix">
            <button data-loading-text="Loading..." class="btn btn-small btn-success" type="submit">
                <?=tt('Добавить')?>
            </button>
        </div>
    </fieldset>
<?php $this->endWidget(); ?>
