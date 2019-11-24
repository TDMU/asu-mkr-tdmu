<?php
/**
 * @var ProgressController $this
 * @var ModuleForm $model
 * @var $modules Modgr[]
 */
Yii::app()->clientScript->registerPackage('autocomplete');
$this->widget('bootstrap.widgets.TbGridView', array(
    'dataProvider' => new CArrayDataProvider($modules, array(
        'keyField' => 'modgr1'
    )),
    'template'=>'{items}',
    'id' => 'modules',
    'htmlOptions' => array(
        'data-url' => Yii::app()->createUrl('progress/changeField')
    ),
    'rowHtmlOptionsExpression' => 'array("data-id"=>$data->modgr1, "data-module"=>$data->modgr2)',
    'type' => 'striped bordered',
    'columns' => array(
        array(
            'header' => Mod::model()->getAttributeLabel('mod3'),
            'type' => 'raw',
            'htmlOptions' => array(
                'style' => 'width: 110px'
            ),
            'value' => function ($data){
                /**
                 * @var $data Modgr
                 */
                return $data->module->mod3Str;
            }
        ),
        array(
            'header' => Mod::model()->getAttributeLabel('mod5'),
            'type' => 'raw',
            'value' => function ($data){
                /**
                 * @var $data Modgr
                 */
                return CHtml::textField('name-'.$data->modgr1, $data->module->mod5, array(
                    'class' => 'input-module-name input-module',
                    'data-field' => 'mod5',
                    'style' => 'margin-bottom:0px; width:98%'
                ));
            }
        ),
        array(
            'header' => Mod::model()->getAttributeLabel('mod6'),
            'htmlOptions' => array(
                'style' => 'width: 70px'
            ),
            'type' => 'raw',
            'value' => function ($data){
                /**
                 * @var $data Modgr
                 */
                return CHtml::numberField('max-'.$data->modgr1, $data->module->mod6, array(
                    'class' => 'input-module-max input-module',
                    'data-field' => 'mod6',
                    'style' => 'margin-bottom:0px;width: 60px'
                ));
            }
        ),
        array(
            'header' => Mod::model()->getAttributeLabel('mod7'),
            'type' => 'raw',
            'htmlOptions' => array(
                'style' => 'width: 60px'
            ),
            'value' => function ($data){
                /**
                 * @var $data Modgr
                 */
                $date = $data->module->mod7;

                return empty($date) ? '' : date('d.m.Y',strtotime($date));
            }
        ),
        array(
            'header' => Mod::model()->getAttributeLabel('mod8'),
            'type' => 'raw',
            'htmlOptions' => array(
                'style' => 'width: 60px'
            ),
            'value' => function ($data){
                /**
                 * @var $data Modgr
                 */
                $date = $data->module->mod8;

                return empty($date) ? '' : date('d.m.Y',strtotime($date));
            }
        ),
        array(
            'header' => Modgr::model()->getAttributeLabel('modgr4'),
            'htmlOptions' => array(
                'style' => 'width: 130px'
            ),
            'value' => function ($data){
                /**
                 * @var $data Modgr
                 */
                if(empty($data->modgr4))
                    return '';
                $teacher = Pd::model()->getTeacherAndChairByPd1($data->modgr4);
                if(empty($teacher))
                    return '';

                return SH::getShortName($teacher['p3'], $teacher['p4'], $teacher['p5']);
            }
        ),
        array(
            'header' => Modgr::model()->getAttributeLabel('modgr5'),
            'type' => 'raw',
            'htmlOptions' => array(
                'style' => 'width: 130px'
            ),
            'value' => function ($data){
                /**
                 * @var $data Modgr
                 */
                if(empty($data->modgr5))
                    $name = '';
                else {
                    $teacher = Pd::model()->getTeacherAndChairByPd1($data->modgr5);
                    if (empty($teacher))
                        $name = '';
                    else
                        $name = SH::getShortName($teacher['p3'], $teacher['p4'], $teacher['p5']);
                }

                return CHtml::textField('input-teacher', $name, array(
                    'class' => 'autocomplete',
                    'data-id' => $data->modgr1,
                ));
            }
        )
    )
));
list($us1, $group) = $model->getGroupParams();
$us = Us::model()->findByPk($us1);
$uo = Uo::model()->findByPk($us->us2);

$url = Yii::app()->createUrl('progress/autocomplete');
$url1 = Yii::app()->createUrl('progress/changeTeacher');
$spinner = '$spinner1';
Yii::app()->clientScript->registerScript('teacher-autocomplite', <<<JS
    $('.autocomplete').autocomplete({
        serviceUrl: function(obj){
                return "{$url}?k1={$uo->uo4}";
            },
        minChars:3,
        delimiter: /(,|;)\s*/, // regex or character
        maxHeight:300,
        width:'auto',
        zIndex: 9999,
        deferRequestBy: 0, //miliseconds
        params: { }, //aditional parameters
        noCache: true, //default is false, set to true to disable caching
        // callback function:
        onSelect: function(obj){
           changeTeacher($(this).data('id'), obj.id); 
        }
    });

    function changeTeacher(moduleId, teacherId) {
        $spinner.show();

        var params = {
            id   : moduleId,
            value : teacherId
        };

        $.ajax({
            url: "$url1",
            dataType: 'json',
            data:params,
            success: function(data) {
                addGritter('', 'Успешно', 'success');
                $spinner.hide();
            },
            error: function(jqXHR, textStatus, errorThrown){
                if (jqXHR.status == 500) {
                    addGritter('', 'Internal error: ' + jqXHR.responseText, 'error')
                } else {
                    if (jqXHR.status == 403) {
                        addGritter('', 'Access error: ' + jqXHR.responseText, 'error')
                    } else {
                        addGritter('', jqXHR.status + ' ' + jqXHR.responseText, 'error');
                    }
                }
                $spinner.hide();
            }
        });
    }
JS
);

Yii::app()->clientScript->registerCss('autocomplete-style', <<<CSS
    #modules{padding-top: 0px;}
    #modules thead>tr {background-image: linear-gradient(to bottom,#f8f8f8,#dddcdc);}
    .autocomplete {margin-bottom: 0px!important;}
    .autocomplete-suggestions { border:1px solid #999; background:#FFF; cursor:pointer; text-align:left; max-height:350px; overflow:auto; margin:-6px 6px 6px -6px; /* IE6 specific: */ _height:350px;  _margin:0; _overflow-x:hidden; }
    .autocomplete-selected { background:#F0F0F0; }
    .autocomplete-suggestions div { padding:2px 5px; white-space:nowrap; overflow:hidden; }
    .autocomplete-suggestions strong { font-weight:normal; color:#3399FF; }
CSS
);