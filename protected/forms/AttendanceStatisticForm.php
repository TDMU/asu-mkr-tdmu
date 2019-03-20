<?php

/**
 * Форма для новой статистики посещаемости
 * Class AttendanceStatisticForm
 */
class AttendanceStatisticForm extends CFormModel
{
    const SCENARIO_GROUP = 'group';
    const SCENARIO_STUDENT = 'student';
    const SCENARIO_STREAM = 'stream';

	public $filial  = 0;

	public $faculty;
	public $course;
	public $group;
	public $student;
    public $stream;

	public $semester = 0;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
            array('filial, faculty, course', 'required'),
            array('stream', 'required', 'on' => self::SCENARIO_STREAM),
            array('student, group', 'required', 'on' => self::SCENARIO_STUDENT),
            array('group', 'required', 'on' => self::SCENARIO_GROUP),
            array('semester', 'numerical', 'integerOnly'=>true)
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

		$universityCode = Yii::app()->controller->universityCode;

        if($universityCode==7)
            $arr = array(
                'filial' => tt('Факультет'),
                'faculty' => tt('Вид подготовки'),
            );
        elseif($universityCode==15)
            $arr = array(
                'filial' => tt('Факультет'),
                'faculty' => tt('Направление подготовки'),
            );
        elseif ($universityCode==42)
            $arr = array(
                'filial' => tt('Факультет'),
                'faculty' => tt('Направление'),
            );


		return array_merge(
            array(
                'course'=> tt('Курс'),
                'group'=> tt('Группа'),
                'student'=> tt('Студент'),
                'semester' => tt('Семестр'),
                'stream' => tt('Поток')
            ),
            $arr);
	}

    /**
     * Сценарии
     * @return array
     */
	public static function scenarios(){
        return array(
            AttendanceStatisticForm::SCENARIO_STREAM => tt('Поток'),
            AttendanceStatisticForm::SCENARIO_GROUP => tt('Группа'),
            AttendanceStatisticForm::SCENARIO_STUDENT => tt('Студент')
        );
    }

    /**
     * Достпуные семестры для просмотра (в зависимости от сценария)
     * @see $steram
     * @see $group
     * @return array
     * @throws
     */
    public function getSemesters(){
	    if($this->scenario == self::SCENARIO_STREAM)
        {
            if(empty($this->stream))
                return array();
            return CHtml::listData(Sem::model()->getSemestersForStream($this->stream), 'sem1', 'sem7', 'name');
        }else{
	        if(empty($this->group))
	            return array();
            return CHtml::listData(Sem::model()->getSemestersForAttendanceStatistic($this->group), 'us3', 'sem7', 'name');
        }
    }

    /**
     * Студенты исходя и фильтров (в зависимости от сценария)
     */
    public function getStudents(){
        if(!$this->validate())
            return array();

        switch ($this->scenario) {
            case self::SCENARIO_STREAM:
                return St::model()->getListStream($this->stream);
                break;
            case self::SCENARIO_GROUP:
                return St::model()->getListGroup($this->group);
                break;
            default:
                return array();
                break;
        }
    }
}