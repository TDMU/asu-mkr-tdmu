<?php

/**
 * This is the model class for table "vvmp".
 *
 * The followings are the available columns in table 'vvmp':
 * @property integer $vvmp1
 * @property integer $vvmp2
 * @property integer $vvmp3
 * @property integer $vvmp4
 * @property integer $vvmp5
 * @property integer $vvmp6
 * @property string $vvmp7
 * @property integer $vvmp8
 * @property integer $vvmp10
 * @property integer $vvmp11
 * @property integer $vvmp12
 * @property integer $vvmp13
 * @property integer $vvmp14
 * @property integer $vvmp15
 * @property integer $vvmp16
 * @property integer $vvmp17
 * @property integer $vvmp18
 * @property integer $vvmp19
 * @property integer $vvmp20
 * @property integer $vvmp21
 */
class Vvmp extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'vvmp';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('vvmp1', 'safe'),
			array('vvmp2, vvmp3, vvmp4, vvmp5, vvmp6, vvmp8, vvmp10, vvmp11, vvmp12, vvmp13, vvmp14, vvmp15, vvmp16, vvmp17, vvmp18, vvmp19, vvmp20, vvmp21', 'numerical', 'integerOnly'=>true),
			array('vvmp7', 'length', 'max'=>8),
        );
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Vvmp the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getModuleBySt($st1)
	{
		$sql = <<<SQL
			select d1,d2,vvmp6,vvmp4,sem1,k2,vmp4,vmp5,vmp6,vmp7,vmp1, vvmp8, vvmp10, vmp9
			from (select vmp1,vmp4,vmp5,vmp6,vmp7,vmp9 from vmp where vmp2 = :ST1)
			   inner join vmpv on (vmp1 = vmpv.vmpv1)
			   inner join vvmp on (vmpv.vmpv2 = vvmp.vvmp1)
			   inner join d on (vvmp.vvmp3 = d.d1)
			   inner join k on (vvmp.vvmp5 = k.k1)
			   inner join sem on (vvmp.vvmp4 = sem.sem7) and (vvmp.vvmp25 = sem.sem2)
			where sem3=:YEAR AND sem5=:SEM
			GROUP BY d2,vvmp6,vvmp4,d1,sem1,k2,vmp4,vmp5,vmp6,vmp7,vmp1, vvmp8, vvmp10, vmp9
SQL;
		$command = Yii::app()->db->createCommand($sql);
		$command->bindValue(':ST1', $st1);
		$command->bindValue(':YEAR', Yii::app()->session['year']);
		$command->bindValue(':SEM', Yii::app()->session['sem']);
		$res = $command->queryAll();

		$modules = array();
		$max=$min=$d1=0;

		$dopColumns = array();

		foreach ($res as $val)
		{
			if($d1!=$val['d1']) {
				$d1=$val['d1'];
			}

			if($val['vvmp6']<98) {
                if ($val['vvmp6'] < $min || $min == 0)
                    $min = $val['vvmp6'];
                if ($val['vvmp6'] > $max || $max == 0)
                    $max = $val['vvmp6'];
            }else{
                if(!in_array($val['vvmp6'], $dopColumns))
                    $dopColumns[] = $val['vvmp6'];
            }

			$modules[$val['d1']]['discipline']=$val['d2'];
			$modules[$val['d1']]['chair']=$val['k2'];
			$modules[$val['d1']][$val['vvmp6']]=$val;
		}

		return array($modules,$min,$max,$dopColumns);
	}

	public function getModule($uo1,$gr1)
	{

		$sql = <<<SQL
			SELECT vvmp1,vvmp6,vvmp4 from vvmp
			INNER JOIN vmpv on (vvmp1=vmpv2)
			WHERE vvmp3=(
			SELECT  uo3 from uo where uo1=:UO1
			)
			and vmpv7=:GR1 and vvmp4 = (
			select
			   first 1 sem7
				from sem
				   inner join sg on (sem.sem2 = sg.sg1)
				   inner join gr on (sg.sg1 = gr.gr2)
				WHERE gr1={$gr1} and sem3=:YEAR and sem5=:SEM
			) and vvmp25=(
			SELECT  gr2 from gr where gr1={$gr1}
			)
			GROUP BY vvmp1,vvmp6,vvmp4 ORDER BY vvmp6 asc
SQL;
		$command = Yii::app()->db->createCommand($sql);
		$command->bindValue(':GR1', $gr1);
		$command->bindValue(':UO1', $uo1);
		$command->bindValue(':YEAR', Yii::app()->session['year']);
		$command->bindValue(':SEM', Yii::app()->session['sem']);
		$res = $command->queryAll();

		return $res;
		/*$modules = array();
		foreach ($res as $row)
		{
			$modules[$row['vvmp6']]=$row;
		}

		return $modules;*/
	}

	public function getModul($uo1,$gr1,$elgz3,$elg1, $st1)
	{
		$sql = <<<SQL
			SELECT COUNT(*) from elgz
			WHERE elgz4 in (2,3,4) AND elgz3<:NOM AND elgz2=:ELG1
SQL;
		$command = Yii::app()->db->createCommand($sql);
		$command->bindValue(':NOM', $elgz3);
		$command->bindValue(':ELG1', $elg1);
		$countModulePrev = $command->queryScalar();
		//print_r($countModulePrev);

		$sql = <<<SQL
			SELECT vvmp1, vmpv1, vmpv4, vmpv3, vmpv5, vvmp6, vmpv6, vmp1,vmp2 from vvmp
			INNER JOIN vmpv on (vvmp1=vmpv2)
			INNER JOIN vmp on (vmpv1=vmp1 and vmp2=:ST1)
			WHERE vvmp3=(
			SELECT  uo3 from uo where uo1=:UO1
			)
			/*and vmpv6 is null*/
			and vmpv7=:GR1 and vvmp4 = (
			select
			   first 1 sem7
				from sem
				   inner join sg on (sem.sem2 = sg.sg1)
				   inner join gr on (sg.sg1 = gr.gr2)
				WHERE gr1={$gr1} and sem3=:YEAR and sem5=:SEM
			) and vvmp25=(
			SELECT  gr2 from gr where gr1={$gr1}
			) ORDER by vvmp6 ASC
SQL;
		$command = Yii::app()->db->createCommand($sql);
		$command->bindValue(':GR1', $gr1);
		$command->bindValue(':UO1', $uo1);
		$command->bindValue(':ST1', $st1);
		$command->bindValue(':YEAR', Yii::app()->session['year']);
		$command->bindValue(':SEM', Yii::app()->session['sem']);
		//$command->bindValue(':NOM', $countModulePrev+1);
		$res = $command->queryAll();
		//var_dump($res);
		//var_dump($command);
		$_res = array();

		$i=0;
		foreach($res as $value){
			if(!isset($_res[$i]))
				$_res[$i] = $value;
			else{
				if($_res[$i]['vvmp1']!=$value['vvmp1']){
					$i++;
				}
				$_res[$i] = $value;
			}
		}

		//var_dump($_res);

		if(empty($_res)||!isset($_res[$countModulePrev])||!empty($_res[$countModulePrev]['vmpv6']))
			return false;

		return $_res[$countModulePrev];
	}

	public function checkModul($uo1,$gr1,$nom)
	{
	    $sql = <<<SQL
          SELECT vvmp1,vmpv1,vmpv4,vmpv3,vmpv5,vvmp6,vmpv6 from vvmp
               INNER JOIN vmpv on (vvmp1=vmpv2)
               left join vmpvf on (vvmp.vvmp1 = vmpvf.vmpvf1 and vmpvf2={$gr1})
            WHERE vvmp3=(SELECT  uo3 from uo where uo1=:UO1)
            and vmpv6 is null AND vvmp6=:NOM and vmpv7=:GR1 and vmpvf3 = (
              select first 1 sem7
              from sem
                  inner join sg on (sem.sem2 = sg.sg1)
                  inner join gr on (sg.sg1 = gr.gr2)
               WHERE gr1={$gr1} and sem3=:YEAR and sem5=:SEM
               )
            and vvmp25=(SELECT  gr2 from gr where gr1={$gr1})
SQL;

		$command = Yii::app()->db->createCommand($sql);
		$command->bindValue(':GR1', $gr1);
		$command->bindValue(':UO1', $uo1);
		$command->bindValue(':NOM', $nom);
		$command->bindValue(':YEAR', Yii::app()->session['year']);
		$command->bindValue(':SEM', Yii::app()->session['sem']);
		$res = $command->queryRow();

		return $res;
	}


}
