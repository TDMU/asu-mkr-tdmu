<?php

/**
 * This is the model class for table "so".
 *
 * The followings are the available columns in table 'so':
 * @property integer $so0
 * @property integer $so1
 * @property double $so2
 * @property string $so3
 * @property string $so4
 * @property integer $so5
 * @property string $so6
 * @property integer $so7
 * @property string $so8
 */
class So extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'so';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('so0', 'required'),
			array('so0, so1, so5, so7', 'numerical', 'integerOnly'=>true),
			array('so2', 'numerical'),
			array('so3, so6, so8', 'length', 'max'=>8),
			array('so4', 'length', 'max'=>60),
		);
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return So the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function getPayments($st1)
    {
        $sql = <<<SQL
            SELECT so2 as money, so3 as dat
			FROM so
			WHERE so1= :ST1 and so5=0
			ORDER BY so3
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':ST1', $st1);
        $payments = $command->queryAll();

        $res = array();

        foreach ($payments as $payment) {

            $date  = $payment['dat'];

            $year  = date('Y', strtotime($date));
            $month = date('m', strtotime($date));

            $res[$year][$month][] = $payment;
        }

        return $payments;
    }
}
