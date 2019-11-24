<?php

/**
 * This is the model class for table "modgr".
 *
 * The followings are the available columns in table 'modgr':
 * @property integer $modgr1
 * @property integer $modgr2
 * @property integer $modgr3
 * @property integer $modgr4
 * @property integer $modgr5
 *
 * The followings are the available model relations:
 * @property Mod $module
 * @property Gr $modgr30
 * @property Pd $modgr40
 * @property Pd $modgr50
 * @property St[] $sts
 */
class Modgr extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'modgr';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('modgr2, modgr3, modgr4, modgr5', 'numerical', 'integerOnly'=>true),
            array('modgr5', 'exist', 'className'=>'Pd', 'attributeName'=>'pd1'),
            array('modgr4', 'exist', 'className'=>'Pd', 'attributeName'=>'pd1'),
            array('modgr3', 'exist', 'className'=>'Gr', 'attributeName'=>'gr1'),
            array('modgr2', 'exist', 'className'=>'Mod', 'attributeName'=>'mod1'),
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
			'module' => array(self::BELONGS_TO, 'Mod', 'modgr2'),
			'modgr30' => array(self::BELONGS_TO, 'Gr', 'modgr3'),
			'modgr40' => array(self::BELONGS_TO, 'Pd', 'modgr4'),
			'modgr50' => array(self::BELONGS_TO, 'Pd', 'modgr5'),
			'sts' => array(self::MANY_MANY, 'St', 'mods(mods1, mods2)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'modgr1' => '#',
			'modgr2' => tt('Модуль'),
			'modgr3' => tt('Группа'),
			'modgr4' => tt('Преподаватель 1'),
			'modgr5' => tt('Преподаватель 2'),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Modgr the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
