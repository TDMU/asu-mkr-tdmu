<?php
/**
 * Created by PhpStorm.
 * User: neffa
 * Date: 04.02.2019
 * Time: 13:22
 */


/**
 * @var $this PortfolioFarmController
 * @var St $student
 * @var TimeTableForm $model
 */

/**
 * @param $code
 * @param $st1
 * @return mixed|string
 * @throws Exception
 */
function gridFiles($code, $st1){

    $dataProvider = new CArrayDataProvider(
        Stpfile::model()->findAllByAttributes(
            array(
                'stpfile6' => $code,
                'stpfile5' => $st1
            )
        ),
        array(
            'keyField'=>'stpfile1',
            'sort'=>false,
            'pagination'=>false
        )
    );

    $html = Yii::app()->controller->widget('bootstrap.widgets.TbGridView', array(
        'dataProvider' => $dataProvider,
        'filter' => null,
        'id' => 'field-block4',
        'type' => 'striped bordered',
        'columns' => array(
            array(
                'name' => 'stpfile2',
                'header' => tt('Файл'),
                'type' => 'raw',
                'value' => function($data){
                    return CHtml::link(
                            $data->stpfile2,
                            array(
                                '/portfolioFarm/file','id'=>$data->stpfile1)
                        );
                }
            ),
            array(
                'class'=>'bootstrap.widgets.TbButtonColumn',
                'template'=>'{delete}',
                'buttons'=>array(
                    'delete' => array
                    (
                        'url'=>'array("/portfolioFarm/deleteFile","id"=>$data->stpfile1)',
                        'options'=>array(
                            'class' => 'text-error'
                        )
                    ),
                    /*'view' => array
                    (
                        'url'=>'array("/portfolioFarm/file","id"=>$data->stpfile1)',
                    ),*/
                ),
            ),
        )
    ), true);

    $html .= CHtml::link(tt('Добавить'), Yii::app()->createUrl('/portfolioFarm/uploadFile', array('st1'=> $st1, 'type' => 'others', 'idField'=>$code)), array('class'=>'btn-mini btn btn-primary btn-create-file'));

    return $html;
}

/**
 * @param $number string номер
 * @param $st1 int
 * @param $code int
 * @param $name string
 * @param $needFile bool
 * @param $inputType string
 * @return string
 * @throws Exception
 */
function renderField($number, $st1, $code, $name, $needFile, $inputType){
    $html = CHtml::openTag('ol');
    $html .= CHtml::openTag('div', array(
        'class' => 'control-group'
    ));
    $html .= CHtml::label($number.'&nbsp;'. $name,'field-'.$code, array(
        'class' => 'control-label label-field',
        'id' => 'label-field-'.$code
    ));
    $html .= CHtml::$inputType('field-'.$code, '', array(
        'class' => 'field-input'
    ));
    $html .= CHtml::link(tt('Добавить'), '#', array('class'=>'btn-mini btn btn-primary btn-add-field', 'data-id' => $code));
    $html .= Yii::app()->controller->widget('bootstrap.widgets.TbGridView', array(
        'dataProvider' => Stportfolio::model()->search($st1, $code),
        'id' => 'field-grid-'.$code,
        'filter' => null,
        'type' => 'striped bordered',
        'columns' => array(
            'stportfolio3',
            array(
                'name' => 'stportfolio6',
                'header' => tt('Файл'),
                'type'   => 'raw',
                'value' => function($data) use (&$needFile){
                    /**
                     * @var $data Stportfolio
                     */
                    if(!$needFile)
                        return '';

                    if(empty($data->stportfolio6))
                        if(!empty($data->stportfolio7))
                            return '';
                        else
                            return CHtml::link(tt('Добавить файл'), Yii::app()->createUrl('/portfolioFarm/uploadFile', array('st1'=> $data->stportfolio2, 'type' => 'field', 'idField'=>$data->stportfolio0)), array(
                                'class' => 'text-success'
                            ));

                    if(!empty($data->stportfolio7))
                        return CHtml::link(
                            $data->stportfolio60->stpfile2,
                            array(
                                '/portfolioFarm/file','id'=>$data->stportfolio6)
                        );
                    else
                        return CHtml::link(
                            $data->stportfolio60->stpfile2,
                            array(
                                '/portfolioFarm/file','id'=>$data->stportfolio6)
                            ). ' ('.CHtml::link(tt('удалить'), '#', array('submit'=>array('/portfolioFarm/deleteFile','id'=>$data->stportfolio6), 'class' => 'text-error')).')';
                },
                'visible' => $needFile
            ),
            array(
                'name' => 'stportfolio7',
                'header' => tt('Подтвердил'),
                'value' => function($data){
                    /**
                     * @var $data Stportfolio
                     */
                    if(empty($data->stportfolio7))
                        return '';

                    return $data->stportfolio70->getName();
                },
            ),
            array(
                'name' => 'stportfolio8',
                'header' => tt('Дата подтверждения'),
                'value' => function($data){
                    /**
                     * @var $data Stportfolio
                     */
                    if(empty($data->stportfolio7))
                        return '';

                    return $data->stportfolio8;
                },
            ),
            array(
                'class'=>'bootstrap.widgets.TbButtonColumn',
                'template'=>'{delete}',
                'buttons'=>array(
                    'delete' => array
                    (
                        'url'=>'array("/portfolioFarm/deleteField","id"=>$data->stportfolio0)',
                        'options'=>array(
                            'class' => 'text-error'
                        )
                    )
                ),
            ),
        )
    ), true);
    $html .= CHtml::closeTag('div');

    $html .= CHtml::closeTag('ol');
    return $html;
}

