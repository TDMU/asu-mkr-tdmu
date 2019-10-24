<?php

/**
 * This is the model class for table "sob".
 *
 * The followings are the available columns in table 'sob':
 * @property integer $sob0
 * @property integer $sob1
 * @property double $sob2
 * @property string $sob3
 * @property string $sob4
 * @property string $sob5
 * @property integer $sob6
 * @property integer $sob7
 * @property integer $sob8
 * @property integer $sob9
 */
class Sob extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sob';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sob0', 'required'),
			array('sob0, sob1, sob6, sob7, sob8, sob9', 'numerical', 'integerOnly'=>true),
			array('sob2', 'numerical'),
			array('sob3, sob5', 'length', 'max'=>8),
			array('sob4', 'length', 'max'=>60),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Sob the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function getPayments($st1)
    {
        $sql = <<<SQL
            SELECT sob2 as money, sob3 as dat
			FROM sob
			WHERE sob1= :ST1
			ORDER BY sob3
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
