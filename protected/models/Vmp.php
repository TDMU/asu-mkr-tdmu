<?php

/**
 * This is the model class for table "vmp".
 *
 * The followings are the available columns in table 'vmp':
 * @property integer $vmp1
 * @property integer $vmp2
 * @property integer $vmp3
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
			array('vmp1, vmp2, vmp3, vmp4, vmp5, vmp6, vmp7', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('vmp1, vmp2, vmp3, vmp4, vmp5, vmp6, vmp7', 'safe', 'on'=>'search'),
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
			'vmp3' => 'Vmp3',
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
		$criteria->compare('vmp3',$this->vmp3);
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

    public function recalculateModulesFor($vvmp1, $nr1)
    {
        $vvmp = Vvmp::model()->findByPk($vvmp1);

        if (empty($vvmp))
            throw new Exception('Can\'t find vvmp with id = '.$vvmp1);

        $gr1  = $vvmp->vvmp2;
        $d1   = $vvmp->vvmp3;
        $sem7 = $vvmp->vvmp4;

        // даты модулей
        $moduleDates = Mej::model()->findAllByAttributes(array('mej3' => $nr1));

        // студенты
        $students = St::model()->getStudentsOfGroup($gr1);

        // получаем все коды nr1 для конкретной дисциплины
        $sql = <<<SQL
                select nr1
				from ug
				   inner join nr on (ug.ug3 = nr.nr1)
				   inner join us on (nr.nr2 = us.us1)
				   inner join uo on (us.us2 = uo.uo1)
				   inner join sem on (us.us3 = sem.sem1)
				where ug2={$gr1} and sem7={$sem7} and uo3={$d1}
				group by nr1
SQL;
        $arrayNr1 = Yii::app()->db->createCommand($sql)->queryColumn();

        $canRecalculate = count($moduleDates) > 0 &&
                          count($students) > 0 &&
                          count($arrayNr1) > 0;

        if ($canRecalculate) {

            // set to 0 all rows
            $condition = 'vmp1 = '.$vvmp->vvmp1.' AND vmp3 >=1';
            Vmp::model()->updateAll(array(
                'vmp4' => 0,
                'vmp5' => 0,
                'vmp6' => 0,
                'vmp7' => 0,
            ), $condition);

            foreach ($moduleDates as $key => $module) {
                foreach($students as $st) {

                    $st1 = $st['st1'];
                    $min = $module->mej4;
                    $max = $module->mej5;
                    $nr1 = implode(',',$arrayNr1);

                    $sql = <<<SQL
                            select sum (case when steg9 <> 0 then steg9 else steg5 end)
                            from steg
                            where steg1 = {$st1} and steg2 in ({$nr1})
                            and steg3>='{$min}' and steg3<='{$max}';

SQL;
                    $data = Yii::app()->db->createCommand($sql)->queryRow();

                    if (! empty($data['sum'])) {
                        $vmp4 = round($data['sum'], 1);
                        $vmp1 = $vvmp->vvmp1;
                        $num  = $key + 1;

                        $sql = <<<SQL
                            update vmp set vmp4 = {$vmp4}
                            where vmp1={$vmp1} and vmp2 = {$st1} and vmp3 = {$num}
SQL;
                        $res = Yii::app()->db->createCommand($sql)->execute();
                        if (! $res)
                            throw new Exception('Can\'t update the module for vvmp1 = '.$vmp1);
                    }
                }
            }

        }
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

    public function getExtraMarks($st1, $vvmp1)
    {
        $marks = array();

        $marks['0'] = Yii::app()->db->createCommand()
                    ->select('vmp4')
                    ->from('vmp')
                    ->where('vmp1 = :VVMP1 and vmp2 = :ST1 and vmp3=0', array(
                        ':VVMP1' => $vvmp1,
                        ':ST1' => $st1,
                    ))
                    ->queryScalar();

        $vvmp = Vvmp::model()->findByPk($vvmp1);

        $marks['stus3'] = Yii::app()->db->createCommand()
                            ->select('stus3')
                            ->from('stus')
                            //->where('stus1=:ST1 and stus18=:D1 and stus19=8 and stus20=:SEM7 and stus21=:K1', array(
                            ->where('stus1=:ST1 and stus18=:D1 and stus19=8 and stus20=:SEM7', array(
                                ':ST1'  => $st1,
                                ':D1'   => $vvmp->vvmp3,
                                ':SEM7' => $vvmp->vvmp4,
                                //':K1'   => $vvmp->vvmp5,
                            ))
                            ->queryScalar();

        $marks['-1'] = Yii::app()->db->createCommand()
                    ->select('vmp4')
                    ->from('vmp')
                    ->where('vmp1 = :VVMP1 and vmp2 = :ST1 and vmp3=-1', array(
                        ':VVMP1' => $vvmp1,
                        ':ST1' => $st1,
                    ))
                    ->queryScalar();

        return $marks;

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

        $dopJoin = "inner join EL_GURNAL_ZAN({$elg->elg2},:GR1,:SEM, {$elg->elg3}) on (elgz.elgz3 = EL_GURNAL_ZAN.nom)";

        if($showInfo)
        {
            //$elg = Elg::model()->findByPk($elgz->elgz2);
            $dopColumn=',r2,elgz3, elgz4, us4';
            //$dopJoin = "inner join EL_GURNAL_ZAN({$elg->elg2},:GR1,:SEM, {$elg->elg3}) on (elgz.elgz3 = EL_GURNAL_ZAN.nom)";
        }
        $sql=<<<SQL
            SELECT elgz3 FROM elgz WHERE elgz2=:ELGZ2 AND elgz4 in (2,3,4) AND elgz3>=:ELGZ3 ORDER by elgz3 asc
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':ELGZ2', $elgz->elgz2);
        $command->bindValue(':ELGZ3', $elgz->elgz3);
        $pmkLessonNom = $command->queryScalar();

        if(empty($pmkLessonNom))
            return array();

        $sql=<<<SQL
            SELECT elgz3 FROM elgz WHERE elgz2=:ELGZ2 AND elgz4 in (2,3,4) AND elgz3<:ELGZ3 ORDER by elgz3 asc
SQL;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':ELGZ2', $elgz->elgz2);
        $command->bindValue(':ELGZ3', $elgz->elgz3);
        $pmkPrevLessonNom = $command->queryScalar();

        $dopMarks = array();
        $prevSem = 0;

        $currentYear = $currentSem = $year = $sem = 0;
        if(empty($pmkPrevLessonNom)) {
            $pmkPrevLessonNom = 0;

            list($year,$sem) = Elgz::model()->getSemYearAndSem($elgz->elgz1);
            $currentYear =$year;
            $currentSem =$sem;

            if($sem==1){
                $sem=0;
            }else{
                $year--;
            }
            $sem1 = Sem::model()->getSemestrForGroupByYearAndSem($gr1,$year,$sem);

            $uo1 = Elgz::model()->getUo1($elgz->elgz1);

            if($sem1!=0){

                $sql=<<<SQL
                      SELECT MAX(elgz3) as nom FROM elgz
                        INNER JOIN elg on (elgz2 = elg1)
                     WHERE elg3=:SEM1 AND elgz4 in (2,3,4) AND elg2=:UO1 ORDER by nom asc
SQL;
                $command = Yii::app()->db->createCommand($sql);
                $command->bindValue(':SEM1', $sem1);
                $command->bindValue(':UO1', $uo1);
                $pmkLessonNomPrevSem = $command->queryScalar();

                if(empty($pmkLessonNomPrevSem)){
                    $pmkLessonNomPrevSem =0;
                }

                $sql=<<<SQL
                      SELECT elgzst5,elgzst4,elgzst3 $dopColumn FROM elg
                          INNER JOIN elgz on (elg.elg1 = elgz.elgz2 AND  (elgz4=0 or elgz4=1)  AND elgz.elgz3>:NOM)
                          INNER JOIN elgzst on (elgzst.elgzst2 = elgz.elgz1  AND elgzst1=:ST1 )
                          $dopJoin
                      WHERE  elg4=:ELG4 and elg3=:SEM1 AND elg2=:UO1 ORDER by elgz3 asc
SQL;
                $command = Yii::app()->db->createCommand($sql);
                /*if($showInfo){*/
                    $command->bindValue(':GR1', $gr1);
                    $command->bindValue(':SEM', $sem1);
                //}
                $command->bindValue(':ST1', $st1);
                $command->bindValue(':NOM', $pmkLessonNomPrevSem);
                $command->bindValue(':SEM1', $sem1);
                $command->bindValue(':UO1', $uo1);
                $command->bindValue(':ELG4', $elg->elg4);

                //var_dump($command);

                $dopMarks = $command->queryAll();

                //var_dump($dopMarks);
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
        /*if($showInfo){*/
            $command->bindValue(':GR1', $gr1);
            $command->bindValue(':SEM', $elg->elg3);
        //}
        $marks= $command->queryAll();

        //var_dump($marks);

        $returnArray = array(
            'current'=>array(
                'year'=>$currentYear,
                'sem'=>$currentSem,
                'marks'=>$marks
            )
        );

        if(!empty($dopMarks)){
            $returnArray['prev']=array(
                'year'=>$year,
                'sem'=>$sem,
                'marks'=>$dopMarks
            );
        }

        return $returnArray;
    }

    /**
     * Пересчет оценко пмк в журнале по студенту
     * @param $st1
     * @param $elgz
     * @param $gr1
     */
    public function recalculate($st1,$elgz,$gr1){
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
            /*$sql=<<<SQL
            SELECT elgz3 FROM elgz WHERE elgz2=:ELGZ2 AND elgz4 in (2,3,4) AND elgz3<:ELGZ3 ORDER by elgz3 asc
SQL;
            $command = Yii::app()->db->createCommand($sql);
            $command->bindValue(':ELGZ2', $elgz->elgz2);
            $command->bindValue(':ELGZ3', $elgz->elgz3);
            $pmkPrevLessonNom = $command->queryScalar();

            $dopMarks = array();
            $prevSem = 0;
            if(empty($pmkPrevLessonNom)) {
                $pmkPrevLessonNom = 0;

                list($year,$sem) = Elgz::model()->getSemYearAndSem($elgz->elgz1);
                if($sem==1){
                    $sem=0;
                }else{
                    $year--;
                }
                $sem1 = Sem::model()->getSemestrForGroupByYearAndSem($gr1,$year,$sem);
                //$prevSem = $sem1;
                $uo1 = Elgz::model()->getUo1($elgz->elgz1);

                //print_r($sem1.'<br>');
                //print_r($uo1.'<br>');
                if($sem1!=0){

                    $sql=<<<SQL
                      SELECT MAX(elgz3) as nom FROM elgz
                        INNER JOIN elg on (elgz2 = elg1)
                     WHERE elg3=:SEM1 AND elgz4 in (2,3,4) AND elg2=:UO1 ORDER by nom asc
SQL;
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindValue(':SEM1', $sem1);
                    //$command->bindValue(':NOM', $elgz->elgz3);
                    $command->bindValue(':UO1', $uo1);
                    $pmkLessonNomPrevSem = $command->queryScalar();

                    //var_dump($pmkLessonNomPrevSem);
                    if(empty($pmkLessonNomPrevSem)){
                        $pmkLessonNomPrevSem =0;
                    }

                    $sql=<<<SQL
                      SELECT elgzst5,elgzst4,elgzst3 FROM elg
                          INNER JOIN elgz on (elg.elg1 = elgz.elgz2 AND  elgz4=0  AND elgz.elgz3>:NOM)
                          INNER JOIN elgzst on (elgzst.elgzst2 = elgz.elgz1  AND elgzst1=:ST1 )
                      WHERE  elg3=:SEM1 AND elg2=:UO1 ORDER by elgz3 asc
SQL;
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindValue(':ST1', $st1);
                    $command->bindValue(':NOM', $pmkLessonNomPrevSem);
                    $command->bindValue(':SEM1', $sem1);
                    $command->bindValue(':UO1', $uo1);
                    $dopMarks = $command->queryAll();

                }
            }

            $sql=<<<SQL
              SELECT elgzst5,elgzst4,elgzst3 FROM elgzst
              INNER JOIN elgz on (elgzst.elgzst2 = elgz.elgz1 AND elgz.elgz2=:ELGZ2 and elgz.elgz3>:MIN AND elgz.elgz3<:MAX)
              WHERE elgzst1=:ST1 AND (elgz4=0 or elgz4=1) ORDER by elgz3 asc
SQL;
            $command = Yii::app()->db->createCommand($sql);
            $command->bindValue(':ELGZ2', $elgz->elgz2);
            $command->bindValue(':ST1', $st1);
            $command->bindValue(':MIN', $pmkPrevLessonNom);
            $command->bindValue(':MAX', $pmkLessonNom);
            $marks= $command->queryAll();*/

            //var_dump($marks);

            //var_dump($dopMarks);

            //if(!empty($marks)){

            $marksArray = $this->getMarksFromJournal($st1,$elgz,$gr1);
            $marks = $marksArray['current']['marks'];
            $dopMarks = array();
            if(isset($marksArray['prev']))
                $dopMarks = $marksArray['prev']['marks'];

            $tek =0;

            foreach($marks as $mark){
                $bal=0;
                if($mark['elgzst3']>0){
                    $bal=$mark['elgzst5'];
                }else
                {
                    $bal=($mark['elgzst5']>0)?$mark['elgzst5']:$mark['elgzst4'];
                }
                $tek+=$bal;
            }

            $count = count($marks);

            if(!empty($dopMarks)){

                foreach($dopMarks as $mark){
                    $bal=0;
                    if($mark['elgzst3']>0){
                        $bal=$mark['elgzst5'];
                    }else
                    {
                        $bal=($mark['elgzst5']>0)?$mark['elgzst5']:$mark['elgzst4'];
                    }
                    $tek+=$bal;
                }
                $count+=count($dopMarks);
            }
            ///Для запрожья где диф зачет считаеться без перевода балов, а среднее делиться на 5 и умножаеться на 200
            $_tek = $tek;
            //var_dump($tek);
            //var_dump($count);
            $ps82 = PortalSettings::model()->findByPk(82)->ps2;
            if($ps82!=0){
                $val = $tek/$count;
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

                /*$sql = <<<SQL
                              SELECT * FROM vmp WHERE vmp2=:ST1 AND vmp1=:VMPV1
SQL;
                $command = Yii::app()->db->createCommand($sql);
                $command->bindValue(':ST1', $st1);
                $command->bindValue(':VMPV1', $module['vmpv1']);
                $vmp = $command->queryRow();*/

                //var_dump($vmp);
                if(!empty($vmp)){
                    $_elgz = Elgz::model()->findByAttributes(array('elgz2'=>$elg->elg1,'elgz3'=>$pmkLessonNom));
                    //var_dump($_elgz->elgz4);
                    if(empty($_elgz))
                        return;

                    if($_elgz->elgz4==2) {
                        $itog = $tek + $vmp['vmp6'];
                        if($vmp['vmp7']>0)
                            $itog+=$vmp['vmp7'];

                        $sql = <<<SQL
                              UPDATE vmp set vmp4=:VMP4, vmp5=:VMP5, vmp10=:VMP10, vmp12=:VMP12 WHERE vmp2=:ST1 AND vmp1=:VMPV1
SQL;
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindValue(':VMP5', $tek);
                        $command->bindValue(':VMP4', $itog);
                        $command->bindValue(':ST1', $st1);
                        $command->bindValue(':VMPV1', $module['vmpv1']);
                        $command->bindValue(':VMP12', Yii::app()->user->dbModel->p1);
                        $command->bindValue(':VMP10', date('Y-m-d H:i:s'));
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

                        $elgpmkst->elgpmkst5 = $tek;
                        $elgpmkst->save();

                    }elseif($_elgz->elgz4==3||$_elgz->elgz4==4){

                        if($_elgz->elgz4==3&&SH::getUniversityCod()==32){
                            $val = $_tek/$count;
                            //print_r($val);
                            $tek = round($val,2);

                            $tek= ($tek*200)/5;

                            $tek = round($tek);
                        }

                        $sql = <<<SQL
                              UPDATE vmp set vmp4=:VMP4, vmp5=:VMP5, vmp6=:VMP6, vmp7=:VMP7, vmp10=:VMP10, vmp12=:VMP12 WHERE vmp2=:ST1 AND vmp1=:VMPV1
SQL;
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindValue(':VMP5', 0);
                        $command->bindValue(':VMP4', $tek);
                        $command->bindValue(':VMP6', 0);
                        $command->bindValue(':VMP7', $tek);
                        $command->bindValue(':ST1', $st1);
                        $command->bindValue(':VMPV1', $module['vmpv1']);
                        $command->bindValue(':VMP12', Yii::app()->user->dbModel->p1);
                        $command->bindValue(':VMP10', date('Y-m-d H:i:s'));
                        $command->execute();
                    }
                }
            //}
        }
    }
}