$url = Yii::app()->createUrl('/portfolioFarm/addField');
$spinner1 = '$spinner1';

Yii::app()->clientScript->registerCss('portfolioBottom', <<<CSS
    .label-field{
        font-weight: bold;
        font-size: 17px;
    }

    .field-input{
        width: 99%;
    }
    
    .ul-fields>ol{
        border-bottom: 1px solid #0B6CBC;
        border-bottom-style: dotted;
        border-bottom-width: 2px;
    }

    .btn-create-file, .btn-add-field, .btn-add-stpwork, .btn-add-stppart, .btn-add-stpeduwork {
        min-width: 100px;
        font-size: 15px!important;
    }

    .btn-add-stpwork, .btn-add-stppart, .btn-add-stpeduwork{
        margin-bottom: 5px!important;
    }

    .ul-fields .table thead tr{
        color: #000;
    }
    
    h3 {
        font-weight: bold!Important;
    }
CSS
    );

$successMessage = tt('Изменение сохранено');

Yii::app()->clientScript->registerScript('portfolioFarm', <<<JS
    var successFieldMessage = '{$successMessage}';
    
    $('.btn-add-field').click(function(event) {
        event.preventDefault();
        var url = '{$url}';
        
        var parentControl = $(this).closest('.control-group');
        
        var id = $(this).data('id');
        
        var params = {
            st1:{$model->student},
            id: id,
            value:$('#field-'+id).val()
        };
        $spinner1.show();
        $.ajax({
            type: 'POST',
            url:  url,
            data: params,
            dataType: 'json',
            success: function(data) {
                addGritter('', successFieldMessage, 'success');
                $spinner1.hide();
                parentControl.removeClass('error');
                parentControl.addClass('success');
                $('#field-'+id).val('');
                $.fn.yiiGridView.update('field-grid-'+id);
            },
            error: function(jqXHR, textStatus, errorThrown){
                addGritter('', textStatus + ':'+ jqXHR.responseText, 'error');
                $spinner1.hide();
                parentControl.removeClass('success');
                parentControl.addClass('error');
            }
        });
    });
JS
   , CClientScript::POS_END );

$fields = Stportfolio::model()->getFieldsList($student->st1);

if(Yii::app()->user->isTch){
    $this->widget('bootstrap.widgets.TbButton', array(
        'url'=>Yii::app()->createUrl('/portfolioFarm/accept', array('id'=>$model->student)),
        'type'=>'warning',
        'icon'=>'ok',
        'label'=>tt('Подтвердить')
    ));
}

