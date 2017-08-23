<?php

/**
 * This is the model class for table "abtmpci".
 *
 * The followings are the available columns in table 'abtmpci':
 * @property integer $abtmpci1
 * @property integer $abtmpci2
 *
 * The followings are the available model relations:
 * @property Abtmpi $abtmpci10
 */
class Abtmpci extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'abtmpci';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('abtmpci1, abtmpci2', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('abtmpci1, abtmpci2', 'safe', 'on'=>'search'),
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
			'abtmpci10' => array(self::BELONGS_TO, 'Abtmpi', 'abtmpci1'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'abtmpci1' => 'Abtmpci1',
			'abtmpci2' => 'Abtmpci2',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('abtmpci1',$this->abtmpci1);
		$criteria->compare('abtmpci2',$this->abtmpci2);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Abtmpci the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function getType()
    {
        $arr=self::model()->getTypes();

        if(isset($arr[$this->abtmpci2]))
            return $arr[$this->abtmpci2];
        else
            return '-';
    }

    public static function getTypes()
    {
        return array(
            0=>tt('Магистр'),
            1=>tt('Дневная'),
            2=>tt('Заочная'),
        );
    }
}
