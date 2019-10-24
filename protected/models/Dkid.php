<?php

/**
 * This is the model class for table "dkid".
 *
 * The followings are the available columns in table 'dkid':
 * @property integer $dkid0
 * @property integer $dkid1
 * @property string $dkid2
 * @property string $dkid3
 * @property $performens Ido[]
 */
class Dkid extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'dkid';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('dkid1, dkid0', 'numerical', 'integerOnly'=>true),
			array('dkid2, dkid3', 'length', 'max'=>20),
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
            'performens' => array(self::HAS_MANY, 'Ido', 'ido11'),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Dkid the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * @param $date false|DateTime
     * @return string
     */
	public function getDateString($date){
        if($date===false)
            return '';
        if($date->format('Y') =='0001')
            return '';

        return $date->format('Y-m-d');
    }
}
