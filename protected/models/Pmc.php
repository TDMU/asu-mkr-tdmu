<?php

/**
 * This is the model class for table "pmc".
 *
 * The followings are the available columns in table 'pmc':
 * @property integer $pmc0
 * @property integer $pmc1
 * @property integer $pmc2
 */
class Pmc extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pmc';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pmc1, pmc2', 'numerical', 'integerOnly'=>true),
		);
	}



	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'pmc0' => 'Pmc0',
			'pmc1' => tt('Родитель'),
			'pmc2' => 'Pmc2',
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Pmc the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
