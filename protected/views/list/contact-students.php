<?php
/**
 *
 * @var WorkPlanController $this
 * @var FilterForm $model
 */

$this->pageHeader=tt('Контакты академической группы');
$this->breadcrumbs=array(
    tt('Контакты группы'),
);

Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/list/group.js', CClientScript::POS_HEAD);

$form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'search-form',
    'htmlOptions' => array('class' => 'form-inline noprint'),
    'method'=>'post',
    'action'=> array('list/searchStudent'),
));
?>
<?php echo $form->textField($student,'st2',array('size'=>60,'maxlength'=>255)); ?>

<?php $this->widget('bootstrap.widgets.TbButton', array(
    'buttonType'=>'submit',
    'type'=>'primary',
    'icon'=>'search',
    'label'=>tt('Поиск'),
    'htmlOptions'=>array(
        'class'=>'btn-small'
    )
)); ?>
<?php
$this->endWidget();

$this->renderPartial('/filter_form/timeTable/group', array(
    'model' => $model,
    'type'=>1,//тип для юрки показывать или нет факультет 5
    'showDateRangePicker' => false
));


echo <<<HTML
    <span id="spinner1"></span>
HTML;


if (! empty($model->group))
    $this->renderPartial('contact-students/_bottom', array(
        'model' => $model
    ));
