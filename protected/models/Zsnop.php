<?php

/**
 * This is the model class for table "zsnop".
 *
 * The followings are the available columns in table 'zsnop':
 * @property integer $zsnop0
 * @property integer $zsnop1
 *
 * The followings are the available model relations:
 * @property Zsno $zsnop00
 * @property Elgp $zsnop10
 */
class Zsnop extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'zsnop';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('zsnop0, zsnop1', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('zsnop0, zsnop1', 'safe', 'on'=>'search'),
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
			'zsnop00' => array(self::BELONGS_TO, 'Zsno', 'zsnop0'),
			'zsnop10' => array(self::BELONGS_TO, 'Elgp', 'zsnop1'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'zsnop0' => 'Zsnop0',
			'zsnop1' => 'Zsnop1',
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

		$criteria->compare('zsnop0',$this->zsnop0);
		$criteria->compare('zsnop1',$this->zsnop1);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Zsnop the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
