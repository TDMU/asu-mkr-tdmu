<?php

/**
 * This is the model class for table "pd".
 *
 * The followings are the available columns in table 'pd':
 * @property integer $pd1
 * @property integer $pd2
 * @property integer $pd3
 * @property integer $pd4
 * @property integer $pd5
 * @property double $pd6
 * @property integer $pd7
 * @property integer $pd8
 * @property integer $pd9
 * @property string $pd10
 * @property string $pd11
 * @property string $pd12
 * @property string $pd13
 * @property string $pd15
 * @property string $pd16
 * @property string $pd17
 * @property string $pd18
 * @property string $pd19
 * @property string $pd20
 * @property integer $pd21
 * @property integer $pd28
 * @property integer $pd31
 * @property string $pd33
 * @property integer $pd34
 * @property integer $pd38
 * @property string $pd40
 * @property string $pd41
 * @property integer $pd43
 * @property string $pd44
 * @property integer $pd45
 * @property integer $pd46
 * @property integer $pd47
 * @property string $pd48
 * @property string $pd49
 * @property integer $pd50
 * @property integer $pd51
 * @property integer $pd52
 * @property integer $pd53
 */
class Pd extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pd';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pd1', 'required'),
			array('pd1, pd2, pd3, pd4, pd5, pd7, pd8, pd9, pd21, pd28, pd31, pd34, pd38, pd43, pd45, pd46, pd47, pd50, pd51, pd52, pd53', 'numerical', 'integerOnly'=>true),
			array('pd6', 'numerical'),
			array('pd10', 'length', 'max'=>100),
			array('pd11, pd12, pd13, pd44', 'length', 'max'=>8),
			array('pd15', 'length', 'max'=>200),
			array('pd16, pd17, pd18, pd19, pd20, pd40, pd41, pd48, pd49', 'length', 'max'=>4),
			array('pd33', 'length', 'max'=>600),
        );
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Pd the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getTeacherAndChairByP1($p1){
		$sql = <<<SQL
            SELECT first 1 p3,p4,p5,k2,k3 FROM pd
            INNER JOIN  P ON ( PD2 = P1)
            INNER JOIN  K ON ( PD4 = K1)
            WHERE p1=:p1
            GROUP BY p3,p4,p5,k2,k3
SQL;
		$command = Yii::app()->db->createCommand($sql);
		$command->bindValue(':p1', $p1);
		$info = $command->queryRow();

		return $info;
	}

	public function getTeacherAndChairByPd1($pd1){
		$sql = <<<SQL
            SELECT first 1 p3,p4,p5,k2,k3 FROM pd
            INNER JOIN  P ON ( PD2 = P1)
            INNER JOIN  K ON ( PD4 = K1)
            WHERE pd1=:pd1
            GROUP BY p3,p4,p5,k2,k3
SQL;
		$command = Yii::app()->db->createCommand($sql);
		$command->bindValue(':pd1', $pd1);
		$info = $command->queryRow();

		return $info;
	}
}
