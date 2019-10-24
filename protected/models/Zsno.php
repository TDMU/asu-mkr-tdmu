<?php

/**
 * This is the model class for table "zsno".
 *
 * The followings are the available columns in table 'zsno':
 * @property integer $zsno0
 * @property integer $zsno1
 * @property string $zsno2
 *
 * The followings are the available model relations:
 * @property St $zsno10
 * @property Zsnop[] $zsnops
 */
class Zsno extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'zsno';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('zsno0, zsno1', 'numerical', 'integerOnly'=>true),
			array('zsno2', 'length', 'max'=>20),
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
			'zsno10' => array(self::BELONGS_TO, 'St', 'zsno1'),
			'zsnops' => array(self::HAS_MANY, 'Zsnop', 'zsnop0'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'zsno0' => '№',
			'zsno1' => 'Zsno1',
			'zsno2' => tt('Дата создания'),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Zsno the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
