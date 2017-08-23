<?php
	$types = array(
            0 => 'label',
            1 => 'label label-success',
            2 => 'label label-warning',
            3 => 'label label-important',
            4 => 'label label-info',
            5 => 'label label-inverse',
            6 =>'label',
            7 => 'label label-success',
            8 =>'label label-warning',
            13 =>'label label-important',
            14 => 'label label-info',
            16 => 'label label-inverse',
            17 => 'label',
            18 => 'label',
        );
	/*$css='';	
	for( $i= 0 ; $i <= 18 ; $i++ ) 
	{
		$css.='.event'.$i.'{
			background-color:'.$.';
		}';
	}*/
	$events='';
	$arr='[';
	$i=0;
	$groups='';
        $text='';
        $fullText='';
        if(!empty($timeTable))
        {
            //$r1=$timeTable[0]['R1'];
            $r2=$timeTable[0]['r2'];
            $r3=$timeTable[0]['r3'];
            foreach($timeTable as $event)
            {
                    
                    $tem_name_full='';
                    $tem_name='';
                    if(isset($event['r1_']))
                    {
                        $tem = $model->getTem($event['r1_'],$event['r2']);
                        if(!empty($tem))
                        {
                            $tem_name_full=$tem['name_temi'];
                            $tem_name=' '.tt('т.').$tem['nom_temi'];
                            if($tem['nom_zan']>0)
                                $tem_name.=' '.tt('з.').$tem['nom_zan'];
                        }
                    }
                    if(isset($event['fio']))
                            $fio=$event['fio'];
                    else
                            $fio='';
                    if(($r2!=$event['r2'])||($r3!=$event['r3']))
                    {		
                            if($arr=='[')
                                    $arr.=$fullText;
                            else
                                    $arr.=','.$fullText;
                            $groups='';
                            $events.=$text;
                            //$r1=$event['r1'];
                            $r2=$event['r2'];
                            $r3=$event['r3'];
                            $i++;
                    }
                    if($groups=='')
                            $groups=$event['gr3'];
                    else
                            $groups.=','.$event['gr3'];
                    $full_name=$event['d2'];
                    $full_name = str_replace("'",'`', $full_name);
                    $full_name=str_replace( '"','&quot', $full_name);
                    $full_name=str_replace( "'",'&quot', $full_name);

                    $tem_name = str_replace("'",'`', $tem_name);
                    $tem_name=str_replace( '"','&quot', $tem_name);
                    $tem_name=str_replace( "'",'&quot', $tem_name);

                    $tem_name_full = str_replace("'",'`', $tem_name_full);
                    $tem_name_full=str_replace( '"','&quot', $tem_name_full);
                    $tem_name_full=str_replace( "'",'&quot', $tem_name_full);

                    $fio= str_replace("'",'`', $fio);

                    $text='{';
                    $text.="id:'".$i."',";
                    $color = SH::getLessonColor($event['tip']);
                    $class = SH::getClassColor($event['tip']);
                    $text.="title:' ".$event['d3'].' '.$tem_name."[".$event['tip']."]\u000A ".$event['a2']." ".$fio."\u000A".$groups."',";
                    $text.="className:'event-num{$i} events event{$class}',";
                    $text.="start:'".date("Y-m-d",strtotime($event['r2']))." ".$event['rz2']."',";
                    $text.="end:'".date("Y-m-d",strtotime($event['r2']))." ".$event['rz3']."',";
                    $text.='},';
                    $fullText='[\''.$tem_name_full.'\u000A'.$full_name.'\',\''.$event['tip'].'\',\''.$fio.'\',\''.$event['a2'].'\',\''.date("Y-m-d",strtotime($event['r2'])).'\',\''.$event['rz2'].'\',\''.$event['rz3'].'\',\''.$groups.'\']';
            }
        }
	$events.=$text;
    if($arr!='[')
        $arr.= ',';
	$arr.=$fullText;
	$arr.=']';
?>
	<button id="print-table" class="btn btn-info btn-small">
        <i class="icon-print bigger-110"></i>
    </button>
	<div id="calendar">
	</div>
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/theme/ace/assets/js/moment.min.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/theme/ace/assets/js/jquery-ui.custom.min.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/theme/ace/assets/js/fullcalendar2.min.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/theme/ace/assets/js/lang-all.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/theme/ace/assets/js/jquery.leanModal.min.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/theme/ace/assets/css/fullcalendar.min.css');
//Yii::app()->clientScript->registerCssFile('/theme/ace/assets/css/fullcalendar.print.css');
Yii::app()->clientScript->registerScript('calendar', "
	$(document).ready(function() {
	var arr=".$arr.";
		$('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			buttonIcons: true,
			defaultDate: '".date("Y-m-d",strtotime($model->date1))."',
			lang: '".Yii::app()->language."',
			editable: false,
			eventLimit: true,
			events: [".$events."
			],
            eventRender: function(calEvent, element) {
				element.popover({

					title: arr[calEvent.id][4]+\" \"+arr[calEvent.id][5]+\"-\"+arr[calEvent.id][6],
					placement: 'bottom',
					content:arr[calEvent.id][0]+\"[\"+arr[calEvent.id][1]+\"]\"+\" \u000A\"+arr[calEvent.id][2]+\" \u000A\"+arr[calEvent.id][3],
				});
			},
		});
		
	});

	$('#print-table').click(
		function(){
			$('#sidebar').addClass('menu-min');
			window.print();
		}
	);
");

?>
