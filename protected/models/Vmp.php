<?php

/**
 * This is the model class for table "vmp".
 *
 * The followings are the available columns in table 'vmp':
 * @property integer $vmp1
 * @property integer $vmp2
 * @property integer $vmp4
 * @property integer $vmp5
 * @property integer $vmp6
 * @property integer $vmp7
 */
class Vmp extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'vmp';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('vmp1, vmp2, vmp4, vmp5, vmp6, vmp7', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('vmp1, vmp2, vmp4, vmp5, vmp6, vmp7', 'safe', 'on'=>'search'),
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
			'vmp1' => 'Vmp1',
			'vmp2' => 'Vmp2',
			'vmp4' => 'Vmp4',
			'vmp5' => 'Vmp5',
			'vmp6' => 'Vmp6',
			'vmp7' => 'Vmp7',
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

		$criteria->compare('vmp1',$this->vmp1);
		$criteria->compare('vmp2',$this->vmp2);
		$criteria->compare('vmp4',$this->vmp4);
		$criteria->compare('vmp5',$this->vmp5);
		$criteria->compare('vmp6',$this->vmp6);
		$criteria->compare('vmp7',$this->vmp7);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Vmp the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function getMarksForStudent($st1, $vvmp1)
    {
        $rows = Yii::app()->db->createCommand()
                ->select('*')
                ->from('vmp')
                ->where('vmp1 = :VVMP1 and vmp2 = :ST1', array(
                    ':VVMP1' => $vvmp1,
                    ':ST1' => $st1,
                ))
                ->queryAll();

        $res = array();
        foreach($rows as $row) {
            $key = $row['vmp3'];
            $res[$key] = $row;
        }

        return $res;
    }

    public function recalculateVmp4()
    {
        $array = array(
            $this->vmp5,
            $this->vmp6,
            $this->vmp7
        );

        $this->saveAttributes(array(
            'vmp4' => array_sum($array)
        ));
    }

    public function isModuleExtended($vvmp1, $module)
    {
        $isExtended = Yii::app()->db->createCommand()
                        ->select('count(*)')
                        ->from('vmp')
                        ->where('vmp1 = :VVMP1 AND vmp3 = :MODULE_NUM and (vmp5 <> 0 OR vmp6 <> 0 OR vmp7 <> 0)', array(
                            ':VVMP1' => $vvmp1,
                            ':MODULE_NUM' => $module,
                        ))
                        ->queryScalar();

        return (bool)$isExtended;
    }

    public function getMarks($vvmp1,$st1,$gr1)
    {
        if(empty($vvmp1)||empty($st1))
            return array();

        $sql = <<<SQL
          SELECT FIRST 1 vmpv1 from vvmp
			INNER JOIN vmpv on (vvmp1=vmpv2)
			INNER JOIN vmp on (vmp1=vmpv1)
          WHERE vmp2=:ST1 AND vvmp1=:VVMP1 and vmpv7=:GR1 ORDER BY vmpv8 DESC,vmpv4 DESC,vmpv1 DESC
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':VVMP1', $vvmp1);
        $command->bindValue(':GR1', $gr1);
        $command->bindValue(':ST1', $st1);
        $vmpv1 = $command->queryScalar();

        if(empty($vmpv1))
            return array();

        $sql = <<<SQL
          SELECT vmp.*,vmpv.* FROM vmp INNER JOIN vmpv on (vmp1=vmpv1) WHERE vmp2=:ST1 AND vmp1=:VMPV1
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':VMPV1', $vmpv1);
        $command->bindValue(':ST1', $st1);
        $res = $command->queryRow();

        return $res;
    }

    /**
     * год и семстр начала дисциплині
     * @param $uo1
     * @return mixed
     */
    public function getStartYearSem($uo1){
        $sql = <<<SQL
            select first 1 sem3,sem5
            from sem
               inner join us on (sem.sem1 = us.us3)
            where us2=:UO1
            order by sem7
SQL;

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValue(':UO1', $uo1);
        $row = $command->queryRow();

        return $row;
    }

    /**
     * Sem1 псоледнего семестра дисциплині
     * @param $uo1
     * @return mixed
     */
    public function getEndSem1($uo1){
        $sql = <<<SQL
            select first 1 sem1
            from sem
               inner join us on (sem.sem1 = us.us3)
            where us2=:UO1 and us4 in (1,2,3,4,5,6) and us6>0
            order by sem7 DESC
SQL;

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValue(':UO1', $uo1);
        $row = $command->queryScalar();

        return $row;
    }

    /**
     * Оценки за семестр
     * @param $elg Elg
     * @param $sem1 int
     * @param $gr1 int
     * @param $st1 int
     * @param $dopJoin string
     * @param $dopColumns string
     * @return array
     */
    private function getMarksBySem($elg, $sem1, $gr1, $st1, $dopJoin, $dopColumns = ''){
        $sql = <<<SQL
                      SELECT elgzst5,elgzst4,elgzst3 $dopColumns FROM elg
                          INNER JOIN elgz on (elg.elg1 = elgz.elgz2 AND  (elgz4=0 or elgz4=1) )
                          INNER JOIN elgzst on (elgzst.elgzst2 = elgz.elgz1  AND elgzst1=:ST1 )
                          $dopJoin
                      WHERE  elg4=:ELG4 and elg3=:SEM1 AND elg2=:UO1 ORDER by elgz3 asc
SQL;
        $command = Yii::app()->db->createCommand($sql);

        $command->bindValue(':GR1', $gr1);
        $command->bindValue(':SEM', $sem1);

        $command->bindValue(':ST1', $st1);
        $command->bindValue(':SEM1', $sem1);
        $command->bindValue(':UO1', $elg->elg2);
        $command->bindValue(':ELG4', $elg->elg4);

        return $command->queryAll();
    }

    /**
     * Оцнеки с журнала для пмк
     * @param $st1 int студент
     * @param $elgz Elgz Занятие
     * @param $gr1 int группа
     * @params $showInfo bool вовпращать ли оценки с доп инофрмацией (дата, номер занятия тема)
     * @return array оценки
     */
    public function getMarksFromJournal($st1,$elgz,$gr1, $showInfo = false){
        /** @var $elg Elg */
        $dopColumn = '';

        $elg =  Elg::model()->findByPk($elgz->elgz2);
        if($elg==null)
            return array();

        $dopJoin = "inner join EL_GURNAL_ZAN({$elg->elg2},:GR1,:SEM, {$elg->elg4}) on (elgz.elgz3 = EL_GURNAL_ZAN.nom)";

        $dopMarks = array();

        if ($showInfo) {
            $dopColumn = ',r2,elgz3, elgz4, us4';
        }

        list($year, $sem) = Elgz::model()->getSemYearAndSem($elgz->elgz1);
        $currentYear = $year;
        $currentSem = $sem;

        $pmkPrevLessonNom = 0;

        $sql = <<<SQL
            SELECT elgz3 FROM elgz WHERE elgz2=:ELGZ2 AND elgz4 in (2,3,4) AND elgz3>=:ELGZ3 ORDER by elgz3 asc
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':ELGZ2', $elgz->elgz2);
        $command->bindValue(':ELGZ3', $elgz->elgz3);
        $pmkLessonNom = $command->queryScalar();

        if (empty($pmkLessonNom))
            return array();


        if($elg->elg20->uo6!=3) {

            $sql = <<<SQL
            SELECT elgz3 FROM elgz WHERE elgz2=:ELGZ2 AND elgz4 in (2,3,4) AND elgz3<:ELGZ3 ORDER by elgz3 asc
SQL;
            $command = Yii::app()->db->createCommand($sql);
            $command->bindValue(':ELGZ2', $elgz->elgz2);
            $command->bindValue(':ELGZ3', $elgz->elgz3);
            $pmkPrevLessonNom = $command->queryScalar();


            if (empty($pmkPrevLessonNom)) {
                $pmkPrevLessonNom = 0;

                list($year, $sem) = SH::getPrevSem($year, $sem);

                $sem1 = Sem::model()->getSemestrForGroupByYearAndSem($gr1, $year, $sem);

                $uo1 = Elgz::model()->getUo1($elgz->elgz1);

                if ($sem1 != 0) {

                    $sql = <<<SQL
                      SELECT MAX(elgz3) as nom FROM elgz
                        INNER JOIN elg on (elgz2 = elg1)
                     WHERE elg3=:SEM1 AND elgz4 in (2,3,4) AND elg2=:UO1 ORDER by nom asc
SQL;
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindValue(':SEM1', $sem1);
                    $command->bindValue(':UO1', $uo1);
                    $pmkLessonNomPrevSem = $command->queryScalar();

                    if (empty($pmkLessonNomPrevSem)) {
                        $pmkLessonNomPrevSem = 0;
                    }

                    $sql = <<<SQL
                      SELECT elgzst5,elgzst4,elgzst3 $dopColumn FROM elg
                          INNER JOIN elgz on (elg.elg1 = elgz.elgz2 AND  (elgz4=0 or elgz4=1)  AND elgz.elgz3>:NOM)
                          INNER JOIN elgzst on (elgzst.elgzst2 = elgz.elgz1  AND elgzst1=:ST1 )
                          $dopJoin
                      WHERE  elg4=:ELG4 and elg3=:SEM1 AND elg2=:UO1 ORDER by elgz3 asc
SQL;
                    $command = Yii::app()->db->createCommand($sql);

                    $command->bindValue(':GR1', $gr1);
                    $command->bindValue(':SEM', $sem1);

                    $command->bindValue(':ST1', $st1);
                    $command->bindValue(':NOM', $pmkLessonNomPrevSem);
                    $command->bindValue(':SEM1', $sem1);
                    $command->bindValue(':UO1', $uo1);
                    $command->bindValue(':ELG4', $elg->elg4);

                    $dopMarks = $command->queryAll();

                    $dopMarks = array(
                        $year.'-'.$sem=>array(
                            'year'=>$year,
                            'sem'=>$sem,
                            'marks'=>$dopMarks
                        )
                    );
                }
            }
        }else{
            //Накопительная система
            //list($year, $sem) = SH::getPrevSem($year, $sem);

            $row = $this->getStartYearSem($elg->elg2);

            if(empty($row)){
                return array();
            }

            $startYear = $row['sem3'];
            $startSem = $row['sem5'];

            $_i = 0;

            while ($startYear!= $year || $startSem!=$sem){

                list($year, $sem) = SH::getPrevSem($year, $sem);

                $sem1 = Sem::model()->getSemestrForGroupByYearAndSem($gr1, $year, $sem);

                $marksBySem = array(
                    $year.'-'.$sem=>array(
                        'year'=>$year,
                        'sem'=>$sem,
                        'marks'=>$this->getMarksBySem($elg, $sem1, $gr1, $st1, $dopJoin, $dopColumn)
                    )
                );

                $dopMarks = array_merge($dopMarks, $marksBySem);

                $_i++;

                if($_i>10)
                    return array();
            }
        }

        $sql=<<<SQL
              SELECT elgzst5,elgzst4,elgzst3 $dopColumn FROM elgzst
              INNER JOIN elgz on (elgzst.elgzst2 = elgz.elgz1 AND elgz.elgz2=:ELGZ2 and elgz.elgz3>:MIN AND elgz.elgz3<:MAX)
              $dopJoin
              WHERE  elgzst1=:ST1 AND (elgz4=0 or elgz4=1) ORDER by elgz3 asc
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':ELGZ2', $elgz->elgz2);
        $command->bindValue(':ST1', $st1);
        $command->bindValue(':MIN', $pmkPrevLessonNom);
        $command->bindValue(':MAX', $pmkLessonNom);

        $command->bindValue(':GR1', $gr1);
        $command->bindValue(':SEM', $elg->elg3);

        $marks= $command->queryAll();

        $returnArray = array(
            'current'=>array(
                'year'=>$currentYear,
                'sem'=>$currentSem,
                'marks'=>$marks
            )
        );

        if(!empty($dopMarks)){
            foreach ($dopMarks as $dopMark) {

                $key = $dopMark['year'].'-'.$dopMark['sem'];

                $returnArray[$key] = $dopMark;
            }
        }

        return $returnArray;
    }

    /**
     * Пересчет оценок в итоговую ведомость  накопительной системы в журнале по студенту
     * Для запорожского меда
     * @param $st1
     * @param $elg Elg
     * @param $gr1
     * @param $elgz Elgz
     * @return void|bool
     */
    public function recalculateItogVmp($st1,$elg, $elgz,$gr1){
        $ps57 = PortalSettings::model()->getSettingFor(57);
        if($ps57!=1)
            return;
        $universityCode = SH::getUniversityCod();

        if($universityCode!=U_ZSMU)
            return;

        $vmp = $this->getVedItog($elg->elg2, $gr1, 98, $st1);
        if(empty($vmp))
            return;

        if(!empty($vmp['vmpv6']))
            return;

        //var_dump($st1);

        //var_dump($vmp);

        $startYear  = null;
        $startSem = null;

        if($elg->elg20->uo6==3){
            $row = $this->getStartYearSem($elg->elg2);

            if(empty($row)){
                return;
            }

            $startYear = $row['sem3'];
            $startSem = $row['sem5'];
        } else
            return;

        if($universityCode == U_ZSMU && $elg->elg20->uo18 > 0 && $elg->elg20->uo6>0)
        {
            $marksArray = $this->getMarksFromJournalNewNakop($st1,$elgz,$gr1,$elg);
        } else
            $marksArray = $this->getMarksFromJournal($st1,$elgz,$gr1);

        $min = Elgzst::model()->getMin();
        $tek =0;
        $count =0;
        $countNb = $countDv = 0;

        foreach ($marksArray as $key => $marks){

            if(!empty($marks)) {
                foreach ($marks['marks'] as $mark) {
                    $bal = 0;
                    if ($mark['elgzst3'] > 0) {
                        $bal = $mark['elgzst5'];
                        //Счиатть не отработаные
                        if ($mark['elgzst5'] <= $min)
                            $countNb++;
                    } else {
                        $bal = ($mark['elgzst5'] > 0) ? $mark['elgzst5'] : $mark['elgzst4'];

                        if ($mark['elgzst4'] <= $min && $mark['elgzst5'] <= $min)
                            $countDv++;
                    }
                    $tek += $bal;
                }

                $count += count($marks['marks']);
            }
        }

        //var_dump($tek);

        //var_dump($count);

        $val = $count>0 ? $tek/$count : 0;

        $tek = round($val,2);

        //ar_dump($tek);

        $sql = <<<SQL
                      SELECT max(markb3) FROM markb WHERE markb2<=:BAL AND markb4=0
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':BAL', $tek);
        $mark = $command->queryScalar();
        //var_dump($mark);
        if(!empty($mark)){
            $tek = $mark;
        }else {
            $tek = 0;
        }
        //var_dump($tek);

        $sql = <<<SQL
                          UPDATE vmp set vmp5=:VMP5, vmp4=:VMP4, vmp10=:VMP10, vmp12=:VMP12 WHERE vmp2=:ST1 AND vmp1=:VMPV1
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':VMP5', $tek);
        $command->bindValue(':VMP4', 0);
        $command->bindValue(':ST1', $st1);
        $command->bindValue(':VMPV1', $vmp['vmp1']);
        $command->bindValue(':VMP12', Yii::app()->user->dbModel->p1);
        $command->bindValue(':VMP10', date('Y-m-d H:i:s'));
        $command->execute();

    }

    /**
     * @param $elg Elg
     * @return array
     * @throws CException
     */
    public function getDispNakop($elg){
        $sql = <<<SQL
          select 
               uo5, d2, sem7, sem5, sem3, uo1, sem1
            from d
               inner join uo on (d.d1 = uo.uo3)
               inner join us on (uo.uo1 = us.us2)
               inner join sem on (us.us3 = sem.sem1)
            where uo18=:UO18 and sem2 = (select u2 from u where u1=:UO22) and uo5<=:UO5 and sem7<=:SEM7
            group BY uo5, d2, sem7, sem5, sem3, uo1, sem1
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':UO5', $elg->elg20->uo5);
        $command->bindValue(':UO22', $elg->elg20->uo22);
        $command->bindValue(':UO18', $elg->elg20->uo18);
        $command->bindValue(':SEM7', $elg->elg30->sem7);

        return $command->queryAll();
    }

    public function getMarksFromJournalNewNakop($st1,$elgz,$gr1, $showInfo = false){
        /** @var $elg Elg */
        $dopColumn = '';

        $elg =  Elg::model()->findByPk($elgz->elgz2);
        if($elg==null)
            return array();

        $dopJoin = "inner join EL_GURNAL_ZAN({$elg->elg2},:GR1,:SEM, {$elg->elg4}) on (elgz.elgz3 = EL_GURNAL_ZAN.nom)";

        $dopMarks = array();

        if ($showInfo) {
            $dopColumn = ',r2,elgz3, elgz4, us4';
        }

        list($year, $sem) = Elgz::model()->getSemYearAndSem($elgz->elgz1);
        $currentYear = $year;
        $currentSem = $sem;

        $pmkPrevLessonNom = 0;

        $sql = <<<SQL
            SELECT elgz3 FROM elgz WHERE elgz2=:ELGZ2 AND elgz4 in (2,3,4) AND elgz3>=:ELGZ3 ORDER by elgz3 asc
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':ELGZ2', $elgz->elgz2);
        $command->bindValue(':ELGZ3', $elgz->elgz3);
        $pmkLessonNom = $command->queryScalar();

        if (empty($pmkLessonNom))
            return array();


        $disciplines = $this->getDispNakop($elg);

        //var_dump($disciplines);

        foreach ($disciplines as $discipline){
            $sem1 = $discipline['sem1'];//Sem::model()->getSemestrForGroupByYearAndSem($gr1, $year, $sem);

            if($sem1 == $elg->elg3 && $elg->elg2 == $discipline['uo1'])
                continue;

            $_elg = Elg::model()->findByAttributes(array(
                'elg2' => $discipline['uo1'],
                'elg3' => $sem1,
                'elg4' => $elg->elg4
            ));

            if(empty($_elg))
                continue;

            $_year = $discipline['sem3'];
            $_sem = $discipline['sem5'];

            $marksBySem = array(
                $discipline['d2'] . '('. $discipline['uo5'] . ') ' . $_year.'-'.$_sem=>array(
                    'discipline' => $discipline['d2'],
                    'year'=>$_year,
                    'sem'=>$_sem,
                    'marks'=>$this->getMarksBySem($_elg, $sem1, $gr1, $st1, $dopJoin, $dopColumn)
                )
            );

            $dopMarks = array_merge($dopMarks, $marksBySem);
        }

        //var_dump($dopMarks);

        $sql=<<<SQL
              SELECT elgzst5,elgzst4,elgzst3 $dopColumn FROM elgzst
              INNER JOIN elgz on (elgzst.elgzst2 = elgz.elgz1 AND elgz.elgz2=:ELGZ2 and elgz.elgz3>:MIN AND elgz.elgz3<:MAX)
              $dopJoin
              WHERE  elgzst1=:ST1 AND (elgz4=0 or elgz4=1) ORDER by elgz3 asc
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':ELGZ2', $elgz->elgz2);
        $command->bindValue(':ST1', $st1);
        $command->bindValue(':MIN', $pmkPrevLessonNom);
        $command->bindValue(':MAX', $pmkLessonNom);

        $command->bindValue(':GR1', $gr1);
        $command->bindValue(':SEM', $elg->elg3);

        $marks= $command->queryAll();

        $returnArray = array(
            'current'=>array(
                'discipline' => $elg->elg20->uo31->d2,
                'year'=>$currentYear,
                'sem'=>$currentSem,
                'marks'=>$marks
            )
        );

        if(!empty($dopMarks)){
            foreach ($dopMarks as $key=>$dopMark) {

                //$key = $dopMark['year'].'-'.$dopMark['sem'];

                $returnArray[$key] = $dopMark;
            }
        }

        return $returnArray;
    }

    /**
     * @param $st1
     * @param $elgz Elgz
     * @param $gr1
     * @param $elg Elg
     * @param $module array
     * @param $vmp array
     * @throws
     */
    protected function _recalculateBlockDisp($st1,$elgz,$gr1, $elg, $module, $vmp){
        $marksArray = $this->getMarksFromJournalNewNakop($st1,$elgz,$gr1,$elg);

        //var_dump($marksArray);

        $min = Elgzst::model()->getMin();
        $tek =0;
        $count =0;
        $countNb = $countDv = 0;

        foreach ($marksArray as $key => $marks){

            if(!empty($marks)) {
                foreach ($marks['marks'] as $mark) {
                    $bal = 0;
                    if ($mark['elgzst3'] > 0) {
                        $bal = $mark['elgzst5'];
                        //Счиатть не отработаные
                        if ($mark['elgzst5'] <= $min)
                            $countNb++;
                    } else {
                        $bal = ($mark['elgzst5'] > 0) ? $mark['elgzst5'] : $mark['elgzst4'];

                        if ($mark['elgzst4'] <= $min && $mark['elgzst5'] <= $min)
                            $countDv++;
                    }
                    $tek += $bal;
                }

                $count += count($marks['marks']);
            }
        }

        //var_dump($tek);
        //var_dump($count);
        ///Для запрожья где диф зачет считаеться без перевода балов, а среднее делиться на 5 и умножаеться на 200
        $_tek = $tek;

        if(!empty($vmp)){

            $dopColumns = ', vmp13=:VMP13, vmp14=:VMP14 ';

            $val = $count>0 ? $_tek/$count : 0;

            $_tek = round($val,2);

            $_tek= ($_tek*200)/5;

            $_tek = round($_tek);

            //var_dump($_tek);

            $sql = <<<SQL
                          UPDATE vmp set vmp4=:VMP4, vmp5=:VMP5, vmp6=:VMP6, vmp7=:VMP7, vmp10=:VMP10, vmp12=:VMP12 $dopColumns WHERE vmp2=:ST1 AND vmp1=:VMPV1
SQL;
            $command = Yii::app()->db->createCommand($sql);
            if($elg->elg20->uo6==3){
                $command->bindValue(':VMP5', $_tek);
                $command->bindValue(':VMP4', $_tek);
                $command->bindValue(':VMP6', 0);
                $command->bindValue(':VMP7', 0);
            }
            else {
                $command->bindValue(':VMP5', 0);
                $command->bindValue(':VMP4', $_tek);
                $command->bindValue(':VMP6', 0);
                $command->bindValue(':VMP7', $_tek);
            }
            $command->bindValue(':ST1', $st1);
            $command->bindValue(':VMPV1', $module['vmpv1']);
            $command->bindValue(':VMP12', Yii::app()->user->dbModel->p1);
            $command->bindValue(':VMP10', date('Y-m-d H:i:s'));
            $command->bindValue(':VMP13', $countNb);
            $command->bindValue(':VMP14', $countDv);
            $command->execute();

            $elgpmkst = Elgpmkst::model()->findByAttributes(
                array(
                    'elgpmkst2'=> $elgz->elgz2,
                    'elgpmkst3' =>$st1,
                    'elgpmkst4' =>$module['vmpv1'],
                )
            );

            if(empty($elgpmkst)){
                $elgpmkst = new Elgpmkst();
                $elgpmkst->elgpmkst1 = new CDbExpression('GEN_ID(GEN_ELGPMKST, 1)');
                $elgpmkst->elgpmkst2 = $elgz->elgz2;
                $elgpmkst->elgpmkst3 =  $st1;
                $elgpmkst->elgpmkst4 = $module['vmpv1'];
            }

            $elgpmkst->elgpmkst5 = $_tek;
            $elgpmkst->save();

            if($elg->elg20->uo6==3){
                $val = $count>0 ? $tek/$count : 0;

                $tek = round($val,2);

                //var_dump($tek);

                $sql = <<<SQL
                      SELECT max(markb3) FROM markb WHERE markb2<=:BAL AND markb4=0
SQL;
                $command = Yii::app()->db->createCommand($sql);
                $command->bindValue(':BAL', $tek);
                $mark = $command->queryScalar();

                //var_dump($mark);

                if(!empty($mark)){
                    $tek = $mark;
                }else {
                    $tek = 0;
                }

                //var_dump($tek);

                $vmp = $this->getVedItog($elg->elg2, $gr1, 98, $st1);

                if(!empty($vmp)){
                    $sql = <<<SQL
                              UPDATE vmp set vmp5=:VMP5, vmp4=:VMP4, vmp10=:VMP10, vmp12=:VMP12 WHERE vmp2=:ST1 AND vmp1=:VMPV1
SQL;
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindValue(':VMP5', $tek);
                    $command->bindValue(':VMP4', 0);
                    $command->bindValue(':ST1', $st1);
                    $command->bindValue(':VMPV1', $vmp['vmp1']);
                    $command->bindValue(':VMP12', Yii::app()->user->dbModel->p1);
                    $command->bindValue(':VMP10', date('Y-m-d H:i:s'));
                    $command->execute();
                }
            }
        }
    }

    /**
     * Пересчет оценко пмк в журнале по студенту
     * @param $st1
     * @param $elgz
     * @param $gr1
     * @throws
     */
    public function recalculate($st1,$elgz,$gr1){

        $universityCode = SH::getUniversityCod();

        $ps57 = PortalSettings::model()->getSettingFor(57);
        if($ps57!=1)
            return;

        $elg = Elg::model()->findByPk($elgz->elgz2);

        $module = Vvmp::model()->getModul($elg->elg2, $gr1,$elgz->elgz3,$elg->elg1, $st1);
        //var_dump($module);
        if(empty($module))
            return;

        $sql = <<<SQL
                              SELECT * FROM vmp WHERE vmp2=:ST1 AND vmp1=:VMPV1
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':ST1', $st1);
        $command->bindValue(':VMPV1', $module['vmpv1']);
        $vmp = $command->queryRow();

        if(empty($vmp))
            return;

        //print_r(1);
        $sql=<<<SQL
            SELECT elgz3 FROM elgz WHERE elgz2=:ELGZ2 AND elgz4 in (2,3,4) AND elgz3>=:ELGZ3 ORDER by elgz3 asc
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':ELGZ2', $elgz->elgz2);
        $command->bindValue(':ELGZ3', $elgz->elgz3);
        $pmkLessonNom = $command->queryScalar();
        //var_dump($pmkLessonNom);
        //$marks = array();
        if(empty($pmkLessonNom)||empty($module))
            return;
        else
        {
            //Пересчет блочных накопительных систем
            if($universityCode == U_ZSMU && $elg->elg20->uo18 > 0 && $elg->elg20->uo6>0){
                $this->_recalculateBlockDisp($st1,$elgz,$gr1, $elg, $module, $vmp);
                return;
            }

            $marksArray = $this->getMarksFromJournal($st1,$elgz,$gr1);

            //var_dump($marksArray);

            $min = Elgzst::model()->getMin();
            $tek =0;
            $count =0;
            $countNb = $countDv = 0;

            foreach ($marksArray as $key => $marks){

                if(!empty($marks)) {
                    foreach ($marks['marks'] as $mark) {
                        $bal = 0;
                        if ($mark['elgzst3'] > 0) {
                            $bal = $mark['elgzst5'];
                            //Счиатть не отработаные
                            if ($mark['elgzst5'] <= $min)
                                $countNb++;
                        } else {
                            $bal = ($mark['elgzst5'] > 0) ? $mark['elgzst5'] : $mark['elgzst4'];

                            if ($mark['elgzst4'] <= $min && $mark['elgzst5'] <= $min)
                                $countDv++;
                        }
                        $tek += $bal;
                    }

                    $count += count($marks['marks']);
                }
            }
            ///Для запрожья где диф зачет считаеться без перевода балов, а среднее делиться на 5 и умножаеться на 200
            $_tek = $tek;
            //var_dump($tek);
            //var_dump($count);
            $ps82 = PortalSettings::model()->findByPk(82)->ps2;
            if($ps82!=0){
                $val = $count>0 ? $tek/$count : 0;
                //print_r($val);
                $tek = round($val,2);
                //var_dump($tek);
                if($ps82==2){
                    //print_r('----');
                    $sql = <<<SQL
                          SELECT max(markb3) FROM markb WHERE markb2<=:BAL AND markb4=0
SQL;
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindValue(':BAL', $tek);
                    $mark = $command->queryScalar();
                    //var_dump($mark);
                    if(!empty($mark)){
                        $tek = $mark;
                    }else {
                        $tek = 0;
                        //print_r($tek);
                    }
                }

                //print_r($tek);
            }

            if(!empty($vmp)){

                $_elgz = Elgz::model()->findByAttributes(array('elgz2'=>$elg->elg1,'elgz3'=>$pmkLessonNom));
                //var_dump($_elgz->elgz4);
                if(empty($_elgz))
                    return;

                /*$dopColumns = '';
                if($elg->elg20->uo6 == 2 || $elg->elg20->uo6 == 3)*/
                $dopColumns = ', vmp13=:VMP13, vmp14=:VMP14 ';

                if($_elgz->elgz4==2) {
                    $itog = $tek + $vmp['vmp6'];
                    if($vmp['vmp7']>0)
                        $itog+=$vmp['vmp7'];

                    $sql = <<<SQL
                          UPDATE vmp set vmp4=:VMP4, vmp5=:VMP5, vmp10=:VMP10, vmp12=:VMP12 $dopColumns WHERE vmp2=:ST1 AND vmp1=:VMPV1
SQL;
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindValue(':VMP5', $tek);
                    $command->bindValue(':VMP4', $itog);
                    $command->bindValue(':ST1', $st1);
                    $command->bindValue(':VMPV1', $module['vmpv1']);
                    $command->bindValue(':VMP12', Yii::app()->user->dbModel->p1);
                    $command->bindValue(':VMP10', date('Y-m-d H:i:s'));
                    $command->bindValue(':VMP13', $countNb);
                    $command->bindValue(':VMP14', $countDv);
                    $command->execute();

                }elseif($_elgz->elgz4==3||$_elgz->elgz4==4){

                    if($_elgz->elgz4==3&&SH::getUniversityCod()==32){
                        $val = $count>0 ? $_tek/$count : 0;
                        //print_r($val);
                        $_tek = round($val,2);

                        $_tek= ($_tek*200)/5;

                        $_tek = round($_tek);

                        //var_dump($_tek);
                    }

                    $sql = <<<SQL
                          UPDATE vmp set vmp4=:VMP4, vmp5=:VMP5, vmp6=:VMP6, vmp7=:VMP7, vmp10=:VMP10, vmp12=:VMP12 $dopColumns WHERE vmp2=:ST1 AND vmp1=:VMPV1
SQL;
                    $command = Yii::app()->db->createCommand($sql);
                    if($elg->elg20->uo6==3){
                        $command->bindValue(':VMP5', $_tek);
                        $command->bindValue(':VMP4', $_tek);
                        $command->bindValue(':VMP6', 0);
                        $command->bindValue(':VMP7', 0);
                    }
                    else {
                        $command->bindValue(':VMP5', 0);
                        $command->bindValue(':VMP4', $_tek);
                        $command->bindValue(':VMP6', 0);
                        $command->bindValue(':VMP7', $_tek);
                    }
                    $command->bindValue(':ST1', $st1);
                    $command->bindValue(':VMPV1', $module['vmpv1']);
                    $command->bindValue(':VMP12', Yii::app()->user->dbModel->p1);
                    $command->bindValue(':VMP10', date('Y-m-d H:i:s'));
                    $command->bindValue(':VMP13', $countNb);
                    $command->bindValue(':VMP14', $countDv);
                    $command->execute();
                }

                $elgpmkst = Elgpmkst::model()->findByAttributes(
                    array(
                        'elgpmkst2'=> $elgz->elgz2,
                        'elgpmkst3' =>$st1,
                        'elgpmkst4' =>$module['vmpv1'],
                    )
                );

                if(empty($elgpmkst)){
                    $elgpmkst = new Elgpmkst();
                    $elgpmkst->elgpmkst1 = new CDbExpression('GEN_ID(GEN_ELGPMKST, 1)');
                    $elgpmkst->elgpmkst2 = $elgz->elgz2;
                    $elgpmkst->elgpmkst3 =  $st1;
                    $elgpmkst->elgpmkst4 = $module['vmpv1'];
                }
                //var_dump($_tek);
                $elgpmkst->elgpmkst5 = ($_elgz->elgz4==3 && $universityCode==U_ZSMU) ? $_tek : $tek;
                $elgpmkst->save();

                if($elg->elg20->uo6==3){
                    $sem1 = $this->getEndSem1($elg->elg2);
                    if($sem1==$elg->elg3){
                        $vmp = $this->getVedItog($elg->elg2, $gr1, 98, $st1);

                        if(!empty($vmp)){
                            $sql = <<<SQL
                              UPDATE vmp set vmp5=:VMP5, vmp4=:VMP4, vmp10=:VMP10, vmp12=:VMP12 WHERE vmp2=:ST1 AND vmp1=:VMPV1
SQL;

                            $command = Yii::app()->db->createCommand($sql);
                            $command->bindValue(':VMP5', $tek);
                            $command->bindValue(':VMP4', 0);
                            $command->bindValue(':ST1', $st1);
                            $command->bindValue(':VMPV1', $vmp['vmp1']);
                            $command->bindValue(':VMP12', Yii::app()->user->dbModel->p1);
                            $command->bindValue(':VMP10', date('Y-m-d H:i:s'));
                            $command->execute();
                        }
                    }
                }
            }
        }
    }

    /**
     * Получить итоговую ведомость дял uo6=3
     * @param $uo1
     * @param $gr1
     * @param $nom
     * @param $st1
     * @return array
     */
    public function getVedItog($uo1,$gr1,$nom,$st1)
    {
        $sql = <<<SQL
			SELECT vmp.*, vmpv.* from vvmp
			INNER JOIN vmpv on (vvmp1=vmpv2)
			INNER JOIN vmp on (vmpv1=vmp1 and vmp2=:ST1)
			left join vmpvf on (vvmp.vvmp1 = vmpvf.vmpvf1 and vmpvf2={$gr1})
			WHERE vvmp3=(
			  SELECT  uo3 from uo where uo1=:UO1
			)
			/*and vmpv6 is null*/
			and vmpv6 is null AND vvmp6=:NOM and vmpv7=:GR1 and vmpvf3 = (
			select
			   first 1 sem7
				from sem
				   inner join sg on (sem.sem2 = sg.sg1)
				   inner join gr on (sg.sg1 = gr.gr2)
				WHERE gr1={$gr1} and sem3=:YEAR and sem5=:SEM
			) and vvmp25=(
			  SELECT  gr2 from gr where gr1={$gr1}
			) ORDER by vvmp6 ASC, vmpv4 DESC
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':GR1', $gr1);
        $command->bindValue(':UO1', $uo1);
        $command->bindValue(':ST1', $st1);
        $command->bindValue(':NOM', $nom);
        $command->bindValue(':YEAR', Yii::app()->session['year']);
        $command->bindValue(':SEM', Yii::app()->session['sem']);

        $row = $command->queryRow();

        return $row;
    }
}
