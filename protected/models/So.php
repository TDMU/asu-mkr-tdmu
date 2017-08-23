<?php

/**
 * This is the model class for table "so".
 *
 * The followings are the available columns in table 'so':
 * @property integer $so0
 * @property integer $so1
 * @property double $so2
 * @property string $so3
 * @property string $so4
 * @property integer $so5
 * @property string $so6
 * @property integer $so7
 * @property string $so8
 */
class So extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'so';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('so0', 'required'),
			array('so0, so1, so5, so7', 'numerical', 'integerOnly'=>true),
			array('so2', 'numerical'),
			array('so3, so6, so8', 'length', 'max'=>8),
			array('so4', 'length', 'max'=>60),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('so0, so1, so2, so3, so4, so5, so6, so7, so8', 'safe', 'on'=>'search'),
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
			'so0' => 'So0',
			'so1' => 'So1',
			'so2' => 'So2',
			'so3' => 'So3',
			'so4' => 'So4',
			'so5' => 'So5',
			'so6' => 'So6',
			'so7' => 'So7',
			'so8' => 'So8',
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

		$criteria->compare('so0',$this->so0);
		$criteria->compare('so1',$this->so1);
		$criteria->compare('so2',$this->so2);
		$criteria->compare('so3',$this->so3,true);
		$criteria->compare('so4',$this->so4,true);
		$criteria->compare('so5',$this->so5);
		$criteria->compare('so6',$this->so6,true);
		$criteria->compare('so7',$this->so7);
		$criteria->compare('so8',$this->so8,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return So the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function getPayments($st1)
    {
        $sql = <<<SQL
            SELECT so2 as money, so3 as dat
			FROM so
			WHERE so1= :ST1 and so5=0
			ORDER BY so3
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':ST1', $st1);
        $payments = $command->queryAll();

        $res = array();

        foreach ($payments as $payment) {

            $date  = $payment['dat'];

            $year  = date('Y', strtotime($date));
            $month = date('m', strtotime($date));

            $res[$year][$month][] = $payment;
        }

        return $payments;
    }
}
