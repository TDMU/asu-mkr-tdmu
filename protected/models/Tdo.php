<?php

/**
 * This is the model class for table "tdo".
 *
 * The followings are the available columns in table 'tdo':
 * @property integer $tdo1
 * @property string $tdo2
 * @property integer $tdo3
 *
 * The followings are the available model relations:
 * @property Tdo $tdo30
 * @property Tdo[] $tdos
 * @property Tddo[] $tddos
 */
class Tdo extends CActiveRecord
{
    /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tdo';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tdo1, tdo3', 'numerical', 'integerOnly'=>true),
			array('tdo2', 'length', 'max'=>400),
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
			'tdo30' => array(self::BELONGS_TO, 'Tdo', 'tdo3'),
			'tdos' => array(self::HAS_MANY, 'Tdo', 'tdo3'),
			'tddos' => array(self::HAS_MANY, 'Tddo', 'tddo20'),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Tdo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public static function getAll()
    {
        $res = CHtml::listData(
            self::model()->findAll(array("select"=>"tdo1, tdo2", "order"=>"tdo2 ASC")),
            // поле модели $myOptionsModel, из которого будет взято value для <option>
            'tdo1',
            // поле модели $myOptionsModel, из которого будет взята подпись для <option>
            'tdo2'
        );
        return $res;
    }
}
