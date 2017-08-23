<?php

/**
 * This is the model class for table "stbl".
 *
 * The followings are the available columns in table 'stbl':
 * @property integer $stbl2
 * @property string $stbl3
 * @property integer $stbl4
 * @property string $stbl5
 * @property integer $stbl6
 *
 * The followings are the available model relations:
 * @property St $stbl20
 * @property I $stbl40
 * @property P $stbl60
 */
class Stbl extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'stbl';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('stbl2, stbl4, stbl6', 'numerical', 'integerOnly'=>true),
			array('stbl3, stbl5', 'length', 'max'=>30),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('stbl1, stbl2, stbl3, stbl4, stbl5, stbl6', 'safe', 'on'=>'search'),
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
			'stbl20' => array(self::BELONGS_TO, 'St', 'stbl2'),
			'stbl40' => array(self::BELONGS_TO, 'I', 'stbl4'),
			'stbl60' => array(self::BELONGS_TO, 'P', 'stbl6'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			//'stbl1' => 'Stbl1',
			'stbl2' => 'Stbl2',
			'stbl3' => 'Stbl3',
			'stbl4' => 'Stbl4',
			'stbl5' => 'Stbl5',
			'stbl6' => 'Stbl6',
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

		//$criteria->compare('stbl1',$this->stbl1);
		$criteria->compare('stbl2',$this->stbl2);
		$criteria->compare('stbl3',$this->stbl3,true);
		$criteria->compare('stbl4',$this->stbl4);
		$criteria->compare('stbl5',$this->stbl5,true);
		$criteria->compare('stbl6',$this->stbl6);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Stbl the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
