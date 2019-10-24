<?php

/**
 * This is the model class for table "grants".
 *
 * The followings are the available columns in table 'grants':
 * @property integer $grants1
 * @property integer $grants2
 * @property integer $grants3
 * @property integer $grants4
 * @property integer $grants6
 * @property integer $grants7
 * @property integer $grants8
 * @property integer $grants9
 *
 * The followings are the available model relations:
 * @property P $p
 */
class Grants extends CActiveRecord
{
    const DIST_EDUCATION = 'grant3';
    const MODULES = 'grant4';
    const DIST_EDUCATION_ADMIN = 'grant6';
    const STUDENT_INFO = 'grant7';
    const WORKLOAD = 'grant8';
    const QUIZ = 'grant9';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'grants';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('grants7', 'required', 'on' => 'admin-teachers'),
			array('grants1, grants2, grants3, grants4, grants6, grants7, grants8, grants9', 'numerical', 'integerOnly'=>true),
            array('grants8, grants7, grants6, grants3, grants4', 'safe', 'on' => 'admin-doctors'),
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
			'p' => array(self::BELONGS_TO, 'P', 'grants2'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'grants3' => tt('Дист. Образование'),
			'grants4' => tt('Ведомости'),
			'grants6' => tt('Дист. Образование админ'),
			'grants7' => tt('Данные студента'),
            'grants8' => tt('Нагрузка'),
            'grants9' => tt('Опрос'),
		);
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Grants the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function getGrantsFor($service)
    {
        switch($service){
            case self::DIST_EDUCATION:
                $grants = $this->grants3;
                break;
            case self::MODULES:
                $grants = $this->grants4;
                break;
            case self::DIST_EDUCATION_ADMIN:
                $grants = $this->grants6;
                break;
            case self::STUDENT_INFO:
                $grants = $this->grants7;
                break;
            case self::WORKLOAD:
                $grants = $this->grants8;
                break;
            case self::QUIZ:
                $grants = $this->grants9;
                break;
            default:
                $grants = null;
        }

        return $grants;
    }
}
