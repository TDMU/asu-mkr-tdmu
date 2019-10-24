<?php

/**
 * This is the model class for table "stdistsub".
 *
 * The followings are the available columns in table 'stdistsub':
 * @property integer $stdistsub1
 * @property integer $stdistsub2
 */
class Stdistsub extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'stdistsub';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('stdistsub1, stdistsub2', 'numerical', 'integerOnly'=>true),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Stdistsub the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
