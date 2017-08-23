<?php

/**
 * This is the model class for table "sob".
 *
 * The followings are the available columns in table 'sob':
 * @property integer $sob0
 * @property integer $sob1
 * @property double $sob2
 * @property string $sob3
 * @property string $sob4
 * @property string $sob5
 * @property integer $sob6
 * @property integer $sob7
 * @property integer $sob8
 * @property integer $sob9
 */
class Sob extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sob';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sob0', 'required'),
			array('sob0, sob1, sob6, sob7, sob8, sob9', 'numerical', 'integerOnly'=>true),
			array('sob2', 'numerical'),
			array('sob3, sob5', 'length', 'max'=>8),
			array('sob4', 'length', 'max'=>60),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('sob0, sob1, sob2, sob3, sob4, sob5, sob6, sob7, sob8, sob9', 'safe', 'on'=>'search'),
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
			'sob0' => 'Sob0',
			'sob1' => 'Sob1',
			'sob2' => 'Sob2',
			'sob3' => 'Sob3',
			'sob4' => 'Sob4',
			'sob5' => 'Sob5',
			'sob6' => 'Sob6',
			'sob7' => 'Sob7',
			'sob8' => 'Sob8',
			'sob9' => 'Sob9',
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

		$criteria->compare('sob0',$this->sob0);
		$criteria->compare('sob1',$this->sob1);
		$criteria->compare('sob2',$this->sob2);
		$criteria->compare('sob3',$this->sob3,true);
		$criteria->compare('sob4',$this->sob4,true);
		$criteria->compare('sob5',$this->sob5,true);
		$criteria->compare('sob6',$this->sob6);
		$criteria->compare('sob7',$this->sob7);
		$criteria->compare('sob8',$this->sob8);
		$criteria->compare('sob9',$this->sob9);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Sob the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function getPayments($st1)
    {
        $sql = <<<SQL
            SELECT sob2 as money, sob3 as dat
			FROM sob
			WHERE sob1= :ST1
			ORDER BY sob3
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
