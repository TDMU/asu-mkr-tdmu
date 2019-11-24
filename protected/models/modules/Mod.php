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
 * @property-read string $mod3Str
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
		    array('mod1', 'required'),
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
			'mod1' => '#',
			'mod2' => 'US',
			'mod3' => tt('Тип'),
			'mod4' => tt('Сортировка'),
			'mod5' => tt('Название модуля'),
			'mod6' => tt('Максимум баллов'),
			'mod7' => tt('Дата начала'),
			'mod8' => tt('Дата окончания'),
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

    /**
     * @return string
     */
	public function getMod3Str(){
	    return $this->mod3 == 1 ? tt('Итоговый модуль') : tt('Модуль');
    }

    /**
     * Проверка что максимальный бал не привыашет 100
     * @param $value
     * @return bool
     * @throws CException
     */
    public function checkMaxBall($value){
        $summ = (int) Yii::app()->db->createCommand(<<<SQL
            SELECT SUM(MOD6) FROM mod where MOD2=:US1 and MOD1!=:MOD1
SQL
        )->queryScalar(array(
            ':US1' => $this->mod2,
            ':MOD1' => $this->mod1
        ));

        return ($summ + $value) > 100;
    }
}
