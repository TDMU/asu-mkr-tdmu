<?php

/**
 * This is the model class for table "pe".
 *
 * The followings are the available columns in table 'pe':
 * @property integer $pe1
 * @property string $pe2
 * @property string $pe3
 * @property string $pe4
 * @property string $pe5
 * @property string $pe6
 * @property string $pe7
 * @property integer $pe8
 * @property string $pe9
 * @property string $pe10
 * @property string $pe11
 * @property integer $pe12
 * @property string $pe20
 * @property integer $pe21
 * @property string $pe22
 * @property string $pe23
 * @property string $pe24
 * @property string $pe25
 * @property integer $pe30
 * @property string $pe31
 * @property integer $pe32
 * @property integer $pe33
 * @property string $pe34
 * @property string $pe35
 * @property string $pe36
 * @property string $pe37
 * @property integer $pe50
 * @property string $pe51
 * @property string $pe52
 * @property string $pe53
 * @property integer $pe54
 * @property integer $pe59
 * @property string $pe60
 * @property string $pe61
 * @property string $pe62
 * @property string $pe63
 * @property integer $pe64
 *
 * The followings are the available model relations:
 * @property St[] $sts
 * @property Pefio[] $pefios
 * @property Peadr[] $peadrs
 * @property Pesem[] $pesems
 * @property Voen $pe540
 * @property Sgr $pe300
 * @property I $pe320
 */
class Person extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pe';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pe1, pe8, pe12, pe21, pe30, pe32, pe33, pe50, pe54, pe59, pe64', 'numerical', 'integerOnly'=>true),
			array('pe2, pe4, pe5', 'length', 'max'=>140),
			array('pe3, pe34, pe35, pe36, pe37', 'length', 'max'=>200),
			array('pe6, pe7', 'length', 'max'=>80),
			array('pe9, pe25, pe31, pe51, pe52, pe60, pe61', 'length', 'max'=>8),
			array('pe10, pe11', 'length', 'max'=>300),
			array('pe20, pe63', 'length', 'max'=>60),
			array('pe22', 'length', 'max'=>40),
			array('pe23', 'length', 'max'=>100),
			array('pe24', 'length', 'max'=>600),
			array('pe53', 'length', 'max'=>400),
			array('pe62', 'length', 'max'=>4),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('pe1, pe2, pe3, pe4, pe5, pe6, pe7, pe8, pe9, pe10, pe11, pe12, pe20, pe21, pe22, pe23, pe24, pe25, pe30, pe31, pe32, pe33, pe34, pe35, pe36, pe37, pe50, pe51, pe52, pe53, pe54, pe59, pe60, pe61, pe62, pe63, pe64', 'safe', 'on'=>'search'),
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
			'sts' => array(self::HAS_MANY, 'St', 'st200'),
			'pefios' => array(self::HAS_MANY, 'Pefio', 'pefio1'),
			'peadrs' => array(self::HAS_MANY, 'Peadr', 'peadr1'),
			'pesems' => array(self::HAS_MANY, 'Pesem', 'pesem1'),
			'pe540' => array(self::BELONGS_TO, 'Voen', 'pe54'),
			'pe300' => array(self::BELONGS_TO, 'Sgr', 'pe30'),
			'pe320' => array(self::BELONGS_TO, 'I', 'pe32'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'pe1' => 'Pe1',
			'pe2' => 'Pe2',
			'pe3' => 'Pe3',
			'pe4' => 'Pe4',
			'pe5' => 'Pe5',
			'pe6' => 'Pe6',
			'pe7' => 'Pe7',
			'pe8' => 'Pe8',
			'pe9' => 'Pe9',
			'pe10' => 'Pe10',
			'pe11' => 'Pe11',
			'pe12' => 'Pe12',
			'pe20' => 'Pe20',
			'pe21' => 'Pe21',
			'pe22' => 'Pe22',
			'pe23' => 'Pe23',
			'pe24' => 'Pe24',
			'pe25' => 'Pe25',
			'pe30' => 'Pe30',
			'pe31' => 'Pe31',
			'pe32' => 'Pe32',
			'pe33' => 'Pe33',
			'pe34' => 'Pe34',
			'pe35' => 'Pe35',
			'pe36' => 'Pe36',
			'pe37' => 'Pe37',
			'pe50' => 'Pe50',
			'pe51' => 'Pe51',
			'pe52' => 'Pe52',
			'pe53' => 'Pe53',
			'pe54' => 'Pe54',
			'pe59' => 'Pe59',
			'pe60' => 'Pe60',
			'pe61' => 'Pe61',
			'pe62' => 'Pe62',
			'pe63' => 'Pe63',
			'pe64' => 'Pe64',
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

		$criteria->compare('pe1',$this->pe1);
		$criteria->compare('pe2',$this->pe2,true);
		$criteria->compare('pe3',$this->pe3,true);
		$criteria->compare('pe4',$this->pe4,true);
		$criteria->compare('pe5',$this->pe5,true);
		$criteria->compare('pe6',$this->pe6,true);
		$criteria->compare('pe7',$this->pe7,true);
		$criteria->compare('pe8',$this->pe8);
		$criteria->compare('pe9',$this->pe9,true);
		$criteria->compare('pe10',$this->pe10,true);
		$criteria->compare('pe11',$this->pe11,true);
		$criteria->compare('pe12',$this->pe12);
		$criteria->compare('pe20',$this->pe20,true);
		$criteria->compare('pe21',$this->pe21);
		$criteria->compare('pe22',$this->pe22,true);
		$criteria->compare('pe23',$this->pe23,true);
		$criteria->compare('pe24',$this->pe24,true);
		$criteria->compare('pe25',$this->pe25,true);
		$criteria->compare('pe30',$this->pe30);
		$criteria->compare('pe31',$this->pe31,true);
		$criteria->compare('pe32',$this->pe32);
		$criteria->compare('pe33',$this->pe33);
		$criteria->compare('pe34',$this->pe34,true);
		$criteria->compare('pe35',$this->pe35,true);
		$criteria->compare('pe36',$this->pe36,true);
		$criteria->compare('pe37',$this->pe37,true);
		$criteria->compare('pe50',$this->pe50);
		$criteria->compare('pe51',$this->pe51,true);
		$criteria->compare('pe52',$this->pe52,true);
		$criteria->compare('pe53',$this->pe53,true);
		$criteria->compare('pe54',$this->pe54);
		$criteria->compare('pe59',$this->pe59);
		$criteria->compare('pe60',$this->pe60,true);
		$criteria->compare('pe61',$this->pe61,true);
		$criteria->compare('pe62',$this->pe62,true);
		$criteria->compare('pe63',$this->pe63,true);
		$criteria->compare('pe64',$this->pe64);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Person the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
