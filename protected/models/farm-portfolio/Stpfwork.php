<?php

/**
 * This is the model class for table "stpfwork".
 *
 * The followings are the available columns in table 'stpfwork':
 * @property integer $stpfwork1
 * @property string $stpfwork2
 * @property string $stpfwork3
 *
 * The followings are the available model relations:
 * @property St $stpfwork10
 */
class Stpfwork extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'stpfwork';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('stpfwork1', 'numerical', 'integerOnly'=>true),
			array('stpfwork2, stpfwork3', 'length', 'max'=>400),
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
			'stpfwork10' => array(self::BELONGS_TO, 'St', 'stpfwork1'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'stpfwork1' => tt('Студент'),
			'stpfwork2' => tt('Будущее место проведения интернатуры'),
			'stpfwork3' => tt('Будущее место трудоустройства'),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Stpfwork the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
