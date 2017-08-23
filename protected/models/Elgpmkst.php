<?php

/**
 * This is the model class for table "elgpmkst".
 *
 * The followings are the available columns in table 'elgpmkst':
 * @property integer $elgpmkst1
 * @property integer $elgpmkst2
 * @property integer $elgpmkst3
 * @property integer $elgpmkst4
 * @property double $elgpmkst5
 *
 * The followings are the available model relations:
 * @property Elg $elgpmkst20
 * @property St $elgpmkst30
 */
class Elgpmkst extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'elgpmkst';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('elgpmkst2, elgpmkst3, elgpmkst4', 'numerical', 'integerOnly'=>true),
			array('elgpmkst5', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('elgpmkst1, elgpmkst2, elgpmkst3, elgpmkst4, elgpmkst5', 'safe', 'on'=>'search'),
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
			'elgpmkst20' => array(self::BELONGS_TO, 'Elg', 'elgpmkst2'),
			'elgpmkst30' => array(self::BELONGS_TO, 'St', 'elgpmkst3'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'elgpmkst1' => 'Elgpmkst1',
			'elgpmkst2' => 'Elgpmkst2',
			'elgpmkst3' => 'Elgpmkst3',
			'elgpmkst4' => 'Elgpmkst4',
			'elgpmkst5' => 'Elgpmkst5',
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

		$criteria->compare('elgpmkst1',$this->elgpmkst1);
		$criteria->compare('elgpmkst2',$this->elgpmkst2);
		$criteria->compare('elgpmkst3',$this->elgpmkst3);
		$criteria->compare('elgpmkst4',$this->elgpmkst4);
		$criteria->compare('elgpmkst5',$this->elgpmkst5);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Elgpmkst the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
