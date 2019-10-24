<?php

/**
 * This is the model class for table "antsg".
 *
 * The followings are the available columns in table 'antsg':
 * @property integer $antsg1
 * @property integer $antsg2
 * @property integer $antsg3
 *
 * The followings are the available model relations:
 * @property Sg $antsg10
 */
class Antsg extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'antsg';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('antsg1, antsg2, antsg3', 'numerical', 'integerOnly'=>true),
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
			'antsg10' => array(self::BELONGS_TO, 'Sg', 'antsg1'),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Antsg the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
