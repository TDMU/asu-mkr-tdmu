<?php

/**
 * This is the model class for table "mods".
 *
 * The followings are the available columns in table 'mods':
 * @property integer $mods1
 * @property integer $mods2
 * @property integer $mods3
 * @property string $mods4
 * @property integer $mods5
 */
class Mods extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'mods';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('mods1, mods2, mods3, mods5', 'numerical', 'integerOnly'=>true),
			array('mods4', 'length', 'max'=>20),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'mods1' => 'Mods1',
			'mods2' => 'Mods2',
			'mods3' => 'Mods3',
			'mods4' => 'Mods4',
			'mods5' => 'Mods5',
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Mods the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
