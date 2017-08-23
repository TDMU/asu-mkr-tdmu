<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 18.07.2017
 * Time: 13:52
 */
Yii::app()->clientScript->registerPackage('datepicker');

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('ochered-list', {
		data: $(this).serialize()
    });
	
	return false;
});

$('.datepicker').datepicker({
    format: 'dd.mm.yyyy',
    language:'ru'
});

var timerId = setInterval(function(){
	 $.fn.yiiGridView.update('ochered-list', {
		data: $(this).serialize()
    });
}, 30000);
");
?>

<?php $this->widget('bootstrap.widgets.TbButton', array(
    'htmlOptions'=>array(
            'class'=>'search-button'
    ),
    'type'=>'success',
    'size'=> 'mini',
    'icon'=>'eye-open',
    'label'=>'',
)); ?>

<div class="search-form row">
<?php $this->renderPartial('ochered/_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('bootstrap.widgets.TbGridView',array(
    'id'=>'ochered-list',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'ajaxType'=>'POST',
    'type'=>'striped hover',
    'columns'=>array(
        'abtmpi11'=>array(
            'name'=>'abtmpi11',
            'filter'=>false
        ),
        'abtmpi2',
        'abtmpi3',
        'abtmpi4',
        'abtmpi9',
        'abtmpi8'=>array(
            'name'=>'abtmpi8',
            'value'=>'date("d.m.Y", strtotime($data->abtmpi8))',
            'filter'=>false
        ),
        'abtmpi5'=>array(
            'name'=>'abtmpi5',
            'type'=>'raw',
            'value'=>'"<label class=\'label label-".$data->getLabelClass()."\'>".$data->getType()."</label>"',
            //'filter'=>Abtmpi::getTypes(),
            'filter'=>false
        ),
        'abtmpci2'=>array(
            'name'=>'abtmpci2',
            'value'=>function($data){
                $text = '';
                /**
                 * @var $data Abtmpi
                 */
                foreach ($data->abtmpcis as $elem){
                    $text.=$elem->getType().', ';
                }
                return $text;
            },
            'filter'=>false
        ),
        /*'i2'=>array(
            'name'=>'i2',
            'filter'=>false
        ),*/
        'abtmpi7'=>array(
            'name'=>'abtmpi7',
            'value'=>'date("d.m.Y", strtotime($data->abtmpi7))',
            'filter'=>false
        ),
    ),
));