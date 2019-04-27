<?php

/**
 * This is the model class for table "elgp".
 *
 * The followings are the available columns in table 'elgp':
 * @property integer $elgp0
 * @property integer $elgp1
 * @property integer $elgp2
 * @property string $elgp3
 * @property string $elgp4
 * @property string $elgp5
 * @property string $elgp6
 * @property integer $elgp7
 *
 * The followings are the available model relations:
 * @property Elgzst $elgp10
 * @property P $elgp70
 */
class Elgp extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'elgp';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('elgp0', 'required'),
			array('elgp1, elgp2, elgp7', 'numerical', 'integerOnly'=>true),
			array('elgp3, elgp4', 'length', 'max'=>80),
			array('elgp5, elgp6', 'length', 'max'=>30),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('elgp0, elgp1, elgp2, elgp3, elgp4, elgp5, elgp6, elgp7', 'safe', 'on'=>'search'),
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
			'elgp10' => array(self::BELONGS_TO, 'Elgzst', 'elgp1'),
			'elgp70' => array(self::BELONGS_TO, 'P', 'elgp7'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'elgp0' => 'Elgp0',
			'elgp1' => 'Elgp1',
			'elgp2' => tt('Тип пропуска'),
            'elgp3' => tt('№ справки'),
			'elgp4' => tt('№ квитанции (тип оплата)'),
			'elgp5' => tt('Дата квитанции(тип оплата)'),
			'elgp6' => 'Elgp6',
			'elgp7' => 'Elgp7',
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

		$criteria->compare('elgp0',$this->elgp0);
		$criteria->compare('elgp1',$this->elgp1);
		$criteria->compare('elgp2',$this->elgp2);
		$criteria->compare('elgp3',$this->elgp3,true);
		$criteria->compare('elgp4',$this->elgp4,true);
		$criteria->compare('elgp5',$this->elgp5,true);
		$criteria->compare('elgp6',$this->elgp6,true);
		$criteria->compare('elgp7',$this->elgp7);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Elgp the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


    public function getOmissions($st1,$date1,$date2,$gr1,$sem1)
    {
        if (empty($st1) || empty($date1) || empty($date2) || empty($gr1)||empty($sem1))
            return array();

        $sql=<<<SQL
        select elgp.*,d2,elgzst3,elgzst0,elgz3,uo1,elg4,elg2
			from elgzst
			   inner join elgz on (elgzst.elgzst2 = elgz.elgz1)
			   inner join elg on (elgz.elgz2 = elg.elg1)
			   left join elgp on (elgzst.elgzst0 = elgp.elgp1)
			   inner join ustem on (elgz.elgz7 = ustem.ustem1)
			   INNER JOIN uo on (elg.elg2 = uo.uo1)
			   INNER JOIN d on (d.d1 = uo.uo3)
			where elgzst1=:ST1 and elg3=:SEM1 and elgzst3>0 and d1 in (select d1 FROM  EL_GURNAL(:P1,:YEAR,:SEM,0,0,0,0,3,0))
			ORDER BY elgz3
SQL;

        $command = Yii::app()->db->createCommand($sql);
       	$command->bindValue(':ST1', $st1);
        $command->bindValue(':P1', Yii::app()->user->dbModel->p1);
        $command->bindValue(':YEAR', Yii::app()->session['year']);
        $command->bindValue(':SEM', Yii::app()->session['sem']);
		$command->bindValue(':SEM1', $sem1);
        $rows = $command->queryAll();
        return $rows;
    }

	public function getDateTypeLesson($uo1,$gr1,$sem1, $elg4,$date1,$date2,$nom)
	{
		if (empty($uo1) || $elg4==null || empty($gr1)||empty($sem1)|| empty($date1) || empty($date2) || empty($nom))
			return array();

		$sql=<<<SQL
        select r2,us4 from EL_GURNAL_ZAN(:UO1,:GR1,:SEM1, :ELG4) where EL_GURNAL_ZAN.nom = :NOM and EL_GURNAL_ZAN.r2>=:DATE1 and EL_GURNAL_ZAN.r2<=:DATE2
SQL;
		$command = Yii::app()->db->createCommand($sql);
		$command->bindValue(':UO1', $uo1);
		$command->bindValue(':ELG4', $elg4);
		$command->bindValue(':NOM', $nom);
		$command->bindValue(':GR1', $gr1);
		$command->bindValue(':DATE1', $date1);
		$command->bindValue(':DATE2', $date2);
		$command->bindValue(':SEM1', $sem1);
		$row = $command->queryRow();

		return $row;
	}
}
