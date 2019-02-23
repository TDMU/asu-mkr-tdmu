<?php

/**
 * This is the model class for table "elgd".
 *
 * The followings are the available columns in table 'elgd':
 * @property integer $elgd1
 * @property integer $elgd0
 * @property integer $elgd2
 */
class Elgd extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'elgd';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('elgd1, elgd2', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('elgd1, elgd2', 'safe', 'on'=>'search'),
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
			'elgd1' => 'Elgd1',
			'elgd2' => 'Elgd2',
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

		$criteria->compare('elgd1',$this->elgd1);
		$criteria->compare('elgd2',$this->elgd2);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Elgd the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function getDop($elg1)
    {
        if(empty($elg1))
            return array();

        $sql=<<<SQL
                SELECT elgd.*,elgsd.*
                FROM elgd
                    INNER JOIN elgsd on (elgd.elgd2 = elgsd.elgsd1)
                WHERE elgd1=:ELG1
                ORDER BY elgsd4, elgd0
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':ELG1', $elg1);
        $rows = $command->queryAll();

        return $rows;
    }

	public static function checkEmptyElgd($elg1)
	{
		if(empty($elg1))
			return;
		$sql=<<<SQL
                SELECT elgsd.*
                FROM elgsd
                    LEFT JOIN elgd on (elgsd.elgsd1 = elgd.elgd2 AND elgd1 = :ELG1)
                WHERE elgd0 is NULL AND elgsd3=0
SQL;
		$command = Yii::app()->db->createCommand($sql);
		$command->bindValue(':ELG1', $elg1);
		$rows = $command->queryAll();
		foreach($rows as $row)
		{
			$elgd = new Elgd();
			$elgd->elgd0=new CDbExpression('GEN_ID(GEN_ELGD, 1)');
			$elgd->elgd1=$elg1;
			$elgd->elgd2=$row['elgsd1'];
			$elgd->save();
		}
		//return $rows;
	}

    /***
     * Проверка доступа на редактрование инд. работы для ирпеня
     * @param int $gr1
     * @return bool
     * @throws
     */
	public function checkAccessIndForIrpen($gr1){

	    $elg = Elg::model()->findByPk($this->elgd1);
	    if(empty($elg))
	        return false;

        $sql=<<<SQL
              SELECT first 1 r2 from EL_GURNAL_ZAN(:UO1,:GR1,:SEM1,:ELG4)
				inner join rz on (EL_GURNAL_ZAN.r4 = rz1)
			  order by EL_GURNAL_ZAN.nom desc
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':UO1', $elg->elg2);
        $command->bindValue(':SEM1', $elg->elg3);
        $command->bindValue(':ELG4', $elg->elg4);
        $command->bindValue(':GR1', $gr1);
        $date = $command->queryScalar();

        if(empty($date))
            return false;

        $date1 = new DateTime(date('Y-m-d H:i:s'));
        $date2 = new DateTime($date);

        if($date1 < $date2)
            return true;

        $diff = $date1->diff($date2)->days;
        if ($diff > 4)
            return false;

	    return true;
    }

    /***
     * Проверка доступа на редактрование доп. колонок
     * @param int $gr1
     * @return bool
     * @throws
     */
    public function checkAccess($gr1){

        $elg = Elg::model()->findByPk($this->elgd1);
        if(empty($elg))
            return false;

        $sql=<<<SQL
              SELECT first 1 r1 from EL_GURNAL_ZAN(:UO1,:GR1,:SEM1,:ELG4)
				inner join rz on (EL_GURNAL_ZAN.r4 = rz1)
			  order by EL_GURNAL_ZAN.nom asc 
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':UO1', $elg->elg2);
        $command->bindValue(':SEM1', $elg->elg3);
        $command->bindValue(':ELG4', $elg->elg4);
        $command->bindValue(':GR1', $gr1);
        $r1 = $command->queryScalar();

        if(empty($r1))
            return false;

        $sql = <<<SQL
            SELECT * FROM  EL_GURNAL(:P1,0,0,0,2,0,:R1,0,0);
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':P1', Yii::app()->user->dbModel->p1);
        $command->bindValue(':R1', $r1);
        $res = $command->queryRow();
        if (count($res) == 0 || empty($res) || $res['dostup'] == 0) {
            return false;
        }

        return true;
    }
}
