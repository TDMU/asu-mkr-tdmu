<?php

/**
 * This is the model class for table "opr".
 *
 * The followings are the available columns in table 'opr':
 * @property integer $opr1
 * @property string $opr2
 *
 * The followings are the available model relations:
 * @property Oprrez[] $oprrezs
 */
class Opr extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'opr';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('opr1', 'numerical', 'integerOnly'=>true),
			array('opr2', 'length', 'max'=>400),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('opr1, opr2', 'safe', 'on'=>'search'),
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
			'oprrezs' => array(self::HAS_MANY, 'Oprrez', 'oprrez3'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'opr1' => 'Opr1',
			'opr2' => 'Opr2',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('opr1',$this->opr1);
		$criteria->compare('opr2',$this->opr2,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Opr the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
