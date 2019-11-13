<?php

/**
 * This is the model class for table "stppart".
 *
 * The followings are the available columns in table 'stppart':
 * @property integer $stppart1
 * @property integer $stppart2
 * @property integer $stppart3
 * @property string $stppart4
 * @property integer $stppart5
 * @property integer $stppart6
 * @property integer $stppart7
 * @property integer $stppart8
 * @property integer $stppart9
 * @property integer $stppart10
 * @property string $stppart11
 * @property integer $stppart12
 * @property string $stppart13
 * @property string $stppart14
 * @property string $stppart15
 *
 * The followings are the available model relations:
 * @property St $stppart20
 * @property Stpfile $stppart90
 * @property Users $stppart100
 * @property Users $stppart120
 */
class Stppart extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'stppart';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
		    array('stppart3, stppart4, stppart5, stppart6, stppart7, stppart8', 'required'),
			array('stppart2, stppart3, stppart5, stppart6, stppart7, stppart8, stppart9, stppart10, stppart12', 'numerical', 'integerOnly'=>true),
			array('stppart4', 'length', 'max'=>200),
            array('stppart14, stppart15', 'length', 'max'=>30),
			array('stppart11, stppart13', 'length', 'max'=>20),
            array('stppart9', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'stppart20' => array(self::BELONGS_TO, 'St', 'stppart2'),
			'stppart90' => array(self::BELONGS_TO, 'Stpfile', 'stppart9'),
			'stppart100' => array(self::BELONGS_TO, 'Users', 'stppart10'),
			'stppart120' => array(self::BELONGS_TO, 'Users', 'stppart12'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'stppart1' => '#',
			'stppart2' => tt('Студент'),
			'stppart3' => tt('Вид мероприятия'),
			'stppart4' => tt('Название мероприятия'),
			'stppart5' => tt('Учебный год'),
			'stppart6' => tt('Уровень'),
			'stppart7' => tt('Форма участия'),
			'stppart8' => tt('Результат'),
			'stppart9' => tt('Файл'),
			'stppart10' => tt('Редактировал'),
			'stppart11' => tt('Дата редактирования'),
			'stppart12' => tt('Подтвердил'),
			'stppart13' => tt('Дата подтверждения'),
            'stppart14' => tt('Форма участия (свой вариант)'),
            'stppart15' => tt('Результат (свой вариант)'),
		);
	}


	public function search($st1)
	{
		$criteria=new CDbCriteria;

		$criteria->compare('stppart2',$st1);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'sort' => false,
            'pagination'=>false
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Stppart the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * Типы мероприятий
     * @return array
     */
	public static function getStppart3Types(){
	   return array(
	       0 => tt('профессиональный конкурс'),
           1 => tt('концерт'),
           2 => tt('волонтерская акция'),
           3 => tt('конкурс (интеллектуальный, творческий)'),
       );
    }

    /**
     * Типы мероприятий
     * @return string
     */
    public function getStppart3Type(){
        $types = static::getStppart3Types();
        if(isset($types[$this->stppart3]))
            return $types[$this->stppart3];
        return '-';
    }


    /**
     * Рівень
     * @return array
     */
    public static function getStppart6Types(){
        return array(
            0 => tt('всеукраинский'),
            1 => tt('региональный'),
            2 => tt('городской'),
            3 => tt('внутриуниверситетский'),
        );
    }

    /**
     * Рівень
     * @return string
     */
    public function getStppart6Type(){
        $types = static::getStppart6Types();
        if(isset($types[$this->stppart6]))
            return $types[$this->stppart6];
        return '-';
    }

    /**
     * Форма участі
     * @return array
     */
    public static function getStppart7Types(){
        return array(
            0 => tt('выступление'),
            1 => tt('презентация'),
            2 => tt('членство в оргкомитете'),
            3 => tt('другое'),
        );
    }

    /**
     * Форма участі
     * @return string
     */
    public function getStppart7Type(){
        if($this->stppart7 == 3)
            return $this->stppart14;
        $types = static::getStppart7Types();
        if(isset($types[$this->stppart7]))
            return $types[$this->stppart7];
        return '-';
    }

    /**
     * Результат
     * @return array
     */
    public static function getStppart8Types(){
        return array(
            0 => tt('грамота'),
            1 => tt('сертификат'),
            2 => tt( 'благодарность'),
            3 => tt('другое'),
        );
    }

    /**
     * Результат
     * @return string
     */
    public function getStppart8Type(){
        if($this->stppart8 == 3)
            return $this->stppart15;
        $types = static::getStppart8Types();
        if(isset($types[$this->stppart8]))
            return $types[$this->stppart8];
        return '-';
    }
}
