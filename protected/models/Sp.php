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
		);
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

    /**
     * Все специальности
     * @return array
     */
    public function getAllSpecialities()
    {
        $sql=<<<SQL
            SELECT pnsp2,sp2,sp1
            FROM sp
            INNER JOIN pnsp on (sp.sp11 = pnsp.pnsp1)
            WHERE sp7 is null and pnsp9 is null
            ORDER BY pnsp2
SQL;

        $command = Yii::app()->db->createCommand($sql);
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
}