$this->widget('bootstrap.widgets.TbButton', array(
    'url'=>Yii::app()->createUrl('/portfolioFarm/print', array('id'=>$model->student)),
    'type'=>'primary',
    'icon'=>'print',
    'label'=>tt('Печать')
));

echo '<div class="alert alert-info">'.tt('Внимание! На печать выводятся только подтвержденные куратором или деканом данные.').'</div>';

echo '<div class="page-header">
  <h3>1. '.tt('Резюме').'</h3>
</div>';

$this->renderPartial('_stInfo', array(
    'student' => $student,
    'model' => $model,
));

echo CHtml::openTag('ul', array(
    'class' => 'ul-fields'
));
$field = $fields[Stportfolio::FIELD_EDUCATION_SCHOOL];
echo renderField('',$student->st1, $field['code'], $field['text'], $field['needFile'], $field['inputType']);

$field = $fields[Stportfolio::FIELD_EXTRA_EDUCATION];
echo renderField('',$student->st1, $field['code'], $field['text'], $field['needFile'], $field['inputType']);

$field = $fields[Stportfolio::FIELD_WORK_EXPERIENCE];
echo renderField('',$student->st1, $field['code'], $field['text'], $field['needFile'], $field['inputType']);

$field = $fields[Stportfolio::FIELD_PHONE];
echo renderField('',$student->st1, $field['code'], $field['text'], $field['needFile'], $field['inputType']);

$field = $fields[Stportfolio::FIELD_EMAIL];
echo renderField('',$student->st1, $field['code'], $field['text'], $field['needFile'], $field['inputType']);

echo CHtml::closeTag('ul');


echo '<div class="page-header">
  <h3>2. '.tt('Портфолио достижений').'</h3>
</div>';

echo CHtml::openTag('ul', array(
    'class' => 'ul-fields'
));


echo CHtml::openTag('ol');
echo CHtml::label('2.1'.'&nbsp;'.tt('Учебно-профессиональная деятельность'), '', array(
    'class' => 'label-field',
    'id' => 'label-field-block21'
));
Yii::app()->controller->widget('bootstrap.widgets.TbGridView', array(
    'dataProvider' => Stpwork::model()->search($model->student),
    'filter' => null,
    'id' => 'field-block21',
    'type' => 'striped bordered',
    'columns' => array(
        'stpwork3',
        'stpwork4',
        'stpwork5',
        'stpwork6',
        array(
            'name' => 'stpwork9',
            'header' => tt('Подтвердил'),
            'value' => function($data){
                /**
                 * @var $data Stpwork
                 */
                if(empty($data->stpwork9))
                    return '';

                return $data->stpwork90->getName();
            },
        ),
        array(
            'name' => 'stpwork10',
            'header' => tt('Дата подтверждения'),
            'value' => function($data){
                /**
                 * @var $data Stpwork
                 */
                if(empty($data->stpwork10))
                    return '';

                return $data->stpwork10;
            },
        ),
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{update} {delete}',
            'buttons'=>array(
                'delete' => array
                (
                    'url'=>'array("/portfolioFarm/deleteStpwork","id"=>$data->stpwork1)',
                    'options'=>array(
                        'class' => 'text-error'
                    )
                ),
                'update' => array
                (
                    'url'=>'array("/portfolioFarm/updateStpwork","id"=>$data->stpwork1)',
                ),
            ),
        ),
    )
));
echo CHtml::link(tt('Добавить'), Yii::app()->createUrl('/portfolioFarm/addStpwork', array( 'id'=> $model->student)), array('class'=>'btn-mini btn btn-primary btn-add-stpwork'));
echo CHtml::closeTag('ol');

$field = $fields[Stportfolio::FIELD_EXTRA_COURSES];
echo renderField('2.2', $student->st1, $field['code'], $field['text'], $field['needFile'], $field['inputType']);

