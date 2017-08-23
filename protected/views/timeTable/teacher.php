<?php
/**
 *
 * @var TimeTableController $this
 * @var TimeTableForm $model
 * @var CActiveForm $form
 */

$this->pageHeader=tt('Расписание преподавателя');
$this->breadcrumbs=array(
    tt('Расписание'),
);

Yii::app()->clientScript->registerPackage('jquery.ui');
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/timetable/timetable.js', CClientScript::POS_HEAD);

$popupTitle = tt('Информация о преподавателе');
Yii::app()->clientScript->registerScript('teacher-messages', <<<JS
    tt.popupTitle = '{$popupTitle}';
JS
    , CClientScript::POS_READY);
	
$attr = array('class'=>'chosen-select', 'autocomplete' => 'off', 'empty' => '&nbsp;');
if(!(PortalSettings::model()->findByPk(75)->ps2&&Yii::app()->user->isGuest)) {
	$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
			'id' => 'search-form',
			'htmlOptions' => array('class' => 'form-inline noprint'),
			'method' => 'post',
			'action' => array('timeTable/searchTeacher'),
	));

	?>
	<?php echo $form->textField($teacher, 'p3', array('size' => 60, 'maxlength' => 255)); ?>

	<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type' => 'primary',
			'icon' => 'search',
			'label' => tt('Поиск'),
			'htmlOptions' => array(
					'class' => 'btn-small'
			)
	)); ?>
	<?php
	$this->endWidget();
}
if($this->mobileCheck()) {
	?>
	<div class="pull-right">
		<?=
		CHtml::link('<i class="icon-hand-right"></i>'.' '.tt('Мобильный вид'), array('/mobile/timeTableTeacher'));
		?>
	</div>
	<?php
}

$form=$this->beginWidget('CActiveForm', array(
    'id'=>'timeTable-form',
	'action'=>array('teacher'),
    'htmlOptions' => array('class' => 'form-inline noprint')
));

$ps64 = PortalSettings::model()->findByPk(64)->ps2;

$html = '<div>';
    $html .= '<fieldset>';

	$filials = Ks::getListDataForKsFilter();
	if (count($filials) > 1) {
        $html .= '<div class="span2 ace-select">';
		if($ps64!=1)
        	$html .= $form->label($model, 'filial');
        $html .= ($ps64==1)?$form->hiddenField($model,'filial'):$form->dropDownList($model, 'filial', $filials, $attr);
        $html .= '</div>';
    }else{
		$model->filial = key($filials);
	}

    //$chairs = CHtml::listData(K::model()->getOnlyChairsFor($model->filial), 'k1', 'k3');
	$chairs = K::model()->getOnlyChairsFor($model->filial);
    $html .= '<div class="span2 ace-select">';
	if($ps64!=1)
		$html .= $form->label($model, 'chair');
    $html .= ($ps64==1)?$form->hiddenField($model,'chair'):$form->dropDownList($model, 'chair', $chairs, $attr);
    $html .= '</div>';

    $teachers = P::model()->getTeachersForTimeTable($model->chair);
    $html .= '<div class="span2 ace-select">';
	if($ps64!=1)
    	$html .= $form->label($model, 'teacher');
    $html .= ($ps64==1)?$form->hiddenField($model,'teacher'):$form->dropDownList($model, 'teacher', $teachers, $attr);
    $html .= '</div>';

    $html .= $form->hiddenField($model, 'date1');
    $html .= $form->hiddenField($model, 'date2');
    $html .= '</fieldset>';

    $html .= '<fieldset style="margin-top:1%;">';
		$html .= $this->renderPartial('_date_interval', array(
			'date1' => $model->date1,
			'date2' => $model->date2,
			'r11'   => $model->r11,
			'showSem'=>true,
			'teacher'=>$model->teacher,
		), true);
if (! empty($model->teacher))
{
	$text=tt('Календарь');
	$checked='';
	if($type==1)
		$checked='checked';
	$html .= CHtml::hiddenField('timeTable',$type);
	$html .= <<<HTML
		<label class="pull-right inline">
			<small class="muted">{$text}</small>
			<input id="checkbox-timeTable" type="checkbox" {$checked} class="ace ace-switch ace-switch-6">
			<span class="lbl"></span>
		</label>
HTML;
}
    $html .= '<div class="span2 ace-block">';
    $html .= $form->label($model, 'r11');
    $html .= ' '.$form->textField($model, 'r11', array('class'=>'input-mini', 'placeholder' => tt('дней'), 'style'=>'background:'.TimeTableForm::r11Color));
    $html .= '</div>';
    $html .= '</fieldset>';
$html .= '</div>';

    echo $html;

$this->endWidget();

echo <<<HTML
    <span id="spinner1"></span>
HTML;
Yii::app()->clientScript->registerScript('calendar-checkbox', "
				$(document).on('change', '#checkbox-timeTable', function(){
					if($(this).is(':checked')) {
						$('#timeTable').val(1);
					}else
					{
						$('#timeTable').val(0);
					}
					$(this).closest('form').submit();
				});
				
				$(document).on('click', '#sem-date', function(){
					$('#TimeTableForm_date1').val($(this).data('date1'));
					$('#TimeTableForm_date2').val($(this).data('date2'));
					$(this).closest('form').submit();
				});
			
		");





if (! empty($model->teacher)) {

    $text = tt('Нажмите на иконку для просмотра фотографии преподавателя');
    echo <<<HTML
<h3 class="blue header lighter tooltip-info noprint">
    <i class="icon-info-sign show-info" style="cursor:pointer"></i>
    <small>
        <i class="icon-double-angle-right"></i> {$text}
    </small>
</h3>
HTML;
	if($type==0)
		$this->renderPartial('schedule', array(
			'model'      => $model,
			'timeTable'  => $timeTable,
			'minMax'     => $minMax,
			'rz'         => $rz,
			'maxLessons' => array(),
            'action' =>'teacherExcel'
		));
	else
		$this->renderPartial('calendar', array(
			'model'      => $model,
			'timeTable'  => $timeTable,
			'minMax'     => $minMax,
			//'maxLessons' => $maxLessons,
			'rz'         => $rz,
		));

    $url = $this->createUrl('site/userPhoto', array('_id' => $model->teacher, 'type' => Users::FOTO_P1));
    echo <<<HTML
<div id="dialog-message" class="hide">
    <div id="foto">
        <img src="{$url}" alt="">
    </div>
    <div class="hr hr-12 hr-double"></div>
    <div id="info"></div>
</div><!-- #dialog-message -->
HTML;

}
