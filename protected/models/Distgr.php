<?php

/**
 * This is the model class for table "distgr".
 *
 * Группы в дистанционном образвании
 *
 * The followings are the available columns in table 'distgr':
 * @property integer $distgr1
 * @property integer $distgr2
 * @property integer $distgr3
 *
 * The followings are the available model relations:
 * @property Gr $distgr10
 * @property DispDist $distgr20
 */
class Distgr extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'distgr';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('distgr1, distgr2, distgr3', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('distgr1, distgr2, distgr3', 'safe', 'on'=>'search'),
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
			'distgr10' => array(self::BELONGS_TO, 'Gr', 'distgr1'),
            'distgr20' => array(self::BELONGS_TO, 'DispDist', 'distgr2'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'distgr1' => 'Группа(АСУ)',
			'distgr2' => 'Код курса',
			'distgr3' => 'Distgr3',
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

		$criteria->compare('distgr1',$this->distgr1);
		$criteria->compare('distgr2',$this->distgr2);
		$criteria->compare('distgr3',$this->distgr3);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Distgr the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
