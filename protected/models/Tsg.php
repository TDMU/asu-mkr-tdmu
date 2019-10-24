<?php

/**
 * This is the model class for table "tsg".
 *
 * The followings are the available columns in table 'tsg':
 * @property integer $tsg1
 * @property string $tsg2
 * @property integer $tsg3
 */
class Tsg extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tsg';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tsg1', 'required'),
			array('tsg1, tsg3', 'numerical', 'integerOnly'=>true),
			array('tsg2', 'length', 'max'=>600),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Tsg the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
