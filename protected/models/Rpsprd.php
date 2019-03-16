<?php

/**
 * This is the model class for table "rpsprd".
 *
 * The followings are the available columns in table 'rpsprd':
 * @property integer $rpsprd0
 * @property integer $rpsprd1
 * @property string $rpsprd2
 * @property string $rpsprd3
 * @property integer $rpsprd4
 *
 * The followings are the available model relations:
 * @property Rpspr $rpsprd00
 * @property Elgp $rpsprd10
 * @property I $rpsprd40
 */
class Rpsprd extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rpsprd';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rpsprd0, rpsprd1, rpsprd4', 'numerical', 'integerOnly'=>true),
			array('rpsprd2', 'length', 'max'=>8),
			array('rpsprd3', 'length', 'max'=>1000),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('rpsprd0, rpsprd1, rpsprd2, rpsprd3, rpsprd4', 'safe', 'on'=>'search'),
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
			'rpsprd00' => array(self::BELONGS_TO, 'Rpspr', 'rpsprd0'),
			'rpsprd10' => array(self::BELONGS_TO, 'Elgp', 'rpsprd1'),
			'rpsprd40' => array(self::BELONGS_TO, 'I', 'rpsprd4'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'rpsprd0' => 'Rpsprd0',
			'rpsprd1' => 'Rpsprd1',
			'rpsprd2' => 'Rpsprd2',
			'rpsprd3' => 'Rpsprd3',
			'rpsprd4' => 'Rpsprd4',
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

		$criteria->compare('rpsprd0',$this->rpsprd0);
		$criteria->compare('rpsprd1',$this->rpsprd1);
		$criteria->compare('rpsprd2',$this->rpsprd2,true);
		$criteria->compare('rpsprd3',$this->rpsprd3,true);
		$criteria->compare('rpsprd4',$this->rpsprd4);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Rpsprd the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
