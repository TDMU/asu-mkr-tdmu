<?php

/**
 * This is the model class for table "stusv".
 *
 * The followings are the available columns in table 'stusv':
 * @property integer $stusv0
 * @property integer $stusv1
 * @property integer $stusv2
 * @property string $stusv3
 * @property string $stusv4
 * @property integer $stusv5
 * @property integer $stusv6
 * @property integer $stusv7
 * @property integer $stusv8
 * @property string $stusv9
 * @property integer $stusv10
 * @property string $stusv11
 * @property integer $stusv12
 * @property string $stusv15
 *
 * The followings are the available model relations:
 * @property Us $stusv13
 * @property Gr $stusv20
 * @property Pd $stusv60
 * @property Pd $stusv70
 * @property Pd $stusv80
 * @property I $stusv100
 * @property St[] $sts
 */
class Stusv extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'stusv';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('stusv0, stusv1, stusv2, stusv6, stusv7, stusv8, stusv10, stusv12', 'numerical', 'integerOnly'=>true),
			array('stusv3, stusv9, stusv11, stusv15', 'length', 'max'=>30),
			array('stusv4', 'length', 'max'=>60),
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
			'stusv13' => array(self::BELONGS_TO, 'Us', 'stusv1'),
			'stusv20' => array(self::BELONGS_TO, 'Gr', 'stusv2'),
			'stusv60' => array(self::BELONGS_TO, 'Pd', 'stusv6'),
			'stusv70' => array(self::BELONGS_TO, 'Pd', 'stusv7'),
			'stusv80' => array(self::BELONGS_TO, 'Pd', 'stusv8'),
			'stusv100' => array(self::BELONGS_TO, 'I', 'stusv10'),
			'sts' => array(self::MANY_MANY, 'St', 'stusvst(stusvst1, stusvst3)'),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Stusv the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Получить ведомость по журналу
	 * @param $elg Elg
	 * @param $gr1 int группа в том числе и вирутальная
	 * @return static
	 */
	public function getStusvByJournal($elg, $gr1){
		$stusv = Stusv::model()->findBySql(
				<<<SQL
        			 SELECT * FROM STUSV
			 INNER JOIN us on (stusv1 = us1)
			 WHERE us2=:UO1 and us3=:SEM1 AND stusv2=:GR1 and us4 in (5,6)
			 ORDER BY stusv3 DESC
SQL
				,array(
				':UO1'=> $elg->elg2,
				':SEM1'=> $elg->elg3,
				':GR1'=> $gr1
		));
		return $stusv;
	}

    /**
     * Получить ведомость по журналу и студенту
     * @param $elg Elg
     * @param $st1 int студент
     * @return static
     */
    public function getStusvByJournalAndStudent($elg, $st1){
        $sql = <<<SQL
            select first 1 STUSV.*
                from stusvst
                   inner join stusv on (stusvst.stusvst1 = stusv.stusv0)
                   inner join us on (stusv.stusv1 = us.us1)
                   inner join sem on (us3 = sem1)
                where stusvst3=:ST1 and us2=:UO1 and sem7>=:SEM7 and us4 in (5,6) order by sem7 asc, stusv11 DESC
SQL;

        $stusv = Stusv::model()->findBySql(
            $sql,
            array(
                ':UO1'=> $elg->elg2,
                ':SEM7'=> $elg->elg30->sem7,
                ':ST1'=> $st1
            )
        );
        return $stusv;
    }

	/**
	 * Сохранние в stusv
	 * @param $st1 int код студента
	 * @param $bal100 int бал в 100-бальной системе
	 * @param $balEts string бал в символьной системе
	 * @param $bal int бал в 5-бальной системе
	 * @return bool
	 */

	public function saveNewStusMark($st1, $bal100, $balEts, $bal){


		$stusvst = Stusvst::model()->findByAttributes(array(
				'stusvst1'=>$this->stusv0,
				'stusvst3'=>$st1
		));

		//var_dump($stusvst);

		if(empty($stusvst))
			return false;

		$stusvst->stusvst4 = $bal100;
		$stusvst->stusvst5 = $balEts;
		$stusvst->stusvst6 = $bal;

		$stusvst->stusvst7=date('Y-m-d H:i:s');
		$stusvst->stusvst8=0;
		$result = $stusvst->save();
		/*if(!$result){
			var_dump($stusvst->getErrors());
		}*/
		return $result;
	}


	/**
	 * расчет зачет оценок
	 * @param $st1 int Код студента
	 * @param $gr1 int Код группа
	 * @param $sem7 int номер семестра
	 * @param $elg Elg
	 * @param $idUniversity int код вуза
	 * @param $stusv Stusv ведомость
	 * @param $marks mixed оценки
	 * @return bool
	 */
	private function calculateZachXarkovMed($st1,$gr1,$sem7,$elg,$idUniversity,$stusv,$marks)
	{
		$sym = 0;

		$count=0;
		foreach ($marks as $mark) {
			$bal = 0;
			if ($mark['elgzst3'] > 0) {
				$bal = $mark['elgzst5'];
			} else {
				$bal = ($mark['elgzst5'] > 0) ? $mark['elgzst5'] : $mark['elgzst4'];
			}
			$sym += $bal;
			//$count++;
		}

		$sql=<<<SQL
				  SELECT COUNT(*) FROM  elgz
						inner join elg on (elgz.elgz2 = elg.elg1)
						inner join ustem on (elgz.elgz7 = ustem.ustem1)
						inner join EL_GURNAL_ZAN(:UO1,:GR1,:SEM1, :TYPE_LESSON) on (elgz.elgz3 = EL_GURNAL_ZAN.nom)
					WHERE elgz.elgz2=:ELGZ2 AND elgz.elgz4=0
SQL;
		$command = Yii::app()->db->createCommand($sql);
		$command->bindValue(':ELGZ2', $elg->elg1);
		$command->bindValue(':UO1', $elg->elg2);
		$command->bindValue(':SEM1', $elg->elg3);
		$command->bindValue(':TYPE_LESSON', $elg->elg4);
		$command->bindValue(':GR1', $gr1);
		$command->bindValue(':ST1', $st1);

		$count = $command->queryScalar();


		//print_r($elg->elg1.'<br>');
		//print_r($count.' count <br>');

		$elgsdInd = Elgsd::model()->findByAttributes(array('elgsd4'=>Elgsd::IND_TYPE));
		//$elgsdExam = Elgsd::model()->findByAttributes(array('elgsd4'=>Elgsd::EXAM_TYPE));

		$elgsdSumm = Elgsd::model()->findByAttributes(array('elgsd4'=>Elgsd::SUM_TYPE));
		$elgsdSred = Elgsd::model()->findByAttributes(array('elgsd4'=>Elgsd::SRED_TYPE));
		$elgsdPerevod1 = Elgsd::model()->findByAttributes(array('elgsd4'=>Elgsd::PEREVOD_1_TYPE));

		$elgdInd = Elgd::model()->findByAttributes(array('elgd1'=>$elg->elg1,'elgd2'=>$elgsdInd->elgsd1));
		//$elgdExam = Elgd::model()->findByAttributes(array('elgd1'=>$elg->elg1,'elgd2'=>$elgsdExam->elgsd1));

		$elgdSumm=null;
		if($elgsdSumm!=null)
			$elgdSumm= Elgd::model()->findByAttributes(array('elgd1'=>$elg->elg1,'elgd2'=>$elgsdSumm->elgsd1));

		$elgdSred=null;
		if($elgsdSred!=null)
			$elgdSred = Elgd::model()->findByAttributes(array('elgd1'=>$elg->elg1,'elgd2'=>$elgsdSred->elgsd1));

		$elgdPerevod1=null;
		if($elgsdPerevod1!=null)
			$elgdPerevod1 = Elgd::model()->findByAttributes(array('elgd1'=>$elg->elg1,'elgd2'=>$elgsdPerevod1->elgsd1));

		if($elgdInd==null/*||$elgdExam==null*/)
			return false;

		$balInd = Elgdst::model()->findByAttributes(array('elgdst1'=>$st1,'elgdst2'=>$elgdInd->elgd0));
		//$balExam = Elgdst::model()->findByAttributes(array('elgdst1'=>$st1,'elgdst2'=>$elgdExam->elgd0));

		if($balInd==null)
		{
			$balInd = new Elgdst();
			$balInd->elgdst3 = 0;
		}

		/*if($balExam==null)
		{
			$balExam = new Elgdst();
			$balExam->elgdst3 = 0;
		}*/

		if($elgsdSumm!=null) {
			if ($elgdSumm != null) {
				$balSumm = Elgdst::model()->findByAttributes(array('elgdst1'=>$st1,'elgdst2'=>$elgdSumm->elgd0));
				if (empty($balSumm)) {
					$balSumm = new Elgdst();
					$balSumm->elgdst0 = new CDbExpression('GEN_ID(GEN_elgdst, 1)');
					$balSumm->elgdst1 = $st1;
					$balSumm->elgdst2 = $elgdSumm->elgd0;
				}

				$balSumm->elgdst3 = $sym;
				$balSumm->elgdst5 = Yii::app()->user->dbModel->p1;
				$balSumm->elgdst4 = date('Y-m-d H:i:s');
				$balSumm->save();
			}
		}
		//print_r($sym.'<br>');
		/*запись среднее*/
		$sr_bal = round($sym/$count,2);
		//print_r($sr_bal.'<br>');
		/*запись среднего*/
		if($elgdSred!=null) {
			if ($elgdSred != null) {
				$balSred = Elgdst::model()->findByAttributes(array('elgdst1'=>$st1,'elgdst2'=>$elgdSred->elgd0));
				if (empty($balSred)) {
					$balSred = new Elgdst();
					$balSred->elgdst0 = new CDbExpression('GEN_ID(GEN_elgdst, 1)');
					$balSred->elgdst1 = $st1;
					$balSred->elgdst2 = $elgdSred->elgd0;
				}

				$balSred->elgdst3 = $sr_bal;
				$balSred->elgdst5 = Yii::app()->user->dbModel->p1;
				$balSred->elgdst4 = date('Y-m-d H:i:s');
				$balSred->save();
			}
		}

		$bal_per2 = $this->getBalMarkb($sr_bal,2);
		//print_r($bal_per1.'<br>');
		/*запись перевода*/
		if($elgdPerevod1!=null) {
			if ($elgdPerevod1 != null) {
				$balPerevod1 = Elgdst::model()->findByAttributes(array('elgdst1'=>$st1,'elgdst2'=>$elgdPerevod1->elgd0));
				if (empty($balPerevod1)) {
					$balPerevod1 = new Elgdst();
					$balPerevod1->elgdst0 = new CDbExpression('GEN_ID(GEN_elgdst, 1)');
					$balPerevod1->elgdst1 = $st1;
					$balPerevod1->elgdst2 = $elgdPerevod1->elgd0;
				}

				$balPerevod1->elgdst3 = $bal_per2;
				$balPerevod1->elgdst5 = Yii::app()->user->dbModel->p1;
				$balPerevod1->elgdst4 = date('Y-m-d H:i:s');
				$balPerevod1->save();
			}
		}

		$bal_itog = $bal_per2+$balInd->elgdst3;
		if($bal_itog>=120){

            if($bal_itog>200)
                $bal_itog=200;

			$mark_itog = -1;
			$bal_itog_2='FX';

			if($bal_itog>=180&&$bal_itog<=200){
				$bal_itog_2='A';
			}elseif($bal_itog>=160&&$bal_itog<=179){
				$bal_itog_2='B';
			}elseif($bal_itog>=150&&$bal_itog<=159){
				$bal_itog_2='C';
			}elseif($bal_itog>=130&&$bal_itog<=149){
				$bal_itog_2='D';
			}elseif($bal_itog>=120&&$bal_itog<=129){
				$bal_itog_2='E';
			}
			//$bal_itog_1 = $this->getBalMarkb($bal_itog,2);


		}else{
			$mark_itog = 0;
			$bal_itog_2='FX';
		}

		/*$stus->stus3 = $bal_itog;
		$stus->stus11 =$bal_itog_2;
		$stus->stus8 = $mark_itog;

		if(!$stus->save())
			print_r($stus->getErrors());
		else{*/
		if($stusv->saveNewStusMark($st1, $bal_itog,$bal_itog_2,$mark_itog)){
			return true;
		}
		//}
		return false;
	}

    /**
     * расчет зачет оценок Фарма
     * @param $st1 int Код студента
     * @param $gr1 int Код группа
     * @param $sem7 int номер семестра
     * @param $elg Elg
     * @param $idUniversity int код вуза
     * @param $stusv Stusv ведомость
     * @param $marks mixed оценки
     * @return bool
     */
    private function calculateFarm($st1,$gr1,$sem7,$elg,$idUniversity,$stusv,$marks)
    {
        $sym = 0;

        $count=0;
        foreach ($marks as $mark) {
            $bal = 0;
            if ($mark['elgzst3'] > 0) {
                $bal = $mark['elgzst5'];
            } else {
                $bal = ($mark['elgzst5'] > 0) ? $mark['elgzst5'] : $mark['elgzst4'];
            }
            $sym += $bal;
            //$count++;
        }

        $elgsdSumm = Elgsd::model()->findByAttributes(array('elgsd4'=>Elgsd::SUM_TYPE));

        $elgsdInd = Elgsd::model()->findByAttributes(array('elgsd4'=>Elgsd::IND_TYPE));
        $elgsdExam = Elgsd::model()->findByAttributes(array('elgsd4'=>Elgsd::EXAM_TYPE));

        $balInd = $balExam = null;

        if($elgsdInd!=null) {
            $elgdInd = Elgd::model()->findByAttributes(array('elgd1' => $elg->elg1, 'elgd2' => $elgsdInd->elgsd1));
            if($elgdInd!=null)
                $balInd = Elgdst::model()->findByAttributes(array('elgdst1'=>$st1,'elgdst2'=>$elgdInd->elgd0));
        }

        if($elgsdExam!=null) {
            $elgdExam = Elgd::model()->findByAttributes(array('elgd1'=>$elg->elg1,'elgd2'=>$elgsdExam->elgsd1));
            if($elgdExam!=null)
                $balExam = Elgdst::model()->findByAttributes(array('elgdst1'=>$st1,'elgdst2'=>$elgdExam->elgd0));
        }

        if($balInd==null)
        {
            $balInd = new Elgdst();
            $balInd->elgdst3 = 0;
        }

        if($balExam==null)
        {
            $balExam = new Elgdst();
            $balExam->elgdst3 = 0;
        }


        $elgdSumm=null;
        if($elgsdSumm!=null)
            $elgdSumm= Elgd::model()->findByAttributes(array('elgd1'=>$elg->elg1,'elgd2'=>$elgsdSumm->elgsd1));

        if($elgsdSumm!=null) {
            if ($elgdSumm != null) {
                $balSumm = Elgdst::model()->findByAttributes(array('elgdst1'=>$st1,'elgdst2'=>$elgdSumm->elgd0));
                if (empty($balSumm)) {
                    $balSumm = new Elgdst();
                    $balSumm->elgdst0 = new CDbExpression('GEN_ID(GEN_elgdst, 1)');
                    $balSumm->elgdst1 = $st1;
                    $balSumm->elgdst2 = $elgdSumm->elgd0;
                }

                $balSumm->elgdst3 = $sym;
                $balSumm->elgdst5 = Yii::app()->user->dbModel->p1;
                $balSumm->elgdst4 = date('Y-m-d H:i:s');
                $balSumm->save();
            }
        }

        $sym=$sym+$balInd->elgdst3+$balExam->elgdst3;

        $bal_itog = round($sym);

        $arr = Cxmb::model()->getMark($bal_itog);
        $cxmb3 = $arr['cxmb3'];
        $cxmb2= $arr['cxmb2'];

        if($stusv->saveNewStusMark($st1, $bal_itog,$cxmb3 ,$cxmb2)){
            return true;
        }
        //}
        return false;
    }

    /**
     * расчет оценок ирпени
     * @param $st1 int Код студента
     * @param $gr1 int Код группа
     * @param $sem7 int номер семестра
     * @param $elg Elg
     * @param $idUniversity int код вуза
     * @param $stusv Stusv ведомость
     * @param $marks mixed оценки
     * @return bool
     */
    private function calculateZachIrpen($st1,$gr1,$sem7,$elg,$idUniversity,$stusv,$marks)
    {
        $sym = 0;

        $count=0;
        foreach ($marks as $mark) {
            $bal = 0;
            if ($mark['elgzst3'] > 0) {
                $bal = $mark['elgzst5'];
            } else {
                $bal = ($mark['elgzst5'] > 0) ? $mark['elgzst5'] : $mark['elgzst4'];
            }
            $sym += $bal;
            //$count++;
        }

        $elgsdSumm = Elgsd::model()->findByAttributes(array('elgsd4'=>Elgsd::SUM_TYPE));

        $elgsdInd = Elgsd::model()->findByAttributes(array('elgsd4'=>Elgsd::IND_TYPE));
        $elgsdExam = Elgsd::model()->findByAttributes(array('elgsd4'=>Elgsd::EXAM_TYPE));

        $balInd = $balExam = null;

        if($elgsdInd!=null) {
            $elgdInd = Elgd::model()->findByAttributes(array('elgd1' => $elg->elg1, 'elgd2' => $elgsdInd->elgsd1));
            if($elgdInd!=null)
                $balInd = Elgdst::model()->findByAttributes(array('elgdst1'=>$st1,'elgdst2'=>$elgdInd->elgd0));
        }

        if($elgsdExam!=null) {
            $elgdExam = Elgd::model()->findByAttributes(array('elgd1'=>$elg->elg1,'elgd2'=>$elgsdExam->elgsd1));
            if($elgdExam!=null)
                $balExam = Elgdst::model()->findByAttributes(array('elgdst1'=>$st1,'elgdst2'=>$elgdExam->elgd0));
        }


        if($balInd==null)
        {
            $balInd = new Elgdst();
            $balInd->elgdst3 = 0;
        }

        if($balExam==null)
        {
            $balExam = new Elgdst();
            $balExam->elgdst3 = 0;
        }


        $elgdSumm=null;
        if($elgsdSumm!=null)
            $elgdSumm= Elgd::model()->findByAttributes(array('elgd1'=>$elg->elg1,'elgd2'=>$elgsdSumm->elgsd1));

        if($elgsdSumm!=null) {
            if ($elgdSumm != null) {
                $balSumm = Elgdst::model()->findByAttributes(array('elgdst1'=>$st1,'elgdst2'=>$elgdSumm->elgd0));
                if (empty($balSumm)) {
                    $balSumm = new Elgdst();
                    $balSumm->elgdst0 = new CDbExpression('GEN_ID(GEN_elgdst, 1)');
                    $balSumm->elgdst1 = $st1;
                    $balSumm->elgdst2 = $elgdSumm->elgd0;
                }

                $balSumm->elgdst3 = $sym;
                $balSumm->elgdst5 = Yii::app()->user->dbModel->p1;
                $balSumm->elgdst4 = date('Y-m-d H:i:s');
                $balSumm->save();
            }
        }

        $sym=$sym+$balInd->elgdst3+$balExam->elgdst3;

        $bal_itog = round($sym);

        $arr = Cxmb::model()->getMark($bal_itog);
        $cxmb3 = $arr['cxmb3'];
        $cxmb2= $arr['cxmb2'];

        $cxmb2 = $bal_itog>= 50?-1:0;

        if($stusv->saveNewStusMark($st1, $bal_itog,$cxmb3 ,$cxmb2)){
            return true;
        }

        return false;
    }

	/**
	 * расчет зачет оценок Сумской аграный
	 * @param $st1 int Код студента
	 * @param $gr1 int Код группа
	 * @param $sem7 int номер семестра
	 * @param $elg Elg
	 * @param $idUniversity int код вуза
	 * @param $stusv Stusv ведомость
	 * @param $marks mixed оценки
	 * @return bool
	 */
	private function calculateSymAgr($st1,$gr1,$sem7,$elg,$idUniversity,$stusv,$marks)
	{
		$sym = 0;

		$count=0;
		foreach ($marks as $mark) {
			$bal = 0;
			if ($mark['elgzst3'] > 0) {
				$bal = $mark['elgzst5'];
			} else {
				$bal = ($mark['elgzst5'] > 0) ? $mark['elgzst5'] : $mark['elgzst4'];
			}
			$sym += $bal;
			//$count++;
		}

		$elgsdInd = Elgsd::model()->findByAttributes(array('elgsd4'=>Elgsd::IND_TYPE));
		//$elgsdExam = Elgsd::model()->findByAttributes(array('elgsd4'=>Elgsd::EXAM_TYPE));

		$elgsdSumm = Elgsd::model()->findByAttributes(array('elgsd4'=>Elgsd::SUM_TYPE));

		$elgdInd = Elgd::model()->findByAttributes(array('elgd1'=>$elg->elg1,'elgd2'=>$elgsdInd->elgsd1));

		$elgdSumm=null;
		if($elgsdSumm!=null)
			$elgdSumm= Elgd::model()->findByAttributes(array('elgd1'=>$elg->elg1,'elgd2'=>$elgsdSumm->elgsd1));

		if($elgdInd==null/*||$elgdExam==null*/)
			return false;

		$balInd = Elgdst::model()->findByAttributes(array('elgdst1'=>$st1,'elgdst2'=>$elgdInd->elgd0));
		//$balExam = Elgdst::model()->findByAttributes(array('elgdst1'=>$st1,'elgdst2'=>$elgdExam->elgd0));

		if($balInd==null)
		{
			$balInd = new Elgdst();
			$balInd->elgdst3 = 0;
		}


		if($elgsdSumm!=null) {
			if ($elgdSumm != null) {
				$balSumm = Elgdst::model()->findByAttributes(array('elgdst1'=>$st1,'elgdst2'=>$elgdSumm->elgd0));
				if (empty($balSumm)) {
					$balSumm = new Elgdst();
					$balSumm->elgdst0 = new CDbExpression('GEN_ID(GEN_elgdst, 1)');
					$balSumm->elgdst1 = $st1;
					$balSumm->elgdst2 = $elgdSumm->elgd0;
				}

				$balSumm->elgdst3 = $sym;
				$balSumm->elgdst5 = Yii::app()->user->dbModel->p1;
				$balSumm->elgdst4 = date('Y-m-d H:i:s');
				$balSumm->save();
			}
		}

		$bal_itog = $sym+$balInd->elgdst3;

        $arr = Cxmb::model()->getMark($bal_itog);
        $cxmb3 = $arr['cxmb3'];
        $cxmb2= $arr['cxmb2'];

		if($stusv->saveNewStusMark($st1, $bal_itog,$cxmb3 ,$cxmb2)){
			return true;
		}
		//}
		return false;
	}

	/**
     * расчет оценок ирпени
     * @param $st1 int Код студента
     * @param $gr1 int Код группа
     * @param $sem7 int номер семестра
     * @param $elg Elg
     * @param $idUniversity int код вуза
     * @param $stusv Stusv ведомость
     * @param $marks mixed оценки
     * @return bool
     */
    private function calculateIrpen($st1,$gr1,$sem7,$elg,$idUniversity,$stusv,$marks)
    {
        $sym = 0;

        $count=0;
        foreach ($marks as $mark) {
            $bal = 0;
            if ($mark['elgzst3'] > 0) {
                $bal = $mark['elgzst5'];
            } else {
                $bal = ($mark['elgzst5'] > 0) ? $mark['elgzst5'] : $mark['elgzst4'];
            }
            $sym += $bal;
            //$count++;
        }

        $elgsdSumm = Elgsd::model()->findByAttributes(array('elgsd4'=>Elgsd::SUM_TYPE));

        $elgsdInd = Elgsd::model()->findByAttributes(array('elgsd4'=>Elgsd::IND_TYPE));
        $elgsdExam = Elgsd::model()->findByAttributes(array('elgsd4'=>Elgsd::EXAM_TYPE));

        $balInd = $balExam = null;

        if($elgsdInd!=null) {
            $elgdInd = Elgd::model()->findByAttributes(array('elgd1' => $elg->elg1, 'elgd2' => $elgsdInd->elgsd1));
            if($elgdInd!=null)
                $balInd = Elgdst::model()->findByAttributes(array('elgdst1'=>$st1,'elgdst2'=>$elgdInd->elgd0));
        }

        if($elgsdExam!=null) {
            $elgdExam = Elgd::model()->findByAttributes(array('elgd1'=>$elg->elg1,'elgd2'=>$elgsdExam->elgsd1));
            if($elgdExam!=null)
                $balExam = Elgdst::model()->findByAttributes(array('elgdst1'=>$st1,'elgdst2'=>$elgdExam->elgd0));
        }


        if($balInd==null)
        {
            $balInd = new Elgdst();
            $balInd->elgdst3 = 0;
        }

        if($balExam==null)
        {
            $balExam = new Elgdst();
            $balExam->elgdst3 = 0;
        }


        $elgdSumm=null;
        if($elgsdSumm!=null)
            $elgdSumm= Elgd::model()->findByAttributes(array('elgd1'=>$elg->elg1,'elgd2'=>$elgsdSumm->elgsd1));

        if($elgsdSumm!=null) {
            if ($elgdSumm != null) {
                $balSumm = Elgdst::model()->findByAttributes(array('elgdst1'=>$st1,'elgdst2'=>$elgdSumm->elgd0));
                if (empty($balSumm)) {
                    $balSumm = new Elgdst();
                    $balSumm->elgdst0 = new CDbExpression('GEN_ID(GEN_elgdst, 1)');
                    $balSumm->elgdst1 = $st1;
                    $balSumm->elgdst2 = $elgdSumm->elgd0;
                }

                $balSumm->elgdst3 = $sym;
                $balSumm->elgdst5 = Yii::app()->user->dbModel->p1;
                $balSumm->elgdst4 = date('Y-m-d H:i:s');
                $balSumm->save();
            }
        }

        $sym=$sym+$balInd->elgdst3+$balExam->elgdst3;

        $bal_itog = round($sym);

        $arr = Cxmb::model()->getMark($bal_itog);
        $cxmb3 = $arr['cxmb3'];
        $cxmb2= $arr['cxmb2'];

        if($stusv->saveNewStusMark($st1, $bal_itog,$cxmb3 ,$cxmb2)){
            return true;
        }
        //}
        return false;
    }
    /**
     * расчет оценок фарм
     * @param $st1 int Код студента
     * @param $gr1 int Код группа
     * @param $sem7 int номер семестра
     * @param $elg Elg
     * @param $idUniversity int код вуза
     * @param $stusv Stusv ведомость
     * @param $marks mixed оценки
     * @return bool
     */
    private function calculateZachFarm($st1,$gr1,$sem7,$elg,$idUniversity,$stusv,$marks)
    {
        $sym = 0;

        $count=0;
        foreach ($marks as $mark) {
            $bal = 0;
            if ($mark['elgzst3'] > 0) {
                $bal = $mark['elgzst5'];
            } else {
                $bal = ($mark['elgzst5'] > 0) ? $mark['elgzst5'] : $mark['elgzst4'];
            }
            $sym += $bal;
            //$count++;
        }

        $elgsdSumm = Elgsd::model()->findByAttributes(array('elgsd4'=>Elgsd::SUM_TYPE));

        $elgsdInd = Elgsd::model()->findByAttributes(array('elgsd4'=>Elgsd::IND_TYPE));
        $elgsdExam = Elgsd::model()->findByAttributes(array('elgsd4'=>Elgsd::EXAM_TYPE));

        $balInd = $balExam = null;

        if($elgsdInd!=null) {
            $elgdInd = Elgd::model()->findByAttributes(array('elgd1' => $elg->elg1, 'elgd2' => $elgsdInd->elgsd1));
            if($elgdInd!=null)
                $balInd = Elgdst::model()->findByAttributes(array('elgdst1'=>$st1,'elgdst2'=>$elgdInd->elgd0));
        }

        if($elgsdExam!=null) {
            $elgdExam = Elgd::model()->findByAttributes(array('elgd1'=>$elg->elg1,'elgd2'=>$elgsdExam->elgsd1));
            if($elgdExam!=null)
                $balExam = Elgdst::model()->findByAttributes(array('elgdst1'=>$st1,'elgdst2'=>$elgdExam->elgd0));
        }


        if($balInd==null)
        {
            $balInd = new Elgdst();
            $balInd->elgdst3 = 0;
        }

        if($balExam==null)
        {
            $balExam = new Elgdst();
            $balExam->elgdst3 = 0;
        }


        $elgdSumm=null;
        if($elgsdSumm!=null)
            $elgdSumm= Elgd::model()->findByAttributes(array('elgd1'=>$elg->elg1,'elgd2'=>$elgsdSumm->elgsd1));

        if($elgsdSumm!=null) {
            if ($elgdSumm != null) {
                $balSumm = Elgdst::model()->findByAttributes(array('elgdst1'=>$st1,'elgdst2'=>$elgdSumm->elgd0));
                if (empty($balSumm)) {
                    $balSumm = new Elgdst();
                    $balSumm->elgdst0 = new CDbExpression('GEN_ID(GEN_elgdst, 1)');
                    $balSumm->elgdst1 = $st1;
                    $balSumm->elgdst2 = $elgdSumm->elgd0;
                }

                $balSumm->elgdst3 = $sym;
                $balSumm->elgdst5 = Yii::app()->user->dbModel->p1;
                $balSumm->elgdst4 = date('Y-m-d H:i:s');
                $balSumm->save();
            }
        }

        $sym=$sym+$balInd->elgdst3+$balExam->elgdst3;

        $bal_itog = round($sym);

        $arr = Cxmb::model()->getMark($bal_itog);
        $cxmb3 = $arr['cxmb3'];
        $cxmb2= $arr['cxmb2'];

        $cxmb2 = $bal_itog>=60?-1:0;

        if($stusv->saveNewStusMark($st1, $bal_itog,$cxmb3 ,$cxmb2)){
            return true;
        }

        return false;
    }
	/*функция для переводов балов */
	private function getBalMarkb($bal,$type){
		$sql = <<<SQL
                              SELECT max(markb3) FROM markb WHERE markb2<=:BAL AND markb4=:TYPE
SQL;
		$command = Yii::app()->db->createCommand($sql);
		$command->bindValue(':BAL', $bal);
		$command->bindValue(':TYPE', $type);
		$mark = $command->queryScalar();
		//print_r($mark);
		if(!empty($mark)){
			$bal_per = $mark;
		}else {
			$bal_per = 0;
		}

		return $bal_per;
	}

	/**
	 * расчет єкзаменационніх оценок
	 * @param $st1 int Код студента
	 * @param $gr1 int Код группа
	 * @param $sem7 int номер семестра
	 * @param $elg Elg
	 * @param $idUniversity int код вуза
	 * @param $stusv Stusv ведомость
	 * @param $marks mixed оценки
	 * @return bool
     * @throws
	 */
	private function calculateExamXarkovMed($st1,$gr1,$sem7,$elg,$idUniversity,$stusv,$marks){
		//var_dump($marks);
		if(!empty($marks)) {
			$sym = 0;

			$count=0;
			foreach ($marks as $mark) {
				$bal = 0;
				if ($mark['elgzst3'] > 0) {
					$bal = $mark['elgzst5'];
				} else {
					$bal = ($mark['elgzst5'] > 0) ? $mark['elgzst5'] : $mark['elgzst4'];
				}
				$sym += $bal;
				//$count++;
			}

			$sql=<<<SQL
				  	SELECT COUNT(*) FROM  elgz
						inner join elg on (elgz.elgz2 = elg.elg1)
						inner join ustem on (elgz.elgz7 = ustem.ustem1)
						inner join EL_GURNAL_ZAN(:UO1,:GR1,:SEM1, :TYPE_LESSON) on (elgz.elgz3 = EL_GURNAL_ZAN.nom)
					WHERE elgz.elgz2=:ELGZ2 AND elgz.elgz4=0
SQL;
			$command = Yii::app()->db->createCommand($sql);
			$command->bindValue(':ELGZ2', $elg->elg1);
			$command->bindValue(':UO1', $elg->elg2);
			$command->bindValue(':SEM1', $elg->elg3);
			$command->bindValue(':TYPE_LESSON', $elg->elg4);
			$command->bindValue(':GR1', $gr1);
			$command->bindValue(':ST1', $st1);

			$count = $command->queryScalar();

			//var_dump($count);

			$elgsdInd = Elgsd::model()->findByAttributes(array('elgsd4'=>Elgsd::IND_TYPE));
			$elgsdExam = Elgsd::model()->findByAttributes(array('elgsd4'=>Elgsd::EXAM_TYPE));

			$elgsdSumm = Elgsd::model()->findByAttributes(array('elgsd4'=>Elgsd::SUM_TYPE));
			$elgsdSred = Elgsd::model()->findByAttributes(array('elgsd4'=>Elgsd::SRED_TYPE));
			$elgsdPerevod1 = Elgsd::model()->findByAttributes(array('elgsd4'=>Elgsd::PEREVOD_1_TYPE));

			$elgdInd = Elgd::model()->findByAttributes(array('elgd1'=>$elg->elg1,'elgd2'=>$elgsdInd->elgsd1));
			$elgdExam = Elgd::model()->findByAttributes(array('elgd1'=>$elg->elg1,'elgd2'=>$elgsdExam->elgsd1));

			//var_dump($elgdInd);
			//var_dump($elgdExam);

			$elgdSumm=null;
			if($elgsdSumm!=null)
				$elgdSumm= Elgd::model()->findByAttributes(array('elgd1'=>$elg->elg1,'elgd2'=>$elgsdSumm->elgsd1));

			$elgdSred=null;
			if($elgsdSred!=null)
				$elgdSred = Elgd::model()->findByAttributes(array('elgd1'=>$elg->elg1,'elgd2'=>$elgsdSred->elgsd1));

			$elgdPerevod1=null;
			if($elgsdPerevod1!=null)
				$elgdPerevod1 = Elgd::model()->findByAttributes(array('elgd1'=>$elg->elg1,'elgd2'=>$elgsdPerevod1->elgsd1));

			if($elgdInd==null||$elgdExam==null)
				return false;

			$balInd = Elgdst::model()->findByAttributes(array('elgdst1'=>$st1,'elgdst2'=>$elgdInd->elgd0));
			$balExam = Elgdst::model()->findByAttributes(array('elgdst1'=>$st1,'elgdst2'=>$elgdExam->elgd0));

			if($balInd==null)
			{
				$balInd = new Elgdst();
				$balInd->elgdst3 = 0;
			}

			if($balExam==null)
			{
				$balExam = new Elgdst();
				$balExam->elgdst3 = 0;
			}

			//print_r($sym.'<br>');
			/*если сумма балов +инд работа >120 то отнимаем разницу от суммы балов*/
			/*if($sym+$balInd->elgdst3>120){
                $sym-=$sym+$balInd->elgdst3-120;
            }*/

			/*запись суммы*/
			if($elgsdSumm!=null) {
				if ($elgdSumm != null) {
					$balSumm = Elgdst::model()->findByAttributes(array('elgdst1'=>$st1,'elgdst2'=>$elgdSumm->elgd0));
					if (empty($balSumm)) {
						$balSumm = new Elgdst();
						$balSumm->elgdst0 = new CDbExpression('GEN_ID(GEN_elgdst, 1)');
						$balSumm->elgdst1 = $st1;
						$balSumm->elgdst2 = $elgdSumm->elgd0;
					}

					$balSumm->elgdst3 = $sym;
					$balSumm->elgdst5 = Yii::app()->user->dbModel->p1;
					$balSumm->elgdst4 = date('Y-m-d H:i:s');
					$balSumm->save();
				}
			}
			//print_r($sym.'<br>');
			/*запись среднее*/
			$sr_bal = round($sym/$count,2);
			//print_r($count.'<br>');
			//print_r($sr_bal.'<br>');
			/*запись среднего*/
			if($elgdSred!=null) {
				if ($elgdSred != null) {
					$balSred = Elgdst::model()->findByAttributes(array('elgdst1'=>$st1,'elgdst2'=>$elgdSred->elgd0));
					if (empty($balSred)) {
						$balSred = new Elgdst();
						$balSred->elgdst0 = new CDbExpression('GEN_ID(GEN_elgdst, 1)');
						$balSred->elgdst1 = $st1;
						$balSred->elgdst2 = $elgdSred->elgd0;
					}

					$balSred->elgdst3 = $sr_bal;
					$balSred->elgdst5 = Yii::app()->user->dbModel->p1;
					$balSred->elgdst4 = date('Y-m-d H:i:s');
					$balSred->save();
				}
			}
			//var_dump($sr_bal);
			$bal_per1 = $this->getBalMarkb($sr_bal,1);

			//print_r($bal_per1.'<br>');
			/*запись перевода*/
			if($elgdPerevod1!=null) {
				if ($elgdPerevod1 != null) {
					$balPerevod1 = Elgdst::model()->findByAttributes(array('elgdst1'=>$st1,'elgdst2'=>$elgdPerevod1->elgd0));
					if (empty($balPerevod1)) {
						$balPerevod1 = new Elgdst();
						$balPerevod1->elgdst0 = new CDbExpression('GEN_ID(GEN_elgdst, 1)');
						$balPerevod1->elgdst1 = $st1;
						$balPerevod1->elgdst2 = $elgdPerevod1->elgd0;
					}

					$balPerevod1->elgdst3 = $bal_per1;
					$balPerevod1->elgdst5 = Yii::app()->user->dbModel->p1;
					$balPerevod1->elgdst4 = date('Y-m-d H:i:s');
					$balPerevod1->save();
				}
			}

			$bal_itog = 0;


			if($bal_per1+$balInd->elgdst3>120){
				$bal_per1-=$bal_per1+$balInd->elgdst3-120;
			}

			if($bal_per1+$balInd->elgdst3>=70&&$bal_per1+$balInd->elgdst3<=120){
				$bal_itog=$bal_per1+$balInd->elgdst3+$balExam->elgdst3;
				if($bal_itog>200)
					$bal_itog=200;

				$bal_itog_1='FX';

				if($bal_itog>=180&&$bal_itog<=200){
					$bal_itog_1='A';
				}elseif($bal_itog>=160&&$bal_itog<=179){
					$bal_itog_1='B';
				}elseif($bal_itog>=150&&$bal_itog<=159){
					$bal_itog_1='C';
				}elseif($bal_itog>=130&&$bal_itog<=149){
					$bal_itog_1='D';
				}elseif($bal_itog>=120&&$bal_itog<=129){
					$bal_itog_1='E';
				}
				//$bal_itog_1 = $this->getBalMarkb($bal_itog,2);
				/*$stus->stus3 = $bal_itog;
                $stus->stus11 =$bal_itog_1;*/
				$cxmb= Cxmb::model()->getMarkByECTS($bal_itog_1);
				//print_r($cxmb);
				//$stus->stus8 = $cxmb['cxmb2'];
				/*if(!$stus->save())
                    print_r($stus->getErrors());
                else{

                }*/
				//var_dump($stusv);

				if($stusv->saveNewStusMark($st1,$bal_itog, $bal_itog_1,$cxmb['cxmb2'])){
					return true;
				}

				return false;
			}else
				return true;
		}
	}

	/**
	 * Пересчет для харьковского меда
	 * @param $st1 int Код студента
	 * @param $gr1 int Код группы
	 * @param $sem7 int номер семстра
	 * @param $elg Elg
	 * @param $idUniversity int еод университета
	 * @param $stusv Stusv
	 * @param $marks mixed оценки
	 * @return bool
	 */
	private function recalculateXarcovMed($st1,$gr1,$sem7,$elg,$idUniversity,$stusv,$marks){
		$us = Us::model()->getUsByStusvFromJournal($elg);
		//var_dump($us);
		if(empty($us))
			return false;
		switch($us->us4){
			/*эКЗАМЕН*/
			case 5:
				return $this->calculateExamXarkovMed($st1,$gr1,$sem7,$elg,$idUniversity,$stusv,$marks);
				break;
			/*ЗАЧЕТ ИЛИ ДИФЗАЧЕТ*/
			case 6:
                if($us->us6==1)//ЗАЧЕТ
				    return $this->calculateZachXarkovMed($st1,$gr1,$sem7,$elg,$idUniversity,$stusv,$marks);
                elseif($us->us6==2)//ДИФЗАЧЕТ
                    return $this->calculateExamXarkovMed($st1,$gr1,$sem7,$elg,$idUniversity,$stusv,$marks);
				break;
		}
		return false;
	}
    /**
     * Пересчет для ирпеня
     * @param $st1 int Код студента
     * @param $gr1 int Код группы
     * @param $sem7 int номер семстра
     * @param $elg Elg
     * @param $idUniversity int еод университета
     * @param $stusv Stusv
     * @param $marks mixed оценки
     * @return bool
     */
    private function recalculateIrpen($st1,$gr1,$sem7,$elg,$idUniversity,$stusv,$marks){
        $us = Us::model()->getUsByStusvFromJournal($elg);
        //var_dump($us);
        if(empty($us))
            return false;
        switch($us->us4){
            /*эКЗАМЕН*/
            case 5:
                return $this->calculateIrpen($st1,$gr1,$sem7,$elg,$idUniversity,$stusv,$marks);
                break;
            /*ЗАЧЕТ ИЛИ ДИФЗАЧЕТ*/
            case 6:
                if($us->us6==1)//ЗАЧЕТ
                {
                    return $this->calculateZachIrpen($st1,$gr1,$sem7,$elg,$idUniversity,$stusv,$marks);
                }	//return $this->calculateZachXarkovMed($st1,$gr1,$sem7,$elg,$idUniversity,$stusv,$marks);
                elseif($us->us6==2)//ДИФЗАЧЕТ
                    return $this->calculateIrpen($st1,$gr1,$sem7,$elg,$idUniversity,$stusv,$marks);
                break;
        }
        return false;
    }
	/**
	* Пересчет для Сумм
	* @param $st1 int Код студента
	* @param $gr1 int Код группы
	* @param $sem7 int номер семстра
	* @param $elg Elg
	* @param $idUniversity int еод университета
	* @param $stusv Stusv
	* @param $marks mixed оценки
	* @return bool
	*/
	private function recalculateSymAgr($st1,$gr1,$sem7,$elg,$idUniversity,$stusv,$marks){
		$us = Us::model()->getUsByStusvFromJournal($elg);
		//var_dump($us);
		if(empty($us))
			return false;
		switch($us->us4){
			/*эКЗАМЕН*/
			case 5:
				return $this->calculateSymAgr($st1,$gr1,$sem7,$elg,$idUniversity,$stusv,$marks);
				break;
			/*ЗАЧЕТ ИЛИ ДИФЗАЧЕТ*/
			case 6:
				if($us->us6==1)//ЗАЧЕТ
				{

				}	//return $this->calculateZachXarkovMed($st1,$gr1,$sem7,$elg,$idUniversity,$stusv,$marks);
				elseif($us->us6==2)//ДИФЗАЧЕТ
					return $this->calculateSymAgr($st1,$gr1,$sem7,$elg,$idUniversity,$stusv,$marks);
				break;
		}
		return false;
	}

    /**
     * Пересчет для Фарма
     * @param $st1 int Код студента
     * @param $gr1 int Код группы
     * @param $sem7 int номер семстра
     * @param $elg Elg
     * @param $idUniversity int еод университета
     * @param $stusv Stusv
     * @param $marks mixed оценки
     * @return bool
     */
    private function recalculateFarm($st1,$gr1,$sem7,$elg,$idUniversity,$stusv,$marks){
        $us = Us::model()->getUsByStusvFromJournal($elg);
        //var_dump($us);
        if(empty($us))
            return false;
        switch($us->us4){
            /*эКЗАМЕН*/
            case 5:
                return $this->calculateFarm($st1,$gr1,$sem7,$elg,$idUniversity,$stusv,$marks);
                break;
            /*ЗАЧЕТ ИЛИ ДИФЗАЧЕТ*/
            case 6:
                if($us->us6==1)//ЗАЧЕТ
                {
                    return $this->calculateZachFarm($st1,$gr1,$sem7,$elg,$idUniversity,$stusv,$marks);
                }	//return $this->calculateZachXarkovMed($st1,$gr1,$sem7,$elg,$idUniversity,$stusv,$marks);
                elseif($us->us6==2)//ДИФЗАЧЕТ
                    return $this->calculateFarm($st1,$gr1,$sem7,$elg,$idUniversity,$stusv,$marks);
                break;
        }
        return false;
    }

	/**
	 * пересчет итоговой оценки
	 * @param $st1 int код студента
	 * @param $gr1 int код группы
	 * @param $sem7 int  номре семетсра
	 * @param $elg Elg
	 * @return bool
	 */
	public function recalculateStusMark($st1,$gr1,$sem7,$elg){

	    if($elg->elg4!=1)
	        return false;

		$idUniversity = Yii::app()->core->universityCode;
		//var_dump($idUniversity);
		if($idUniversity===null)
			return false;

		//$stusv = Stusv::model()->getStusvByJournal($elg, $gr1);
        $stusv = Stusv::model()->getStusvByJournalAndStudent($elg, $st1);
		if($stusv == null)
		    return false;

		if(!empty($stusv->stusv15))
			return false;

		if($stusv->stusv12!=0&&!empty($stusv->stusv11)){
		    $date = date('Y-m-d H:i:s');
            $date2 = new DateTime($stusv->stusv11);
            $date1 = new DateTime($date);

            $date2->modify('+'.$stusv->stusv12.' days');

            if($date1>$date2)
                return false;
        }

		if($stusv!=null){
			$sql=<<<SQL
			  SELECT elgzst5,elgzst4,elgzst3 FROM elgzst
				  INNER JOIN elgz on (elgzst.elgzst2 = elgz.elgz1 AND elgz.elgz2=:ELGZ2 AND elgz.elgz4=0)
				  inner join elg on (elgz.elgz2 = elg.elg1)
					inner join ustem on (elgz.elgz7 = ustem.ustem1)
					inner join EL_GURNAL_ZAN(:UO1,:GR1,:SEM1, :TYPE_LESSON) on (elgz.elgz3 = EL_GURNAL_ZAN.nom)
			  WHERE elgzst1=:ST1 ORDER by elgz3 asc
SQL;
			$command = Yii::app()->db->createCommand($sql);
			$command->bindValue(':ELGZ2', $elg->elg1);
			$command->bindValue(':UO1', $elg->elg2);
			$command->bindValue(':SEM1', $elg->elg3);
			$command->bindValue(':TYPE_LESSON', $elg->elg4);
			$command->bindValue(':GR1', $gr1);
			$command->bindValue(':ST1', $st1);
			$marks= $command->queryAll();

			switch($idUniversity){
				case U_XNMU:
					return $this->recalculateXarcovMed($st1,$gr1,$sem7,$elg,$idUniversity,$stusv,$marks);
					break;
                case U_IRPEN://Ирпень
                    return $this->recalculateIrpen($st1,$gr1,$sem7,$elg,$idUniversity,$stusv,$marks);
                    break;
                case U_FARM:
                    return $this->recalculateFarm($st1,$gr1,$sem7,$elg,$idUniversity,$stusv,$marks);
                    break;
				case 1:
					return $this->recalculateSymAgr($st1,$gr1,$sem7,$elg,$idUniversity,$stusv,$marks);
					break;
			}
		}

		return false;
	}

	/**
	 * @param $st1 int студен
	 * @return Stusvst
	 */
	public function getMarkForStudent($st1)
	{
		$stusvst = Stusvst::model()->findByAttributes(array(
				'stusvst1'=>$this->stusv0,
				'stusvst3'=>$st1
		));

		return $stusvst;
	}

	public function getMarksForStudent($st1)
	{
		if (empty($st1))
			return array();

		$sql = <<<SQL
          	select
    			d2,d27,us4,us6, stusv3, stusv4, stusvst4, stusvst5, stusvst6, sem7, stusv1,stusv11
			from stusvst
			   inner join stusv on (stusvst.stusvst1 = stusv.stusv0)
			   inner join us on (stusv.stusv1 = us.us1)
			   INNER JOIN uo on (us.us2 = uo.uo1)
			   INNER JOIN d ON (uo.uo3 = d.d1)
			   INNER JOIN sem on (us.us3 = sem.sem1)
			where stusvst3=:ST1 and stusv11 is not null
			group by d2,d27,us4,us6, stusv3, stusv4, stusvst4, stusvst5, stusvst6, sem7, stusv1,stusv11
			ORDER by sem7 ASC, d2 collate UNICODE, stusv11 desc
SQL;

		$command = Yii::app()->db->createCommand($sql);
		$command->bindValue(':ST1', $st1);
		$marks = $command->queryAll();

		$res = array();

		foreach ($marks as $mark) {
			if(!isset($res[$mark['stusv1']])){
				$res[$mark['stusv1']] = $mark;
			}
		}

		return $res;
	}

	public function getDisciplineExam($st1,$gr1)
	{
		$sql = <<<SQL
		select
			d2,us4,us6, stusv3, stusv4, stusvst4, stusvst5, stusvst6,sem7, stusv1,stusv11, stusv9
		from stusvst
			inner join stusv on (stusvst.stusvst1 = stusv.stusv0)
		  	inner join us on (stusv.stusv1 = us.us1)
			INNER JOIN uo on (us.us2 = uo.uo1)
			INNER JOIN d ON (uo.uo3 = d.d1)
			INNER JOIN sem on (us.us3 = sem.sem1)
	  	where stusvst3=:ST1 and sem7 in
					 (select sem7
					 from gr
						inner join std on (gr.gr1 = std.std3)
						inner join st on (std.std2 = st.st1)
						inner join sg on (gr.gr2 = sg.sg1)
						inner join sem on (sg.sg1 = sem.sem2)
					 where st1={$st1} and std11<>1 and std7 is null and sem3=:YEAR and sem5=:SEM
					 group by sem7)
			group by d2,us4,us6, stusv3, stusv4, stusvst4, stusvst5, stusvst6,sem7, stusv1,stusv11, stusv9
			order by d2 COLLATE UNICODE, stusv11 desc, stusv9 desc
SQL;
		$command = Yii::app()->db->createCommand($sql);
		$command->bindValue(':GR1', $gr1);
		$command->bindValue(':ST1', $st1);
		$command->bindValue(':YEAR', Yii::app()->session['year']);
		$command->bindValue(':SEM', Yii::app()->session['sem']);
		$disciplines = $command->queryAll();

		$res = array();

		foreach ($disciplines as $discipline) {
			if(!isset($res[$discipline['stusv1']])){
				$res[$discipline['stusv1']] = $discipline;
			}
		}

		return $res;
		//return $disciplines;
	}
}
