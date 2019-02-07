<?php
/**
 *
 * @var TimeTableController $this
 * @var TimeTableForm $model
 * @var CActiveForm $form
 */

$this->pageHeader=tt('Расписание академ. группы');
$this->breadcrumbs=array(
    tt('Расписание'),
);

$showCheckBoxCalendar=true;
if($type==-1)
{
   $showCheckBoxCalendar=false; 
   $type=0;
}
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/timetable/timetable.js', CClientScript::POS_HEAD);
$this->renderPartial('/filter_form/timeTable/group', array(
    'model' => $model,
    'showDateRangePicker' => true,
    'type'=>$type,
    'showCheckBoxCalendar'=>$showCheckBoxCalendar
));
if($showCheckBoxCalendar)
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

    ");
echo <<<HTML
    <span id="spinner1"></span>
HTML;

	

if (!empty($model->group))
{
	if($type==0)
		$this->renderPartial('/timeTable/schedule', array(
			'model'      => $model,
			'timeTable'  => $timeTable,
			'minMax'     => $minMax,
			'maxLessons' => $maxLessons,
			'rz'         => $rz,
            'action' =>'groupExcel'
		));
	else
		$this->renderPartial('/timeTable/calendar', array(
			'model'      => $model,
			'timeTable'  => $timeTable,
			'minMax'     => $minMax,
			'maxLessons' => $maxLessons,
			'rz'         => $rz,
		));
}
