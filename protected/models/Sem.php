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
		);
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

    /**
     * @param $gr1
     * @return array
     * @throws CException
     */
	public function getSemestrForGroup($gr1)
    {
		 if (empty($gr1))
            return array();
		$sql='select sem10,sem11 from sg
			   inner join sem on (sg.sg1 = sem.sem2)
			   inner join gr on (sg.sg1 = gr.gr2)
			where gr.gr1='.$gr1.' and sem.sem10<=\''.date('d.m.Y',strtotime("+15 day")).'\' and sem.sem11>=\''.date('d.m.Y',strtotime("+15 day")).'\'';
		$command = Yii::app()->db->createCommand($sql);

		$sem = $command->queryRow();
		
		$date1 = $sem['sem10'];
        $date2 = $sem['sem11'];

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

    ///TODO: Обьеденить с getSemestersForRating
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

    ///TODO: Обьеденить с getSemestersForWorkPlan
	public function getSemestersForRating($gr1, $type)
    {
        if ($type == RatingForm::SPECIALITY)
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

    /**
     * @param $sg1
     * @return array
     * @throws CException
     */
    public function getSemestersForStream($sg1)
    {
        $sql = <<<SQL
                SELECT sem3,sem4,sem5,sem7,sem1
                FROM sem
                WHERE sem2=:ID
                GROUP BY sem3,sem4,sem5,sem7,sem1
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':ID', $sg1);
        $semesters = $command->queryAll();

        foreach ($semesters as $key => $sem) {
            $semesters[$key]['name'] = $sem['sem3'].' ('.$sem['sem4'].' '.tt('курс').')';
        }

        return $semesters;
    }

    /**
     * @param $gr1
     * @return array
     * @throws CException
     */
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
