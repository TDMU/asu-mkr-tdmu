<?php

/**
 * This is the model class for table "kdist".
 *
 * The followings are the available columns in table 'kdist':
 * @property integer $kdist1
 * @property integer $kdist2
 * @property integer $kdist3
 * @property integer $kdist4
 * @property string $kdist5
 *
 * The followings are the available model relations:
 * @property K $kdist10
 * @property P $kdist40
 */
class Kdist extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'kdist';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('kdist1, kdist2, kdist3, kdist4', 'numerical', 'integerOnly'=>true),
			array('kdist5', 'length', 'max'=>25),
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
			'kdist10' => array(self::BELONGS_TO, 'K', 'kdist1'),
			'kdist40' => array(self::BELONGS_TO, 'P', 'kdist4'),
		);
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Kdist the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
