<?php

/**
 * This is the model class for table "ks".
 *
 * The followings are the available columns in table 'ks':
 * @property integer $ks1
 * @property string $ks2
 * @property string $ks3
 * @property string $ks4
 * @property string $ks5
 * @property string $ks6
 * @property integer $ks7
 * @property string $ks8
 */
class Ks extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ks';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ks1, ks7', 'numerical', 'integerOnly'=>true),
			array('ks2, ks8', 'length', 'max'=>400),
			array('ks3', 'length', 'max'=>120),
			array('ks4', 'length', 'max'=>4),
			array('ks5, ks6', 'length', 'max'=>8),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ks1, ks2, ks3, ks4, ks5, ks6, ks7, ks8', 'safe', 'on'=>'search'),
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
			'ks1' => 'Ks1',
			'ks2' => 'Ks2',
			'ks3' => 'Ks3',
			'ks4' => 'Ks4',
			'ks5' => 'Ks5',
			'ks6' => 'Ks6',
			'ks7' => 'Ks7',
			'ks8' => 'Ks8',
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

		$criteria->compare('ks1',$this->ks1);
		$criteria->compare('ks2',$this->ks2,true);
		$criteria->compare('ks3',$this->ks3,true);
		$criteria->compare('ks4',$this->ks4,true);
		$criteria->compare('ks5',$this->ks5,true);
		$criteria->compare('ks6',$this->ks6,true);
		$criteria->compare('ks7',$this->ks7);
		$criteria->compare('ks8',$this->ks8,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Ks the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getFieldByLanguage($short){
		switch(Yii::app()->language){
			case 'uk':
				if(!$short)
					return 'ks2';
				else
					return 'ks3';
				break;
			case 'ru':
				if(!$short)
					return 'ks2';
				else
					return 'ks3';
				break;
			case 'en': if(!$short)
					return 'ks10';
				else
					return 'ks11';
				break;
		}

		return 'ks2';
	}

	public static function getListDataForKsFilter(){
		$res = array();
		/*--------------госаудит-----------скрывать все филиалы кроме ихнего*/
		$univeristyCod = SH::getUniversityCod();
		if($univeristyCod==U_URFAK){
			if($_SERVER['SERVER_NAME']=='tt.audit.msu.ru')
			{
				$filial = Ks::model()->findByPk(1);
				if(!empty($filial)){
					$name =  $filial[Ks::getFieldByLanguage(false)];
					//$filials[$key]['name'] =
					$res[0]['name'] = (isset($name)&&!empty($name)&&$name!="")?$name:$filial['ks2'];
					$res[0]['ks1'] = $filial->ks1;
					return CHtml::listData($res, 'ks1', 'name');
				}
			}
		}
		/*--------------госаудит-----------скрывать все филиалы кроме ихнего*/
		//$filials = CHtml::listData(Ks::model()->findAllByAttributes(array('ks12'=>null,'ks13'=>0)), 'ks1', 'ks2');
		$filials = Ks::model()->findAllByAttributes(array('ks12'=>null,'ks13'=>0));

		foreach($filials as $key => $filial){
			$name =  $filial[Ks::getFieldByLanguage(false)];
			//$filials[$key]['name'] =
			$res[$key]['name'] = (isset($name)&&!empty($name)&&$name!="")?$name:$filial['ks2'];
			$res[$key]['ks1'] = $filial->ks1;
		}
		return CHtml::listData($res, 'ks1', 'name');
	}
}
