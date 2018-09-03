<?php
/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 20.09.2016
 * Time: 20:10
 */

$this->pageHeader=tt('Генерация пользователей');
$this->breadcrumbs=array(
    tt('Генерация пользователей'),
);

Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/admin/generateUser.js', CClientScript::POS_HEAD);

?>

<div class="span8" style="overflow: auto">
    <blockquote>
        <p><?=tt('Поиск людей')?></p>
        <small><?=tt('Выберите людей')?></small>
    </blockquote>

    <?php
    $pageSize=Yii::app()->user->getState('pageSize',10);
    $this->widget('bootstrap.widgets.TbGridView',array(
        'id'=>'user-grid',
        'dataProvider'=>$model->search(),
        'filter'=>$model,
        'type'=>'striped hover bordered',
        'rowHtmlOptionsExpression' => 'array("data-id"=>$data["id"], "data-type"=>$data["type"])',
        'columns'=>array(
            'lastName'=>array(
                'name'=>'lastName',
                'header'=>$model->getAttributeLabel('lastName'),
                'value'=>function($data) {
                    return $data['last_name'];
                },
            ),
            'firstName'=>array(
                'name'=>'firstName',
                'header'=>$model->getAttributeLabel('firstName'),
                'value'=>function($data) {
                    return $data['first_name'];
                },
            ),
            'secondName'=>array(
                'name'=>'secondName',
                'header'=>$model->getAttributeLabel('secondName'),
                'value'=>function($data) {
                    return $data['second_name'];
                },
            ),

            'bDate'=>array(
                'name'=>'bDate',
                'header'=>$model->getAttributeLabel('bDate'),
                'value'=>function($data) {
                    return date_format(date_create_from_format('Y-m-d H:i:s', $data['b_date']), 'd-m-Y');
                },
            ),
            'type'=>array(
                'name'=>'type',
                'header'=>$model->getAttributeLabel('type'),
                'value'=>function($data) {
                    return GenerateUserForm::getType($data['type']);
                },
                'filter'=>GenerateUserForm::getTypes(),
            ),
            'course'=>array(
                'name'=>'course',
                'header'=>$model->getAttributeLabel('course'),
                'value'=>function($data) {
                    return $data['course'];
                },
                'filter' => array(
                    1=>'1',
                    2=>'2',
                    3=>'3',
                    4=>'4',
                    5=>'5',
                    6=>'6'
                )
            ),
            'faculty'=>array(
                'name'=>'faculty',
                'header'=>$model->getAttributeLabel('faculty'),
                'value'=>function($data) {
                    return $data['faculty_name'];
                },
                'filter'=> CHtml::listData(F::model()->getAllFaculties(),'f1','f3')
            ),
            'speciality'=>array(
                'name'=>'speciality',
                'header'=>$model->getAttributeLabel('speciality'),
                'value'=>function($data) {
                    return $data['speciality_name'];
                },
                'filter'=> CHtml::listData(Sp::model()->getAllSpecialities(), 'sp1','name')
            ),
            'chairName'=>array(
                'name'=>'chairName',
                'header'=>$model->getAttributeLabel('chair'),
                'value'=>function($data) {
                    return $data['chair_name'];
                },
            ),

            array(
                'class'=>'bootstrap.widgets.TbButtonColumn',
                'template'=>'{check}',
                'header'=>CHtml::dropDownList(
                    'pageSize',
                    $pageSize,
                    $this->getPageSizeArray(),
                    array('class'=>'change-pageSize','style'=>'max-width:70px')
                ),
                'buttons'=>array(
                    'check'=>array(
                        'label'=>'<i class="icon-check bigger-120"></i>',
                        'imageUrl'=>false,
                        'options' => array(
                            'class' => 'btn btn-mini btn-success btn-check-user',
                            'title'=>tt('Выбрать'),
                            'data-type'=>'$data["type"]',
                            'data-id'=>'$data["id"]',
                            'data-name'=>'SH::getShortName($data["last_name"],$data["first_name"],$data["second_name"])',
                            'data-type-name'=>'GenerateUserForm::getType($data["type"])'
                        ),
                    )
                )
            ),
        ),
    ));

    Yii::app()->clientScript->registerScript('initPageSize',"
	   $(document).on('change','.change-pageSize', function() {
	        $.fn.yiiGridView.update('user-grid',{ data:{ pageSize: $(this).val() }})
	    });",CClientScript::POS_READY);
    ?>
</div>
<div class="span4">
    <blockquote>
        <p><?=tt('Пользователи для генерации')?></p>
        <small><?=tt('Нажмите сгенерировать')?></small>
    </blockquote>
    <ul class="nav nav-list user-list">
        <li class="nav-header">List person</li>
        <li class="li-empty"><?=tt('Выберите нужных людей')?></li>
        <li class="divider"></li>
        <li>
            <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
                'id'=>'generate-user-form',
                'enableAjaxValidation'=>false,
                'action'=>array('generateUserExcel')
            )); ?>
            <?=$form->hiddenField($model,'users')?>
            <input type="hidden" name="createGogle" value="0">
            <input type="submit" href="#" class="btn btn-primary btn-generate-user" value="<?=tt('Сгенерировать')?>"/>
            <input type="button" href="#" class="btn btn-primary btn-generate-user" value="Згенерувати(+Google)"/>
            <?php $this->endWidget(); ?>
        </li>
    </ul>
</div>

<?php
   /* Yii::app()->clientScript->registerScript('btn-search-users',"
        $('#btn-search-users').click(function(event) {
            $('#modal-search-users').modal('show');
            event.preventDefault();
        });
        
        $(document).on('submit', '#add-users-form', function(){
            $.fn.yiiGridView.update('user-grid', {
                data: $(this).serialize()
            });
             $('#modal-search-users').modal('hide');
            return false;
        })
        ",CClientScript::POS_READY);
    ?>

<?php
$this->beginWidget(
    'bootstrap.widgets.TbModal',
    array(
        'id' => 'modal-search-users',
    )
); ?>

    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4><?=tt('Массовое добавление')?></h4>
    </div>

    <div class="modal-body">
        <?php
        echo $this->renderPartial('generateUsers/_searchForm', array('model'=>$model));
        ?>
    </div>
<?php $this->endWidget();*/
