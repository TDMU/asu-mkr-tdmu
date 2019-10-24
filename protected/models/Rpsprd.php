<?php

/**
 * This is the model class for table "rpsprd".
 *
 * The followings are the available columns in table 'rpsprd':
 * @property integer $rpsprd0
 * @property integer $rpsprd1
 * @property string $rpsprd2
 * @property string $rpsprd3
 * @property integer $rpsprd4
 *
 * The followings are the available model relations:
 * @property Rpspr $rpsprd00
 * @property Elgp $rpsprd10
 * @property I $rpsprd40
 */
class Rpsprd extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rpsprd';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rpsprd0, rpsprd1, rpsprd4', 'numerical', 'integerOnly'=>true),
			array('rpsprd2', 'length', 'max'=>8),
			array('rpsprd3', 'length', 'max'=>1000),
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
			'rpsprd00' => array(self::BELONGS_TO, 'Rpspr', 'rpsprd0'),
			'rpsprd10' => array(self::BELONGS_TO, 'Elgp', 'rpsprd1'),
			'rpsprd40' => array(self::BELONGS_TO, 'I', 'rpsprd4'),
		);
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Rpsprd the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
