<?php

/**
 * This is the model class for table "users_history".
 *
 * The followings are the available columns in table 'users_history':
 * @property integer $uh1
 * @property integer $uh2
 * @property integer $uh3
 * @property string $uh4
 * @property string $uh5
 * @property string $uh6
 *
 * The followings are the available model relations:
 * @property Users $uh20
 */
class UsersHistory extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'users_history';
	}

	public $name, $login,$type, $adm;
	public $st_name1, $st_name2,$st_name3;
	public $p_name3, $p_name2,$p_name1;
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uh1', 'required'),
			array('uh2, uh3,type,adm', 'numerical', 'integerOnly'=>true),
			array('uh4,login,name', 'length', 'max'=>200),
			array('uh5', 'length', 'max'=>200),
			array('uh6', 'length', 'max'=>200),
			array('uh1, uh2, uh3, uh4, uh5, uh6,type,login,name,adm', 'safe', 'on'=>'search'),
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
			'uh20' => array(self::BELONGS_TO, 'Users', 'uh2'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'uh1' => tt('Uh1'),
			'uh2' =>  tt('Uh2'),
			'uh3' =>  tt('Тип устройства'),
			'uh4' =>  tt('ip'),
			'uh5' =>  tt('Дата'),
			'uh6' =>  tt('Версия браузера'),
			'name' =>  tt('Имя'),
			'type' =>  tt('Тип пользователя'),
			'login' =>  tt('Login'),
			'adm' =>  tt('Админские права'),
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
		$criteria=new CDbCriteria;
		$criteria->select = 't.*,u2 as login,u5 as type,u7 as adm,
							st2 as st_name1,st3 as st_name2,st4 as st_name3,
							p3 as p_name1,p4 as p_name2,p5 as p_name3';
		$criteria->join = 'JOIN users ON (t.uh2=users.u1) ';
		$criteria->join .= 'LEFT JOIN st ON (users.u6=st.st1) ';
		$criteria->join .= 'LEFT JOIN p ON (users.u6=p.p1) ';
		$criteria->compare('uh1',$this->uh1);
		$criteria->compare('uh2',$this->uh2);
		$criteria->compare('uh3',$this->uh3);
		$criteria->compare('uh4',$this->uh4,true);
		$criteria->compare('uh5',$this->uh5,true);
		$criteria->compare('uh6',$this->uh6,true);
		$criteria->compare('u5',$this->type);
		$criteria->compare('u7',$this->adm);
		$criteria->compare('u2',$this->login,true);
		if(!empty($this->name))
			$criteria->addCondition("(st2 CONTAINING '".$this->name."'or p3 CONTAINING '".$this->name."')");

		$sort = new CSort();
		$sort->sortVar = 'sort';
		$sort->defaultOrder = 'uh5 desc';
		$sort->attributes = array(
				'name'=>array(
						'asc'=>'st2 ASC, p3 ASC',
						'desc'=>'st2 DESC, p3 DESC',
						'default'=>'ASC',
				),
				'login'=>array(
						'asc'=>'u2 ASC',
						'desc'=>'u2 DESC',
						'default'=>'ASC',
				),
				'type'=>array(
						'asc'=>'u5 ASC',
						'desc'=>'u5 DESC',
						'default'=>'ASC',
				),
				'adm'=>array(
						'asc'=>'u7 ASC',
						'desc'=>'u7 DESC',
						'default'=>'ASC',
				),
				'uh6'=>array(
						'asc'=>'uh6 ASC',
						'desc'=>'uh6 DESC',
						'default'=>'ASC',
				),
				'uh5'=>array(
						'asc'=>'uh5 asc',
						'desc'=>'uh5 DESC',
						'default'=>'ASC',
				),
				'uh4'=>array(
						'asc'=>'uh4 asc',
						'desc'=>'uh4 DESC',
						'default'=>'ASC',
				),
				'uh3'=>array(
						'asc'=>'uh3 asc',
						'desc'=>'uh3 DESC',
						'default'=>'ASC',
				),
				'uh2'=>array(
						'asc'=>'u1 asc',
						'desc'=>'u1 DESC',
						'default'=>'ASC',
				),

		);

		$pageSize=Yii::app()->user->getState('pageSize',10);
		if($pageSize==0)
			$pageSize=10;

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort,
			'pagination'=>array(
					'pageSize'=> $pageSize,
			),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UsersHistory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getNewLogin(){
		$model =new UsersHistory();
		$model->uh1 = new CDbExpression('GEN_ID(GEN_UH, 1)');
		$model->uh2 = Yii::app()->user->id;
		$isMob =0;
		$detect = Yii::app()->mobileDetect;
		if($detect->isMobile())
		{
			$isMob = 2;
		}else if($detect->isTablet()){
			$isMob = 1;
		}
		$model->uh3 = $isMob;
		$model->uh4 = self::getRealIp();
		$model->uh5 = date('Y-m-d H:i:s');
		$model->uh6 = SH::user_browser(Yii::app()->request->getUserAgent());
		$model->save();
			//print_r($model->getErrors());
	}

	private static function getRealIp()
	{
		/*if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
		{
			$ip=$_SERVER['HTTP_CLIENT_IP'];
		}
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
		{
			$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{

		}*/
		$ip=$_SERVER['REMOTE_ADDR'];

		return $ip;
	}

	public function getDevicesTypes()
	{
		return array(0=>tt('Desktop'),1=>tt('Tablet'),2=>tt('Mobile'));
	}

	public function getDeviceType()
	{
		$arr=self::model()->getDevicesTypes();
		if(isset( $arr[$this->uh3]))
			return $arr[$this->uh3];
		else
			return '-';
	}

	public function getType()
	{
		$arr=Users::model()->getTypes();
		if(isset( $arr[$this->type]))
			return $arr[$this->type];
		else
			return '-';
	}

	public function getAdminType()
	{
		$arr=Users::model()->getAdminTypes();
		if(!empty($this->adm)) {
			if (isset($arr[$this->adm]))
				return $arr[$this->adm];
			else
				return '-';
		}else{
			return '-';

		}
	}

	public function getTchName()
	{
		if($this->type==1)
			return SH::getShortName($this->p_name1,$this->p_name2,$this->p_name3);
		else
			return '-';
	}

	public function getStdName()
	{
		if($this->type==0||$this->type==1)
			return SH::getShortName($this->st_name1,$this->st_name2,$this->st_name3);
		else
			return '-';
	}
}
