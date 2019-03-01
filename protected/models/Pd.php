<?php

/**
 * This is the model class for table "pd".
 *
 * The followings are the available columns in table 'pd':
 * @property integer $pd1
 * @property integer $pd2
 * @property integer $pd3
 * @property integer $pd4
 * @property integer $pd5
 * @property double $pd6
 * @property integer $pd7
 * @property integer $pd8
 * @property integer $pd9
 * @property string $pd10
 * @property string $pd11
 * @property string $pd12
 * @property string $pd13
 * @property string $pd15
 * @property string $pd16
 * @property string $pd17
 * @property string $pd18
 * @property string $pd19
 * @property string $pd20
 * @property integer $pd21
 * @property integer $pd28
 * @property integer $pd31
 * @property string $pd33
 * @property integer $pd34
 * @property integer $pd38
 * @property string $pd40
 * @property string $pd41
 * @property integer $pd43
 * @property string $pd44
 * @property integer $pd45
 * @property integer $pd46
 * @property integer $pd47
 * @property string $pd48
 * @property string $pd49
 * @property integer $pd50
 * @property integer $pd51
 * @property integer $pd52
 * @property integer $pd53
 */
class Pd extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pd';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pd1', 'required'),
			array('pd1, pd2, pd3, pd4, pd5, pd7, pd8, pd9, pd21, pd28, pd31, pd34, pd38, pd43, pd45, pd46, pd47, pd50, pd51, pd52, pd53', 'numerical', 'integerOnly'=>true),
			array('pd6', 'numerical'),
			array('pd10', 'length', 'max'=>100),
			array('pd11, pd12, pd13, pd44', 'length', 'max'=>8),
			array('pd15', 'length', 'max'=>200),
			array('pd16, pd17, pd18, pd19, pd20, pd40, pd41, pd48, pd49', 'length', 'max'=>4),
			array('pd33', 'length', 'max'=>600),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('pd1, pd2, pd3, pd4, pd5, pd6, pd7, pd8, pd9, pd10, pd11, pd12, pd13, pd15, pd16, pd17, pd18, pd19, pd20, pd21, pd28, pd31, pd33, pd34, pd38, pd40, pd41, pd43, pd44, pd45, pd46, pd47, pd48, pd49, pd50, pd51, pd52, pd53', 'safe', 'on'=>'search'),
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
			'pd1' => 'Pd1',
			'pd2' => 'Pd2',
			'pd3' => 'Pd3',
			'pd4' => 'Pd4',
			'pd5' => 'Pd5',
			'pd6' => 'Pd6',
			'pd7' => 'Pd7',
			'pd8' => 'Pd8',
			'pd9' => 'Pd9',
			'pd10' => 'Pd10',
			'pd11' => 'Pd11',
			'pd12' => 'Pd12',
			'pd13' => 'Pd13',
			'pd15' => 'Pd15',
			'pd16' => 'Pd16',
			'pd17' => 'Pd17',
			'pd18' => 'Pd18',
			'pd19' => 'Pd19',
			'pd20' => 'Pd20',
			'pd21' => 'Pd21',
			'pd28' => 'Pd28',
			'pd31' => 'Pd31',
			'pd33' => 'Pd33',
			'pd34' => 'Pd34',
			'pd38' => 'Pd38',
			'pd40' => 'Pd40',
			'pd41' => 'Pd41',
			'pd43' => 'Pd43',
			'pd44' => 'Pd44',
			'pd45' => 'Pd45',
			'pd46' => 'Pd46',
			'pd47' => 'Pd47',
			'pd48' => 'Pd48',
			'pd49' => 'Pd49',
			'pd50' => 'Pd50',
			'pd51' => 'Pd51',
			'pd52' => 'Pd52',
			'pd53' => 'Pd53',
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

		$criteria->compare('pd1',$this->pd1);
		$criteria->compare('pd2',$this->pd2);
		$criteria->compare('pd3',$this->pd3);
		$criteria->compare('pd4',$this->pd4);
		$criteria->compare('pd5',$this->pd5);
		$criteria->compare('pd6',$this->pd6);
		$criteria->compare('pd7',$this->pd7);
		$criteria->compare('pd8',$this->pd8);
		$criteria->compare('pd9',$this->pd9);
		$criteria->compare('pd10',$this->pd10,true);
		$criteria->compare('pd11',$this->pd11,true);
		$criteria->compare('pd12',$this->pd12,true);
		$criteria->compare('pd13',$this->pd13,true);
		$criteria->compare('pd15',$this->pd15,true);
		$criteria->compare('pd16',$this->pd16,true);
		$criteria->compare('pd17',$this->pd17,true);
		$criteria->compare('pd18',$this->pd18,true);
		$criteria->compare('pd19',$this->pd19,true);
		$criteria->compare('pd20',$this->pd20,true);
		$criteria->compare('pd21',$this->pd21);
		$criteria->compare('pd28',$this->pd28);
		$criteria->compare('pd31',$this->pd31);
		$criteria->compare('pd33',$this->pd33,true);
		$criteria->compare('pd34',$this->pd34);
		$criteria->compare('pd38',$this->pd38);
		$criteria->compare('pd40',$this->pd40,true);
		$criteria->compare('pd41',$this->pd41,true);
		$criteria->compare('pd43',$this->pd43);
		$criteria->compare('pd44',$this->pd44,true);
		$criteria->compare('pd45',$this->pd45);
		$criteria->compare('pd46',$this->pd46);
		$criteria->compare('pd47',$this->pd47);
		$criteria->compare('pd48',$this->pd48,true);
		$criteria->compare('pd49',$this->pd49,true);
		$criteria->compare('pd50',$this->pd50);
		$criteria->compare('pd51',$this->pd51);
		$criteria->compare('pd52',$this->pd52);
		$criteria->compare('pd53',$this->pd53);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Pd the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getChairs()
    {
		$sql = <<<SQL
            SELECT pd1,pd4,k2,k3 FROM pd JOIN k ON pd4=k1 WHERE pd2=:p1  AND pd4>0 and pd28 in (0,2,5,9) and pd11<=current_timestamp and (pd13 is null or pd13>current_timestamp) GROUP BY pd1,pd4,k2,k3
SQL;
		$command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':p1', Yii::app()->user->dbModel->p1);
        $chairs = $command->queryAll();

        return $chairs;
	}

	public function getTeacherAndChairByP1($p1){
		$sql = <<<SQL
            SELECT first 1 p3,p4,p5,k2,k3 FROM pd
            INNER JOIN  P ON ( PD2 = P1)
            INNER JOIN  K ON ( PD4 = K1)
            WHERE p1=:p1
            GROUP BY p3,p4,p5,k2,k3
SQL;
		$command = Yii::app()->db->createCommand($sql);
		$command->bindValue(':p1', $p1);
		$info = $command->queryRow();

		return $info;
	}

	public function getTeacherAndChairByPd1($pd1){
		$sql = <<<SQL
            SELECT p3,p4,p5,k2,k3 FROM pd
            INNER JOIN  P ON ( PD2 = P1)
            INNER JOIN  K ON ( PD4 = K1)
            WHERE pd1=:pd1
            GROUP BY p3,p4,p5,k2,k3
SQL;
		$command = Yii::app()->db->createCommand($sql);
		$command->bindValue(':pd1', $pd1);
		$info = $command->queryRow();

		return $info;
	}
}
