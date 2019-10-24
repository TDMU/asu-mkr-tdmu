<?php

/**
 * This is the model class for table "uo".
 *
 * The followings are the available columns in table 'uo':
 * @property integer $uo1
 * @property integer $uo2
 * @property integer $uo3
 * @property integer $uo4
 * @property integer $uo5
 * @property integer $uo6
 * @property double $uo10
 * @property double $uo11
 * @property integer $uo12
 * @property integer $uo13
 * @property integer $uo14
 * @property integer $uo15
 * @property integer $uo16
 * @property integer $uo17
 * @property integer $uo18
 * @property integer $uo19
 * @property string $uo20
 * @property integer $uo21
 * @property integer $uo22
 * @property integer $uo23
 * @property double $uo24
 * @property double $uo25
 * @property integer $uo26
 * @property string $uo27
 * @property integer $uo28
 * @property double $uo29
 * @property integer $uo30
 * @property integer $uo33
 * @property integer $uo34
 * @property string $uo35
 * @property integer $uo36
 *
 * The followings are the available model relations:
 * @property Pvvd[] $pvvds
 * @property Sem[] $sems
 * @property Mtt[] $mtts
 * @property Uosv[] $uosvs
 * @property Us[] $uses
 * @property Elgno[] $elgnos
 * @property Elg[] $elgs
 * @property D $uo31
 * @property K $uo40
 * @property Ad $uo170
 * @property D $uo180
 * @property Ucx $uo190
 * @property I $uo210
 * @property U $uo220
 * @property K $uo300
 * @property I $uo360
 * @property Spr $uo330
 * @property Sgos[] $sgoses
 * @property Mtz[] $mtzs
 * @property Jpv[] $jpvs
 * @property Sg[] $sgs
 */
class Uo extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'uo';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uo1, uo2, uo3, uo4, uo5, uo6, uo12, uo13, uo14, uo15, uo16, uo17, uo18, uo19, uo21, uo22, uo23, uo26, uo28, uo30, uo33, uo34, uo36', 'numerical', 'integerOnly'=>true),
			array('uo10, uo11, uo24, uo25, uo29', 'numerical'),
			array('uo20, uo35', 'length', 'max'=>8),
			array('uo27', 'length', 'max'=>4),
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
			'pvvds' => array(self::HAS_MANY, 'Pvvd', 'pvvd3'),
			'sems' => array(self::MANY_MANY, 'Sem', 'mtrp(mtrp1, mtrp14)'),
			'mtts' => array(self::MANY_MANY, 'Mtt', 'mtdm(mtdm1, mtdm2)'),
			'uosvs' => array(self::HAS_MANY, 'Uosv', 'uosv1'),
			'uses' => array(self::HAS_MANY, 'Us', 'us2'),
			'elgnos' => array(self::HAS_MANY, 'Elgno', 'elgno2'),
			'elgs' => array(self::HAS_MANY, 'Elg', 'elg2'),
			'uo31' => array(self::BELONGS_TO, 'D', 'uo3'),
			'uo40' => array(self::BELONGS_TO, 'K', 'uo4'),
			'uo170' => array(self::BELONGS_TO, 'Ad', 'uo17'),
			'uo180' => array(self::BELONGS_TO, 'D', 'uo18'),
			'uo190' => array(self::BELONGS_TO, 'Ucx', 'uo19'),
			'uo210' => array(self::BELONGS_TO, 'I', 'uo21'),
			'uo220' => array(self::BELONGS_TO, 'U', 'uo22'),
			'uo300' => array(self::BELONGS_TO, 'K', 'uo30'),
			'uo360' => array(self::BELONGS_TO, 'I', 'uo36'),
			'uo330' => array(self::BELONGS_TO, 'Spr', 'uo33'),
			'sgoses' => array(self::HAS_MANY, 'Sgos', 'sgos15'),
			'mtzs' => array(self::HAS_MANY, 'Mtz', 'mtz3'),
			'jpvs' => array(self::HAS_MANY, 'Jpv', 'jpv3'),
			'sgs' => array(self::MANY_MANY, 'Sg', 'drsg(drsg2, drsg1)'),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Uo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * Список uo1 по
     * @param $ucgns1 int
     * @return array
     */
	public function getListByUcgns1($ucgns1)
    {
        $sql = <<<SQL
          select 
            uo1, d2
            from ucxg
               inner join ucx on (ucxg.ucxg1 = ucx.ucx1)
               inner join uo on (ucx.ucx1 = uo.uo19)
               inner join d on (uo.uo3 = d.d1)
               inner join ucgn on (ucxg.ucxg2 = ucgn.ucgn1)
               inner join ucgns on (ucgn.ucgn1 = ucgns.ucgns2)
            WHERE ucgns1=:UCGNS1
            GROUP BY uo1, d2
SQL;


        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':UCGNS1', $ucgns1);

        return $command->queryAll();
    }
}
