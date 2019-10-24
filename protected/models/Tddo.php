<?php

/**
 * This is the model class for table "tddo".
 *
 * The followings are the available columns in table 'tddo':
 * @property integer $tddo1
 * @property integer $tddo2
 * @property integer $tddo3
 * @property string $tddo4
 * @property string $tddo5
 * @property string $tddo6
 * @property string $tddo7
 * @property string $tddo8
 * @property string $tddo9
 * @property integer $tddo10
 * @property integer $tddo11
 * @property string $tddo12
 * @property string $tddo13
 * @property string $tddo14
 * @property integer $tddo15
 * @property integer $tddo16
 * @property integer $tddo17
 * @property integer $tddo18
 * @property string $tddo19
 * @property integer $tddo20
 * @property string $tddo21
 * @property integer $tddo22
 * @property integer $tddo23
 * @property integer $tddo24
 * @property integer $tddo25
 * @property integer $tddo26
 *
 * The followings are the available model relations:
 * @property Ido[] $idos
 * @property Ddo $tddo240
 * @property I $tddo150
 * @property Tddo $tddo160
 * @property Tddo[] $tddos
 * @property Org $tddo180
 * @property Tdo $tddo200
 * @property Dkid[] $dks
 */
class Tddo extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tddo';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tddo1, tddo2, tddo3, tddo10, tddo11, tddo15, tddo16, tddo17, tddo18, tddo20, tddo22, tddo23, tddo24, tddo25, tddo26', 'numerical', 'integerOnly'=>true),
			array('tddo13,tddo21,tddo4, tddo9', 'length', 'max'=>20),
			//array('tddo21', 'type', 'type' => 'date', 'message' => '{attribute}: is not a date!', 'dateFormat' => 'dd-MM-yyyy H:i:s'),
			//array('tddo4, tddo9', 'type', 'type' => 'date', 'message' => '{attribute}: is not a date!', 'dateFormat' => 'dd-MM-yyyy'),
			array('tddo5, tddo19', 'length', 'max'=>400),
			array('tddo6', 'length', 'max'=>4000),
			array('tddo7, tddo8, tddo12', 'length', 'max'=>180),
			array('tddo14', 'length', 'max'=>1000),
			array('tddo23', 'default', 'value'=> date('Y')),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('tddo1, tddo2, tddo3, tddo4, tddo5, tddo6, tddo7, tddo8, tddo9, tddo10, tddo11, tddo12, tddo13, tddo14, tddo15, tddo16, tddo17, tddo18, tddo19, tddo20, tddo21, tddo22, tddo23', 'safe', 'on'=>'search'),
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
			'idos' => array(self::HAS_MANY, 'Ido', 'ido1'),
			'tddo240' => array(self::BELONGS_TO, 'Ddo', 'tddo2'),
			'tddo150' => array(self::BELONGS_TO, 'I', 'tddo15'),
			'tddo160' => array(self::BELONGS_TO, 'Tddo', 'tddo16'),
			'tddos' => array(self::HAS_MANY, 'Tddo', 'tddo16'),
			'tddo180' => array(self::BELONGS_TO, 'Org', 'tddo18'),
			'tddo200' => array(self::BELONGS_TO, 'Tdo', 'tddo20'),
			'dks' => array(self::HAS_MANY, 'Dkid', 'dkid1'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'tddo2' => tt('Тип'),
			'tddo3' => tt('Номер'),
			'tddo21' => tt('Дата создания'),
			'tddo23' => tt('Год'),
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

		$criteria->compare('tddo1',$this->tddo1);
		$criteria->compare('tddo2',$this->tddo2);
		$criteria->compare('tddo3',$this->tddo3);
		$criteria->compare('tddo4',$this->tddo4,true);
		$criteria->compare('tddo5',$this->tddo5,true);
		$criteria->compare('tddo6',$this->tddo6,true);
		$criteria->compare('tddo7',$this->tddo7,true);
		$criteria->compare('tddo8',$this->tddo8,true);
		$criteria->compare('tddo9',$this->tddo9,true);
		$criteria->compare('tddo10',$this->tddo10);
		$criteria->compare('tddo11',$this->tddo11);
		$criteria->compare('tddo12',$this->tddo12,true);
		$criteria->compare('tddo13',$this->tddo13,true);
		$criteria->compare('tddo14',$this->tddo14,true);
		$criteria->compare('tddo15',$this->tddo15);
		$criteria->compare('tddo16',$this->tddo16);
		$criteria->compare('tddo17',$this->tddo17);
		$criteria->compare('tddo18',$this->tddo18);
		$criteria->compare('tddo19',$this->tddo19,true);
		$criteria->compare('tddo20',$this->tddo20);
		$criteria->compare('tddo21',$this->tddo21,true);
		$criteria->compare('tddo22',$this->tddo22);
		$criteria->compare('tddo23',$this->tddo23);
		$criteria->addCondition("tddo3>0");
		$criteria->addCondition("tddo24=0");

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort' => array(

				'defaultOrder' => array(
						'tddo3' => CSort::SORT_DESC, //default sort value
				),
			),
			'pagination' => array(
					'pageSize' => Yii::app()->user->getState('pageSize',10),
			),
		));
	}

	public function searchSelf()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('tddo1',$this->tddo1);
		$criteria->compare('tddo2',$this->tddo2);
		$criteria->compare('tddo3',$this->tddo3);
		$criteria->compare('tddo4',$this->tddo4,true);
		$criteria->compare('tddo5',$this->tddo5,true);
		$criteria->compare('tddo6',$this->tddo6,true);
		$criteria->compare('tddo7',$this->tddo7,true);
		$criteria->compare('tddo8',$this->tddo8,true);
		$criteria->compare('tddo9',$this->tddo9,true);
		$criteria->compare('tddo10',$this->tddo10);
		$criteria->compare('tddo11',$this->tddo11);
		$criteria->compare('tddo12',$this->tddo12,true);
		$criteria->compare('tddo13',$this->tddo13,true);
		$criteria->compare('tddo14',$this->tddo14,true);
		$criteria->compare('tddo15',$this->tddo15);
		$criteria->compare('tddo16',$this->tddo16);
		$criteria->compare('tddo17',$this->tddo17);
		$criteria->compare('tddo18',$this->tddo18);
		$criteria->compare('tddo19',$this->tddo19,true);
		$criteria->compare('tddo20',$this->tddo20);
		$criteria->compare('tddo21',$this->tddo21,true);
		$criteria->compare('tddo22',$this->tddo22);
		$criteria->compare('tddo23',$this->tddo23);
		$criteria->addCondition("tddo3>0");
		$criteria->addCondition("tddo24=0");

		$criteria->join = " JOIN IDO ON (IDO1 = tddo1)";
		$criteria->join .= " LEFT JOIN PD ON (IDO2 = PD1)";
		$criteria->join .= " LEFT JOIN INNFP ON (IDO4 = INNFP2)";

		$p1 = Yii::app()->user->dbModel->p1;
		$criteria->addCondition(" (pd2={$p1} OR innfp1={$p1} or IDO9={$p1}) ");

		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
				'sort' => array(

						'defaultOrder' => array(
								'tddo3' => CSort::SORT_DESC, //default sort value
						),
				),
				'pagination' => array(
						'pageSize' => Yii::app()->user->getState('pageSize',10),
				),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Tddo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getTddo17Types(){
		return
				array(
						0=>tt('Другое'),
						1=>tt('FAX'),
						2=>tt('Почта'),
						3=>tt('Нарочно'),
						4=>tt('Спец.почта'),
						5=>tt('Кур.доставка'),
						6=>tt('ДСК'),
				);
	}

	public function getTddo17Type(){
		$arr=self::model()->getTddo17Types();

		if(!isset($arr[$this->tddo17]))
			return '';

		return $arr[$this->tddo17];
	}

	public function getTddo10Types(){
		return
			array(
					1=>tt('Сдан'),
					2=>tt('Не сдан'),
			);
	}

	public function getTddo10Type(){
		$arr=self::model()->getTddo10Types();

		if(!isset($arr[$this->tddo10]))
			return '';

		return $arr[$this->tddo10];
	}

	public function getTddo11Types(){
		return
			array(
					2=>tt('-'),
					1=>tt('Поставлено на контроль'),
			);
	}
	/*
	 * учитываеться ли в контрле исполнения
	 * */
	public function  isControl(){
		if($this->tddo11!=1)
			return false;

		if($this->tddo22==0)
			return true;

		return false;
	}

	public function getTddo11Type(){
		$arr=self::model()->getTddo11Types();

		if(!isset($arr[$this->tddo11]))
			return '';

		if($this->tddo11!=1)
			return $arr[$this->tddo11];
		else{
			if($this->tddo22==0)
				return $arr[$this->tddo11];
			else
				return tt('Поставлено на контроль (неучитываеться)');
		}
	}

	/**
	 * исполнителю по документу
	 */
	public function getPerformans(){
		$data = array();

		$models = Ido::model()->findAll('ido1=:TDDO1 AND (ido8 is null OR ido8=0) and ido11=0', array(
			':TDDO1'=>$this->tddo1
		));

		foreach ($models as $per) {
			/**
			* @var $per Ido
		 	*/
			$data[$per->ido0] = array(
					'id'   => $per->ido0,
					'text' => $per->getFullText(),
					'children' => $per->getChildren()

			);

		}
		return $data;
	}

	public function getDkid(){
		return Dkid::model()->findAllByAttributes(array('dkid1'=>$this->tddo1),array('order'=>'dkid2 DESC'));
	}

	public function getFiles(){
		return Fpdd::model()->findAllByAttributes(array('fpdd2'=>$this->tddo1));
	}
}
