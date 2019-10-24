<?php

/**
 * This is the model class for table "elgdst".
 *
 * The followings are the available columns in table 'elgdst':
 * @property integer $elgdst0
 * @property integer $elgdst1
 * @property integer $elgdst2
 * @property double $elgdst3
 *
 * The followings are the available model relations:
 * @property St $elgdst10
 * @property Elgd $elgdst20
 */
class Elgdst extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'elgdst';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('elgdst0', 'required'),
			array('elgdst1, elgdst2', 'numerical', 'integerOnly'=>true),
			array('elgdst3', 'numerical')
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
			'elgdst10' => array(self::BELONGS_TO, 'St', 'elgdst1'),
			'elgdst20' => array(self::BELONGS_TO, 'Elgd', 'elgdst2'),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Elgdst the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function getMarksForStudent($st1,$elg1)
    {
        if(empty($st1)||empty($elg1))
            return array();

        $sql=<<<SQL
                SELECT elgdst3,elgd2,elgd0
                FROM elgdst
                    INNER JOIN elgd on (elgdst.elgdst2 = elgd.elgd0)
                WHERE elgd1=:ELG1 AND elgdst1=:ST1
                ORDER BY elgdst5 DESC
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':ELG1', $elg1);
        $command->bindValue(':ST1', $st1);
        $rows = $command->queryAll();

        $res= array();

        foreach($rows as $row)
        {
            $res[$row['elgd0']]=$row['elgdst3'];
        }

        return $res;
    }
}
