<?php

/**
 * This is the model class for table "innf".
 *
 * The followings are the available columns in table 'innf':
 * @property integer $innf1
 * @property string $innf2
 * @property string $innf3
 * @property string $innf4
 */
class Innf extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'innf';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('innf1', 'numerical', 'integerOnly'=>true),
			array('innf2, innf4', 'length', 'max'=>20),
			array('innf3', 'length', 'max'=>1000),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Innf the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
