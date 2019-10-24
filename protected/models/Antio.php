<?php

/**
 * This is the model class for table "antio".
 *
 * The followings are the available columns in table 'antio':
 * @property integer $antio1
 * @property integer $antio2
 * @property integer $antio3
 *
 * The followings are the available model relations:
 * @property St $antio10
 */
class Antio extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'antio';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('antio1, antio2, antio3', 'numerical', 'integerOnly'=>true),
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
			'antio10' => array(self::BELONGS_TO, 'St', 'antio1'),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Antio the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
