<?php

/**
 * This is the model class for table "elgz".
 *
 * The followings are the available columns in table 'elgz':
 * @property integer $elgz1
 * @property integer $elgz2
 * @property integer $elgz3
 * @property integer $elgz4
 * @property double $elgz5
 * @property double $elgz6
 * @property integer $elgz7
 *
 * The followings are the available model relations:
 * @property R[] $rs
 * @property Elg $elgz20
 * @property Ustem $elgz70
 * @property Elgzst[] $elgzsts
 */
class Elgz extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'elgz';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('elgz1', 'required'),
			array('elgz1, elgz2, elgz3, elgz4, elgz7', 'numerical', 'integerOnly'=>true),
			array('elgz5, elgz6', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('elgz1, elgz2, elgz3, elgz4, elgz5, elgz6, elgz7', 'safe', 'on'=>'search'),
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
			'rs' => array(self::HAS_MANY, 'R', 'r8'),
			'elgz20' => array(self::BELONGS_TO, 'Elg', 'elgz2'),
			'elgz70' => array(self::BELONGS_TO, 'Ustem', 'elgz7'),
			'elgzsts' => array(self::HAS_MANY, 'Elgzst', 'elgzst2'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'elgz1' => 'Elgz1',
			'elgz2' => 'Elgz2',
			'elgz3' => 'Elgz3',
			'elgz4' => 'Elgz4',
			'elgz5' => 'Elgz5',
			'elgz6' => 'Elgz6',
			'elgz7' => 'Elgz7',
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

		$criteria->compare('elgz1',$this->elgz1);
		$criteria->compare('elgz2',$this->elgz2);
		$criteria->compare('elgz3',$this->elgz3);
		$criteria->compare('elgz4',$this->elgz4);
		$criteria->compare('elgz5',$this->elgz5);
		$criteria->compare('elgz6',$this->elgz6);
		$criteria->compare('elgz7',$this->elgz7);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Elgz the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getSemYearAndSem($elgz1){
		$sql=<<<SQL
		 SELECT sem3,sem5 FROM elgz
		 	INNER JOIN elg on (elgz2 = elg1)
		 	INNER JOIN sem on (elg3 = sem1)
		 WHERE elgz1=:ELGZ1
SQL;

		$command = Yii::app()->db->createCommand($sql);
		$command->bindValue(':ELGZ1', $elgz1);
		$row = $command->queryRow();

		if(!empty($row))
			return array($row['sem3'],$row['sem5']);
		else
			return array(0,0);
	}

	public function getUo1($elgz1){
		$sql=<<<SQL
		 SELECT elg2 FROM elgz
		 	INNER JOIN elg on (elgz2 = elg1)
		 WHERE elgz1=:ELGZ1
SQL;

		$command = Yii::app()->db->createCommand($sql);
		$command->bindValue(':ELGZ1', $elgz1);
		$row = $command->queryScalar();

		if(!empty($row))
			return $row;
		else
			return 0;
	}
	/*Для смены тем, проверка закрыто занятие иди нет*/
	public function checkLesson($date,$permLesson,$ps78,$date1,$ps27){
		$disabled = false;
		if(isset($permLesson[$date['elgz1']]))
			if(strtotime($permLesson[$date['elgz1']]) <= strtotime('yesterday'))
				$disabled = true;

		if($ps78==0) {
			$date2 = new DateTime($date['r2']);
			if (!empty($ps27) && !isset($permLesson[$date['elgz1']])) {
				$diff = $date1->diff($date2)->days;
				if ($diff > $ps27) {
					$disabled = true;
				}
			}
		}

		return $disabled;
	}

    /**
     * Проверка можно ли редактированиять мин/макс для ирпеня
     * @return bool
     */
	public function checkAccessMinMixIrpen($gr1){

        $elg = Elg::model()->findByPk($this->elgz2);
        if(empty($elg))
            return false;

        $sql=<<<SQL
              SELECT first 1 r2 from EL_GURNAL_ZAN(:UO1,:GR1,:SEM1,:ELG4)
				inner join rz on (EL_GURNAL_ZAN.r4 = rz1)
			  order by  EL_GURNAL_ZAN.nom asc
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':UO1', $elg->elg2);
        $command->bindValue(':SEM1', $elg->elg3);
        $command->bindValue(':ELG4', $elg->elg4);
        $command->bindValue(':ELG1', $elg->elg1);
        $command->bindValue(':GR1', $gr1);
        $date = $command->queryScalar();

        if(empty($date))
            return false;

        $date1 = new DateTime(date('Y-m-d H:i:s'));
        $date2 = new DateTime($date);

        if($date1 < $date2)
            return true;

        $diff = $date1->diff($date2)->days;
        if ($diff > PortalSettings::model()->getSettingFor(PortalSettings::IRPEN_COUNT_DAYS_FOR_MIN_MAX))
            return false;

	    return true;
    }

}
