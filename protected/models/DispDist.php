<?php

/**
 * This is the model class for table "dispdist".
 * закрпеленеи дисциплин за дистанционім образованием
 * The followings are the available columns in table 'dispdist':
 * @property integer $dispdist1
 * @property string $dispdist2
 * @property string $dispdist3
 * @property integer $dispdist4
 * @property string $dispdist5
 *
 *
 *
 * The followings are the available model relations:
 * @property Uo $dispdist10
 * @property P $dispdist40
 */
class DispDist extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'dispdist';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('dispdist1, dispdist4', 'numerical', 'integerOnly'=>true),
			array('dispdist2, dispdist3', 'length', 'max'=>200),
			array('dispdist5', 'length', 'max'=>25),
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
			'dispdist10' => array(self::BELONGS_TO, 'Uo', 'dispdist1'),
			'dispdist40' => array(self::BELONGS_TO, 'P', 'dispdist4'),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DispDist the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
