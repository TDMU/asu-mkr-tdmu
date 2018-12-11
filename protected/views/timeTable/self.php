<?php
/**
 *
 * @var TimeTableController $this
 * @var TimeTableForm $model
 */
$title='';
if($rasp==1)
    $title=tt('Расписание студента');
elseif($rasp==2)
    $title=tt('Расписание преподавателя');

$this->pageHeader=$title.': '.tt('Личное');
$this->breadcrumbs=array(
    tt('Расписание'),
);

Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/timetable/timetable.js', CClientScript::POS_HEAD);

if($this->mobileCheck()) {
	?>
	<div class="pull-right">
		<?=
		CHtml::link('<i class="icon-hand-right"></i>'.' '.tt('Мобильный вид'), array('/mobile/timeTableSelf'));
		?>
	</div>
	<?php
}

echo '<div class="noprint">';
$this->renderPartial('/filter_form/timeTable/self', array(
    'model' => $model,
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

	if($type==0)
		$this->renderPartial('schedule', array(
			'model'      => $model,
			'timeTable'  => $timeTable,
			'minMax'     => $minMax,
			'rz'         => $rz,
            'maxLessons' => $maxLessons,
            'action' =>'selfExcel'
		));
	else
		$this->renderPartial('/timeTable/calendar', array(
			'model'      => $model,
			'timeTable'  => $timeTable,
			'minMax'     => $minMax,
            'maxLessons' => $maxLessons,
			'rz'         => $rz,
		));
