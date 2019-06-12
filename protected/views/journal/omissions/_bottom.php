<?php

function getSelect($type,$i)
{
    if($i==0)
    {
        $options=array('data-field'=>"elgp2",'class'=>"select-elgp2");
        $name ='select-elgp2';
    }  else {
        $options=array('id'=>"select-type");
        $name ='select-type';
    }
    $options=$options+array('empty' => tt('--Выберите тип--'));
    $data = CHtml::listData(Elgzst::model()->getTypesByGroup(),'id','text','group');
    return CHtml::dropDownList($name,$type,$data,$options);

}
///Возможный баг если есть триметсры
$sem1 = Sem::model()->getSem1ByGr1($model->group);
$omissions = Elgp::model()->getOmissions($model->student,$model->date1,$model->date2,$model->group,$sem1);

if($omissions==null)
    echo '<div><span class="label label-success" style="font-size:16px">'.tt('Зарегистрированных пропусков нет').'</span></div>';
else {
    
    $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'=>'button',
		'type'=>'success',
		'icon'=>'list-alt',
		'label'=>tt('Ввести уважительные пропуски'),
		'htmlOptions'=>array(
			'class'=>'btn-small',
                        'data-toggle' => 'modal',
                        'data-target' => '#myModal',
		)
	));
    $url       = Yii::app()->createUrl('/journal/insertOmissionsStMark');

    $table = '<table data-url="'.$url.'" id="omissions" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>№</th>
                        <th>'.tt('Дата').'</th>
                        <th>'.tt('№ зан.').'</th>
                        <th>'.tt('Дисциплина').'</th>
                        <th>'.tt('Тип').'</th>
                        <th>'.tt('Уваж./Неув.').'</th>
                        <th class="td-elgp3">'.tt('Номер справки').'</th>
                        <th class="td-elgp2">'.tt('Тип').'</th>
                        <th class="td-elgp4">'.tt('№ квитанции (тип оплата)').'</th>
                        <th class="td-elgp5">'.tt('Дата квитанции(тип оплата)').'</th>
                    </tr>
                </thead>
                <tbody>
                    %s
                </tbody>
            </table>';
    $tr="";
    $i=1;
    $type1=tt("Не ув.");
    $type2=tt("Уваж.");
    $pattern=<<<HTML
        <tr data-type1="{$type1}" data-type2="{$type2}" data-elgp0="%s">
                <td>%s</td>
                <td class="date">%s</td>
                <td class="nom">%s</td>
                <td class="d-name">%s</td>
                <td class="type-us">%s</td>
                <td class="type-omissions">%s</td>
                <td class="td-elgp3">%s</td>
                <td class="td-elgp2">%s</td>
                <td class="td-elgp4">%s</td>
                <td class="td-elgp5">%s</td>
        </tr>
HTML;

    foreach($omissions as $key)
    {
        $val = Elgp::model()->getDateTypeLesson($key['elg2'],$model->group,$sem1,$key['elg4'],$model->date1,$model->date2,$key['elgz3']);
        if(!empty($val)) {
            $type = $type1;
            if ($key['elgzst3'] == 2)
                $type = $type2;

            $elgp3 = '<input data-field="elgp3" class="input-elgp3" type="text" value="' . $key['elgp3'] . '"/>';
            $elgp2 = getSelect($key['elgp2'], 0);

            $elgp4 = '';
            $elgp5 = '';

            if ($key['elgp2'] == 5) {
                $elgp4 = '<input data-field="elgp4" class="input-elgp4" type="text" value="' . $key['elgp4'] . '"/>';
                $elgp5 = '<input data-field="elgp5" class="input-elgp5 datepicker" type="text" value="' . date('d.m.Y', strtotime($key['elgp5'])) . '"/>';
            }

            $tr .= sprintf($pattern, $key['elgp0'], $i, date('d.m.Y', strtotime($val['r2'])),$key['elgz3'], $key['d2'], SH::convertUS4($val['us4']), $type, $elgp3, $elgp2, $elgp4, $elgp5);
            $i++;
        }
    }
    echo sprintf($table, $tr); // 2 table


$this->beginWidget(
    'bootstrap.widgets.TbModal',
    array('id' => 'myModal')
); ?>
 
    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4><?=tt('Ввести уважительные пропуски')?></h4>
    </div>
 
    <div class="modal-body">
        <?php
            echo CHtml::label(tt('Дата пропуска с'), 'date1_omissions_change');
            echo CHtml::textField('date1_omissions_change', $model->date1, array('class' => 'datepicker', 'id'=>'date1_omissions_change'));
            echo CHtml::label(tt('Дата пропуска по'), 'date2_omissions_change');
            echo CHtml::textField('date2_omissions_change', $model->date2, array('class' => 'datepicker', 'id'=>'date2_omissions_change'));
            echo CHtml::label(tt('Номер справки'), 'number_reference');
            echo CHtml::textField('number_reference', '', array('id'=>'number_reference'));
            echo CHtml::label(tt('Тип'), 'select_type');
            echo getSelect(-1, 1);

            echo '<div id="elgp" style="display: none">';
            echo CHtml::label(tt('Номер квитанции'), 'elgp4');
            echo CHtml::textField('elgp4', '', array('id'=>'elgp4'));
            echo CHtml::label(tt('Дата квитанции'), 'elgp5');
            echo CHtml::textField('elgp5', $model->date2, array('class' => 'datepicker', 'id'=>'elgp5'));
            echo '</div>';

        ?>
    </div>
 
    <div class="modal-footer">
        <?php 
            $url = Yii::app()->createUrl('/journal/updateOmissionsStMark');
            $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'type' => 'primary',
                'label' => tt('Сохранить'),
                'url' => '#',
                'htmlOptions' => array(
                    'id'=>'save-change-omissions',
                    'data-st1'=>$model->student,
                    'data-url'=>$url
                ),
            )
        ); ?>
        <?php $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'label' => tt('Отмена'),
                'url' => '#',
                'htmlOptions' => array('data-dismiss' => 'modal'),
            )
        ); ?>
    </div>
 
<?php $this->endWidget();

}
?>

