<?php

/**
 * This is the model class for table "rpspr".
 *
 * The followings are the available columns in table 'rpspr':
 * @property integer $rpspr0
 * @property integer $rpspr1
 * @property string $rpspr2
 * @property integer $rpspr3
 * @property string $rpspr4
 * @property string $rpspr5
 * @property string $rpspr6
 * @property double $rpspr7
 * @property integer $rpspr8
 * @property integer $rpspr9
 *
 * The followings are the available model relations:
 * @property Elgp[] $elgps
 * @property I $rpspr30
 * @property Rppr $rpspr80
 * @property F $rpspr90
 * @property Rpsprd[] $rpsprds
 * @property Rpprd[] $rpprds
 */
class Rpspr extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rpspr';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rpspr0, rpspr1, rpspr3, rpspr8, rpspr9', 'numerical', 'integerOnly'=>true),
			array('rpspr7', 'numerical'),
			array('rpspr2, rpspr6', 'length', 'max'=>30),
			array('rpspr4, rpspr5', 'length', 'max'=>80),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('rpspr0, rpspr1, rpspr2, rpspr3, rpspr4, rpspr5, rpspr6, rpspr7, rpspr8, rpspr9', 'safe', 'on'=>'search'),
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
			'elgps' => array(self::HAS_MANY, 'Elgp', 'elgp8'),
			'rpspr30' => array(self::BELONGS_TO, 'I', 'rpspr3'),
			'rpspr80' => array(self::BELONGS_TO, 'Rppr', 'rpspr8'),
			'rpspr90' => array(self::BELONGS_TO, 'F', 'rpspr9'),
			'rpsprds' => array(self::HAS_MANY, 'Rpsprd', 'rpsprd0'),
			'rpprds' => array(self::HAS_MANY, 'Rpprd', 'rpprd1'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'rpspr0' => 'Rpspr0',
			'rpspr1' => tt('Тип'),
			'rpspr2' => tt('Дата регистрации'),
			'rpspr3' => 'Rpspr3',
			'rpspr4' => tt('Номер справки'),
			'rpspr5' => tt('Номер квитанции (по оплате)'),
			'rpspr6' => tt('Дата квитанции (по оплате)'),
			'rpspr7' => tt('Сумма (по оплате)'),
			'rpspr8' => 'Rpspr8',
			'rpspr9' => 'Rpspr9',
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

		$criteria->compare('rpspr0',$this->rpspr0);
		$criteria->compare('rpspr1',$this->rpspr1);
		$criteria->compare('rpspr2',$this->rpspr2,true);
		$criteria->compare('rpspr3',$this->rpspr3);
		$criteria->compare('rpspr4',$this->rpspr4,true);
		$criteria->compare('rpspr5',$this->rpspr5,true);
		$criteria->compare('rpspr6',$this->rpspr6,true);
		$criteria->compare('rpspr7',$this->rpspr7);
		$criteria->compare('rpspr8',$this->rpspr8);
		$criteria->compare('rpspr9',$this->rpspr9);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Rpspr the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * Типы пропуска
     * @return array
     */
	public function getTypes(){
	    return Elgzst::model()->getTypes();
    }

    /**
     * Тип
     * @return mixed|string
     */
    public function getType(){
	    $arr = $this->getTypes();

	    if(!isset($arr[$this->rpspr1]))
	        return '';

	    return $arr[$this->rpspr1];
    }
}
