<?php

/**
 * This is the model class for table "spkr".
 *
 * The followings are the available columns in table 'spkr':
 * @property integer $spkr1
 * @property string $spkr2
 * @property string $spkr3
 * @property integer $spkr4
 * @property integer $spkr5
 * @property integer $spkr6
 */
class Spkr extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'spkr';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('spkr1', 'required'),
			array(' spkr4, spkr5, spkr6', 'numerical', 'integerOnly'=>true),
			array('spkr2, spkr3', 'length', 'max'=>1000),
		);
	}



	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'spkr2' => tt('Оригинальное название'),
			'spkr3' => tt('Название на английском'),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Spkr the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function findAllInArray()
    {
        $rows = Yii::app()->db->createCommand()
                ->select('*')
                ->from($this->tableName())
                ->queryAll();

        $rus = array();
        foreach ($rows as $row) {
            $rus[$row['spkr1']] = $row['spkr2'];
            $eng[$row['spkr1']] = $row['spkr3'];
        }
        return array($rus, $eng);
    }

}
