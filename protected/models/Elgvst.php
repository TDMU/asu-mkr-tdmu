<?php

/**
 * This is the model class for table "elgvst".
 *
 * The followings are the available columns in table 'elgvst':
 * @property integer $elgvst1
 * @property string $elgvst2
 * @property string $elgvst3
 *
 * The followings are the available model relations:
 * @property St $elgvst10
 */
class Elgvst extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'elgvst';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('elgvst1', 'numerical', 'integerOnly'=>true),
			array('elgvst2, elgvst3', 'length', 'max'=>8),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('elgvst1, elgvst2, elgvst3', 'safe', 'on'=>'search'),
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
			'elgvst10' => array(self::BELONGS_TO, 'St', 'elgvst1'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'elgvst1' => 'Elgvst1',
			'elgvst2' => 'Elgvst2',
			'elgvst3' => 'Elgvst3',
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

		$criteria->compare('elgvst1',$this->elgvst1);
		$criteria->compare('elgvst2',$this->elgvst2,true);
		$criteria->compare('elgvst3',$this->elgvst3,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Elgvst the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
