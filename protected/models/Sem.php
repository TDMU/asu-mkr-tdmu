<?php

/**
 * This is the model class for table "sem".
 *
 * The followings are the available columns in table 'sem':
 * @property integer $sem1
 * @property integer $sem2
 * @property integer $sem3
 * @property integer $sem4
 * @property integer $sem5
 * @property integer $sem7
 * @property integer $sem9
 * @property string $sem10
 * @property string $sem11
 * @property string $sem12
 * @property string $sem13
 * @property string $sem14
 * @property string $sem15
 * @property string $sem17
 * @property string $sem18
 * @property integer $sem19
 * @property integer $sem20
 * @property integer $sem21
 * @property integer $sem22
 */
class Sem extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sem';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sem1, sem2, sem3, sem4, sem5, sem7, sem9, sem19, sem20, sem21, sem22', 'numerical', 'integerOnly'=>true),
			array('sem10, sem11, sem12, sem13, sem14, sem15, sem18', 'length', 'max'=>8),
			array('sem17', 'length', 'max'=>4),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('sem1, sem2, sem3, sem4, sem5, sem7, sem9, sem10, sem11, sem12, sem13, sem14, sem15, sem17, sem18, sem19, sem20, sem21, sem22', 'safe', 'on'=>'search'),
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
			'sem1' => 'Sem1',
			'sem2' => 'Sem2',
			'sem3' => 'Sem3',
			'sem4' => 'Sem4',
			'sem5' => 'Sem5',
			'sem7' => 'Sem7',
			'sem9' => 'Sem9',
			'sem10' => 'Sem10',
			'sem11' => 'Sem11',
			'sem12' => 'Sem12',
			'sem13' => 'Sem13',
			'sem14' => 'Sem14',
			'sem15' => 'Sem15',
			'sem17' => 'Sem17',
			'sem18' => 'Sem18',
			'sem19' => 'Sem19',
			'sem20' => 'Sem20',
			'sem21' => 'Sem21',
			'sem22' => 'Sem22',
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

		$criteria->compare('sem1',$this->sem1);
		$criteria->compare('sem2',$this->sem2);
		$criteria->compare('sem3',$this->sem3);
		$criteria->compare('sem4',$this->sem4);
		$criteria->compare('sem5',$this->sem5);
		$criteria->compare('sem7',$this->sem7);
		$criteria->compare('sem9',$this->sem9);
		$criteria->compare('sem10',$this->sem10,true);
		$criteria->compare('sem11',$this->sem11,true);
		$criteria->compare('sem12',$this->sem12,true);
		$criteria->compare('sem13',$this->sem13,true);
		$criteria->compare('sem14',$this->sem14,true);
		$criteria->compare('sem15',$this->sem15,true);
		$criteria->compare('sem17',$this->sem17,true);
		$criteria->compare('sem18',$this->sem18,true);
		$criteria->compare('sem19',$this->sem19);
		$criteria->compare('sem20',$this->sem20);
		$criteria->compare('sem21',$this->sem21);
		$criteria->compare('sem22',$this->sem22);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Sem the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getSemestrForGroup($gr1)
    {
		 if (empty($gr1))
            return array();
		$sql='select sem10,sem11 from sg
			   inner join sem on (sg.sg1 = sem.sem2)
			   inner join gr on (sg.sg1 = gr.gr2)
			where gr.gr1='.$gr1.' and sem.sem10<=\''.date('d.m.Y',strtotime("+15 day")).'\' and sem.sem11>=\''.date('d.m.Y',strtotime("+15 day")).'\'';
		$command = Yii::app()->db->createCommand($sql);
		//print_r($sql);
		/*$command->bindValue(':gr1', $gr1);
		$command->bindValue(':nac', date('d.m.Y'));*/
		$sem = $command->queryRow();
		
		$date1 = $sem['sem10'];
        $date2 = $sem['sem11'];

        /*if (empty($date1) || empty($date2))
            Yii::app()->user->setFlash('error', tt('Неуказаны дата начала и/или дата окончания семестра'));*/

        return array($date1, $date2);
	}

    public function getSemestrForGroupByYearAndSem($gr1,$year,$sem)
    {
        if (empty($gr1))
            return 0;

        $sql='select sem1 from sg
			   inner join sem on (sg.sg1 = sem.sem2)
			   inner join gr on (sg.sg1 = gr.gr2)
			where gr.gr1='.$gr1.' and sem.sem3='.$year.' and sem.sem5='.$sem;
        $command = Yii::app()->db->createCommand($sql);

        $sem1 = $command->queryScalar();

        return !empty($sem1)?$sem1:0;
    }
	
    public function getYearsForThematicPlan($speciality)
    {
        if (empty($speciality))
            return array(array(), array());

        $sql=<<<SQL
            SELECT sg3,sg4,sg1,sem4
            FROM sem
            INNER JOIN sg on (sem.sem2 = sg.sg1)
            WHERE sg2=:SPECIALITY and (sem3=:YEAR1 or sem3=:YEAR2)
            GROUP BY sg3,sg4,sg1,sem4
            ORDER BY sg4,sg3 DESC,sem4 DESC
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':SPECIALITY', $speciality);
        $command->bindValue(':YEAR1', date('Y', strtotime('previous year')));
        $command->bindValue(':YEAR2', date('Y'));
        $years = $command->queryAll();

        foreach ($years as $key => $year) {
            $years[$key]['name'] = $year['sg3'].' ('.SH::convertEducationType($year['sg4']).') '.$year['sem4'].' '.tt('курс');
        }

        $dataAttrs = array();
        foreach ($years as $year) {
            $dataAttrs[$year['sg1']] = array('data-sem4' => $year['sem4']);
        }

        return array($years, $dataAttrs);
    }

    public function getSemestersForThematicPlan1($uo1)
    {
        if (empty($uo1))
            return array();

        $sql=<<<SQL
           SELECT us4,sem7,sem3,sem5,sem1,us1
           FROM sem
           INNER JOIN us on (sem.sem1 = us.us3)
           INNER JOIN uo on (us.us2 = uo.uo1)
           WHERE us2=:UO1 and us4 in (1,2,3,4)
           GROUP BY us4,sem7,sem3,sem5,sem1,us1
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':UO1', $uo1);
        $semesters = $command->queryAll();

        foreach ($semesters as $key => $sem) {
            $semesters[$key]['name'] = SH::convertUS4($sem['us4']).', '.$sem['sem7'].' '.tt('сем').'. ('.$sem['sem3'].', '.SH::convertSem5($sem['sem5']).')';
        }

        return $semesters;
    }

    public function getSemestersForWorkPlan($gr1, $type)
    {
        if ($type == WorkPlanController::SPECIALITY)
            $sql = <<<SQL
                SELECT sem3,sem4,sem5,sem7,us3
                FROM us
                INNER JOIN sem on (us.us3 = sem.sem1)
                INNER JOIN sg on (sem.sem2 = sg.sg1)
                INNER JOIN gr on (sg.sg1 = gr.gr2)
                WHERE sg1=:ID
                GROUP BY sem3,sem4,sem5,sem7,us3
SQL;
        else
            $sql = <<<SQL
                SELECT sem3,sem4,sem5,sem7,us3
                FROM us
                INNER JOIN sem on (us.us3 = sem.sem1)
                INNER JOIN sg on (sem.sem2 = sg.sg1)
                INNER JOIN gr on (sg.sg1 = gr.gr2)
                WHERE gr1=:ID
                GROUP BY sem3,sem4,sem5,sem7,us3
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':ID', $gr1);
        $semesters = $command->queryAll();

        foreach ($semesters as $key => $sem) {
            $semesters[$key]['name'] = $sem['sem3'].' ('.$sem['sem4'].' '.tt('курс').')';
        }

        return $semesters;
    }
	
	public function getSemestersForThematicPlan($sg1)
    {
            $sql = <<<SQL
                SELECT sem3,sem4,sem5,sem7,us3
                FROM us
                INNER JOIN sem on (us.us3 = sem.sem1)
                INNER JOIN sg on (sem.sem2 = sg.sg1)
                INNER JOIN gr on (sg.sg1 = gr.gr2)
                WHERE sg1=:ID
                GROUP BY sem3,sem4,sem5,sem7,us3
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':ID', $sg1);
        $semesters = $command->queryAll();

        foreach ($semesters as $key => $sem) {
            $semesters[$key]['name'] = $sem['sem3'].' ('.$sem['sem4'].' '.tt('курс').')';
        }

        return $semesters;
    }
	
	public function getSemestersForRating($gr1, $type)
    {
        if ($type == ProgressController::SPECIALITY)
            $sql = <<<SQL
                SELECT sem3,sem4,sem5,sem7,us3
                FROM us
                INNER JOIN sem on (us.us3 = sem.sem1)
                INNER JOIN sg on (sem.sem2 = sg.sg1)
                INNER JOIN gr on (sg.sg1 = gr.gr2)
                WHERE sg1=:ID
                GROUP BY sem3,sem4,sem5,sem7,us3
SQL;
        else
            $sql = <<<SQL
                SELECT sem3,sem4,sem5,sem7,us3
                FROM us
                INNER JOIN sem on (us.us3 = sem.sem1)
                INNER JOIN sg on (sem.sem2 = sg.sg1)
                INNER JOIN gr on (sg.sg1 = gr.gr2)
                WHERE gr1=:ID
                GROUP BY sem3,sem4,sem5,sem7,us3
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':ID', $gr1);
        $semesters = $command->queryAll();

        foreach ($semesters as $key => $sem) {
            //$semesters[$key]['name'] = tt('Семестр').' №'.$sem['sem7'].' ('.$sem['sem3'].' '.$sem['sem4'].' '.tt('курс').')';
            $semesters[$key]['name'] = $sem['sem3'].' ('.$sem['sem4'].' '.tt('курс').')';
        }

        return $semesters;
    }

    public function getSemestersForAttendanceStatistic($gr1)
    {
        $sql = <<<SQL
                SELECT sem3,sem4,sem5,sem7,us3
                FROM us
                INNER JOIN sem on (us.us3 = sem.sem1)
                INNER JOIN sg on (sem.sem2 = sg.sg1)
                INNER JOIN gr on (sg.sg1 = gr.gr2)
                WHERE gr1=:ID
                GROUP BY sem3,sem4,sem5,sem7,us3
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':ID', $gr1);
        $semesters = $command->queryAll();

        foreach ($semesters as $key => $sem) {
            $semesters[$key]['name'] = $sem['sem3'].' ('.$sem['sem4'].' '.tt('курс').')';
        }

        return $semesters;
    }

    public function getSemesterStartAndEnd($sem1)
    {
        $sql = <<<SQL
                SELECT sem10,sem11
                FROM sem
                WHERE sem1=:SEM1
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':SEM1', $sem1);
        $dates = $command->queryRow();

        $date1 = $dates['sem10'];
        $date2 = $dates['sem11'];

        if (empty($date1) || empty($date2))
            Yii::app()->user->setFlash('error', tt('Неуказаны дата начала и/или дата окончания семестра'));

        return array($date1, $date2);
    }

    public function getMonthsNamesForAttendanceStatistic($sem1)
    {
        $months = array();

        list($start, $end) = Sem::model()->getSemesterStartAndEnd($sem1);

        $start = strtotime($start);
        $end   = strtotime($end);

        while($start <= $end) {
            $months[] = array(
                'firstDay' => date('Y-m-d', $start),
                'name'     => SH::russianMonthName(date('m', $start))
            );
            $start = strtotime('first day of next month', $start);
        }

        return $months;
    }

    public function getSemestersForSt($st1)
    {
        $sql = <<<SQL
            SELECT sem7
				from gr
				   inner join ucsn on (gr.gr1 = ucsn.ucsn3)
				   inner join sg on (gr.gr2 = sg.sg1)
				   inner join sem on (sg.sg1 = sem.sem2)
				WHERE ucsn2 = :ST1
				GROUP BY sem7
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':ST1', $st1);
        $semesters = $command->queryColumn();

        return $semesters;
    }
	
	public function getModule($uo1)
	{
		if (empty($uo1))
            return array();
		$sql = <<<SQL
		SELECT sem7,sem3,sem5,vvmp6,sem1,vvmp1,vvmp8,vvmp9,vvmp10,vvmp24
                    FROM sem
                      JOIN us ON (sem1=us3)
                      JOIN uo ON (us2=uo1)
                      JOIN u ON (uo22=u1)
                      JOIN nr ON (us1=nr2)
                      JOIN ug ON (nr1=ug1)
                      JOIN gr ON (ug2=gr1)
                      JOIN vvmp ON (vvmp2=uo1 AND vvmp4=sem7)
                    WHERE uo1=:uo1 AND us4 in (1,2,3,4) AND sem3=:YEAR AND sem5=:SEM
                    GROUP BY sem7,sem3,sem5,vvmp6,sem1,vvmp1,vvmp8,vvmp9,vvmp10,vvmp24
	
SQL;
		$command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':uo1', $uo1);
		$command->bindValue(':YEAR', Yii::app()->session['year']);
        $command->bindValue(':SEM', Yii::app()->session['sem']);
        $modules = $command->queryAll();

        return $modules;
		//return array();
	}

    public function getSem1ByGr1($gr1){
        if(empty($gr1))
            return 0;
        $sql = <<<SQL
          select sem1
            from us
               inner join sem on (us.us3 = sem.sem1)
               inner join nr on (us.us1 = nr.nr2)
               inner join ug on (nr.nr1 = ug.ug1)
            where ug2=:GR1 and sem3=:YEAR and sem5=:SEM
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':GR1', $gr1);
        $command->bindValue(':YEAR', Yii::app()->session['year']);
        $command->bindValue(':SEM', Yii::app()->session['sem']);
        $sem1 = $command->queryScalar();

        return $sem1;
    }

    public function getSem1ByUo1($uo1){
        if(empty($uo1))
            return 0;
        $sql = <<<SQL
          select sem1
            from us
               inner join sem on (us.us3 = sem.sem1)
            where us2=:UO1 and sem3=:YEAR and sem5=:SEM
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':UO1', $uo1);
        $command->bindValue(':YEAR', Yii::app()->session['year']);
        $command->bindValue(':SEM', Yii::app()->session['sem']);
        $sem1 = $command->queryScalar();

        return $sem1;
    }

    public function getSem1List($uo1){
        if(empty($uo1))
            return array();

        $sql = <<<SQL
          select sem1,sem7
            from us
               inner join sem on (us.us3 = sem.sem1)
            where us2=:UO1 and sem3=:YEAR and sem5=:SEM
          GROUP BY sem1,sem7
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':UO1', $uo1);
        $command->bindValue(':YEAR', Yii::app()->session['year']);
        $command->bindValue(':SEM', Yii::app()->session['sem']);
        $sem1 = $command->queryAll();

        $res = array();

        foreach($sem1 as $key => $val){
            $res[$key]['name']= $val['sem7'];
            $res[$key]['sem1']= $val['sem1'];
        }
        return $res;
    }
}
