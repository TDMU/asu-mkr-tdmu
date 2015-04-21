<?php
/**
 *
 * @var TimeTableController $this
 * @var TimeTableForm $model
 */

$this->pageHeader=tt('Расписание студента');
$this->breadcrumbs=array(
    tt('Расписание'),
);

Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/timetable/timetable.js', CClientScript::POS_HEAD);
echo '<div class="noprint">';
$this->renderPartial('/filter_form/timeTable/student', array(
    'model' => $model,
    'showDateRangePicker' => true,
	'type'=>$type,
	'showCheckBoxCalendar'=>true
));
echo '</div>';
Yii::app()->clientScript->registerScript('calendar-checkbox',"
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
echo <<<HTML
    <span id="spinner1"></span>
HTML;




if (! empty($model->student))
	if($type==0)
		$this->renderPartial('schedule', array(
			'model'      => $model,
			'timeTable'  => $timeTable,
			'minMax'     => $minMax,
			'rz'         => $rz,
			'maxLessons' => array(),
		));
	else
		$this->renderPartial('/timeTable/calendar', array(
			'model'      => $model,
			'timeTable'  => $timeTable,
			'minMax'     => $minMax,
			'maxLessons' => array(),
			'rz'         => $rz,
		));
