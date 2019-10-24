<?php

/**
 * This is the model class for table "stbl".
 *
 * The followings are the available columns in table 'stbl':
 * @property integer $stbl2
 * @property string $stbl3
 * @property integer $stbl4
 * @property string $stbl5
 * @property integer $stbl6
 *
 * The followings are the available model relations:
 * @property St $stbl20
 * @property I $stbl40
 * @property P $stbl60
 */
class Stbl extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'stbl';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('stbl2, stbl4, stbl6', 'numerical', 'integerOnly'=>true),
			array('stbl3, stbl5', 'length', 'max'=>30),
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
			'stbl20' => array(self::BELONGS_TO, 'St', 'stbl2'),
			'stbl40' => array(self::BELONGS_TO, 'I', 'stbl4'),
			'stbl60' => array(self::BELONGS_TO, 'P', 'stbl6'),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Stbl the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
