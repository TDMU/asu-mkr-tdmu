<?php

/**
 * This is the model class for table "elgsd".
 *
 * The followings are the available columns in table 'elgsd':
 * @property integer $elgsd1
 * @property string $elgsd2
 * @property integer $elgsd3
 * @property integer $elgsd4
 * @property integer $elgsd5
 *
 * The followings are the available model relations:
 * @property Elgd[] $elgds
 */
class Elgsd extends CActiveRecord
{
	const EXAM_TYPE=2;
	const IND_TYPE=1;

	const SUM_TYPE=3;
	const SRED_TYPE=4;
	const PEREVOD_1_TYPE=5;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'elgsd';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('elgsd1', 'required'),
			array('elgsd1, elgsd3, elgsd4, elgsd5', 'numerical', 'integerOnly'=>true),
			array('elgsd2', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('elgsd1, elgsd2, elgsd3, elgsd4, elgsd5', 'safe', 'on'=>'search'),
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
			'elgds' => array(self::HAS_MANY, 'Elgd', 'elgd2'),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Elgsd the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
