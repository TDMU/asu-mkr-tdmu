<?php

class TimeTableForm extends CFormModel
{
	public $filial  = 0;
	public $chair   = 0;
	public $faculty;
	public $course;
	public $teacher;
	public $group;
	public $student;
	public $classroom;
	public $housing = 0;

	public $lessonStart = 1;
	public $lessonEnd   = 1;


    public $date1;
    public $date2;
    public $dateLesson;
    public $r11 = 5;

    const r11Color = '#EFBCEF';

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
            array('filial, date1, date2, r11', 'required'),

            array('chair, teacher', 'numerical', 'allowEmpty' => false, 'on' => 'teacher'),
            array('chair', 'numerical', 'allowEmpty' => false, 'on' => 'chair'),
            array('chair, teacher', 'required', 'on' => 'teacher'),
            array('chair', 'required', 'on' => 'chair'),        
            array('faculty, course, group', 'numerical', 'allowEmpty' => false, 'on' => 'group, student,omissions,mobile-group'),
            array('faculty, course, group', 'required', 'on' => 'group, student,omissions,mobile-group'),
            array('dateLesson', 'required','on' => 'mobile-group'),
            array('student', 'required', 'on' => 'student,omissions'),

            array('housing, classroom', 'required', 'on' => 'classroom'),

            array('lessonStart, lessonEnd', 'required', 'on' => 'free-classroom'),
            array('housing', 'safe', 'on' => 'free-classroom'),

            array('filial, faculty, course, group', 'required', 'on' => 'list-group'),
            array('filial, chair', 'required', 'on' => 'list-chair'),
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		$arr= array(
			'filial'=> tt('Учебн. заведение'),
			'faculty'=> tt('Факультет'),
		);
		
		$sql=<<<SQL
			select b15 from b where b1=0
SQL;
		$command = Yii::app()->db->createCommand($sql);
		$id=$command->queryRow();
		if(!empty($id['b15'])&&$id['b15']==7)
			$arr= array(
				'filial'=> tt('Факультет'),
				'faculty'=> tt('Вид подготовки'),
			);
			
