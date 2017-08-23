<?php

/**
 * This is the model class for table "abtmpi".
 *
 * The followings are the available columns in table 'abtmpi':
 * @property integer $abtmpi1
 * @property string $abtmpi2
 * @property string $abtmpi3
 * @property string $abtmpi4
 * @property integer $abtmpi5
 * @property integer $abtmpi6
 * @property string $abtmpi7
 * @property string $abtmpi8
 * @property string $abtmpi9
 * @property integer $abtmpi10
 * @property integer $abtmpi11
 * @property integer $abtmpi12
 *
 * The followings are the available model relations:
 * @property I $abtmpi60
 * @property Abi $abtmpi120
 * @property Abtmpci[] $abtmpcis
 */
class Abtmpi extends CActiveRecord
{
    public $i2;

    public $abtmpci2;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'abtmpi';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('abtmpi1, abtmpi5, abtmpi6, abtmpi10, abtmpi11, abtmpi12', 'numerical', 'integerOnly'=>true),
			array('abtmpi2, abtmpi3, abtmpi4', 'length', 'max'=>120),
			array('abtmpi7, abtmpi8', 'length', 'max'=>20),
			array('abtmpi9', 'length', 'max'=>40),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('abtmpci2,abtmpi1, abtmpi2, abtmpi3, abtmpi4, abtmpi5, abtmpi6, abtmpi7, abtmpi8, abtmpi9, abtmpi10, abtmpi11, abtmpi12', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'abtmpi60' => array(self::BELONGS_TO, 'I', 'abtmpi6'),
			'abtmpi120' => array(self::BELONGS_TO, 'Abi', 'abtmpi12'),
			'abtmpcis' => array(self::HAS_MANY, 'Abtmpci', 'abtmpci1'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'abtmpi1' => 'ID',
			'abtmpi2' => tt('Фамилия'),
			'abtmpi3' => tt('Имя'),
			'abtmpi4' => tt('Отчество'),
			'abtmpi5' => tt('Тип'),
			'abtmpi6' => tt('Кто редактировал'),
			'abtmpi7' => tt('Когда редактировали'),
			'abtmpi8' => tt('Дата рождения'),
			'abtmpi9' => tt('Телефон'),
			'abtmpi10' => tt('Год'),
			'abtmpi11' => tt('Номер'),
			'abtmpi12' => tt('Абитуриент'),
            'i2' => tt('Кто редактировал'),
            'abtmpci2' => tt('Форма обучения')
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

        $criteria->select = ' abtmpi1, abtmpi2, abtmpi3, abtmpi4, abtmpi5, abtmpi6, abtmpi7, abtmpi8, abtmpi9, abtmpi10, abtmpi11, abtmpi12';
        //$criteria->join = 'JOIN i ON (t.abtmpi6=i.i1) ';

		$criteria->compare('abtmpi1',$this->abtmpi1);
		$criteria->compare('abtmpi2',$this->abtmpi2,true);
		$criteria->compare('abtmpi3',$this->abtmpi3,true);
		$criteria->compare('abtmpi4',$this->abtmpi4,true);
		$criteria->compare('abtmpi5',$this->abtmpi5);
		$criteria->compare('abtmpi6',$this->abtmpi6);
		if(!empty($this->abtmpi7)) {
            $criteria->addCondition('abtmpi7>=:abtmpi7');
            $criteria->params[':abtmpi7'] = $this->abtmpi7;
        }
		$criteria->compare('abtmpi8',$this->abtmpi8,true);
		$criteria->compare('abtmpi9',$this->abtmpi9,true);
		$criteria->compare('abtmpi10',$this->abtmpi10);
		$criteria->compare('abtmpi11',$this->abtmpi11);
		$criteria->compare('abtmpi12',$this->abtmpi12);

        if(!empty($this->abtmpci2)) {
            $criteria->join .= ' INNER JOIN abtmpci on (abtmpi1 = abtmpci1)  ';
            $criteria->compare('abtmpci2',$this->abtmpci2);
            $criteria->group = $criteria->select;
        }



		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'pagination'=>false
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Abtmpi the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


    public function getType()
    {
        $arr=self::model()->getTypes();

        if(isset($arr[$this->abtmpi5]))
            return $arr[$this->abtmpi5];
        else
            return '-';
    }

    public static function getTypes()
    {
        return array(
            0=>tt('Предрегистрация'),
            1=>tt('Найден в ЕДБО'),
            2=>tt('Не найден в ЕДБО'),
            3=>tt('Два заявления')
        );
    }

    public function getLabelClass()
    {
        $arr=self::model()->getLabelClasses();

        if(isset($arr[$this->abtmpi5]))
            return $arr[$this->abtmpi5];
        else
            return '-';
    }

    public static function getLabelClasses()
    {
        return array(
            0=>'success',
            1=>'warning',
            2=>'error',
            3=>'info'
        );
    }
}
