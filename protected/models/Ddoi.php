<?php

/**
 * This is the model class for table "ddoi".
 *
 * The followings are the available columns in table 'ddoi':
 * @property integer $ddoi1
 * @property integer $ddoi2
 * @property integer $ddoi3
 * @property integer $ddoi4
 * @property integer $ddoi5
 * @property integer $ddoi6
 * @property integer $ddoi7
 * @property integer $ddoi8
 * @property integer $ddoi9
 * @property integer $ddoi10
 * @property integer $ddoi11
 * @property integer $ddoi12
 * @property integer $ddoi13
 * @property integer $ddoi14
 * @property integer $ddoi15
 *
 * The followings are the available model relations:
 * @property Ddo $ddoi20
 */
class Ddoi extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ddoi';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('ddoi1, ddoi2, ddoi3, ddoi4, ddoi5, ddoi6, ddoi7, ddoi8, ddoi9, ddoi10, ddoi11, ddoi12, ddoi13, ddoi14, ddoi15', 'numerical', 'integerOnly'=>true),
			array('ddoi3', 'default', 'value'=>1),
			array('ddoi4', 'default', 'value'=>2),
			array('ddoi5', 'default', 'value'=>3),
			array('ddoi6', 'default', 'value'=>4),
			array('ddoi7', 'default', 'value'=>5),
			array('ddoi8', 'default', 'value'=>6),
			array('ddoi9', 'default', 'value'=>7),
			array('ddoi10', 'default', 'value'=>8),
			array('ddoi11', 'default', 'value'=>9),
			array('ddoi12', 'default', 'value'=>10),
			array('ddoi13', 'default', 'value'=>11),
			array('ddoi14', 'default', 'value'=>12),
			array('ddoi15', 'default', 'value'=>13),
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
			'ddoi20' => array(self::BELONGS_TO, 'Ddo', 'ddoi2'),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Ddoi the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
