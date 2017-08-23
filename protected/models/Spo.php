<?php

/**
 * This is the model class for table "spo".
 *
 * The followings are the available columns in table 'spo':
 * @property integer $spo0
 * @property integer $spo1
 * @property double $spo2
 * @property double $spo3
 * @property string $spo4
 * @property integer $spo5
 * @property integer $spo6
 * @property double $spo7
 * @property string $spo8
 * @property integer $spo9
 */
class Spo extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'spo';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('spo0', 'required'),
			array('spo0, spo1, spo5, spo6, spo9', 'numerical', 'integerOnly'=>true),
			array('spo2, spo3, spo7', 'numerical'),
			array('spo4', 'length', 'max'=>200),
			array('spo8', 'length', 'max'=>8),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('spo0, spo1, spo2, spo3, spo4, spo5, spo6, spo7, spo8, spo9', 'safe', 'on'=>'search'),
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
			'spo0' => 'Spo0',
			'spo1' => 'Spo1',
			'spo2' => 'Spo2',
			'spo3' => 'Spo3',
			'spo4' => 'Spo4',
			'spo5' => 'Spo5',
			'spo6' => 'Spo6',
			'spo7' => 'Spo7',
			'spo8' => 'Spo8',
			'spo9' => 'Spo9',
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

		$criteria->compare('spo0',$this->spo0);
		$criteria->compare('spo1',$this->spo1);
		$criteria->compare('spo2',$this->spo2);
		$criteria->compare('spo3',$this->spo3);
		$criteria->compare('spo4',$this->spo4,true);
		$criteria->compare('spo5',$this->spo5);
		$criteria->compare('spo6',$this->spo6);
		$criteria->compare('spo7',$this->spo7);
		$criteria->compare('spo8',$this->spo8,true);
		$criteria->compare('spo9',$this->spo9);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Spo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function getPlan($st1)
    {
        $sql = <<<SQL
            SELECT spo6 as y,'01' as m1, '12' as m2, spo2+spo7 as money
			FROM spo
			WHERE spo1= :ST1 and spo5=0
			ORDER BY spo6
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':ST1', $st1);
        $plan = $command->queryAll();

        return $plan;
    }
}
