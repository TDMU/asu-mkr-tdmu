<?php

/**
 * This is the model class for table "stportfolio".
 *
 * The followings are the available columns in table 'stportfolio':
 * @property integer $stportfolio0
 * @property integer $stportfolio1
 * @property integer $stportfolio2
 * @property string $stportfolio3
 * @property integer $stportfolio4
 * @property string $stportfolio5
 * @property integer $stportfolio6
 * @property integer $stportfolio7
 * @property string $stportfolio8
 *
 * The followings are the available model relations:
 * @property St $stportfolio20
 * @property Users $stportfolio40
 * @property Stpfile $stportfolio60
 * @property Users $stportfolio70
 */
class Stportfolio extends CActiveRecord
{
    const FIELD_EXTRA_EDUCATION = 1;

    const FIELD_WORK_EXPERIENCE = 2;

    const FIELD_PHONE = 3;

    const FIELD_EMAIL = 4;

    const FIELD_EXTRA_COURSES = 5;

    const FIELD_OLIMPIADS = 6;

    const FIELD_SPORTS = 7;

    const FIELD_SCIENCES = 8;

    const FIELD_STUD_ORGS = 9;

    const FIELD_VOLONTER = 10;

    const FIELD_GROMADSKE = 11;

    const FIELD_EDUCATION_SCHOOL = 12;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'stportfolio';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('stportfolio1, stportfolio2, stportfolio4, stportfolio6, stportfolio7', 'numerical', 'integerOnly'=>true),
			array('stportfolio3', 'length', 'max'=>1400),
			array('stportfolio5, stportfolio8', 'length', 'max'=>20),
            array('stportfolio3','filter','filter'=>array($obj=new CHtmlPurifier(),'purify')),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'stportfolio20' => array(self::BELONGS_TO, 'St', 'stportfolio2'),
			'stportfolio40' => array(self::BELONGS_TO, 'Users', 'stportfolio4'),
			'stportfolio60' => array(self::BELONGS_TO, 'Stpfile', 'stportfolio6'),
            'stportfolio70' => array(self::BELONGS_TO, 'Users', 'stportfolio7'),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Stportfolio the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function attributeLabels()
    {
        return array(
            'stportfolio3' => tt('Текст')
        );
    }

    /**
     * @param $st1 int
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search($st1, $code)
    {
        $criteria=new CDbCriteria;

        $criteria->compare('stportfolio2',$st1);
        $criteria->compare('stportfolio1',$code);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort' => false,
            'pagination'=>false
        ));
    }


    /**
     * Список id полей
     * @return array
     */
    public function getFieldsIdList(){
        return array(
            self::FIELD_EXTRA_EDUCATION,
            self::FIELD_WORK_EXPERIENCE,
            self::FIELD_PHONE,
            self::FIELD_EMAIL,
            self::FIELD_EXTRA_COURSES,
            self::FIELD_OLIMPIADS,
            self::FIELD_SPORTS,
            self::FIELD_SCIENCES,
            self::FIELD_STUD_ORGS,
            self::FIELD_VOLONTER,
            self::FIELD_GROMADSKE,
            self::FIELD_EDUCATION_SCHOOL,
            //self::FIELD_EDUCATION_DATE_END
        );
    }

    /**
     * Поулчить конечный результат для печати
     * @param $st1 int
     * @param $id int
     * @return mixed
     * @throws CException
     */
    public function getPrintValue($st1, $id){
        $sql = <<<SQL
            SELECT list(stportfolio3, '; ') FROM stportfolio WHERE  stportfolio7 is not null and stportfolio2=:st1 and stportfolio1=:id
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':st1', $st1);
        $command->bindValue(':id', $id);

        return $command->queryScalar();
    }

    /**
     * Список полей для заполнения
     * @return array
     */
    public function getFieldsList(){
        return array(
            self::FIELD_EXTRA_EDUCATION => array(
                'code' => self::FIELD_EXTRA_EDUCATION,
                'text' => tt('Данные о дополнительно полученном образование (музыкальное, художественное, спортивной школы, школы иностранных языков и т.д.)'),
                'needFile' => false,
                'inputType' => 'textArea'
            ),
            self::FIELD_WORK_EXPERIENCE => array(
                'code' => self::FIELD_WORK_EXPERIENCE,
                'text' => tt('Опыт работы по специальности (где и на какой должности)'),
                'needFile' => false,
                'inputType' => 'textArea'
            ),
            self::FIELD_PHONE => array(
                'code' => self::FIELD_PHONE,
                'text' => tt('Контактный телефон'),
                'needFile' => false,
                'inputType' => 'textField'
            ),
            self::FIELD_EMAIL => array(
                'code' => self::FIELD_EMAIL,
                'text' => 'Е-mail',
                'needFile' => false,
                'inputType' => 'textField'
            ),
            self::FIELD_EXTRA_COURSES => array(
                'code' => self::FIELD_EXTRA_COURSES,
                'text' => tt('Курсы, дополнительное образование: название курсов, полученный документ - название (сертификат, удостоверение и т.д.), дата, уровень'),
                'needFile' => true,
                'inputType' => 'textArea'
            ),
            self::FIELD_OLIMPIADS => array(
                'code' => self::FIELD_OLIMPIADS,
                'text' => tt('Олимпиады, конкурсы по учебным дисциплинам (учебный год, название дисциплины, результат)'),
                'needFile' => true,
                'inputType' => 'textArea'
            ),
            self::FIELD_SPORTS => array(
                'code' => self::FIELD_SPORTS,
                'text' => tt('Спортивные достижения (учебный год, уровень соревнований, вид спорта, результат)'),
                'needFile' => true,
                'inputType' => 'textArea'
            ),
            self::FIELD_SCIENCES => array(
                'code' => self::FIELD_SCIENCES,
                'text' => tt('Научная деятельность (участие в научно-практических конференциях, уровень, темы исследований, статьи или тезисы, результат)'),
                'needFile' => true,
                'inputType' => 'textArea'
            ),
            self::FIELD_STUD_ORGS => array(
                'code' => self::FIELD_STUD_ORGS,
                'text' => tt('Участие в органах студенческого самоуправления (название, форма участия, поручения, которые выполнялись)'),
                'needFile' => false,
                'inputType' => 'textArea'
            ),
            self::FIELD_VOLONTER => array(
                'code' => self::FIELD_VOLONTER,
                'text' => tt('Участие в волонтерской деятельности (название мероприятия, форма участия)'),
                'needFile' => true,
                'inputType' => 'textArea'
            ),
            self::FIELD_GROMADSKE => array(
                'code' => self::FIELD_GROMADSKE,
                'text' => tt('Достижения в творческой и общественной деятельности (название мероприятия, форма участия)'),
                'needFile' => false,
                'inputType' => 'textArea'
            ),
            self::FIELD_EDUCATION_SCHOOL => array(
                'code' => self::FIELD_EDUCATION_SCHOOL,
                'text' => tt('Предыдущее образование (учебное заведение, когда закончил)'),
                'needFile' => false,
                'inputType' => 'textField'
            )
        );
    }

    /**
     * Список годов
     * @return array
     */
    public static function getYears(){
        $list = array();

        $currentYear = date('Y');

        for($i = 0; $i<5; $i++){
            $year = $currentYear - $i;
            $list[$year] = $year.'/'.($year +1);
        }

        return $list;
    }
}
