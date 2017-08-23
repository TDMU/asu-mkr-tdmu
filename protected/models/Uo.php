<?php

/**
 * This is the model class for table "uo".
 *
 * The followings are the available columns in table 'uo':
 * @property integer $uo1
 * @property integer $uo2
 * @property integer $uo3
 * @property integer $uo4
 * @property integer $uo5
 * @property integer $uo6
 * @property double $uo10
 * @property double $uo11
 * @property integer $uo12
 * @property integer $uo13
 * @property integer $uo14
 * @property integer $uo15
 * @property integer $uo16
 * @property integer $uo17
 * @property integer $uo18
 * @property integer $uo19
 * @property string $uo20
 * @property integer $uo21
 * @property integer $uo22
 * @property integer $uo23
 * @property double $uo24
 * @property double $uo25
 * @property integer $uo26
 * @property string $uo27
 * @property integer $uo28
 * @property double $uo29
 * @property integer $uo30
 * @property integer $uo33
 * @property integer $uo34
 * @property string $uo35
 * @property integer $uo36
 *
 * The followings are the available model relations:
 * @property Pvvd[] $pvvds
 * @property Sem[] $sems
 * @property Mtt[] $mtts
 * @property Uosv[] $uosvs
 * @property Us[] $uses
 * @property Elgno[] $elgnos
 * @property Elg[] $elgs
 * @property D $uo31
 * @property K $uo40
 * @property Ad $uo170
 * @property D $uo180
 * @property Ucx $uo190
 * @property I $uo210
 * @property U $uo220
 * @property K $uo300
 * @property I $uo360
 * @property Spr $uo330
 * @property Sgos[] $sgoses
 * @property Mtz[] $mtzs
 * @property Jpv[] $jpvs
 * @property Sg[] $sgs
 */
class Uo extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'uo';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uo1, uo2, uo3, uo4, uo5, uo6, uo12, uo13, uo14, uo15, uo16, uo17, uo18, uo19, uo21, uo22, uo23, uo26, uo28, uo30, uo33, uo34, uo36', 'numerical', 'integerOnly'=>true),
			array('uo10, uo11, uo24, uo25, uo29', 'numerical'),
			array('uo20, uo35', 'length', 'max'=>8),
			array('uo27', 'length', 'max'=>4),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('uo1, uo2, uo3, uo4, uo5, uo6, uo10, uo11, uo12, uo13, uo14, uo15, uo16, uo17, uo18, uo19, uo20, uo21, uo22, uo23, uo24, uo25, uo26, uo27, uo28, uo29, uo30, uo33, uo34, uo35, uo36', 'safe', 'on'=>'search'),
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
			'pvvds' => array(self::HAS_MANY, 'Pvvd', 'pvvd3'),
			'sems' => array(self::MANY_MANY, 'Sem', 'mtrp(mtrp1, mtrp14)'),
			'mtts' => array(self::MANY_MANY, 'Mtt', 'mtdm(mtdm1, mtdm2)'),
			'uosvs' => array(self::HAS_MANY, 'Uosv', 'uosv1'),
			'uses' => array(self::HAS_MANY, 'Us', 'us2'),
			'elgnos' => array(self::HAS_MANY, 'Elgno', 'elgno2'),
			'elgs' => array(self::HAS_MANY, 'Elg', 'elg2'),
			'uo31' => array(self::BELONGS_TO, 'D', 'uo3'),
			'uo40' => array(self::BELONGS_TO, 'K', 'uo4'),
			'uo170' => array(self::BELONGS_TO, 'Ad', 'uo17'),
			'uo180' => array(self::BELONGS_TO, 'D', 'uo18'),
			'uo190' => array(self::BELONGS_TO, 'Ucx', 'uo19'),
			'uo210' => array(self::BELONGS_TO, 'I', 'uo21'),
			'uo220' => array(self::BELONGS_TO, 'U', 'uo22'),
			'uo300' => array(self::BELONGS_TO, 'K', 'uo30'),
			'uo360' => array(self::BELONGS_TO, 'I', 'uo36'),
			'uo330' => array(self::BELONGS_TO, 'Spr', 'uo33'),
			'sgoses' => array(self::HAS_MANY, 'Sgos', 'sgos15'),
			'mtzs' => array(self::HAS_MANY, 'Mtz', 'mtz3'),
			'jpvs' => array(self::HAS_MANY, 'Jpv', 'jpv3'),
			'sgs' => array(self::MANY_MANY, 'Sg', 'drsg(drsg2, drsg1)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'uo1' => 'Uo1',
			'uo2' => 'Uo2',
			'uo3' => 'Uo3',
			'uo4' => 'Uo4',
			'uo5' => 'Uo5',
			'uo6' => 'Uo6',
			'uo10' => 'Uo10',
			'uo11' => 'Uo11',
			'uo12' => 'Uo12',
			'uo13' => 'Uo13',
			'uo14' => 'Uo14',
			'uo15' => 'Uo15',
			'uo16' => 'Uo16',
			'uo17' => 'Uo17',
			'uo18' => 'Uo18',
			'uo19' => 'Uo19',
			'uo20' => 'Uo20',
			'uo21' => 'Uo21',
			'uo22' => 'Uo22',
			'uo23' => 'Uo23',
			'uo24' => 'Uo24',
			'uo25' => 'Uo25',
			'uo26' => 'Uo26',
			'uo27' => 'Uo27',
			'uo28' => 'Uo28',
			'uo29' => 'Uo29',
			'uo30' => 'Uo30',
			'uo33' => 'Uo33',
			'uo34' => 'Uo34',
			'uo35' => 'Uo35',
			'uo36' => 'Uo36',
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

		$criteria->compare('uo1',$this->uo1);
		$criteria->compare('uo2',$this->uo2);
		$criteria->compare('uo3',$this->uo3);
		$criteria->compare('uo4',$this->uo4);
		$criteria->compare('uo5',$this->uo5);
		$criteria->compare('uo6',$this->uo6);
		$criteria->compare('uo10',$this->uo10);
		$criteria->compare('uo11',$this->uo11);
		$criteria->compare('uo12',$this->uo12);
		$criteria->compare('uo13',$this->uo13);
		$criteria->compare('uo14',$this->uo14);
		$criteria->compare('uo15',$this->uo15);
		$criteria->compare('uo16',$this->uo16);
		$criteria->compare('uo17',$this->uo17);
		$criteria->compare('uo18',$this->uo18);
		$criteria->compare('uo19',$this->uo19);
		$criteria->compare('uo20',$this->uo20,true);
		$criteria->compare('uo21',$this->uo21);
		$criteria->compare('uo22',$this->uo22);
		$criteria->compare('uo23',$this->uo23);
		$criteria->compare('uo24',$this->uo24);
		$criteria->compare('uo25',$this->uo25);
		$criteria->compare('uo26',$this->uo26);
		$criteria->compare('uo27',$this->uo27,true);
		$criteria->compare('uo28',$this->uo28);
		$criteria->compare('uo29',$this->uo29);
		$criteria->compare('uo30',$this->uo30);
		$criteria->compare('uo33',$this->uo33);
		$criteria->compare('uo34',$this->uo34);
		$criteria->compare('uo35',$this->uo35,true);
		$criteria->compare('uo36',$this->uo36);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Uo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
