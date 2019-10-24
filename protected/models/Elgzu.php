<?php

/**
 * This is the model class for table "elgzu".
 *
 * The followings are the available columns in table 'elgzu':
 * @property integer $elgzu1
 * @property integer $elgzu2
 * @property integer $elgzu3
 * @property integer $elgzu4
 * @property string $elgzu5
 * @property integer $elgzu6
 *
 * The followings are the available model relations:
 * @property Gr $elgzu20
 * @property Elgz $elgzu30
 * @property Ustem $elgzu40
 */
class Elgzu extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'elgzu';
	}

	public $r1, $elgz1;
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('elgzu1', 'required'),
			array(' elgzu2, elgzu3, elgzu4, elgzu6', 'numerical', 'integerOnly'=>true),
			array('elgzu5', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('elgzu1, elgzu2, elgzu3, elgzu4, elgzu5, elgzu6', 'safe', 'on'=>'search'),
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
			'elgzu20' => array(self::BELONGS_TO, 'Gr', 'elgzu2'),
			'elgzu30' => array(self::BELONGS_TO, 'Elgz', 'elgzu3'),
			'elgzu40' => array(self::BELONGS_TO, 'Ustem', 'elgzu4'),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Elgzu the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getUstem1ByElgz1AndGroup($elgz1,$gr1,$elg){

		$sql = <<<SQL
			select elgzu4
			from elgzu
			WHERE elgzu2=:GR1 AND elgzu3=:ELGZ1
SQL;

		$command = Yii::app()->db->createCommand($sql);
		$command->bindValue(':GR1', $gr1);
		$command->bindValue(':ELGZ1', $elgz1);
		$ustem1 = $command->queryScalar();

		//print_r('--'.$ustem1.'--'.$elgz1.' '.$gr1.'--');

		if(empty($ustem1)) {
			$sql = <<<SQL
				select ustem1
				from elgz
				inner join elg on (elgz.elgz2 = elg.elg1)
				inner join ustem on (elgz.elgz7 = ustem.ustem1)
				inner join EL_GURNAL_ZAN(:UO1,:GR1,:SEM1, :TYPE_LESSON ) on (elgz.elgz3 = EL_GURNAL_ZAN.nom)
				WHERE elgz1=:ELGZ1
SQL;

			$command = Yii::app()->db->createCommand($sql);
			$command->bindValue(':UO1', $elg->elg2);
			$command->bindValue(':GR1', $gr1);
			$command->bindValue(':TYPE_LESSON', $elg->elg4);
			$command->bindValue(':SEM1', $elg->elg3);
			$command->bindValue(':ELGZ1', $elgz1);
			$ustem1 = $command->queryScalar();
		}

		//print_r('++'.$ustem1.'++');
		return $ustem1;
	}

	public function getUstemFromElgzuByElgz1AndGroup($elgz1,$gr1){

		$sql = <<<SQL
			select ustem.*
			from elgzu
			INNER JOIN ustem on (elgzu4 = ustem1)
			WHERE elgzu2=:GR1 AND elgzu3=:ELGZ1
SQL;

		$command = Yii::app()->db->createCommand($sql);
		$command->bindValue(':GR1', $gr1);
		$command->bindValue(':ELGZ1', $elgz1);
		$ustem = $command->queryRow();

		return $ustem;
	}
}
