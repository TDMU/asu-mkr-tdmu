<?php

/**
 * This is the model class for table "gr".
 *
 * The followings are the available columns in table 'gr':
 * @property integer $gr1
 * @property integer $gr2
 * @property string $gr3
 * @property string $gr4
 * @property string $gr6
 * @property integer $gr7
 * @property integer $gr8
 * @property string $gr13
 * @property integer $gr14
 * @property integer $gr15
 * @property string $gr16
 * @property string $gr17
 * @property string $gr19
 * @property string $gr20
 * @property string $gr21
 * @property string $gr22
 * @property string $gr23
 * @property string $gr24
 * @property string $gr25
 * @property integer $gr26
 * @property string $gr27
 * @property string $gr28
 * @property integer $gr10
 */
class Gr extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'gr';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('gr1, gr2, gr7, gr8, gr14, gr15, gr26, gr10', 'numerical', 'integerOnly'=>true),
			array('gr3, gr19, gr20, gr21, gr22, gr23, gr24, gr28', 'length', 'max'=>180),
			array('gr4', 'length', 'max'=>100),
			array('gr6, gr25', 'length', 'max'=>8),
			array('gr13, gr16', 'length', 'max'=>4),
			array('gr17', 'length', 'max'=>28),
			array('gr27', 'length', 'max'=>140),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('gr1, gr2, gr3, gr4, gr6, gr7, gr8, gr13, gr14, gr15, gr16, gr17, gr19, gr20, gr21, gr22, gr23, gr24, gr25, gr26, gr27, gr28, gr10', 'safe', 'on'=>'search'),
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
			'gr1' => 'Gr1',
			'gr2' => 'Gr2',
			'gr3' => 'Gr3',
			'gr4' => 'Gr4',
			'gr6' => 'Gr6',
			'gr7' => 'Gr7',
			'gr8' => 'Gr8',
			'gr13' => 'Gr13',
			'gr14' => 'Gr14',
			'gr15' => 'Gr15',
			'gr16' => 'Gr16',
			'gr17' => 'Gr17',
			'gr19' => 'Gr19',
			'gr20' => 'Gr20',
			'gr21' => 'Gr21',
			'gr22' => 'Gr22',
			'gr23' => 'Gr23',
			'gr24' => 'Gr24',
			'gr25' => 'Gr25',
			'gr26' => 'Gr26',
			'gr27' => 'Gr27',
			'gr28' => 'Gr28',
			'gr10' => 'Gr10',
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

		$criteria->compare('gr1',$this->gr1);
		$criteria->compare('gr2',$this->gr2);
		$criteria->compare('gr3',$this->gr3,true);
		$criteria->compare('gr4',$this->gr4,true);
		$criteria->compare('gr6',$this->gr6,true);
		$criteria->compare('gr7',$this->gr7);
		$criteria->compare('gr8',$this->gr8);
		$criteria->compare('gr13',$this->gr13,true);
		$criteria->compare('gr14',$this->gr14);
		$criteria->compare('gr15',$this->gr15);
		$criteria->compare('gr16',$this->gr16,true);
		$criteria->compare('gr17',$this->gr17,true);
		$criteria->compare('gr19',$this->gr19,true);
		$criteria->compare('gr20',$this->gr20,true);
		$criteria->compare('gr21',$this->gr21,true);
		$criteria->compare('gr22',$this->gr22,true);
		$criteria->compare('gr23',$this->gr23,true);
		$criteria->compare('gr24',$this->gr24,true);
		$criteria->compare('gr25',$this->gr25,true);
		$criteria->compare('gr26',$this->gr26);
		$criteria->compare('gr27',$this->gr27,true);
		$criteria->compare('gr28',$this->gr28,true);
		$criteria->compare('gr10',$this->gr10);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Gr the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getStatements($gr1,$vvmp1)
	{
		if (empty($gr1)||empty($vvmp1))
            return array();
		$sql=<<<SQL
          SELECT vmpv3,vmpv4,vmpv6,vmpv1 FROM vmpv WHERE vmpv2=:vvmp1 AND vmpv7=:gr1 ORDER BY vmpv8, vmpv4 DESC
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':vvmp1', $vvmp1);
        $command->bindValue(':gr1', $gr1);
        $statements = $command->queryAll();
		return $statements;
	}
	
	public function getStatement($gr1,$vmpv1)
	{
		if (empty($gr1)||empty($vmpv1))
            return array();
		$sql=<<<SQL
          select st2,st3,st4,st1,st5,vmp4
                   from ucgn
                   inner join ucgns on (ucgn.ucgn1 = ucgns.ucgns2)
                   inner join ucsn on (ucgns.ucgns1 = ucsn.ucsn1)
                   inner join st on (ucsn.ucsn2 = st.st1)
                   inner join std on (st1=std2)
                   inner join vmp ON (st1=vmp2 AND vmp1=:vmpv1)
                   where st1>0 AND std11 in (0,5,6,8) and std7 is null AND ucgn2=:gr1 AND ucgns6=:SEM 
                   GROUP BY st2,st3,st4,st1,st5,vmp4
                   ORDER BY st2 collate UNICODE
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':vmpv1', $vmpv1);
        $command->bindValue(':gr1', $gr1);
		$command->bindValue(':SEM', Yii::app()->session['sem']);
        $statements = $command->queryAll();
		return $statements;
	}
	
	public function getGroupsByModule($uo1,$vvmp1)
	{
		if (empty($uo1)||empty($vvmp1))
            return array();
		$sql=<<<SQL
          select gr7,gr3,gr1,sem4,gr19,gr20,gr21,gr22,gr23,gr24,gr28
                   from us
                   inner join nr on (us.us1 = nr.nr2)
                   inner join ug on (nr.nr1 = ug.ug3)
                   inner join gr on (ug.ug2 = gr.gr1)
                   inner join sem on (us.us3 = sem.sem1)
                   JOIN uo ON (us2=uo1)
                   JOIN vvmp ON (vvmp2=uo1 AND vvmp4=sem7)
                   where us2=:uo1 and vvmp1=:vvmp1
                   group by gr7,gr3,gr1,sem4,gr19,gr20,gr21,gr22,gr23,gr24,gr28
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':vvmp1', $vvmp1);
        $command->bindValue(':uo1', $uo1);
        $groups = $command->queryAll();
        foreach($groups as $key => $group) {
            $groups[$key]['name'] = $this->getGroupName($group['sem4'], $group);
        }
		return $groups;
	}
    
    public function getGroupsForJournal($discipline)
    {
        if (empty($discipline))
            return array();
            $sql = <<<SQL
                    
                select us4,ug3,us1,  sem4,gr3,gr7,gr1,gr19,gr20,gr21,gr22,gr23,gr24,gr25,gr26,gr28
                from sem
                  inner join us on (sem1 = us3)
                  inner join uo on (us2 = uo1)
                  inner join u on (uo22 = u1)
                  inner join sg on (u2 = sg1)
                  inner join (
                     select nr1,nr2
                     from pd
                       inner join nr on (pd1 = nr6) or (pd1 = nr7) or (pd1 = nr8) or (pd1 = nr9)
                       inner join us on (nr2 = us1)
                     where pd1>0 and pd2=:P1
                     group by nr1,nr2) on (us1 = nr2)
                  inner join ug on (ug3 = nr1)
                  inner join r on (nr1 = r1)
                  inner join gr on (ug2 = gr1)
                where sg4=0 and sem3=:YEAR and sem5=:SEM and uo3=:D1 and us4>=1 and us4<=4
                group by us4,ug3,us1,  sem4,gr3,gr7,gr1,gr19,gr20,gr21,gr22,gr23,gr24,gr25,gr26,gr28
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':P1', Yii::app()->user->dbModel->p1);
        $command->bindValue(':D1', $discipline);
        $command->bindValue(':YEAR', Yii::app()->session['year']);
        $command->bindValue(':SEM', Yii::app()->session['sem']);
        $groups = $command->queryAll();
        $type=array(
			1=> tt('Лк'),
			2=> tt('Пз'),
			3=> tt('Сем'),
			4=> tt('Лб')
		);
        foreach($groups as $key => $group) {
            $groups[$key]['name'] = $type[$group['us4']].', '.$this->getGroupName($group['sem4'], $group);
            $groups[$key]['group'] = $group['us1'].'/'.$group['gr1'];
        }

        return $groups;
    }

    /**
     * Список дисциплин для старосты для кориктеровки (с вирутальными группами)
     * @param $st1 int код старросты
     * @return array
     */
    public function getGroupsForJournalPermitionSst($st1)
    {
        if (empty($st1))
            return array();
        $sql = <<<SQL
             select st56, gr13,gr1, gr3, gr7,gr19,gr20,gr21,gr22,gr23,gr24,gr25,gr26,gr28
                from std
                   inner join sst on (std.std3 = sst.sst3)
                   inner join st on (std.std2 = st.st1)
                   inner join ucsn on (st.st1 = ucsn.ucsn2)
                   inner join ucgns on (ucsn.ucsn1 = ucgns.ucgns1)
                   inner join ucgn on (ucgns.ucgns2 = ucgn.ucgn1)
                   inner join gr on (ucgn.ucgn2 = gr.gr1)
                where std11 in (0,5,6,8) and std7 is null and sst2=:ST1 and sst6 is null and ucgns5=:YEAR and ucgns6=:SEM
                group by st56, gr13, gr1, gr3, gr7,gr19,gr20,gr21,gr22,gr23,gr24,gr25,gr26,gr28
                ORDER by gr13, gr7 DESC, gr3 ASC;
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':ST1', $st1);
        $command->bindValue(':YEAR', Yii::app()->session['year']);
        $command->bindValue(':SEM', Yii::app()->session['sem']);
        $groups = $command->queryAll();

        foreach($groups as $key => $group) {
            $groups[$key]['name'] = $this->getGroupName($group['st56'], $group);
            $groups[$key]['group'] = $group['gr1'];
        }

        return $groups;
    }

    public function getGroupsForJournalPermition($discipline,$type_lesson)
    {
        if (empty($discipline))
            return array();
        $sql = <<<SQL
             SELECT * FROM  EL_GURNAL(:P1,:YEAR,:SEM,:D1,1,0,0,0,:TYPE_LESSON) ORDER by gr7 DESC, gr3 ASC;
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':P1', Yii::app()->user->dbModel->p1);
        $command->bindValue(':D1', $discipline);
        $command->bindValue(':TYPE_LESSON', $type_lesson);
        $command->bindValue(':YEAR', Yii::app()->session['year']);
        $command->bindValue(':SEM', Yii::app()->session['sem']);
        $groups = $command->queryAll();

        foreach($groups as $key => $group) {
            $groups[$key]['name'] = $this->getGroupName($group['sem4'], $group);
            $groups[$key]['group'] = $group['kod_uo1'].'/'.$group['gr1'];
        }

        return $groups;
    }

    public function getGroupsForModulesPermition($discipline)
    {
        if (empty($discipline))
            return array();
        $sql = <<<SQL
             SELECT * FROM  EL_GURNAL(:P1,:YEAR,:SEM,:D1,1,0,0,5,2) ORDER by gr7 DESC, gr3 ASC;
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':P1', Yii::app()->user->dbModel->p1);
        $command->bindValue(':D1', $discipline);
        $command->bindValue(':YEAR', Yii::app()->session['year']);
        $command->bindValue(':SEM', Yii::app()->session['sem']);
        $groups = $command->queryAll();

        foreach($groups as $key => $group) {
            $groups[$key]['name'] = $this->getGroupName($group['sem4'], $group);
            $groups[$key]['group'] = $group['kod_uo1'].'/'.$group['gr1'];
        }

        return $groups;
    }

    public function getGroupsForTPlanPermition($discipline)
    {
        if (empty($discipline))
            return array();
        $sql = <<<SQL
              SELECT * FROM  EL_GURNAL(:P1,:YEAR,:SEM,:D1,3,0,0,1,0)
              /*INNER JOIN us on (EL_GURNAL.us1 = us.us1)
              INNER JOIN sem on (us3 = sem1)*/
              ORDER by sem4 ASC,us1,us4 ASC;
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':P1', Yii::app()->user->dbModel->p1);
        $command->bindValue(':D1', $discipline);
        //$command->bindValue(':TYPE_LESSON', $type_lesson);
        $command->bindValue(':YEAR', Yii::app()->session['year']);
        $command->bindValue(':SEM', Yii::app()->session['sem']);
        $res = $command->queryAll();
        $pattern='(%s) %s %s '.tt('ч.');
        foreach($res as $key => $val) {
            $res[$key]['name'] = sprintf($pattern,SH::convertUS4($val['us4']),$val['spec'],$val['chasi']);
            $res[$key]['group'] = $val['us1'].'/'.$val['chasi'];
            $groups = Us::model()->getGroups($val['us1']);
            if(!empty($groups))
            {
                $res[$key]['name'].=' ( ';
                $group = reset($groups);
                $res[$key]['name'].=Gr::model()->getGroupName($group['sem4'], $group);
                $res[$key]['name'].=', ..., ';
                $group = end($groups);
                $res[$key]['name'].=Gr::model()->getGroupName($group['sem4'], $group);
                $res[$key]['name'].=')';
            }
        }

        return $res;
    }

    public function getPotokByThematicExcel($discipline,$us1)
    {
        if (empty($discipline))
            return array();
        $sql = <<<SQL
              SELECT * FROM  EL_GURNAL(:P1,:YEAR,:SEM,:D1,3,0,0,1,0)
              WHERE us1=:US1
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':P1', Yii::app()->user->dbModel->p1);
        $command->bindValue(':D1', $discipline);
        $command->bindValue(':US1', $us1);
        $command->bindValue(':YEAR', Yii::app()->session['year']);
        $command->bindValue(':SEM', Yii::app()->session['sem']);
        $res = $command->queryRow();
        $pattern='(%s) %s %s '.tt('ч.');
        $res['name'] = sprintf($pattern,SH::convertUS4($res['us4']),$res['spec'],$res['chasi']);

        return $res;
    }
    
    public function getGroupsFor($discipline, $type = null)
    {
        if (empty($discipline))
            return array();

        $today = date('Y-m-d 00:00');

        if ($type == 0)
            $sql = <<<SQL
                select gr1,gr3,  sem4,gr19,gr20,gr21,gr22,gr23,gr24,gr25,gr26,gr28,gr7
                from sem
                   inner join us on (sem1 = us12)
                   inner join uo on (us2 = uo1)
                   inner join u on (uo22 = u1)
                   inner join sg on (u2 = sg1)
                   inner join
                              (select nr1,nr2
                              from pd
                                 inner join nr on (pd1 = nr6) or (pd1 = nr7) or (pd1 = nr8) or (pd1 = nr9)
                                 inner join us on (nr2 = us1)
                              where pd1>0 and pd2=:P1
                              group by nr1,nr2)
                    on (us1 = nr2)
                   inner join ug on (ug3 = nr1)
                   inner join r on (nr1 = r1)
                   inner join gr on (ug2 = gr1)
                where sg4=0 and sem3=:YEAR and sem5=:SEM and uo3=:D1 and us4 in (2,3,4)
                group by sem4,gr3,gr7,gr1,gr19,gr20,gr21,gr22,gr23,gr24,gr25,gr26,gr28
SQL;
        elseif ($type == 1)
            $sql = <<<SQL
                select gr1,gr3,sem4,gr19,gr20,gr21,gr22,gr23,gr24,gr25,gr26,gr28,gr7
                from sem
                   inner join us on (sem1 = us12)
                   inner join uo on (us2 = uo1)
                   inner join u on (uo22 = u1)
                   inner join sg on (u2 = sg1)
                   inner join nr on (us1 = nr2)
                   inner join ug on (ug3 = nr1)
                   inner join r on (nr1 = r1)
                   inner join gr on (ug2 = gr1)
                where sg4=0 and sem3=:YEAR and sem5=:SEM and uo3=:D1 and us4 in (2,3,4) and uo4 in (
                      select pd4
                      from pd
                      WHERE  (PD2 = :P1) and (PD28 in (0, 2, 5, 9)) and (PD13 IS NULL or PD13>'{$today}')
                      group by pd4
                     )
                group by sem4,gr3,gr7,gr1,gr19,gr20,gr21,gr22,gr23,gr24,gr25,gr26,gr28
                order by gr7,gr3 collate UNICODE
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':P1', Yii::app()->user->dbModel->p1);
        $command->bindValue(':D1', $discipline);
        $command->bindValue(':YEAR', Yii::app()->session['year']);
        $command->bindValue(':SEM', Yii::app()->session['sem']);
        $groups = $command->queryAll();

        foreach($groups as $key => $group) {
            $groups[$key]['name'] = $this->getGroupName($group['sem4'], $group);
        }

        return $groups;
    }

    public function getGroupName($cr, $data)
    {
        switch($cr) {
            case 1:
                $name = !empty($data['gr19']) ? $data['gr19'] : $data['gr3']; break;
            case 2:
                $name = !empty($data['gr20']) ? $data['gr20'] : $data['gr3']; break;
            case 3:
                $name = !empty($data['gr21']) ? $data['gr21'] : $data['gr3']; break;
            case 4:
                $name = !empty($data['gr22']) ? $data['gr22'] : $data['gr3']; break;
            case 5:
                $name = !empty($data['gr23']) ? $data['gr23'] : $data['gr3']; break;
            case 6:
                $name = !empty($data['gr24']) ? $data['gr24'] : $data['gr3']; break;
            case 7:
                $name = !empty($data['gr28']) ? $data['gr28'] : $data['gr3']; break;
            default:
                $name = $data['gr3'];
        }

        return $name;
    }

    public function getGroupsForTimeTable($faculty, $course)
    {
        if (empty($faculty) || empty($course))
            return array();

        $sql=<<<SQL
           SELECT sg4, sem4, gr7,gr3,gr1, gr19,gr20,gr21,gr22,gr23,gr24,gr25,gr26
			from sp
			   inner join sg on (sp.sp1 = sg.sg2)
			   inner join sem on (sg.sg1 = sem.sem2)
			   inner join gr on (sg.sg1 = gr.gr2)
			   inner join ucgn on (gr.gr1 = ucgn.ucgn2)
			   inner join ucgns on (ucgn.ucgn1 = ucgns.ucgns2)
			   inner join ucxg on (ucgn.ucgn1 = ucxg.ucxg2)
			WHERE ucxg1<30000 and gr13=0 and gr6 is null
				 and sp5=:FACULTY and sem3=:YEAR1 and sem5=:SEM1 and ucgns5=:YEAR2 and ucgns6=:SEM2 and sem4=:COURSE
			GROUP BY sg4, sem4, gr7,gr3,gr1, gr19,gr20,gr21,gr22,gr23,gr24,gr25,gr26
			ORDER BY gr7,gr3
SQL;

        list($year, $sem) = SH::getCurrentYearAndSem();

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':FACULTY', $faculty);
        $command->bindValue(':COURSE', $course);
        $command->bindValue(':YEAR1', $year);
        $command->bindValue(':YEAR2', $year);
        $command->bindValue(':SEM1', $sem);
        $command->bindValue(':SEM2', $sem);
        $groups = $command->queryAll();

        foreach($groups as $key => $group) {
            $groups[$key]['name'] = $this->getGroupName($course, $group);
        }

        return $groups;
    }

    /**
     * список групп по куратору (оплата за обучение)
     * @param $p1
     * @return mixed
     */
    public function getGroupsForCurator($p1)
    {
        $sql=<<<SQL
           SELECT sg4, sem4, gr7,gr3,gr1, gr19,gr20,gr21,gr22,gr23,gr24,gr25,gr26
			from sp
			   inner join sg on (sp.sp1 = sg.sg2)
			   inner join sem on (sg.sg1 = sem.sem2)
			   inner join gr on (sg.sg1 = gr.gr2)
			   inner join kgrp on (gr.gr1 = kgrp.kgrp2)
			   inner join ucgn on (gr.gr1 = ucgn.ucgn2)
			   inner join ucgns on (ucgn.ucgn1 = ucgns.ucgns2)
			   inner join ucxg on (ucgn.ucgn1 = ucxg.ucxg2)
			WHERE ucxg1<30000 and gr13=0 and gr6 is null
				 and kgrp1=:P1 and sem3=:YEAR1 and sem5=:SEM1 and ucgns5=:YEAR2 and ucgns6=:SEM2
			GROUP BY sg4, sem4, gr7,gr3,gr1, gr19,gr20,gr21,gr22,gr23,gr24,gr25,gr26
			ORDER BY gr7,gr3
SQL;

        list($year, $sem) = SH::getCurrentYearAndSem();

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':P1', $p1);
        $command->bindValue(':YEAR1', $year);
        $command->bindValue(':YEAR2', $year);
        $command->bindValue(':SEM1', $sem);
        $command->bindValue(':SEM2', $sem);
        $groups = $command->queryAll();

        foreach($groups as $key => $group) {
            $groups[$key]['name'] = $this->getGroupName($group['sem4'], $group);
        }

        return $groups;
    }

    public function getVirtualGroupByDiscipline($group)
    {
        $sql=<<<SQL
           select gr3,gr1
            from sem
               inner join us on (sem.sem1 = us.us3)
               inner join uo on (us.us2 = uo.uo1)
               inner join ucx on (uo.uo19 = ucx.ucx1)
               inner join u on (uo.uo22 = u.u1)
               inner join sg on (u.u2 = sg.sg1)
               inner join sp on (sg.sg2 = sp.sp1)
               inner join nr on (us.us1 = nr.nr2)
               inner join ug on (nr.nr1 = ug.ug1)
               inner join gr on (ug.ug2 = gr.gr1)
            where ucx5=3 and gr1=:GR1
            group by gr3,gr1
            order by gr3 collate UNICODE
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':GR1', $group);
        $group = $command->queryRow();

        return $group;
    }
    /*
     * Список фиртуальных груп по дисциплине
     */
    public function getVirtualGroupsByDisciplines($faculty, $course, $discipline)
    {
        if(empty($faculty)||empty($course)||empty($discipline))
            return array();

        $sql=<<<SQL
           select gr3,gr1
            from sem
               inner join us on (sem.sem1 = us.us3)
               inner join uo on (us.us2 = uo.uo1)
               inner join ucx on (uo.uo19 = ucx.ucx1)
               inner join u on (uo.uo22 = u.u1)
               inner join sg on (u.u2 = sg.sg1)
               inner join sp on (sg.sg2 = sp.sp1)
               inner join nr on (us.us1 = nr.nr2)
               inner join ug on (nr.nr1 = ug.ug1)
               inner join gr on (ug.ug2 = gr.gr1)
            where ucx5=3 and sem3=:YEAR and sem5=:SEM and sp5=:F1 and sem4=:COURSE and uo3=:D1
            group by gr3,gr1
            order by gr3 collate UNICODE
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':F1', $faculty);
        $command->bindValue(':COURSE', $course);
        $command->bindValue(':D1', $discipline);
        $command->bindValue(':YEAR', Yii::app()->session['year']);
        $command->bindValue(':SEM', Yii::app()->session['sem']);
        $groups = $command->queryAll();

        return $groups;
    }

    public function getStarostaFromGr1($gr1)
    {
        $sql = <<<SQL
            select st1,st2,st3,st4
            from st
               inner join sst on (st1 = sst2)
            where sst3=:GR1
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':GR1', $gr1);
        $res = $command->queryRow();

        return $res;
    }

    public static function getTimeTable($id, $date1, $date2,$type)
    {
        $max=8;
        switch($type)
        {
                case 0:
                        $sql ='SELECT * FROM RAGR(:LANG, :ID, :DATE_1, :DATE_2) ORDER BY r2,r3';
                        break;
                case 1:
                        $sql ='SELECT * FROM RAST(:LANG, :ID, :DATE_1, :DATE_2) ORDER BY r2,r3';
                        break;
                case 2:
                        $sql ='SELECT * FROM RAPR(:ID, :DATE_1, :DATE_2) ORDER BY r2,r3';
                        break;
                case 3:
                        //$sql ="SELECT *,(DATEDIFF(DAY,r2, :DATE_1)*{$max}+r3) as colonka  FROM RAPR(:ID, :DATE_1, :DATE_2) ORDER BY colonka";
                        $sql ='SELECT * FROM RAPR(:ID, :DATE_1, :DATE_2) ORDER BY r2,r3';
                        break;
                case 4: /*расписание кафедры по группам*/
                        $sql ='SELECT * FROM RAGR(:LANG, :ID, :DATE_1, :DATE_2) ORDER BY r2,r3';
                        break;
        }
        $command = Yii::app()->db->createCommand($sql);
		if($type!=2)
			$command->bindValue(':LANG', 1);
        $command->bindValue(':ID', $id);
        $command->bindValue(':DATE_1', $date1);
        $command->bindValue(':DATE_2', $date2);
        $timeTable = $command->queryAll();

        if (empty($timeTable))
            return array();
        if($type==3||$type==4)
        {
            $datetime1 = new DateTime($date1);
            foreach($timeTable as $key => $val) {
                
                $datetime2 = new DateTime($val['r2']);
                $interval = $datetime1->diff($datetime2);
                $timeTable[$key]['colonka'] = $interval->days*8+$val['r3'];
            }
        }
        return $timeTable;
    }
	
	public static function getRating($sg1,$gr1, $sem1, $sem2, $inost,$tmp)
    {
		$sql ='SELECT fio,kyrs,gr.gr1,credniy_bal_5,credniy_bal_100,ne_sdano,name,otch,gr3,gr19,gr20,gr21,gr22,gr23,gr24,gr28 FROM STUD_REYTING(:SG1, :GR1, :SEM1,:SEM2, :INOST) JOIN gr On gr.gr1=STUD_REYTING.gr1 ORDER BY '.$tmp.' DESC';
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':SG1', $sg1);
        $command->bindValue(':GR1', $gr1);
        $command->bindValue(':SEM1', $sem1);
        $command->bindValue(':SEM2', $sem2);
		$command->bindValue(':INOST', $inost);
        $rating = $command->queryAll();
        if (empty($rating))
            return array();
            foreach($rating as $key => $group) {
                $rating[$key]['group_name'] = Gr::model()->getGroupName($rating[$key]['kyrs'], $group);
            }
        return $rating;
    }

    public function getGroupsForThematicPlan($ustem1, $course)
    {
        $sql = <<<SQL
            select nr1,nr6,nr18,gr3,p3,p4,p5,r2,r3,a2, gr19,gr20,gr21,gr22,gr23,gr24,gr28
            from a
               inner join r on (a.a1 = r.r5)
               right outer join nr on (r.r1 = nr.nr1)
               inner join pd on (nr.nr6 = pd.pd1)
               inner join p on (pd.pd2 = p.p1)
               inner join ug on (nr.nr1 = ug.ug3)
               inner join gr on (ug.ug2 = gr.gr1)
            where nr31=:USTEM1
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':USTEM1', $ustem1);
        $groups = $command->queryAll();

        foreach($groups as $key => $group) {
            $groups[$key]['name'] = $this->getGroupName($course, $group);
        }

        return $groups;
    }

    public function getGroupsForCopyThematicPlan($d1, $year,$sem)
    {
        $sql = <<<SQL
            select sp2,sg3,sg1,us1,us4
            from sem
               inner join us on (sem1 = us3)
               inner join uo on (us2 = uo1)
               inner join u on (uo22 = u1)
               inner join sg on (u2 = sg1)
               inner join sp on (sg2 = sp1)
               inner join ustem on (us1 = ustem2)
            where (sem3 = :uch_god) and (sem5 = :semestr) and (uo3 = :d1)
            group by sp2,sg3,sg1,us1,us4
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':uch_god', $year);
        $command->bindValue(':semestr', $sem);
        $command->bindValue(':d1', $d1);
        $groups = $command->queryAll();

        foreach($groups as $key => $group) {
            $groups[$key]['name'] = SH::convertUS4($group['us4']).' '.$group['sp2'].' '.$group['sg3'];
            $grs = Us::model()->getGroups($group['us1']);
            if(!empty($grs))
            {
                $groups[$key]['name'].=' ( ';
                $gr = reset($grs);
                $groups[$key]['name'].=Gr::model()->getGroupName($gr['sem4'], $gr);
                $groups[$key]['name'].=', ..., ';
                $gr = end($grs);
                $groups[$key]['name'].=Gr::model()->getGroupName($gr['sem4'], $gr);
                $groups[$key]['name'].=')';
            }
        }

        return $groups;
    }

    public function getGroupsBySg1ForWorkPlan($sg1, $year, $sem)
    {
        $sql= <<<SQL
				SELECT gr1,sem4, gr19,gr20,gr21,gr22,gr23,gr24,gr28
				 from ucgns
				   inner join ucgn on (ucgns2 = ucgn1)
				   inner join ucxg on (ucgn1 = ucxg2)
				   inner join gr on (ucgn2 = gr1)
				   inner join sg on (gr2 = sg1)
				   INNER JOIN sem on (sg.sg1 = sem.sem2)
				WHERE ucxg3=0 and gr2 = :SG1 and UCGNS5 = :YEAR1 and UCGNS6 = :SEM1 and sem3 = :YEAR2 and sem5 = :SEM2
				GROUP BY gr1,sem4, gr19,gr20,gr21,gr22,gr23,gr24,gr28
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':SG1', $sg1);
        $command->bindValue(':YEAR1', $year);
        $command->bindValue(':SEM1', $sem);
        $command->bindValue(':YEAR2', $year);
        $command->bindValue(':SEM2', $sem);
        $groups = $command->queryAll();

        $names = $gr1 = array();

        foreach ($groups as $group) {
            $names[] = Gr::model()->getGroupName($group['sem4'], $group);
            $gr1[] = $group['gr1'];
        }

        $names = implode(', ', $names);

        return array($gr1, $names);
    }

    public function getCourseFor($gr1, $year, $sem)
    {
        $sql = <<<SQL
            SELECT first 1 sem4
            FROM gr
            INNER JOIN sg on (gr.gr2 = sg.sg1)
            INNER JOIN sem on (sg.sg1 = sem.sem2)
            WHERE gr1=:GR1 and sem3=:YEAR and sem5=:SEM
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':GR1', $gr1);
        $command->bindValue(':YEAR', $year);
        $command->bindValue(':SEM', $sem);
        $course = $command->queryScalar();

        return $course;
    }

    public function getGroupsAndStudentsForWorkLoadAmount(FilterForm $model, array $discipline)
    {
        $condition = $fields = null;
        if ($model->extendedForm == 1) {
            $condition = 'and ug3 = '.$discipline['ug3'].' and us4 = '.$discipline['us4'];
            $fields = ',us4';
        }

        $sql = <<<SQL
            SELECT d1, gr1, ug2, sem4, gr3, gr19, gr20, gr21, gr22, gr23, gr24, gr28 {$fields}
            FROM sem
            INNER JOIN us ON (sem.sem1 = us.us12)
            INNER JOIN nr ON (us.us1 = nr.nr2)
            INNER JOIN pd ON (nr.nr6 = pd.pd1) or (nr.nr7 = pd.pd1) or (nr.nr8 = pd.pd1) or (nr.nr9 = pd.pd1)
            INNER JOIN ug ON (nr.nr1 = ug.ug3)
            INNER JOIN uo ON (us.us2 = uo.uo1)
            INNER JOIN d ON (uo.uo3 = d.d1)
            INNER JOIN gr ON (ug.ug2 = gr.gr1)
            WHERE pd1=:PD1 and sem3=:SEM3 and sem5=:SEM5 and d1 = :D1 {$condition}
            GROUP BY  d1, d2, gr1, ug2, sem4, gr3, gr19, gr20, gr21, gr22, gr23, gr24, gr28 {$fields}
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':PD1', $model->teacher);
        $command->bindValue(':SEM3', $model->year);
        $command->bindValue(':SEM5', $model->semester);
        $command->bindValue(':D1', $discipline['d1']);
        $groups = $command->queryAll();

        $groupNames = array();
        $studentsAmount = 0;

        foreach ($groups as $group) {
            $groupNames[] = Gr::model()->getGroupName($group['sem4'], $group);
            $studentsAmount += St::model()->getStudentsAmountFor($group['gr1']);
        }

        return array($groupNames, $studentsAmount);
    }

    public function getGroupsForWorkPlan(FilterForm $model)
    {
        if (empty($model->speciality) || empty($model->course) || empty($model->faculty))
            return array();

        $sql = <<<SQL
            SELECT sp2,gr1,gr7,gr3,gr19,gr20,gr21,gr22,gr23,gr24,gr28,sg3,sg4,gr13,sg1
            FROM sem
            INNER JOIN sg on (sem.sem2 = sg.sg1)
            INNER JOIN sp on (sg.sg2 = sp.sp1)
            INNER JOIN gr on (sg.sg1 = gr.gr2)
            WHERE sp5=:FACULTY and sp11 =:SPECIALITY and sem3 =:YEAR  and sem4=:COURSE and sp7 is null and gr13<>1
            GROUP BY sp2,gr1,gr7,gr3,gr19,gr20,gr21,gr22,gr23,gr24,gr28,sg3,sg4,gr13,sg1
SQL;

        list($year,) = SH::getCurrentYearAndSem();

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':SPECIALITY', $model->speciality);
        $command->bindValue(':YEAR', $year);
        $command->bindValue(':COURSE', $model->course);
        $command->bindValue(':FACULTY', $model->faculty);

        $groups = $command->queryAll();

        $_sg1 = 0; // previous row values
        $data = array();

        foreach ($groups as $group) {

            // this row values
            $sg1 = $group['sg1'];

            $changeRow = $sg1 != $_sg1;
            if ($changeRow)
                $_sg1 = $sg1;


            if (! isset($data[$sg1]))
                $data[$sg1] = array('groups' => array());


            $data[$sg1]['groups'][] = Gr::model()->getGroupName($model->course, $group);
        }

        foreach ($data as $sg1 => $flow) {
            $flowGroups = implode(', ', $flow['groups']);
            $data[$sg1]['name'] = mb_strimwidth($flowGroups, 0, 50, '...');
            $data[$sg1]['sg1']  = $sg1;
        }

        return $data;
    }

    public function getGraduatingYears(FilterForm $model)
    {
        if (empty($model->speciality))
            return array();

        $sql = <<<SQL
            SELECT sg11
            FROM gr
            INNER JOIN sg on (GR.GR2 = SG.SG1)
            INNER JOIN sp on (SG.SG2 = SP.SP1)
            WHERE gr13<>1 and gr1>0 and sp11=:SPECIALITY and sp7 is null and sg11<=:YEAR and sg11>0
            GROUP BY sg11
            ORDER BY sg11 DESC
SQL;

        list($year,) = SH::getCurrentYearAndSem();

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':SPECIALITY', $model->speciality);
        $command->bindValue(':YEAR', $year);
        $years = $command->queryAll();

        return $years;
    }

    public function getGraduatedGroups(FilterForm $model)
    {
        if (empty($model->speciality) || empty($model->year))
            return array();

        $sql = <<<SQL
            SELECT gr1,gr7,gr3,gr19,gr20,gr21,gr22,gr23,gr24,gr28,sg3,sg4,gr13,sg1
            FROM gr
            INNER JOIN sg on (gr.gr2 = sg.sg1)
            INNER JOIN sp on (sg.sg2 = sp.sp1)
            WHERE gr13<>'1' and gr1>0 and sp11=:SPECIALITY and sp7 is null and sg11 = :YEAR
            GROUP BY gr1,gr7,gr3,gr19,gr20,gr21,gr22,gr23,gr24,gr28,sg3,sg4,gr13,sg1
            ORDER BY gr1,gr3,sg1
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':SPECIALITY', $model->speciality);
        $command->bindValue(':YEAR', $model->year);
        $groups = $command->queryAll();

        foreach ($groups as $key => $group) {
            $sql = <<<SQL
            SELECT FIRST 1 sem4
            FROM sem
            WHERE sem2=:SG1
            ORDER BY sem7 DESC
SQL;
            $command = Yii::app()->db->createCommand($sql);
            $command->bindValue(':SG1', $group['sg1']);
            $course = $command->queryScalar();

            $groups[$key]['name'] = Gr::model()->getGroupName($course, $group);
        }

        $_sg1 = 0; // previous row values
        $data = array();

        foreach ($groups as $group) {

            // this row values
            $sg1 = $group['sg1'];

            $changeRow = $sg1 != $_sg1;
            if ($changeRow)
                $_sg1 = $sg1;

            if (! isset($data[$sg1]))
                $data[$sg1] = array('groups' => array());

            $data[$sg1]['groups'][] = $group['name'];
        }

        foreach ($data as $sg1 => $flow) {
            $flowGroups = implode(', ', $flow['groups']);
            $data[$sg1]['name'] = mb_strimwidth($flowGroups, 0, 50, '...');
            $data[$sg1]['sg1']  = $sg1;
        }

        return $data;
    }

    public function getSem7ByGr1($gr1)
    {
        $sql = <<<SQL
            SELECT first 1 sem7
            from sem
               inner join sg on (sem.sem2 = sg.sg1)
               inner join gr on (sg.sg1 = gr.gr2)
            WHERE gr1=:GR1 and sem3=:YEAR and sem5=:SEM
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':GR1', $gr1);
        $command->bindValue(':YEAR', Yii::app()->session['year']);
        $command->bindValue(':SEM', Yii::app()->session['sem']);
        $course = $command->queryScalar();

        return $course;
    }

    public function getSem7ByGr1ByDate($gr1,$date)
    {
        $sql = <<<SQL
            SELECT first 1 sem7
            from sem
               inner join sg on (sem.sem2 = sg.sg1)
               inner join gr on (sg.sg1 = gr.gr2)
            WHERE gr1=:GR1 and sem10<=:DATE_SEM ORDER BY sem10 DESC
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':GR1', $gr1);
        $command->bindValue(':YEAR', Yii::app()->session['year']);
        $command->bindValue(':SEM', Yii::app()->session['sem']);
        $command->bindValue(':DATE_SEM', $date);
        $course = $command->queryScalar();

        return $course;
    }

    public function getGroupsByChair($chair, $date1, $date2){
        $sql = <<<SQL
          SELECT gr13,gr3,gr1,gr19,gr20,gr21,gr22,gr23,gr24,gr28,sg1
            FROM UO
              INNER JOIN US ON (UO.UO1 = US.US2)
              INNER JOIN NR ON (US.US1 = NR.NR2)
              INNER JOIN UG ON (NR.NR1 = UG.UG3)
              INNER JOIN GR ON (UG.UG2 = GR.GR1)
              INNER JOIN R ON (UG.UG3 = R.R1)
              INNER JOIN D ON (UO.UO3 = D.D1)
              inner join U on (UO.UO22 = U.U1)
              inner join sg on (U.U2 = sg.sg1)
            WHERE us4>=0 and(R.R2>=:DATE1)and(R.R2<=:DATE2)   and (SG7 IS NULL OR R2 < SG7) and UO4=:K1
            GROUP BY gr13, gr3, gr1,gr19,gr20,gr21,gr22,gr23,gr24,gr28,sg1
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':K1', $chair);
        $command->bindValue(':DATE1', $date1);
        $command->bindValue(':DATE2', $date2);
        $groups = $command->queryAll();

        foreach ($groups as $key => $group) {
            $sql = <<<SQL
            SELECT FIRST 1 sem4
            FROM sem
            WHERE sem2=:SG1
            ORDER BY sem7 DESC
SQL;
            $command = Yii::app()->db->createCommand($sql);
            $command->bindValue(':SG1', $group['sg1']);
            $course = $command->queryScalar();

            $groups[$key]['name'] = Gr::model()->getGroupName($course, $group);
        }

        return $groups;
    }

    public function getSem1ByGroup($gr1, $year, $sem){
        if(empty($gr1)||empty($year)||$sem===null)
            return null;

        $sql = <<<SQL
          select  first 1 sem1
            from sg
               inner join sem on (sg.sg1 = sem.sem2)
               inner join gr on (sg.sg1 = gr.gr2)
            where gr1=:GR1 and sem3=:YEAR and sem5=:SEM
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':GR1', $gr1);
        $command->bindValue(':YEAR', $year);
        $command->bindValue(':SEM', $sem);
        $sem1 = $command->queryScalar();

        return $sem1;
    }
}
