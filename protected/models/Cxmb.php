<?php

/**
 * This is the model class for table "cxmb".
 *
 * The followings are the available columns in table 'cxmb':
 * @property integer $cxmb0
 * @property integer $cxmb2
 * @property string $cxmb3
 * @property integer $cxmb4
 * @property integer $cxmb5
 */
class Cxmb extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cxmb';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cxmb0, cxmb2, cxmb4, cxmb5', 'numerical', 'integerOnly'=>true),
			array('cxmb3', 'length', 'max'=>8),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Cxmb the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getMark($mark)
	{
		if ((int)$mark < 0)
			return array('cxmb3' => '', 'cxmb2' => 0);

		$sql = <<<SQL
            select cxmb2,cxmb3
            from cxmb
            where cxmb4<=:MARK1 and cxmb5>=:MARK2
SQL;

		$command = Yii::app()->db->createCommand($sql);
		$command->bindValue(':MARK1', $mark);
		$command->bindValue(':MARK2', $mark);
		$marks = $command->queryRow();

		if(empty($mark))
            return array('cxmb3' => '', 'cxmb2' => 0);

		return $marks;
	}

	public function getMarkByECTS($mark)
	{

		$sql = <<<SQL
            select cxmb2,cxmb3
            from cxmb
            where CXMB3='{$mark}'
SQL;

		$command = Yii::app()->db->createCommand($sql);
		//$command->bindValue(':MARK', $mark);
		//$command->bindValue(':MARK2', $mark);
		$marks = $command->queryRow();
		return $marks;
	}

}
