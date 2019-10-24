<?php

/**
 * This is the model class for table "oprrez".
 *
 * The followings are the available columns in table 'oprrez':
 * @property integer $oprrez1
 * @property integer $oprrez2
 * @property integer $oprrez3
 * @property string $oprrez4
 * @property integer $oprrez5
 *
 * The followings are the available model relations:
 * @property St $oprrez20
 * @property Opr $oprrez30
 * @property Users $oprrez50
 */
class Oprrez extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'oprrez';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('oprrez2, oprrez3, oprrez5', 'numerical', 'integerOnly'=>true),
			array('oprrez4', 'length', 'max'=>20),
			array('oprrez1, oprrez2, oprrez3, oprrez4, oprrez5', 'safe', 'on'=>'search'),
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
			'oprrez20' => array(self::BELONGS_TO, 'St', 'oprrez2'),
			'oprrez30' => array(self::BELONGS_TO, 'Opr', 'oprrez3'),
			'oprrez50' => array(self::BELONGS_TO, 'Users', 'oprrez5'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'oprrez1' => '#',
			'oprrez2' => tt('Студент'),
			'oprrez3' => tt('Вариант ответа'),
			'oprrez4' => tt('Дата'),
			'oprrez5' => tt('Кто проставил'),
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
		$criteria=new CDbCriteria;

		$criteria->compare('oprrez2',$this->oprrez2);
		if($this->oprrez3>0)
		    $criteria->compare('oprrez3',$this->oprrez3);

		$criteria->order = 'oprrez4 DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'sort'=>false,
            'pagination'=> false
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Oprrez the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
