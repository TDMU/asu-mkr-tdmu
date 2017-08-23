<?php

/**
 * This is the model class for table "spob".
 *
 * The followings are the available columns in table 'spob':
 * @property integer $spob0
 * @property integer $spob1
 * @property integer $spob2
 * @property integer $spob3
 * @property double $spob4
 * @property double $spob6
 * @property string $spob7
 * @property string $spob8
 * @property integer $spob9
 * @property string $spob10
 * @property double $spob11
 * @property integer $spob12
 * @property integer $spob13
 * @property integer $spob14
 */
class Spob extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'spob';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('spob0', 'required'),
			array('spob0, spob1, spob2, spob3, spob9, spob12, spob13, spob14', 'numerical', 'integerOnly'=>true),
			array('spob4, spob6, spob11', 'numerical'),
			array('spob7', 'length', 'max'=>200),
			array('spob8', 'length', 'max'=>8),
			array('spob10', 'length', 'max'=>4),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('spob0, spob1, spob2, spob3, spob4, spob6, spob7, spob8, spob9, spob10, spob11, spob12, spob13, spob14', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'spob0' => 'Spob0',
			'spob1' => 'Spob1',
			'spob2' => 'Spob2',
			'spob3' => 'Spob3',
			'spob4' => 'Spob4',
			'spob6' => 'Spob6',
			'spob7' => 'Spob7',
			'spob8' => 'Spob8',
			'spob9' => 'Spob9',
			'spob10' => 'Spob10',
			'spob11' => 'Spob11',
			'spob12' => 'Spob12',
			'spob13' => 'Spob13',
			'spob14' => 'Spob14',
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

		$criteria->compare('spob0',$this->spob0);
		$criteria->compare('spob1',$this->spob1);
		$criteria->compare('spob2',$this->spob2);
		$criteria->compare('spob3',$this->spob3);
		$criteria->compare('spob4',$this->spob4);
		$criteria->compare('spob6',$this->spob6);
		$criteria->compare('spob7',$this->spob7,true);
		$criteria->compare('spob8',$this->spob8,true);
		$criteria->compare('spob9',$this->spob9);
		$criteria->compare('spob10',$this->spob10,true);
		$criteria->compare('spob11',$this->spob11);
		$criteria->compare('spob12',$this->spob12);
		$criteria->compare('spob13',$this->spob13);
		$criteria->compare('spob14',$this->spob14);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Spob the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function getPlan($st1)
    {
        $sql = <<<SQL
            SELECT spob2 as y,spob3 as m1, spob3 as m2,spob4+spob6-spob11 as money
			FROM spob
			WHERE spob1= :ST1
			ORDER BY spob2,spob3
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':ST1', $st1);
        $plan = $command->queryAll();

        return $plan;
    }
}
