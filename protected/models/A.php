<?php

/**
 * This is the model class for table "a".
 *
 * The followings are the available columns in table 'a':
 * @property integer $a1
 * @property string $a2
 * @property integer $a3
 * @property string $a4
 * @property string $a5
 * @property integer $a6
 * @property string $a8
 * @property integer $a9
 * @property string $a11
 * @property integer $a12
 * @property string $a14
 * @property string $a15
 * @property integer $a16
 */
class A extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'a';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('a1, a3, a6, a9, a12, a16', 'numerical', 'integerOnly'=>true),
			array('a2', 'length', 'max'=>48),
			array('a4, a14', 'length', 'max'=>200),
			array('a5, a11', 'length', 'max'=>8),
			array('a8', 'length', 'max'=>4),
			array('a15', 'length', 'max'=>40),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('a1, a2, a3, a4, a5, a6, a8, a9, a11, a12, a14, a15, a16', 'safe', 'on'=>'search'),
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
			'a1' => 'A1',
			'a2' => 'A2',
			'a3' => 'A3',
			'a4' => 'A4',
			'a5' => 'A5',
			'a6' => 'A6',
			'a8' => 'A8',
			'a9' => 'A9',
			'a11' => 'A11',
			'a12' => 'A12',
			'a14' => 'A14',
			'a15' => 'A15',
			'a16' => 'A16',
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

		$criteria->compare('a1',$this->a1);
		$criteria->compare('a2',$this->a2,true);
		$criteria->compare('a3',$this->a3);
		$criteria->compare('a4',$this->a4,true);
		$criteria->compare('a5',$this->a5,true);
		$criteria->compare('a6',$this->a6);
		$criteria->compare('a8',$this->a8,true);
		$criteria->compare('a9',$this->a9);
		$criteria->compare('a11',$this->a11,true);
		$criteria->compare('a12',$this->a12);
		$criteria->compare('a14',$this->a14,true);
		$criteria->compare('a15',$this->a15,true);
		$criteria->compare('a16',$this->a16);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return A the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public static function getTimeTable($a1, $date1, $date2)
    {
        // `'' as a2` is used only to avoid inconvenience in the code
        $sql = <<<SQL
        SELECT *
        FROM RAGRST(:LANG, 0, 0, :A1, 0, 0, :DATE_1, :DATE_2)
        ORDER BY r2,r3,rz2
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':LANG', TimeTableForm::getLangCode());
        $command->bindValue(':A1', $a1);
        $command->bindValue(':DATE_1', $date1);
        $command->bindValue(':DATE_2', $date2);
        $timeTable = $command->queryAll();

        if (empty($timeTable))
            return array();

        return $timeTable;
    }

    public function getClassRooms($filial, $housing)
    {
        if (! empty($housing)) {
            $sql=<<<SQL
                SELECT *
                FROM kaa
                INNER JOIN a on (kaa.kaa2 = a.a1)
                WHERE A5 is null and A6=:FILIAL and A1>0 and kaa1=:HOUSING
                ORDER BY A8,A9,A2
SQL;
            $command = Yii::app()->db->createCommand($sql);
            $command->bindValue(':FILIAL', $filial);
            $command->bindValue(':HOUSING', $housing);
        } else {
            $sql= <<<SQL
                SELECT *
                FROM A
                WHERE A5 is null and A6=:FILIAL and A1>0
                ORDER BY A8,A9,A2
SQL;
            $command = Yii::app()->db->createCommand($sql);
            $command->bindValue(':FILIAL', $filial);
        }

        $classrooms = $command->queryAll();

        return $classrooms;
    }

    public function getOccupiedRooms(TimeTableForm $model)
    {
        if ($model->filial)
            $sql=<<<SQL
                SELECT a1
                FROM kaa
                INNER JOIN a on (kaa.kaa2 = a.a1)
                INNER JOIN r on (a.a1 = r.r5)
                INNER JOIN rz on (r.r4 = rz.rz1)
                WHERE r2=:DATE and rz6>=:LESSON_START and rz6<=:LESSON_END and kaa1=:FILIAL
SQL;
        else
            $sql=<<<SQL
                SELECT a1
                FROM a
                INNER JOIN r on (a.a1 = r.r5)
                INNER JOIN rz on (r.r4 = rz.rz1)
                WHERE r2=:DATE and rz6>=:LESSON_START and rz6<=:LESSON_END
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':FILIAL', $model->filial);
        $command->bindValue(':DATE',   $model->date1);
        $command->bindValue(':LESSON_START', $model->lessonStart);
        $command->bindValue(':LESSON_END', $model->lessonEnd);

        $classrooms = $command->queryColumn();

        return array_filter($classrooms);
    }

    public function getFreeRooms($filial, $r2, $r3)
    {
        $filial = ! empty($filial) ? 'and ka3 = '.$filial : '';

        $sql= <<<SQL
            SELECT A1,A2,KA2
            FROM a
            INNER JOIN kaa on (a.a1 = kaa.kaa2)
            INNER JOIN ka on (kaa.kaa1 = ka.ka1)
            WHERE a5 IS NULL AND a1>0 {$filial} AND a1 NOT IN
            (
                SELECT  a1
                FROM a
                INNER JOIN r on (a.a1 = r.r5)
                INNER JOIN rz on (r.r4 = rz.rz1)
                WHERE r2=:R2 and  rz6=:RZ6 {$filial}
                GROUP BY a1
            )
            ORDER BY A8,A9,A2
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':R2', $r2);
        $command->bindValue(':RZ6', $r3);

        $classrooms = $command->queryAll();

        return $classrooms;
    }
}
