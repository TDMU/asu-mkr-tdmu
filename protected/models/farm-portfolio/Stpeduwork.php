<?php

/**
 * This is the model class for table "stpeduwork".
 *
 * The followings are the available columns in table 'stpeduwork':
 * @property integer $stpeduwork1
 * @property integer $stpeduwork2
 * @property integer $stpeduwork3
 * @property string $stpeduwork4
 * @property integer $stpeduwork5
 * @property integer $stpeduwork6
 * @property string $stpeduwork7
 * @property integer $stpeduwork8
 * @property string $stpeduwork9
 * @property integer $stpeduwork10
 * @property string $stpeduwork11
 *
 * The followings are the available model relations:
 * @property St $stpeduwork20
 * @property Users $stpeduwork60
 * @property Stpfile $stpeduwork100
 * @property Users $stpeduwork80
 */
class Stpeduwork extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'stpeduwork';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('stpeduwork3, stpeduwork4, stpeduwork5', 'required'),
			array('stpeduwork2, stpeduwork3, stpeduwork5, stpeduwork6, stpeduwork8, stpeduwork10', 'numerical', 'integerOnly'=>true),
			array('stpeduwork4', 'length', 'max'=>200),
            array('stpeduwork11', 'length', 'max'=>30),
            array('stpeduwork11, stpeduwork4','filter','filter'=>array($obj=new CHtmlPurifier(),'purify')),
			array('stpeduwork11', 'validateStpeduwork11'),
			array('stpeduwork7, stpeduwork9', 'length', 'max'=>20),
        );
	}

    /**
     *
     */
	public function validateStpeduwork11()
    {
        if($this->stpeduwork3 != 4)
            return;

        if(empty($this->stpeduwork11))
            $this->addError('stpeduwork11', tt('Введите свой вариант.'));
    }

    /**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'stpeduwork20' => array(self::BELONGS_TO, 'St', 'stpeduwork2'),
			'stpeduwork60' => array(self::BELONGS_TO, 'Users', 'stpeduwork6'),
			'stpeduwork100' => array(self::BELONGS_TO, 'Stpfile', 'stpeduwork10'),
			'stpeduwork80' => array(self::BELONGS_TO, 'Users', 'stpeduwork8'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'stpeduwork1' => '#',
			'stpeduwork2' => tt('Студент'),
			'stpeduwork3' => tt('Вид научно-иследовательской работы'),
			'stpeduwork4' => tt('Название работы'),
			'stpeduwork5' => tt('Учебный год'),
			'stpeduwork6' => tt('Редактировал'),
			'stpeduwork7' => tt('Дата редактирования'),
			'stpeduwork8' => tt('Подтвердил'),
			'stpeduwork9' => tt('Дата подтверждения'),
			'stpeduwork10' => tt('Файл'),
            'stpeduwork11' => tt('Вид работи (свой вариант)'),
		);
	}

	/**
     * @param $st1
     *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search($st1)
	{
		$criteria=new CDbCriteria;

		$criteria->compare('stpeduwork2',$st1);

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
	 * @return Stpeduwork the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * Вид науково-дослідницької роботи
     * @return array
     */
    public static function getStpeduwork3Types(){
        return array(
            0 => tt('исследовательские работы'),
            1 => tt('доклады научно-практических конференций'),
            2 => tt('рефераты'),
            3 => tt('печатные работы'),
            4 => tt('другое')
        );
    }

    /**
     * Вид науково-дослідницької роботи
     * @return string
     */
    public function getStpeduwork3Type(){
        if($this->stpeduwork3 == 4)
            return $this->stpeduwork11;
        $types = static::getStpeduwork3Types();
        if(isset($types[$this->stpeduwork3]))
            return $types[$this->stpeduwork3];
        return '-';
    }
}