echo CHtml::openTag('ol');
echo CHtml::label('2.3'.'&nbsp;'.tt('Данные об участии в мероприятиях'), '', array(
    'class' => 'label-field',
    'id' => 'label-field-block23'
));
Yii::app()->controller->widget('bootstrap.widgets.TbGridView', array(
    'dataProvider' => Stppart::model()->search($model->student),
    'filter' => null,
    'id' => 'field-block23',
    'type' => 'striped bordered',
    'columns' => array(
        array(
            'name' => 'stppart3',
            'header' => Stppart::model()->getAttributeLabel('stppart3'),
            'value' => '$data->getStppart3Type()',
        ),
        'stppart4',
        array(
            'name' => 'stppart5',
            'header' => Stppart::model()->getAttributeLabel('stppart5'),
            'value' => '$data->stppart5."/".($data->stppart5+1)',
        ),
        array(
            'name' => 'stppart6',
            'header' => Stppart::model()->getAttributeLabel('stppart6'),
            'value' => '$data->getStppart6Type()',
        ),
        array(
            'name' => 'stppart7',
            'header' => Stppart::model()->getAttributeLabel('stppart7'),
            'value' => '$data->getStppart7Type()',
        ),
        array(
            'name' => 'stppart8',
            'header' => Stppart::model()->getAttributeLabel('stppart8'),
            'value' => '$data->getStppart8Type()',
        ),
        array(
            'name' => 'stppart9',
            'header' => tt('Файл'),
            'type'   => 'raw',
            'value' => function($data){
                /**
                 * @var $data Stppart
                 */
                if(empty($data->stppart9))
                    return CHtml::link(tt('Добавить файл'), Yii::app()->createUrl('/portfolioFarm/uploadFile', array('st1'=> $data->stppart2, 'type' => 'type23', 'idField'=>$data->stppart1)), array(
                        'class' => 'text-success'
                    ));

                return CHtml::link(
                        $data->stppart90->stpfile2,
                        array(
                            '/portfolioFarm/file','id'=>$data->stppart9)
                    ). ' ('.CHtml::link(tt('удалить'), '#', array('submit'=>array('/portfolioFarm/deleteFile','id'=>$data->stppart9), 'class' => 'text-error')).')';
            },
        ),
        array(
            'name' => 'stppart12',
            'header' => tt('Подтвердил'),
            'value' => function($data){
                /**
                 * @var $data Stppart
                 */
                if(empty($data->stppart12))
                    return '';

                return $data->stppart120->getName();
            },
        ),
        array(
            'name' => 'stppart13',
            'header' => tt('Дата подтверждения'),
            'value' => function($data){
                /**
                 * @var $data Stppart
                 */
                if(empty($data->stppart13))
                    return '';

                return $data->stppart13;
            },
        ),
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{update} {delete}',
            'buttons'=>array(
                'delete' => array
                (
                    'url'=>'array("/portfolioFarm/deleteStppart","id"=>$data->stppart1)',
                    'options'=>array(
                        'class' => 'text-error'
                    )
                ),
                'update' => array
                (
                    'url'=>'array("/portfolioFarm/updateStppart","id"=>$data->stppart1)',
                ),
            ),
        ),
    )
));
echo CHtml::link(tt('Добавить'), Yii::app()->createUrl('/portfolioFarm/addStppart', array( 'id'=> $model->student)), array('class'=>'btn-mini btn btn-primary btn-add-stppart'));
echo CHtml::closeTag('ol');

$field = $fields[Stportfolio::FIELD_OLIMPIADS];
echo renderField('2.4',  $student->st1, $field['code'], $field['text'], $field['needFile'], $field['inputType']);

$field = $fields[Stportfolio::FIELD_SPORTS];
echo renderField('2.5',  $student->st1, $field['code'], $field['text'], $field['needFile'], $field['inputType']);

$field = $fields[Stportfolio::FIELD_SCIENCES];
echo renderField('2.6',  $student->st1, $field['code'], $field['text'], $field['needFile'], $field['inputType']);

$field = $fields[Stportfolio::FIELD_STUD_ORGS];
echo renderField('2.7',  $student->st1, $field['code'], $field['text'], $field['needFile'], $field['inputType']);