		return array(
                    //'filial'=> tt('Филиал'),
                    'chair'=> tt('Кафедра'),
                    //'faculty'=> tt('Факультет'),
                    'course'=> tt('Курс'),
                    'group'=> tt('Группа'),
                    'teacher'=> tt('Преподаватель'),
                    'student'=> tt('Студент'),
                    'classroom'=> tt('Аудитория'),
                    'housing'=> tt('Корпус'),
                    'r11' => tt('Индикация изменений в расписании'),
                    'date1' => tt('Дата'),
                    'lessonStart' => tt('Начало'),
                    'lessonEnd' => tt('Окончание'),
                        )+$arr;
	}

    public function getMinMaxLessons($timeTable)
    {
        $min = $max = array();

        foreach ($timeTable as $v) {

            $day = $v['nday'];
            $r3  = (int)$v['r3'];

            if (! isset($min[$day]))
                $min[$day] = $r3;

            if (! isset($max[$day]))
                $max[$day] = $r3;

            if ($min[$day] > $r3)
                $min[$day] = $r3;

            if ($max[$day] < $r3)
                $max[$day] = $r3;
        }

        for($i = 1; $i <= 7; $i++) {

            if (! isset($min[$i]))
                $min[$i] = 1;

            if (! isset($max[$i]))
                $max[$i] = 1;
        }

        $names = array(
            '1' => tt('Пн'),
            '2' => tt('Вт'),
            '3' => tt('Ср'),
            '4' => tt('Чт'),
            '5' => tt('Пт'),
            '6' => tt('Сб'),
            '7' => tt('Вс')
        );

        ksort($min);
        ksort($max);

        $data = array(
            'names' => $names,
            'min'   => $min,
            'max'   => $max
        );

        return $data;
    }




    private function fillMissingCells($timeTable)
    {
        list($firstMonday,) = $this->getWeekBoundary($this->date1);

        list(,$lastSunday)  = $this->getWeekBoundary($this->date2);

        while($firstMonday <= $lastSunday) {

            if (! isset($timeTable[$firstMonday]))
                $timeTable[$firstMonday] = array('timeTable' => array());

            $timeTable[$firstMonday]['date'] = date('d.m.Y', $firstMonday);

            $firstMonday += 86400;
        }

        ksort($timeTable);

        return $timeTable;
    }

    private function getWeekBoundary($date)
    {
        $num = date('w', strtotime($date));
        if ($num == 0)
            $num = 7;

        $ts = strtotime($date);

        $monday = $ts;
        if ($num > 1)
            $monday = $ts - (($num-1)*86400);

        $sunday = $ts;
        if ($num > 1 || $num != 7)
            $sunday = $ts + ((7-$num)*86400);

        //$monday = date('d.m.Y', $monday);
        //$sunday = date('d.m.Y', $sunday);

        return array($monday, $sunday);
    }

    private function cellColorFor($day)
    {
        $color = SH::getLessonColor($day['tip']);
        // индикация изменений
        $indicated = !empty($r11) &&
            strtotime('today -'.$this->r11.' days') <= strtotime($r11);
        if ($indicated)
            $color = TimeTableForm::r11Color;

        return $color;
    }

    private function cellPrintTextFor($day, $type)
    {
        $d2  = $day['d2'];
        $d2 = str_replace('"', "'", $d2);
        $tip = SH::convertUS4($day['us4']);
        //$tip = $day['tip'];

        $gr3 = '{$gr3}';
        $a2  = $day['a2'];
        $tem_name='';
        if(isset($day['r1_']))
        {
            $tem = $this->getTem($day['r1_'],$day['r2']);
            if(!empty($tem))
                $tem_name=$tem['name_temi'];
        }
        $class = tt('ауд');
        if (isset($day['fio']))
            $fio = $day['fio'];

        $time="";
        $pos=stripos($day['d3'],"(!)");
        if($pos!==false)
            $time=$day['rz2'].'-'.$day['rz3'];
        if ($type == 1) // teacher
            $pattern = <<<TEXT
{$time}
{$tem_name}
{$d2}[{$tip}]
{$gr3}
{$class}. {$a2}
TEXT;
        elseif($type == 2) // group / student
            $pattern = <<<TEXT
{$time}
{$tem_name}
{$d2}[{$tip}]
{$class}. {$a2}
{$fio}
TEXT;
        elseif($type == 3) // classroom
            $pattern = <<<TEXT
{$d2}[{$tip}]
{$gr3}
{$fio}
TEXT;

        return trim($pattern);
    }

    private function cellShortTextFor($day, $type)
    {
        $maxLength = 17;

        $d3    = $day['d3'];
        //$tip   = $day['tip'];
        $tip = SH::convertUS4($day['us4']);
        $a2    = $day['a2'];
        $r11   = $day['r11'];
        $class = tt('ауд');
        $hiddenParams = null;
        $tem_name='';
        if(isset($day['r1_']))
        {
            $tem = $this->getTem($day['r1_'],$day['r2']);
            if(!empty($tem))
            {
                $tem_name='&nbsp;'.tt('т.').$tem['nom_temi'];
                if($tem['nom_zan']>0)
                    $tem_name.='&nbsp;'.tt('з.').$tem['nom_zan'];
                //$tem_name.='</br>';
            }
        }

        // for order lesson service {{{
        if (Yii::app()->controller->action->id == 'orderLesson')
            $hiddenParams = implode('/', array($day['r2'], $day['r3'], $day['r5'], $day['r7']));
        // }}}

        $rowDisc = $d3.'['.$tip.']';
        $rowDisc = mb_strimwidth($rowDisc, 0, $maxLength, '...');

        $rowClass = $class.'. '.$a2;
        $rowClass = mb_strimwidth($rowClass, 0, $maxLength, '...');

        $gr3 = mb_strimwidth($day['gr3'], 0, $maxLength, '...');

        if (isset($day['fio']))
            $fio = mb_strimwidth($day['fio'], 0, $maxLength, '...');


        $color = SH::getLessonColor($day['tip']);
        // индикация изменений
        $indicated = !empty($r11) &&
                     strtotime('today -'.$this->r11.' days') <= strtotime($r11);
        if ($indicated)
            $color = TimeTableForm::r11Color;


        if ($type == 1) // teacher
            $pattern = <<<HTML
<div style="background:{$color}">
    <span>{$rowDisc}</span><br>
    {$gr3}<br>
    {$rowClass}
</div>
HTML;
        elseif($type == 2) // group / student
            $pattern = <<<HTML
<div style="background:{$color}">
    <span>{$rowDisc}{$tem_name}</span><br>
    {$rowClass}<br>
    {$fio}
    <span class="hidden">{$hiddenParams}</span>
</div>
HTML;
        elseif($type == 3) // classroom
            $pattern = <<<HTML
<div style="background:{$color}">
    <span>{$rowDisc}</span><br>
    {$gr3}<br>
    {$fio}
</div>
HTML;

        return trim($pattern);
    }
    
    public function getTem($r1,$r2)
    {
        $arr=array(32,38,40,43,44,45);
        $sql=<<<SQL
            select b15 from b where b1=0
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $id=$command->queryRow();
        if(!empty($id['b15'])&&in_array($id['b15'], $arr))
        {
            $sql ='SELECT * FROM TEMA_NAME(:r1, :r2)';
            $command = Yii::app()->db->createCommand($sql);
            $command->bindValue(':r1', $r1);
            $command->bindValue(':r2', $r2);
            $tem = $command->queryRow();
            return $tem;
        }  else {
            return null;
        }
    }
    
    private function cellFullTextFor($day, $type)
    {
        $d2  = $day['d2'];
        $d2 = str_replace('"', "'", $d2);
        $tip = $day['tip'];
        //$gr3 = $day['gr3'];
        $gr3 = '{$gr3}';
        $a2  = $day['a2'];
        $tem_name='';
        if(isset($day['r1_']))
        {
            $tem = $this->getTem($day['r1_'],$day['r2']);
            if(!empty($tem))
                $tem_name=$tem['name_temi'];
        }
        $class = tt('ауд');
        $text  = tt('Добавлено');
        $added = date('d.m.Y H:i', strtotime($day['r11']));
        if (isset($day['fio']))
            $fio = $day['fio'];
        $link = "<a href='#'>Доп. материалы</a>";
        
        $time="";
        $pos=stripos($day['d3'],"(!)");
        if($pos!==false)
            $time='<br>'.$day['rz2'].'-'.$day['rz3'].'<br>';
        if ($type == 1) // teacher
            $pattern = <<<HTML
{$time}<br>
{$d2}[{$tip}]<br>
{$gr3}<br>
{$class}. {$a2}<br>
{$text}: {$added}
HTML;
        elseif($type == 2) // group / student
            $pattern = <<<HTML
{$time}
 {$tem_name}
 <br>{$d2}[{$tip}]<br>
{$class}. {$a2}<br>
{$fio}<br>
{$text}: {$added}<br>
{$link}<br>
HTML;
        elseif($type == 3) // classroom
            $pattern = <<<HTML
<br>{$d2}[{$tip}]<br>
{$gr3}<br>
{$fio}<br>
{$text}: {$added}<br>
HTML;

        return trim($pattern);
    }

    public function fillTameTable($timeTable, $type)
    {
        $timeTable = $this->joinGroups($timeTable, $type);

        $timeTable = $this->fillMissingCells($timeTable);
        //die(var_dump($timeTable));
        return $timeTable;
    }

    private function joinGroups($timeTable, $type)
    {
        $res = array();
        foreach($timeTable as $day) {

            $r2 = strtotime($day['r2']); // date
            $r3 = $day['r3'];            // lesson

            if (! isset($res[$r2]['timeTable'][$r3])) {

                $res[$r2]['timeTable'][$r3] = $day;
                $res[$r2]['timeTable'][$r3]['gr3'] = $day['gr3'];
                $res[$r2]['timeTable'][$r3]['shortText'] = $this->cellShortTextFor($day, $type);
                $res[$r2]['timeTable'][$r3]['fullText']  = $this->cellFullTextFor($day, $type);

                $res[$r2]['timeTable'][$r3]['color'] = $this->cellColorFor($day);
                $res[$r2]['timeTable'][$r3]['printText']  = $this->cellPrintTextFor($day, $type);
                //$res[$r2]['timeTable'][$r3]['printText']  = '=СЦЕПИТЬ("'.$this->cellPrintTextFor($day, $type).'";СИМВОЛ(10))';

            } else
                $res[$r2]['timeTable'][$r3]['gr3'] .= ','.$day['gr3'];

        }
        //die(var_dump($res));
        return $res;
    }




    public function fillTameTableForGroup($timeTable)
    {
        $timeTable = $this->joinLessons($timeTable);

        $timeTable = $this->fillMissingCells($timeTable);

        $maxLessons = $this->countMaxSubjects($timeTable);

        return array($timeTable, $maxLessons);
    }

    private function joinLessons($timeTable)
    {
        $type = 2;

        $res  = array();

        foreach($timeTable as $day) {

            $r2 = strtotime($day['r2']); // date
            $r3 = $day['r3'];            // lesson

            if (! isset($res[$r2]['timeTable'][$r3])) {

                $res[$r2]['timeTable'][$r3]['shortText'] = $this->cellShortTextFor($day, $type);
                $res[$r2]['timeTable'][$r3]['fullText']  = $this->cellFullTextFor($day, $type);

                $res[$r2]['timeTable'][$r3]['color'] = $this->cellColorFor($day);
                $res[$r2]['timeTable'][$r3]['printText']  = $this->cellPrintTextFor($day, $type);
                //$res[$r2]['timeTable'][$r3]['printText']  = '=СЦЕПИТЬ("'.$this->cellPrintTextFor($day, $type).'";СИМВОЛ(10))';

                $res[$r2]['timeTable'][$r3][] = $day;

            } else {

                $res[$r2]['timeTable'][$r3]['shortText'] .= $this->cellShortTextFor($day, $type);
                $res[$r2]['timeTable'][$r3]['fullText']  .= $this->cellFullTextFor($day, $type);
                $res[$r2]['timeTable'][$r3]['printText']  .= ' '.$this->cellPrintTextFor($day, $type);

                $res[$r2]['timeTable'][$r3][] = $day;
            }

        }

        return $res;
    }

    private function countMaxSubjects($timeTable)
    {
        $res = array();

        foreach ($timeTable as $date => $params) {

            foreach ($params['timeTable'] as $lessonNum => $data) {

                unset($data['shortText'], $data['fullText'], $data['printText'], $data['color']);

                $lessonAmount = count($data);

                $dayOfWeek = date('N', $date);// 1- понедельник

                if (! isset($res[$dayOfWeek][$lessonNum]))
                    $res[$dayOfWeek][$lessonNum] = 0;

                if ($res[$dayOfWeek][$lessonNum] < $lessonAmount)
                    $res[$dayOfWeek][$lessonNum] = $lessonAmount;
            }

        }

        return $res;
    }




    public function generateGroupTimeTable()
    {
        $timeTable = Gr::getTimeTable($this->group, $this->date1, $this->date2,0);
        $minMax    = $this->getMinMaxLessons($timeTable);

        list($fullTimeTable, $maxLessons) = $this->fillTameTableForGroup($timeTable);

        return array($minMax, $fullTimeTable, $maxLessons);
    }

    public function generateStudentTimeTable()
    {
        $timeTable = St::getTimeTable($this->student, $this->date1, $this->date2);
        $minMax    = $this->getMinMaxLessons($timeTable);

        $fullTimeTable = $this->fillTameTable($timeTable, 2);

        return array($minMax, $fullTimeTable);
    }

    public function generateClassroomTimeTable()
    {
        $timeTable = A::getTimeTable($this->classroom, $this->date1, $this->date2);
        $minMax    = $this->getMinMaxLessons($timeTable);

        $fullTimeTable = $this->fillTameTable($timeTable, 3);

        return array($minMax, $fullTimeTable);
    }

    public function generateTeacherTimeTable()
    {
        $timeTable = P::getTimeTable($this->teacher, $this->date1, $this->date2);
        $minMax    = $this->getMinMaxLessons($timeTable);

        $fullTimeTable = $this->fillTameTable($timeTable, 1);

        return array($minMax, $fullTimeTable);
    }
}