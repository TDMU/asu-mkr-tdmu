<?php

/**
 * This is the model class for table "stusvst".
 *
 * The followings are the available columns in table 'stusvst':
 * @property integer $stusvst1
 * @property integer $stusvst3
 * @property integer $stusvst4
 * @property string $stusvst5
 * @property integer $stusvst6
 * @property string $stusvst7
 * @property integer $stusvst8
 */
class Stusvst extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'stusvst';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('stusvst1, stusvst3, stusvst4, stusvst6, stusvst8', 'numerical', 'integerOnly'=>true),
			array('stusvst5, stusvst7', 'length', 'max'=>30),
            array('stusvst5', 'default', 'value'=>'', 'setOnEmpty'=>TRUE),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('stusvst1, stusvst3, stusvst4, stusvst5, stusvst6, stusvst7, stusvst8', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'stusvst1' => 'Stusvst1',
			'stusvst3' => 'Stusvst3',
			'stusvst4' => 'Stusvst4',
			'stusvst5' => 'Stusvst5',
			'stusvst6' => 'Stusvst6',
			'stusvst7' => 'Stusvst7',
			'stusvst8' => 'Stusvst8',
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

		$criteria->compare('stusvst1',$this->stusvst1);
		$criteria->compare('stusvst3',$this->stusvst3);
		$criteria->compare('stusvst4',$this->stusvst4);
		$criteria->compare('stusvst5',$this->stusvst5,true);
		$criteria->compare('stusvst6',$this->stusvst6);
		$criteria->compare('stusvst7',$this->stusvst7,true);
		$criteria->compare('stusvst8',$this->stusvst8);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Stusvst the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
