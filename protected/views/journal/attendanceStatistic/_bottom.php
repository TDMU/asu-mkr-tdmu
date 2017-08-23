<style>
    input.ace.ace-switch.ace-switch-6[type="checkbox"]:checked + .lbl:before {
        background-color: #468fcc;
    }
</style>
<?php
/**
 * @var ProgressController $this
 * @var FilterForm $model
 */

$options = array('class'=>'chosen-select', 'autocomplete' => 'off', 'empty' => '&nbsp;');

$data = CHtml::listData(Sem::model()->getSemestersForAttendanceStatistic($model->group), 'us3', 'sem7', 'name');

$html  = '<div class="row-fluid" style="margin-bottom:2%">';
$html .= '<div class="span2 ace-select">';
$html .= CHtml::label(tt('Семестр'), 'FilterForm_semester');
$html .= CHtml::dropDownList('FilterForm[semester]', $model->semester, $data, $options);
$html .= '</div>';

if ($model->semester) {
	if(empty($model->student))
	{
		$data = CHtml::listData(Sem::model()->getMonthsNamesForAttendanceStatistic($model->semester), 'firstDay', 'name');
        $html .= '<div class="span2 ace-select">';
        $html .= CHtml::label(tt('Месяц'), 'FilterForm_month');
        $html .= CHtml::dropDownList('FilterForm[month]', $model->month, $data, $options);
        $html .= '</div>';

        $data = CHtml::listData(D::model()->getDisciplinesForAttendanceStatistic($model->group,$model->semester), 'd2', 'd2');
        $html .= '<div class="span2 ace-select">';
        $html .= CHtml::label(tt('Дисциплина'), 'FilterForm_discipline');
        $html .= CHtml::dropDownList('FilterForm[discipline]', $model->discipline, $data, $options);
        $html .= '</div>';
	}
	$html .= CHtml::hiddenField('FilterForm[student]', $model->student, array('id'=>'student-field'));
}
$html .= '</div>';
echo $html;
if ((! empty($model->student))&&(! empty($model->semester))){ 
		echo CHtml::link(tt('Назад к группе'),'#',array('class'=>'student-statistic','data-id'=>''));
		$statistic=St::model()->getStatisticForStudent($model->student,$model->semester);
		$st=St::model()->getStudentName($model->student);
		$this->renderPartial('attendanceStatistic/_student', array(
			'statistic' => $statistic,
			'model'    => $model,
			'st'=>$st,
		));
}
else
{
	$tooltip = tt('Зн - суммарное кол-во занятий').'&nbsp;&nbsp;&nbsp;'.
			   tt('Ну - кол-во пропусков по неуважительной причине').'&nbsp;&nbsp;&nbsp;'.
			   tt('Ув - кол-во пропусков по уважительной причине').'&nbsp;&nbsp;&nbsp;';
	$text = tt('в процентах');

$caption = <<<HTML
<h3 class="blue header lighter tooltip-info" >
	<i class="icon-info-sign"></i>
	<small>
		<i class="icon-double-angle-right"></i>
		{$tooltip}
	</small>
	<span class="">
		<label class="pull-right inline">
			<small class="muted">{$text}</small>
			<input type="checkbox" class="ace ace-switch ace-switch-6" name="show-percents">
			<span class="lbl"></span>
		</label>
	</span>
</h3>
HTML;



	if (! empty($model->semester)) :

		echo $caption;

		$students = St::model()->getStudentsOfGroup($model->group);

		$this->renderPartial('attendanceStatistic/_table_1', array(
			'students' => $students,
			'type_statistic'=>$type_statistic,
		));

		$this->renderPartial('attendanceStatistic/_table_2', array(
			'students' => $students,
			'model'    => $model
		));

	endif;
}