<?php

/**
 * This is the model class for table "grants".
 *
 * The followings are the available columns in table 'grants':
 * @property integer $grants1
 * @property integer $grants2
 * @property integer $grants3
 * @property integer $grants4
 * @property integer $grants5
 * @property integer $grants6
 * @property integer $grants7
 * @property integer $grants8
 * @property integer $grants9
 *
 * The followings are the available model relations:
 * @property P $p
 */
class Grants extends CActiveRecord
{
    //const EL_JOURNAL = 'grant3';
    const DIST_EDUCATION = 'grant3';
    const MODULES = 'grant4';
    const DOCS = 'grant5';
    const DIST_EDUCATION_ADMIN = 'grant6';
    const STUDENT_INFO = 'grant7';
    const WORKLOAD = 'grant8';
    const QUIZ = 'grant9';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'grants';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('grants7', 'required', 'on' => 'admin-teachers'),
			array('grants1, grants2, grants3, grants4, grants5, grants6, grants7, grants8, grants9', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('grants1, grants2, grants3', 'safe', 'on'=>'search'),
            array('grants8, grants7, grants6, grants3, grants4, grants5', 'safe', 'on' => 'admin-doctors'),
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
			'p' => array(self::BELONGS_TO, 'P', 'grants2'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'grants1' => 'Grants1',
			'grants2' => 'Grants2',
			'grants3' => tt('Дист. Образование'),
			'grants4' => tt('Ведомости'),
			'grants5' => tt('Документооборот'),
			'grants6' => tt('Дист. Образование админ'),
			'grants7' => tt('Данные студента'),
            'grants8' => tt('Нагрузка'),
            'grants9' => tt('Опрос'),
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

		$criteria->compare('grants1',$this->grants1);
		$criteria->compare('grants2',$this->grants2);
		$criteria->compare('grants3',$this->grants3);
		$criteria->compare('grants4',$this->grants4);
        $criteria->compare('grants5',$this->grants5);
        $criteria->compare('grants6',$this->grants6);
        $criteria->compare('grants7',$this->grants7);
        $criteria->compare('grants8',$this->grants8);
        $criteria->compare('grants9',$this->grants9);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Grants the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function getGrantsFor($service)
    {
        switch($service){
            case self::DIST_EDUCATION:
                $grants = $this->grants3;
                break;
            case self::MODULES:
                $grants = $this->grants4;
                break;
            case self::DOCS:
                $grants = $this->grants5;
                break;
            case self::DIST_EDUCATION_ADMIN:
                $grants = $this->grants6;
                break;
            case self::STUDENT_INFO:
                $grants = $this->grants7;
                break;
            case self::WORKLOAD:
                $grants = $this->grants8;
                break;
            case self::QUIZ:
                $grants = $this->grants9;
                break;
            default:
                $grants = null;
        }

        return $grants;
    }
}
