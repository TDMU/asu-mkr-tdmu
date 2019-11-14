<?php

/**
 * This is the model class for table "mod".
 *
 * The followings are the available columns in table 'mod':
 * @property integer $mod1
 * @property integer $mod2
 * @property integer $mod3
 * @property integer $mod4
 * @property string $mod5
 * @property integer $mod6
 * @property string $mod7
 * @property string $mod8
 *
 * The followings are the available model relations:
 * @property Us $mod20
 * @property Modgr[] $modgrs
 */
class Mod extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'mod';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('mod2, mod3, mod4, mod6', 'numerical', 'integerOnly'=>true),
			array('mod5', 'length', 'max'=>400),
			array('mod7, mod8', 'length', 'max'=>20),
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
			'mod20' => array(self::BELONGS_TO, 'Us', 'mod2'),
			'modgrs' => array(self::HAS_MANY, 'Modgr', 'modgr2'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'mod1' => 'Mod1',
			'mod2' => 'Mod2',
			'mod3' => 'Mod3',
			'mod4' => 'Mod4',
			'mod5' => 'Mod5',
			'mod6' => 'Mod6',
			'mod7' => 'Mod7',
			'mod8' => 'Mod8',
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Mod the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
