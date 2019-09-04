<?php

/**
 * This is the model class for table "stpfile".
 *
 * The followings are the available columns in table 'stpfile':
 * @property integer $stpfile1
 * @property string $stpfile2
 * @property integer $stpfile3
 * @property string $stpfile4
 * @property integer $stpfile5
 *
 * The followings are the available model relations:
 * @property Users $stpfile30
 * @property St $stpfile50
 */
class Stpfile extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'stpfile';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('stpfile1, stpfile3, stpfile5', 'numerical', 'integerOnly'=>true),
			array('stpfile2', 'length', 'max'=>180),
			array('stpfile4', 'length', 'max'=>20)
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
			'stpfile30' => array(self::BELONGS_TO, 'Users', 'stpfile3'),
			'stpfile50' => array(self::BELONGS_TO, 'St', 'stpfile5'),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Stpfile the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * @return bool
     */
    public function beforeSave()
    {
        $this->stpfile3 = Yii::app()->user->id;
        $this->stpfile4 = date('Y-m-d H:i:s');

        return parent::beforeSave();
    }
}
