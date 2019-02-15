<?php

/**
 * This is the model class for table "um".
 *
 * The followings are the available columns in table 'um':
 * @property integer $um1
 * @property integer $um2
 * @property string $um3
 * @property integer $um4
 * @property string $um5
 * @property integer $um7
 * @property integer $um8
 * @property integer $um9
 * @property integer $um10
 *
 * The followings are the available model relations:
 * @property Users $um20
 * @property Um $um100
 * @property Um[] $ums
 */
class Um extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'um';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('um1, um2, um4, um7, um8, um9, um10', 'numerical', 'integerOnly'=>true),
			array('um3, um5', 'length', 'max'=>8),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('um1, um2, um3, um4, um5, um7, um8, um9, um10', 'safe', 'on'=>'search'),
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
			'um20' => array(self::BELONGS_TO, 'Users', 'um2'),
			'um100' => array(self::BELONGS_TO, 'Um', 'um10'),
			'ums' => array(self::HAS_MANY, 'Um', 'um10'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'um1' => '#',
			'um2' => tt('Оправитель'),
			'um3' => tt('Дата'),
			'um4' => tt('Уведомление'),
			'um5' => tt('Текст'),
			'um7' => tt('Получатель (пользователь)'),
			'um8' => tt('Получатель (группа)'),
			'um9' => tt('Получатель (поток)'),
			'um10' => tt('Начальное сообщение'),
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

		$criteria->compare('um1',$this->um1);
		$criteria->compare('um2',$this->um2);
		$criteria->compare('um3',$this->um3,true);
		$criteria->compare('um4',$this->um4);
		$criteria->compare('um5',$this->um5,true);
		$criteria->compare('um7',$this->um7);
		$criteria->compare('um8',$this->um8);
		$criteria->compare('um9',$this->um9);
		$criteria->compare('um10',$this->um10);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Um the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
