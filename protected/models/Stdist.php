<?php

/**
 * ПРивязка студентов к учеткам дист образования
 * This is the model class for table "stdist".
 *
 * The followings are the available columns in table 'stdist':
 * @property integer $stdist1 st1
 * @property string $stdist2 email учетки дистанционного образования
 * @property int $stdist3 id учетки дистанционного образования (moodle)
 *
 * The followings are the available model relations:
 * @property St $stdist10
 */
class Stdist extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'stdist';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('stdist1, stdist3', 'numerical', 'integerOnly'=>true),
			array('stdist2', 'length', 'max'=>200),
			array('stdist2', 'unique'),
            array('stdist2', 'application.validators.EmailValidator', 'validateDomen'=> true, 'universityCode' => Yii::app()->core->universityCode),
            // The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('stdist1, stdist2, stdist3', 'safe', 'on'=>'search'),
		);
	}

    /**
     * @param $attribute
     * @param $params
     */
    public function checkEmail($attribute, $params){

        if (empty($this->$attribute))
            return;

        if(!$this->hasErrors()) {

            $validator = new EmailValidator();
            $validator->validateDomen = true;
            $validator->universityCode = Yii::app()->core->universityCode;

            if (!$validator->validateDomen($this->$attribute)){

                $this->addError($attribute, tt('{attribute} не является правильным E-Mail адресом в домене {domen}.', array(
                    '{attribute}' => $this->getAttributeLabel($attribute),
                    '{domen}' => $validator->universitiesDomens[$validator->universityCode]
                )));
            }
        }
    }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'stdist10' => array(self::BELONGS_TO, 'St', 'stdist1'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'stdist1' => tt('Код студента'),
			'stdist2' => tt('Email dist. education'),
            'stdist3' => tt('Id dist. education'),
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

		$criteria->compare('stdist1',$this->stdist1);
		$criteria->compare('stdist2',$this->stdist2,true);
        $criteria->compare('stdist3',$this->stdist3);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Stdist the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
