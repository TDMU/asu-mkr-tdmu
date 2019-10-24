<?php

/**
 * This is the model class for table "stusvst".
 *
 * The followings are the available columns in table 'stusvst':
 * @property integer $stusvst1
 * @property integer $stusvst3
 * @property integer $stusvst4
 * @property string $stusvst5
 * @property integer $stusvst6
 * @property string $stusvst7
 * @property integer $stusvst8
 */
class Stusvst extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'stusvst';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('stusvst1, stusvst3, stusvst4, stusvst6, stusvst8', 'numerical', 'integerOnly'=>true),
			array('stusvst5, stusvst7', 'length', 'max'=>30),
            array('stusvst5', 'default', 'value'=>'', 'setOnEmpty'=>TRUE),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Stusvst the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
