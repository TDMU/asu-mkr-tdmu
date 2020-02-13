<?php

/**
 * This is the model class for table "stpfwork".
 *
 * The followings are the available columns in table 'stpfwork':
 * @property integer $stpfwork1
 * @property integer $stpfwork2
 * @property string $stpfwork3
 * @property string $stpfwork4
 * @property string $stpfwork5
 * @property integer $stpfwork6
 * @property string $stpfwork7
 *
 * The followings are the available model relations:
 * @property St $student
 * @property Users $user
 */
class Stpfwork extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'stpfwork';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('stpfwork2, stpfwork6', 'numerical', 'integerOnly'=>true),
			array('stpfwork3, stpfwork4, stpfwork5', 'length', 'max'=>400),
            array('stpfwork3, stpfwork4, stpfwork5','filter','filter'=>array($obj=new CHtmlPurifier(),'purify')),
			array('stpfwork7', 'length', 'max'=>20),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'student' => array(self::BELONGS_TO, 'St', 'stpfwork2'),
			'user' => array(self::BELONGS_TO, 'Users', 'stpfwork6'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'stpfwork1' => '#',
			'stpfwork2' => tt('Студент'),
			'stpfwork3' => tt('Название организации трудоустройства и ее местонахождение'),
			'stpfwork4' => tt('Карьерный путь (должность)'),
			'stpfwork5' => tt('Период работы на должности'),
			'stpfwork6' => tt('Редактировал'),
			'stpfwork7' => tt('Дата редактирования'),
		);
	}

    /**
     * @param $st1
     * @return CActiveDataProvider
     */
    public function search($st1)
    {
        $criteria=new CDbCriteria;

        $criteria->compare('stpfwork2',$st1);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort' => false,
            'pagination'=>false
        ));
    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Stpfwork the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
