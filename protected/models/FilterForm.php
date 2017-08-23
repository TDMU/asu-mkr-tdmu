<?php

class FilterForm extends CFormModel
{
	public $discipline;
	public $group;
	
	public $module;
	public $stream;
	public $statement;
	
	public $filial = 0;
    public $faculty;
    public $speciality;
    public $year;
    public $semester;

    public $duration = 1;
    public $teacher = 0;
    public $student;
    public $sg1;
    public $code = 0;
    public $course = 0;

    public $chair = 0;
    public $d1 = 0;
    public $gostem1;
    public $nr1;

    public $sel_1;
    public $sel_2;
    public $extendedForm = 0;

    public $adp1;
    public $cn1;

    public $month;

    public $currentYear;

    public $category = 0;
	
	public $type_rating = 0;
	public $st_rating = 0;
	
	public $type_lesson = 1;

	public $sem1 = 0;
	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
            return array(
                array('discipline, group', 'required', 'on' => 'journal, stJournal, modules,thematicPlan'),
                array('module', 'required', 'on' => 'modules'),
                array('type_lesson, sem1', 'required', 'on' => 'journal, stJournal'),
                array('module, chair,stream, discipline, group,statement', 'required', 'on' => 'module'),
                array('discipline', 'required', 'on' => 'exam-session'),
                array('filial, faculty, speciality, course, group,discipline', 'required', 'on' => 'retake'),
                //array('filial, faculty, speciality, course, group, semester, discipline,type_lesson', 'required', 'on' => 'thematicPlan'),
                array('duration, teacher, code, course', 'safe', 'on' => 'thematicPlan'),
                array('chair, gostem1, nr1, d1', 'safe', 'on' => 'gostem'),
                array('sel_1, sel_2, course, extendedForm', 'safe', 'on' => 'documentReception'),
                array('sel_1, sel_2, course, adp1, cn1, speciality', 'safe', 'on' => 'rating'),
                array('filial, teacher, chair, year, semester, extendedForm', 'required', 'on' => 'workLoad-teacher'),
                array('filial, faculty, course, group, student, semester', 'required', 'on' => 'workPlan-student'),
                array('filial, faculty, course, group, semester', 'required', 'on' => 'workPlan-group'),
                array('filial, faculty, course, group, sel_1, sel_2, type_rating, st_rating', 'required', 'on' => 'rating-group'),
                array('filial, faculty, speciality, course, group, semester', 'required', 'on' => 'workPlan-speciality'),
                array('filial, faculty, course, group, semester, month, student, discipline', 'required', 'on' => 'attendanceStatistic'),
                array('filial, faculty, speciality, course, group, category, year', 'required', 'on' => 'employment'),
				array('filial, faculty, course, discipline, group', 'required', 'on' => 'list-virtual-group'),
				array('group, student', 'required', 'on' => 'payment'),
			);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
        $sel_1 = $sel_2 = '';
        $isEntrance = $this->scenario == 'documentReception' ||
        $this->scenario == 'rating';
        if ($isEntrance) {
            $sel_1 = tt('Направление подготовки');
            $sel_2 = tt('Форма обучения');
        }
		
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
			'discipline'=> tt('Дисциплина'),
			'group'=> tt('Группа'),
			//'filial'=> tt('Филиал'),
			//'faculty'=> tt('Факультет'),
			'speciality'=> tt('Специальность'),
			'year'=> tt('Год'),
			'type'=> tt('Вид'),
			'semester'=> tt('№ семестра'),
            'duration' => tt('Длительность занятий'),
            'teacher' => tt('Преподаватель'),
            'chair' => tt('Кафедра'),
            'gostem1' => tt('Гос. экзамен'),
            'nr1' => tt('Кафедра'),
            'sel_1' => $sel_1,
            'sel_2' => $sel_2,
            'course' => tt('Курс'),
            'extendedForm' => tt('Расширенная форма'),
            'adp1' => tt('Доп. признак'),
            'cn1' => tt('Цел. направление'),
            'student' => tt('Студент'),
            'month' => tt('Месяц'),
			'module'=>tt('Модуль'),
			'stream'=>tt('Поток'),
			'statement'=>tt('Ведомость'),
			'type_rating'=>tt('Рейтинг потока'),
			'sem1'=>tt('Семестр'),
			'type_lesson'=>tt('Тип занять')
		)+$arr;
	}

    public static function getTypesForJournal()
    {
        return array(
            //'-1'=>'--Select type-- ',
            '0'=>SH::convertUS4(1),
            '1'=>tt('пз/лб/сем')
        );
    }
}