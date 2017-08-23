<?php

/**
 * This is the model class for table "sp".
 *
 * The followings are the available columns in table 'sp':
 * @property integer $sp1
 * @property string $sp2
 * @property string $sp4
 * @property integer $sp5
 * @property string $sp7
 * @property integer $sp8
 * @property string $sp9
 * @property integer $sp11
 * @property string $sp12
 * @property integer $sp13
 * @property integer $sp14
 * @property integer $sp15
 * @property string $sp16
 * @property string $sp17
 */
class Sp extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sp';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sp1', 'required'),
			array('sp1, sp5, sp8, sp11, sp13, sp14, sp15', 'numerical', 'integerOnly'=>true),
			array('sp2, sp4', 'length', 'max'=>100),
			array('sp7, sp12', 'length', 'max'=>8),
			array('sp9', 'length', 'max'=>1000),
			array('sp16', 'length', 'max'=>180),
			array('sp17', 'length', 'max'=>400),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('sp1, sp2, sp4, sp5, sp7, sp8, sp9, sp11, sp12, sp13, sp14, sp15, sp16, sp17', 'safe', 'on'=>'search'),
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
			'sp1' => 'Sp1',
			'sp2' => 'Sp2',
			'sp4' => 'Sp4',
			'sp5' => 'Sp5',
			'sp7' => 'Sp7',
			'sp8' => 'Sp8',
			'sp9' => 'Sp9',
			'sp11' => 'Sp11',
			'sp12' => 'Sp12',
			'sp13' => 'Sp13',
			'sp14' => 'Sp14',
			'sp15' => 'Sp15',
			'sp16' => 'Sp16',
			'sp17' => 'Sp17',
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

		$criteria->compare('sp1',$this->sp1);
		$criteria->compare('sp2',$this->sp2,true);
		$criteria->compare('sp4',$this->sp4,true);
		$criteria->compare('sp5',$this->sp5);
		$criteria->compare('sp7',$this->sp7,true);
		$criteria->compare('sp8',$this->sp8);
		$criteria->compare('sp9',$this->sp9,true);
		$criteria->compare('sp11',$this->sp11);
		$criteria->compare('sp12',$this->sp12,true);
		$criteria->compare('sp13',$this->sp13);
		$criteria->compare('sp14',$this->sp14);
		$criteria->compare('sp15',$this->sp15);
		$criteria->compare('sp16',$this->sp16,true);
		$criteria->compare('sp17',$this->sp17,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Sp the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * статитика посещаемости по потокам (курсы по специальности)
     * @param $faculty
     * @param $speciality
     * @return array
     */
    public function getCoursesForSp($faculty, $speciality)
    {
        if (empty($faculty)||empty($speciality))
            return array();


        $extraCondition = ' and sp1='.$speciality;

        $sql=<<<SQL
            SELECT sem4
            FROM SP
            INNER JOIN SG ON (SP.SP1 = SG.SG2)
            INNER JOIN SEM ON (SG.SG1 = SEM.SEM2)
            WHERE sp5=:FACULTY and sem3=:YEAR and sem5=:SEM {$extraCondition}
            GROUP BY sem4
SQL;

        list($year, $sem) = SH::getCurrentYearAndSem();

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':FACULTY', $faculty);
        $command->bindValue(':YEAR', $year);
        $command->bindValue(':SEM', $sem);
        $courses = $command->queryAll();

        $res = array();
        foreach ($courses as $course) {
            $res[$course['sem4']] = $course['sem4'];
        }

        return $res;
    }

    public function getCoursesFor($faculty, $speciality = null)
    {
        if (empty($faculty))
            return array();

        $extraCondition = null;
        if (! empty($speciality))
            $extraCondition = ' and sp11='.$speciality;

        $sql=<<<SQL
            SELECT sem4
            FROM SP
            INNER JOIN SG ON (SP.SP1 = SG.SG2)
            INNER JOIN SEM ON (SG.SG1 = SEM.SEM2)
            WHERE sp5=:FACULTY and sem3=:YEAR and sem5=:SEM {$extraCondition}
            GROUP BY sem4
SQL;

        list($year, $sem) = SH::getCurrentYearAndSem();

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':FACULTY', $faculty);
        $command->bindValue(':YEAR', $year);
        $command->bindValue(':SEM', $sem);
        $courses = $command->queryAll();

        $res = array();
        foreach ($courses as $course) {
            $res[$course['sem4']] = $course['sem4'];
        }

        return $res;
    }

    public function getSpecialitiesForFaculty($faculty)
    {
        if (empty($faculty))
            return array();

        $sql=<<<SQL
            SELECT pnsp2,sp2,sp1
            FROM sp
            INNER JOIN pnsp on (sp.sp11 = pnsp.pnsp1)
            WHERE sp5=:FACULTY and sp7 is null and pnsp9 is null
            ORDER BY pnsp2
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':FACULTY', $faculty);
        $specialities = $command->queryAll();

        foreach ($specialities as $key => $speciality) {
            $specialities[$key]['name'] = $speciality['pnsp2'].' ('.$speciality['sp2'].')';
        }

        return $specialities;
    }

    public function getSpecializations($speciality)
    {
        if (empty($speciality))
            return array();

        $sql=<<<SQL
            SELECT SPC1,SPC4
            FROM spc
            INNER JOIN pnsp on (spc.spc11 = pnsp.pnsp1)
            WHERE spc11 = :SPC11 and spc1>0
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':SPC11', $speciality);
        $specializations = $command->queryAll();
        return $specializations;
    }
	
	public function getStreams($year,$kaf)
	{
	    if (empty($kaf))
            return array();
		$sql=<<<SQL
            SELECT sp8,sp2,sg4,sg3,sg1,sem4 FROM SP JOIN SG ON (SP1=SG2) JOIN SEM ON (SG1=SEM2) JOIN u ON (u2=sg1) JOIN uo on uo22=u1 WHERE sem3=:year and uo4=:kaf and sp7 is null GROUP BY sp8,sp2,sg4,sg3,sg1,sem4
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':year', $year);
		$command->bindValue(':kaf', $kaf);
        $streams = $command->queryAll();
        return $streams;
	}

}
