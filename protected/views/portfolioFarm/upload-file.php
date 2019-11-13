<?php
/**
 * Created by PhpStorm.
 * User: Lesha
 * Date: 11.09.2019
 * Time: 13:09
 */

/**
 * @var PortfolioFarmController $this
 * @var CreateStpfileForm $model
 */
/**
 * @var $form TbActiveForm
 * @var $type string
 * @var $id string
 */

$this->pageHeader=tt('Добавление файла');
$this->breadcrumbs=array(
    tt('Портфолио')=> array('/portfolioFarm/index'),
    tt('Добавление'),
);


if($type == CreateStpfileForm::TYPE_FIELD)
{
    $fields = Stportfolio::model()->getFieldsList(null);
    if(isset($fields[$id]))
        echo CHtml::tag('h3', array(), $fields[$id]['text']);
}

$form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'attach-file-form',
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data',
    )
));
$extension = 'jpeg, jpg, png, doc, docx, pdf';
switch ($type){
    case CreateStpfileForm::TYPE_FIELD3:
        $extension = 'doc, docx, pdf';
        break;
    case CreateStpfileForm::TYPE_FIELD23:
    case CreateStpfileForm::TYPE_FIELD:
        $extension = 'jpeg, jpg, png, pdf';
        break;
}
echo $form->fileFieldRow($model, 'file', array( 'labelOptions' => array('label' => tt('Разрешены файлы размером не более 8МБ и расширением: {extension}', array(
        '{extension}' => $extension
)))));
?>
    <div class="buttons">
        <?php echo CHtml::submitButton( tt('Добавить'), array(
            'class' => 'btn btn-small btn-success'
        )); ?>
    </div>
<?php
$this->endWidget();