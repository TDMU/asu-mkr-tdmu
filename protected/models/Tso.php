<?php

/**
 * This is the model class for table "tso".
 *
 * The followings are the available columns in table 'tso':
 * @property integer $tso1
 * @property integer $tso2
 * @property string $tso3
 * @property integer $tso4
 * @property integer $tso5
 * @property integer $tso6
 * @property string $tso7
 * @property string $tso8
 * @property integer $tso9
 * @property integer $tso10
 */
class Tso extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tso';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tso1', 'required'),
			array('tso1, tso2, tso4, tso5, tso6, tso9, tso10', 'numerical', 'integerOnly'=>true),
			array('tso3', 'length', 'max'=>600),
			array('tso7', 'length', 'max'=>40),
			array('tso8', 'length', 'max'=>20),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Tso the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function getAllPhonesInArray($department)
    {
        $condition = '';
        if (! empty($department))
            $condition = ' AND tsg1 = :DEPARTMENT';

        $sql = <<<SQL
        SELECT tso.*, b1, b2, k1, tsg2, k3
        FROM tso
           INNER JOIN k on (k.k1 = tso.tso4)
           INNER JOIN tsg on (tso.tso2 = tsg.tsg1)
           INNER JOIN b on (tso.tso5 = b.b1)
        WHERE tso1 > 0 $condition
        ORDER BY tso9
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':DEPARTMENT', $department);
        $phones = $command->queryAll();

        foreach ($phones as $key => $phone) {

            if ($phone['tso4'] == 0) {
                $teacher = $phone['tso11'];
            } elseif ($phone['tso10'] == 1) {
                $teacher = P::model()->getTeacherNameBy($phone['tso6'], false);
            } else
                $teacher = P::model()->getTeacherNameForPhones($phone['b1'], $phone['k1']);

            $phones[$key]['teacher'] = $teacher;
        }

        return $phones;
    }
}