$field = $fields[Stportfolio::FIELD_VOLONTER];
echo renderField('2.8',  $student->st1, $field['code'], $field['text'], $field['needFile'], $field['inputType']);

$field = $fields[Stportfolio::FIELD_GROMADSKE];
echo renderField('2.9',  $student->st1, $field['code'], $field['text'], $field['needFile'], $field['inputType']);

echo CHtml::closeTag('ul');



echo '<div class="page-header">
  <h3 id="label-field-block3">3. '.tt('Портфолио работ').'</h3>
</div>';

Yii::app()->controller->widget('bootstrap.widgets.TbGridView', array(
    'dataProvider' => Stpeduwork::model()->search($model->student),
    'filter' => null,
    'id' => 'field-block3',
    'type' => 'striped bordered',
    'columns' => array(
        array(
            'name' => 'stpeduwork3',
            'header' => Stpeduwork::model()->getAttributeLabel('stpeduwork3'),
            'value' => '$data->getStpeduwork3Type()',
        ),
        'stpeduwork4',
        array(
            'name' => 'stpeduwork5',
            'header' => Stpeduwork::model()->getAttributeLabel('stpeduwork5'),
            'value' => '$data->stpeduwork5."/".($data->stpeduwork5+1)',
        ),
        array(
            'name' => 'stpeduwork10',
            'header' => tt('Файл'),
            'type'   => 'raw',
            'value' => function($data){
                /**
                 * @var $data Stpeduwork
                 */
                if(empty($data->stpeduwork10))
                    return CHtml::link(tt('Добавить файл'), Yii::app()->createUrl('/portfolioFarm/uploadFile', array('st1'=> $data->stpeduwork2, 'type' => 'type3', 'idField'=>$data->stpeduwork1)), array(
                        'class' => 'text-success'
                    ));

                return CHtml::link(
                        $data->stpeduwork100->stpfile2,
                        array(
                            '/portfolioFarm/file','id'=>$data->stpeduwork10)
                    ). ' ('.CHtml::link(tt('удалить'), '#', array('submit'=>array('/portfolioFarm/deleteFile','id'=>$data->stpeduwork10), 'class' => 'text-error')).')';
            },
        ),
        array(
            'name' => 'stpeduwork8',
            'header' => tt('Подтвердил'),
            'value' => function($data){
                /**
                 * @var $data Stpeduwork
                 */
                if(empty($data->stpeduwork8))
                    return '';

                return $data->stpeduwork80->getName();
            },
        ),
        array(
            'name' => 'stpeduwork9',
            'header' => tt('Дата подтверждения'),
            'value' => function($data){
                /**
                 * @var $data Stpeduwork
                 */
                if(empty($data->stpeduwork9))
                    return '';

                return $data->stpeduwork9;
            },
        ),
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{update} {delete}',
            'buttons'=>array(
                'delete' => array
                (
                    'url'=>'array("/portfolioFarm/deleteStpeduwork","id"=>$data->stpeduwork1)',
                    'options'=>array(
                        'class' => 'text-error'
                    )
                ),
                'update' => array
                (
                    'url'=>'array("/portfolioFarm/updateStpeduwork","id"=>$data->stpeduwork1)',
                ),
            ),
        ),
    )
));
echo CHtml::link(tt('Добавить'), Yii::app()->createUrl('/portfolioFarm/addStpeduwork', array( 'id'=> $model->student)), array('class'=>'btn-mini btn btn-primary btn-add-stpeduwork'));

echo '<div class="page-header">
  <h3 id="label-field-block4">4. '.tt('Портфолио отзывов').'</h3>
</div>';

echo tt('<div class="alert alert-info"><ul>Здесь можно разместить сканированные копии файлов:<ol> 1.Характеристики с мест прохождения практик,</ol><ol> 2.Рекомендательные письма, </ol><ol> 3.Благодарственные письма, </ol><ol> 4.Отзывы о достижениях, </ol><ol> 5.Характеристики куратора и т.д.</ol> </ul></div>');

echo gridFiles(-1, $model->student);



