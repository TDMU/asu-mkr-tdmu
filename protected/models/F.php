<?php

/**
 * This is the model class for table "f".
 *
 * The followings are the available columns in table 'f':
 * @property integer $f1
 * @property string $f2
 * @property string $f3
 * @property string $f4
 * @property string $f5
 * @property string $f6
 * @property string $f7
 * @property string $f8
 * @property string $f9
 * @property string $f10
 * @property string $f11
 * @property string $f12
 * @property string $f13
 * @property integer $f14
 * @property integer $f15
 * @property integer $f16
 * @property string $f17
 * @property string $f18
 * @property string $f19
 * @property integer $f22
 * @property string $f23
 * @property integer $f24
 * @property integer $f25
 * @property string $f26
 * @property string $f27
 * @property string $f28
 * @property string $f29
 * @property string $f30
 * @property string $f31
 * @property string $f20
 * @property string $f21
 * @property integer $f32
 */
class F extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'f';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('f1', 'required'),
			array('f1, f14, f15, f16, f22, f24, f25, f32', 'numerical', 'integerOnly'=>true),
			array('f2, f31', 'length', 'max'=>100),
			array('f3, f4, f5', 'length', 'max'=>800),
			array('f6, f7, f8, f9, f10, f11, f12, f17, f18, f29', 'length', 'max'=>4),
			array('f13', 'length', 'max'=>12),
			array('f19, f23', 'length', 'max'=>8),
			array('f26, f27, f28, f30, f21', 'length', 'max'=>400),
			array('f20', 'length', 'max'=>180),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('f1, f2, f3, f4, f5, f6, f7, f8, f9, f10, f11, f12, f13, f14, f15, f16, f17, f18, f19, f22, f23, f24, f25, f26, f27, f28, f29, f30, f31, f20, f21, f32', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'f1' => 'F1',
			'f2' => 'F2',
			'f3' => 'F3',
			'f4' => 'F4',
			'f5' => 'F5',
			'f6' => 'F6',
			'f7' => 'F7',
			'f8' => 'F8',
			'f9' => 'F9',
			'f10' => 'F10',
			'f11' => 'F11',
			'f12' => 'F12',
			'f13' => 'F13',
			'f14' => 'F14',
			'f15' => 'F15',
			'f16' => 'F16',
			'f17' => 'F17',
			'f18' => 'F18',
			'f19' => 'F19',
			'f22' => 'F22',
			'f23' => 'F23',
			'f24' => 'F24',
			'f25' => 'F25',
			'f26' => 'F26',
			'f27' => 'F27',
			'f28' => 'F28',
			'f29' => 'F29',
			'f30' => 'F30',
			'f31' => 'F31',
			'f20' => 'F20',
			'f21' => 'F21',
			'f32' => 'F32',
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

		$criteria->compare('f1',$this->f1);
		$criteria->compare('f2',$this->f2,true);
		$criteria->compare('f3',$this->f3,true);
		$criteria->compare('f4',$this->f4,true);
		$criteria->compare('f5',$this->f5,true);
		$criteria->compare('f6',$this->f6,true);
		$criteria->compare('f7',$this->f7,true);
		$criteria->compare('f8',$this->f8,true);
		$criteria->compare('f9',$this->f9,true);
		$criteria->compare('f10',$this->f10,true);
		$criteria->compare('f11',$this->f11,true);
		$criteria->compare('f12',$this->f12,true);
		$criteria->compare('f13',$this->f13,true);
		$criteria->compare('f14',$this->f14);
		$criteria->compare('f15',$this->f15);
		$criteria->compare('f16',$this->f16);
		$criteria->compare('f17',$this->f17,true);
		$criteria->compare('f18',$this->f18,true);
		$criteria->compare('f19',$this->f19,true);
		$criteria->compare('f22',$this->f22);
		$criteria->compare('f23',$this->f23,true);
		$criteria->compare('f24',$this->f24);
		$criteria->compare('f25',$this->f25);
		$criteria->compare('f26',$this->f26,true);
		$criteria->compare('f27',$this->f27,true);
		$criteria->compare('f28',$this->f28,true);
		$criteria->compare('f29',$this->f29,true);
		$criteria->compare('f30',$this->f30,true);
		$criteria->compare('f31',$this->f31,true);
		$criteria->compare('f20',$this->f20,true);
		$criteria->compare('f21',$this->f21,true);
		$criteria->compare('f32',$this->f32);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return F the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getFieldByLanguage($short){
		switch(Yii::app()->language){
			case 'uk':
				if(!$short)
					return 'f3';
				else
					return 'f2';
				break;
			case 'ru':
				if(!$short)
					return 'f30';
				else
					return 'f2';
				break;
			case 'en': if(!$short)
				return 'f26';
			else
				return 'f2';
				break;
		}

		return 'f3';
	}
    /**
     * @param int $type тип если 1 то нужно блокировать для юрки факультет номер 5
     * @return array
     */
    public function getFacultiesFor($filial, $type=0)
    {
        $sql=<<<SQL
            SELECT f1, f3, f26, f27, f28, f30
            FROM f
            WHERE f1>0 and f12<>0 and f17=0 and (f19 is null) and f14=:FILIAL and f32 = 0
            ORDER BY f15,f3 collate UNICODE
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':FILIAL', $filial);
        $faculties = $command->queryAll();

		$res = array();
		foreach($faculties as $key => $faculty){
            if($type==1&&Yii::app()->core->universityCode==U_NULAU&&$faculty['f1']==5)
                continue;
			$name =  $faculty[F::getFieldByLanguage(false)];
			$res[$key] = $faculty;
			$res[$key]['name'] = (isset($name)&&!empty($name)&&$name!="")?$name:$faculty['f3'];
		}
		return CHtml::listData($res, 'f1', 'name');
        //return $faculties;
    }

    /**
     * Все факультеты
     * @return array
     */
    public function getAllFaculties()
    {
        $sql=<<<SQL
            SELECT f1, f3
            FROM f
            WHERE f1>0 and f12<>0 and f17=0 and (f19 is null) and f32 = 0
            ORDER BY f15,f3 collate UNICODE
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $faculties = $command->queryAll();

        return $faculties;
    }
}
