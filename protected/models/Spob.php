<?php

/**
 * This is the model class for table "spob".
 *
 * The followings are the available columns in table 'spob':
 * @property integer $spob0
 * @property integer $spob1
 * @property integer $spob2
 * @property integer $spob3
 * @property double $spob4
 * @property double $spob6
 * @property string $spob7
 * @property string $spob8
 * @property integer $spob9
 * @property string $spob10
 * @property double $spob11
 * @property integer $spob12
 * @property integer $spob13
 * @property integer $spob14
 */
class Spob extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'spob';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('spob0', 'required'),
			array('spob0, spob1, spob2, spob3, spob9, spob12, spob13, spob14', 'numerical', 'integerOnly'=>true),
			array('spob4, spob6, spob11', 'numerical'),
			array('spob7', 'length', 'max'=>200),
			array('spob8', 'length', 'max'=>8),
			array('spob10', 'length', 'max'=>4),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Spob the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function getPlan($st1)
    {
        $sql = <<<SQL
            SELECT spob2 as y,spob3 as m1, spob3 as m2,spob4+spob6-spob11 as money
			FROM spob
			WHERE spob1= :ST1
			ORDER BY spob2,spob3
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':ST1', $st1);
        $plan = $command->queryAll();

        return $plan;
    }
}
