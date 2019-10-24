<?php

/**
 * This is the model class for table "ka".
 *
 * The followings are the available columns in table 'ka':
 * @property integer $ka1
 * @property string $ka2
 * @property integer $ka3
 * @property integer $ka4
 */
class Ka extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ka';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ka1, ka3, ka4', 'numerical', 'integerOnly'=>true),
			array('ka2', 'length', 'max'=>100),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Ka the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function getHousingFor($filial)
    {
        $sql=<<<SQL
            SELECT KA1,KA2
            FROM KA
            WHERE KA3=:FILIAL and KA1>0
            ORDER BY KA2
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':FILIAL', $filial);
        $housing = $command->queryAll();

        return $housing;
    }
}
