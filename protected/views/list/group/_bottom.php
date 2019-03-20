<?php
function getPassportLabel($id,$type, $pattern, $patternAdmin){
    if(!Yii::app()->user->isAdmin)
        echo sprintf($pattern,'success','+');
    else{
        echo sprintf($patternAdmin,'success', Yii::app()->createUrl('/site/studentPassport',array('_id'=>$id, 'type'=>$type)),'+');
    }
}
/**
 * @var ListController $this
 * @var FilterForm $model
 * @var $ps35 int
 */
	$students=St::model()->getListGroup($model->group);
	Yii::app()->clientScript->registerScript('list-group', "
		initDataTable('list-group');
	");
    $ps34=PortalSettings::model()->findByPk(34)->ps2;

    $visible_passport=false;
    if($ps35==1&&Yii::app()->user->isAdmin)
        $visible_passport=true;

    $modelStForm = new StInfoForm();

    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=>'button',
        'type'=>'primary',

        'icon'=>'print',
        'label'=>tt('Печать'),
        'htmlOptions'=>array(
            'class'=>'btn-small',
            'data-url'=>Yii::app()->createUrl('/list/groupExcel'),
            'id'=>'btn-print-excel',
        )
    ));

    $distShow = false;
    if(!Yii::app()->user->isGuest && Yii::app()->user->isTch ) {
        $modelDist = new DistEducationFilterForm(Yii::app()->user);
        if ($modelDist->isAdminDistEducation)
            $distShow = true;
    }
?>
<table id="list-group" class="table table-striped table-hover">
<thead>
	<tr>
		<th style="width:40px">№</th>
		<th style="width:200px">№ <?=tt('зач. книжки')?></th>
        <?php if($ps34==1):?>
		<th style="width:90px"><?=tt('Вид фин.')?></th>
        <?php endif;?>
		<th><?=tt('ФИО студента')?></th>
        <?php if($visible_passport):?>
            <th><?=$modelStForm->getAttributeLabel('passport')?></th>
            <th><?=$modelStForm->getAttributeLabel('internationalPassport')?></th>
            <th><?=$modelStForm->getAttributeLabel('inn')?></th>
            <th><?=$modelStForm->getAttributeLabel('snils')?></th>
        <?php endif;?>
        <?php if($distShow):?>
            <th><?=tt('дист. обр.').CHtml::link('<i class="icon-ok"></i>', array('/distEducation/signUpNewDistEducationGroup', 'gr1'=>$model->group ), array(
                    'class'=>'btn btn-success btn-mini btn-subscript-group'
                ))?></th>
        <?php endif;?>
	</tr>
</thead>
<tbody>
<?php
    $pattern ='<td><span class="label label-%s">%s</span></td>';
    $patternAdmin ='<td><a class="passport-link btn btn-%s btn-mini" href="%s">%s</a></td>';

	$type=array(
		0=>tt('бюджет'),
		1=>tt('контракт')
	);
	$i=1;
	foreach($students as $student)
	{
        if($ps34==1){
            if($student['sk3']==0){
                echo '<tr class="success">';
            }else
            {
                echo '<tr class="warning">';
            }
        }else
            echo '<tr class="info">';
		echo '<td>'.$i.'</td>';
		echo '<td>'.$student['st5'].'</td>';
        if($ps34==1)
		    echo '<td>'.$type[$student['sk3']].'</td>';

        $name = $student['st2'].' '.$student['st3'].' '.$student['st4'];

        if(Yii::app()->language == 'en' && !empty($student['st74']))
            $name = $student['st74'].' '.$student['st75'].' '.$student['st76'];

		echo '<td>'.$name.'</td>';
        if($visible_passport){
            if(St::model()->checkPassport($student['st1'],1)){
                getPassportLabel($student['st1'],1,$pattern, $patternAdmin);
            }else
            {
                echo sprintf($pattern,'important','-');
            }

            if(St::model()->checkPassport($student['st1'],2)){
                getPassportLabel($student['st1'],2,$pattern, $patternAdmin);
            }else
            {
                echo sprintf($pattern,'important','-');
            }

            if(St::model()->checkPassport($student['st1'],3)){
                getPassportLabel($student['st1'],3,$pattern, $patternAdmin);
            }else
            {
                echo sprintf($pattern,'important','-');
            }

            if(St::model()->checkPassport($student['st1'],4)){
                getPassportLabel($student['st1'],41,$pattern, $patternAdmin);
            }else
            {
                echo sprintf($pattern,'important','-');
            }
        }

        if($distShow){
            $stDist = Stdist::model()->findByPk($student['st1']);
            $button =
                CHtml::link('<i class="icon-ok"></i>', array('/distEducation/signUpNewDistEducation', 'st1'=>$student['st1'], 'type'=>1 ), array(
                    'class'=>'btn btn-success btn-mini btn-subscript-student',
                    'style'=> !empty($stDist) ? 'display:none' : ''
                ))
                .
                CHtml::link('<i class="icon-trash"></i>', array('/distEducation/signUpNewDistEducation', 'st1'=>$student['st1'], 'type'=>0), array(
                    'class'=>'btn btn-danger btn-mini btn-unsubscript-student',
                    'style'=> !empty($stDist) ? '' : 'display:none'
                ))
            ;

            echo '<td class="action-td">'.$button.'</td>';
        }

		echo '</tr>';
		$i++;
	}

    $js=<<<JS
        $(document).on('click','.passport-link',function(event){
            event.preventDefault();
            openImageWindow($(this).attr('href'));
        });
JS;
Yii::app()->clientScript->registerScript('passport-link', $js, CClientScript::POS_READY);
?>
</tbody>
</table>
