<?php

/**
 * This is the model class for table "ants".
 *
 * The followings are the available columns in table 'ants':
 * @property integer $ants1
 * @property integer $ants2
 * @property integer $ants3
 *
 * The followings are the available model relations:
 * @property St $ants10
 */
class Ants extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ants';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ants1, ants2, ants3', 'numerical', 'integerOnly'=>true),
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
			'ants10' => array(self::BELONGS_TO, 'St', 'ants1'),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Ants the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
