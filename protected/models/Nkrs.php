<?php

/**
 * This is the model class for table "nkrs".
 *
 * The followings are the available columns in table 'nkrs':
 * @property integer $nkrs1
 * @property integer $nkrs2
 * @property integer $nkrs3
 * @property string $nkrs4
 * @property string $nkrs5
 * @property integer $nkrs6
 * @property integer $nkrs7
 * @property string $nkrs8
 *
 * The followings are the available model relations:
 * @property St $nkrs20
 * @property Us $nkrs30
 * @property P $nkrs60
 * @property Spkr $nkrs70
 */
class Nkrs extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nkrs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nkrs2, nkrs3, nkrs6, nkrs7', 'numerical', 'integerOnly'=>true),
			array('nkrs4, nkrs5', 'length', 'max'=>1000),
			array('nkrs8', 'length', 'max'=>1400),
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
			'nkrs20' => array(self::BELONGS_TO, 'St', 'nkrs2'),
			'nkrs30' => array(self::BELONGS_TO, 'Us', 'nkrs3'),
			'nkrs60' => array(self::BELONGS_TO, 'P', 'nkrs6'),
			'nkrs70' => array(self::BELONGS_TO, 'Spkr', 'nkrs7'),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Nkrs the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
