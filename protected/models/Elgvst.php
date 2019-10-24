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
