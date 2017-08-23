<?php

/**
 * This is the model class for table "cxmb".
 *
 * The followings are the available columns in table 'cxmb':
 * @property integer $cxmb0
 * @property integer $cxmb2
 * @property string $cxmb3
 * @property integer $cxmb4
 * @property integer $cxmb5
 */
class Cxmb extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cxmb';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cxmb0, cxmb2, cxmb4, cxmb5', 'numerical', 'integerOnly'=>true),
			array('cxmb3', 'length', 'max'=>8),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cxmb0, cxmb2, cxmb3, cxmb4, cxmb5', 'safe', 'on'=>'search'),
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
			'cxmb0' => 'Cxmb0',
			'cxmb2' => 'Cxmb2',
			'cxmb3' => 'Cxmb3',
			'cxmb4' => 'Cxmb4',
			'cxmb5' => 'Cxmb5',
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

		$criteria->compare('cxmb0',$this->cxmb0);
		$criteria->compare('cxmb2',$this->cxmb2);
		$criteria->compare('cxmb3',$this->cxmb3,true);
		$criteria->compare('cxmb4',$this->cxmb4);
		$criteria->compare('cxmb5',$this->cxmb5);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Cxmb the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function getExtraMarks($cxmb0, $mark)
    {
        if (is_null($cxmb0))
            return null;

        if ((int)$mark < 0)
            return array('cxmb3' => '', 'cxmb2' => 0);

        $sql = <<<SQL
            select cxmb2,cxmb3
            from cxmb
            where cxmb0=:CXMB0 and cxmb4<=:MARK1 and cxmb5>=:MARK2
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':CXMB0', $cxmb0);
        $command->bindValue(':MARK1', $mark);
        $command->bindValue(':MARK2', $mark);
        $marks = $command->queryRow();
        return $marks;
    }

    public function getList()
    {
        
    }

	public function getMark($mark)
	{
		if ((int)$mark < 0)
			return array('cxmb3' => '', 'cxmb2' => 0);

		$sql = <<<SQL
            select cxmb2,cxmb3
            from cxmb
            where cxmb4<=:MARK1 and cxmb5>=:MARK2
SQL;

		$command = Yii::app()->db->createCommand($sql);
		$command->bindValue(':MARK1', $mark);
		$command->bindValue(':MARK2', $mark);
		$marks = $command->queryRow();

		if(empty($mark))
            return array('cxmb3' => '', 'cxmb2' => 0);

		return $marks;
	}

	public function getMarkByECTS($mark)
	{

		$sql = <<<SQL
            select cxmb2,cxmb3
            from cxmb
            where CXMB3='{$mark}'
SQL;

		$command = Yii::app()->db->createCommand($sql);
		//$command->bindValue(':MARK', $mark);
		//$command->bindValue(':MARK2', $mark);
		$marks = $command->queryRow();
		return $marks;
	}

}
