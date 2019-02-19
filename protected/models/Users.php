<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $u1
 * @property string $u2
 * @property string $u3
 * @property string $u4
 * @property integer $u5
 * @property integer $u6
 * @property integer $u7
 * @property integer $u8
 * @property string $u9
 * @property string $u10
 * @property string $u11
 * @property string $u12
 * @property string $u13
 *
 * @property bool isStudent
 * @property bool isAdmin
 * @property bool isTeacher
 * @property bool isDoctor
 * @property bool isBlock
 * @property bool isParent
 * @property string name
 *
 */
class Users extends CActiveRecord
{
    const ST1  = 0;
    const P1   = 1;
    const PRNT = 2;
    const DOCTOR = 3;

    const FOTO_ST1  = 1;
    const FOTO_P1   = 0;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'users';
	}

	public $password;
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('u1', 'required'),
			array('u1, u5, u6, u7,u8', 'numerical', 'integerOnly'=>true),
			array('u6', 'default', 'value'=>0, 'setOnEmpty'=>TRUE),
			array('u10', 'default', 'value'=>'', 'setOnEmpty'=>TRUE),
			//array('u2, u3','length',  'min' => 8,'max'=>30),
			array('u3', 'match', 'pattern'=>'/^[a-zA-Z0-9-_\.,\/$|]{7,}$/','message'=>tt('В password могут быть только строчные и прописные латинские буквы, цифры, спецсимволы. Минимум 8 символов')),
			array('u4', 'length', 'max'=>400),
			array('u9, u10, u12', 'length', 'max'=>45),
            array('u13', 'length', 'max'=>20),
            array('u2, u4', 'checkIfUnique'),
            //array('u2', 'length', 'min'=>5, 'max'=>30),
            // Логин должен соответствовать шаблону
            array('u2', 'match', 'pattern'=>'/^[a-zA-Z][a-zA-Z0-9-_.]{7,30}$/','message'=>tt('В login могут быть только латинские символы и цифры, а так же символы "." и "_",  длиной от 8 до 30 символов. Также логин должен начинаться с латинской буквы')),
            //array('u4', 'application.validators.EmailValidator', 'validateDomen'=> true, 'universityCode' => SH::getUniversityCod()),
            array('u2, u3, u4, password', 'required', 'on'=>'admin-create,admin-update'),
			array('u2,u4 ,u3, password', 'safe', 'on'=>'change-password'),
			array('u8', 'unsafe', 'on'=>'change-password'),
			array('u2,u4 ,u3, password', 'required', 'on'=>'change-password'),
			array('u3', 'compare', 'compareAttribute'=>'password', 'on'=>'change-password,admin-create,admin-update'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('u1, u2, u3, u4, u5, u6, u7,u8', 'safe', 'on'=>'search'),
			array('u5,u6,u7,u9,u10,u11,u12, u13','unsafe')

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
			'u1' => 'U1',
			'u2' => 'Login',
			'u3' => tt('Пароль'),
			'password' => tt('Повторите пароль'),
			'u4' => 'Email',
			'u5' => 'U5',
			'u6' => 'U6',
			'u7' => 'U7',
			'u8' => tt('Заблокирован'),
			'u9' => 'U9',
            'name' => 'Имя пользователя'
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

		$criteria->compare('u1',$this->u1);
		$criteria->compare('u2',$this->u2,true);
		$criteria->compare('u3',$this->u3,true);
		$criteria->compare('u4',$this->u4,true);
		$criteria->compare('u5',$this->u5);
		$criteria->compare('u6',$this->u6);
		$criteria->compare('u7',$this->u7);
		$criteria->compare('u8',$this->u8);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=> Yii::app()->user->getState('pageSize',10),
            ),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Users the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * Returns true if this user is admin.
     * @return boolean
     */
    public function getIsAdmin()
    {
        return (int)$this->u7 === 1;
    }

	public function getIsBlock()
	{
		return (int)$this->u8 === 1;
	}

    /**
     * Returns true if this user is teacher.
     * @return boolean
     */
    public function getIsTeacher()
    {
        return (int)$this->u5 === self::P1;
    }

	public function getIsParent()
	{
		return (int)$this->u5 === self::PRNT;
	}

    /**
     * Returns true if this user is student.
     * @return boolean
     */
    public function getIsStudent()
    {
        return (int)$this->u5 === self::ST1;
    }

    /**
     * Returns true if this user is doctor.
     * @return boolean
     */
    public function getIsDoctor()
    {
        return (int)$this->u5 === self::DOCTOR;
    }

    public function getName()
    {
        $id = $this->u6;

        if ($this->getIsStudent() || $this->getIsParent()) { // student or parent
            $model = St::model()->findByPk($id);
            $name  = $model->getShortName();
        } elseif ($this->getIsTeacher() || $this->getIsDoctor()) {                // teacher or doctor
            $model = P::model()->findByPk($id);
            $name  = $model->getShortName();
        } elseif (empty($this->u5) && empty($this->u6))
            $name = 'mkp';
        else
            $name = '';

        return $name;
    }

    /**
     * Правило валидации для проверки логина и почты на уникальность
     * @param $attribute
     * @param $params
     */
    public function checkIfUnique($attribute, $params)
    {
        if (empty($this->$attribute))
            return;

        $criteria = new CDbCriteria;
        $criteria->addCondition('u1 != :U1');
        $criteria->addInCondition($attribute, array($this->$attribute));
        $criteria->params[':U1'] = $this->u1;

        $amount = Users::model()->count($criteria);

        if ($amount > 0) {
            $labels = $this->attributeLabels();
            $errorMessage = $labels[$attribute].' '.tt('значение должно быть уникально!');
            $this->addError($attribute, $errorMessage);
        }
    }


    /*public function checkEmail($attribute, $params){

        if (empty($this->$attribute))
            return;

        if(!$this->hasErrors()) {

            $validator = new EmailValidator();
            $validator->validateDomen = true;
            $validator->universityCode = SH::getUniversityCod();

            if (!$validator->validateDomen($this->$attribute)){

                $this->addError($attribute, tt('{attribute} не является правильным E-Mail адресом в домене {domen}.', array(
                    '{attribute}' => $this->getAttributeLabel($attribute),
                    '{domen}' => $validator->universitiesDomens[$validator->universityCode]
                )));
            }
        }
    }*/

    public function renderPhoto($foto1, $type)
    {
        $sql = <<<SQL
        SELECT foto3 as foto
        FROM foto
        WHERE foto1 = {$foto1} AND foto2 = {$type}
SQL;

        $string = Yii::app()->db->connectionString;
        $parts  = explode('=', $string);

        $host = str_replace(';role', '', $parts[1]);

        $host     = trim($host.'d');

        $login    = Yii::app()->db->username;
        $password = Yii::app()->db->password;
        $dbh      = ibase_connect($host, $login, $password);

        $result = ibase_query($dbh, $sql);
        $data   = ibase_fetch_object($result);

        if (empty($data->FOTO)) {
            $defaultImg = imagecreatefrompng(Yii::app()->basePath.'/../theme/ace/assets/avatars/avatar2.png');
            imagepng($defaultImg);
        } else {
            header("Content-type: image/jpeg");
            ibase_blob_echo($data->FOTO);
        }

        ibase_free_result($result);
    }

	public function getU8Type(){
		if($this->getIsBlock()){
			return tt('Заблокирован');
		}else
			return '';
	}

	public function getTypes()
	{
		return array(0=>tt('Студент'),1=>tt('Преподаватель'),2=>tt('Родитель'), self::DOCTOR=>tt('Врач'));
	}

	public function getType()
	{
		$arr=self::model()->getTypes();
		if(isset( $arr[$this->u5]))
			return $arr[$this->u5];
		else
			return '-';
	}
	public function getAdminTypes()
	{
		return array(0=>tt('не имеет'),1=>tt('имеет'));
	}

	public function getAdminType()
	{
		$arr=self::model()->getAdminTypes();
		if(isset( $arr[$this->u7]))
			return $arr[$this->u7];
		else
			return '-';
	}

	private function generateSalt(){
		$salt = openssl_random_pseudo_bytes(12);
		$hex   = bin2hex($salt);
		$salt = '$1$' .$hex /*strtr($salt, array('_' => '.', '~' => '/'))*/;

		$this->u9 = $salt;
	}

	private function setPassword(){
		$this->generateSalt();
		$password = $this->u3;
		$this->u3 = crypt($this->u3,$this->u9);

		$this->sendChangePasswordMail($password);
	}

	private function sendChangePasswordMail($password){
		if(empty($this->u4))
			return;

		$text = tt('Пароль изменен, текущий пароль');

		$ps87=PortalSettings::model()->findByPk(87)->ps2;
		if(!empty($ps87)){
			$message = str_replace('{username}',$this->u2,$ps87);
			$message = str_replace('{password}',$password,$message);
		}else
			$message = <<<HTML
					{$text}: {$password}
HTML;
		Controller::mail($this->u4, tt('Пароль изменен'), $message);
	}

	private function genAuthKey(){
        $token = openssl_random_pseudo_bytes(12);
        $key   = bin2hex($token);

        $this->u12 = $key;
    }

	public function beforeSave(){
		if($this->isNewRecord){
			$this->setPassword();
			$this->genAuthKey();
		}
		else{
			$model=self::model()->findByPk($this->u1);
			if($this->u3!=$model['u3'])
				$this->setPassword();
		}
		return parent::beforeSave();
	}

	public function validatePassword($password){
		return $this->u3 === crypt($password,$this->u9);
	}

	public function generatePasswordResetToken()
	{
		$token = openssl_random_pseudo_bytes(12);
		$hex   = bin2hex($token);
		$this->u10 = $hex. '_' . time();
	}

	/**
	 * Removes password reset token
	 */
	public function removePasswordResetToken()
	{
		$this->u10 = '';
	}

	public static function isPasswordResetTokenValid($token)
	{
		if (empty($token)) {
			return false;
		}

		$timestamp = (int) substr($token, strrpos($token, '_') + 1);
		$expire = 3600;
		return $timestamp + $expire >= time();
	}

	/**
	 * доп ключ для востановления пароля
	 */
	public function getValidationKey(){
		$uCod = SH::getUniversityCod();
		return md5(crypt($uCod.'mkp'.$uCod.$this->u2.'mkr',$this->u1.$uCod.$this->u10));
	}

	public function isValidKey($key){
		$uCod = SH::getUniversityCod();
		return $key===md5(crypt($uCod.'mkp'.$uCod.$this->u2.'mkr',$this->u1.$uCod.$this->u10));
	}

	/**
	 * Количество неудачных попыток авторизаций за последнии $countMin минут
	 * @param $countMin int количетво минут
	 * @return int
	 */
	public function getCountFail($countMin){
		//getAttribute('loginAttempt')

		$date = new DateTime();
		$date->modify("-{$countMin} minutes");

		$count = Yii::app()->db->createCommand()
				->select('count(*)')
				->from('users_auth_fail')
				->where('user_id=:id and date_fail>=:date', array(':id'=>$this->u1, ':date'=>$date->format('d.m.Y H:i:s')))
				->queryScalar();
		return $count;
	}

	public function saveNewFail(){
		$command = Yii::app()->db->createCommand();
		$command->insert('users_auth_fail', array(
				'user_id'=>$this->u1,
				'date_fail'=>date('Y-m-d H:i:s')
		));
	}

	const COOKIE_NAME_AUTH_KEY = 'akc';

	const SESSION_NAME_AUTH_KEY = 'aks';

	const SESSION_NAME_AUTH_KEY1 = 'aks1';

	public function afterLogin(){
		$ps114 = PortalSettings::model()->getSettingFor(114);
		if($ps114==1){
			$this->_generateU12();
		}
		$ps115 = PortalSettings::model()->getSettingFor(115);
		if($ps115==1) {
			$key = $this->_getKey();

			$nameCookie = self::COOKIE_NAME_AUTH_KEY;
			$cookie = new CHttpCookie($nameCookie, $this->_getCookieKey($key));
			//$cookie->httpOnly = true;
			Yii::app()->request->cookies[$nameCookie] = $cookie;

			//записіваем в сессию
			Yii::app()->session[self::SESSION_NAME_AUTH_KEY] = $this->_getSessionKey($key);

			//записіваем в сессию
			Yii::app()->session[self::SESSION_NAME_AUTH_KEY1] = $this->_getSessionKey1($key);
		}


        switch($this->u5){
            case self::ST1:
                $message = PortalSettings::model()->findByPk(92)->ps2;
                break;
            case self::P1:
                $message = PortalSettings::model()->findByPk(93)->ps2;
                break;
            case self::PRNT:
                $message = PortalSettings::model()->findByPk(94)->ps2;
                break;
        }

        if(!empty($message))
            Yii::app()->user->setFlash('info', $message);

        $universityCode = SH::getUniversityCod();

        if(empty($this->u4)) {
            Yii::app()->user->setFlash('warning', '<strong>' . tt('Внимание!') . '</strong> ' . tt('Заполните Email!'));
        }else{

        }

        if($universityCode==U_NULAU){
            Yii::app()->session['timeUserLogin'] = time();
        }
	}

	public function validateLogin(){
		$ps115 = PortalSettings::model()->getSettingFor(115);
		if($ps115==0)
			return true;
		$key = $this->_getKey();
		//проверка на совпадении спец ключа в куки
		$cookie=Yii::app()->request->cookies[self::COOKIE_NAME_AUTH_KEY];
		if($cookie==null)
			return false;
		if($cookie->value !== $this->_getCookieKey($key))
			return false;

		if(Yii::app()->session[self::SESSION_NAME_AUTH_KEY]!==$this->_getSessionKey($key))
            return false;

		if(Yii::app()->session[self::SESSION_NAME_AUTH_KEY1]!==$this->_getSessionKey1($key))
			return false;

        if(SH::getUniversityCod()==U_NULAU){
            if(!isset(Yii::app()->session['timeUserLogin']))
                return false;

            $timeLogin = Yii::app()->session['timeUserLogin'];
            if(time() - $timeLogin > 3600*8){
                return false;
            }
        }

		return true;
	}

	/**
	 * доб ключ для сессии и кук
	 * @return string
	 */
	protected function _getKey(){
		$key = '';
		if(isset(Yii::app()->params['login-key']))
			$key .= Yii::app()->params['login-key'];
		$ps117 = PortalSettings::model()->getSettingFor(117);
		if($ps117==1)
			$key.=Yii::app()->request->userAgent;

		$ps116 = PortalSettings::model()->getSettingFor(116);
		if($ps116==1)
			$key.=Yii::app()->request->userHostAddress;

		return $key;
	}

	const CRYPT_KEY_COOKIE = 'fsdfsd1';
	const CRYPT_KEY_SESSION = 'sada32';
	/**
	 * шифровка названиии ключа для сессии
	 * @return string
	 */
	protected function _getSessionKey($key){

		return md5($this->u12.self::CRYPT_KEY_SESSION.$key);
	}
	/**
	 * шифровка названиии ключа для куки
	 * @return string
	 */
	protected function _getCookieKey($key){
		return crypt($key.$this->u12,self::CRYPT_KEY_COOKIE);
	}

	/**
	 * шифровка названиии ключа для сессии1
	 * @return string
	 */
	protected function _getSessionKey1($key = ''){
		return crypt($this->u1,$key.'mkp');
	}

	protected function _generateU12(){
		$token = openssl_random_pseudo_bytes(10);
		$key   = bin2hex($token);

		$this->saveAttributes(array('u12'=>$key));
	}


    /**
     * Входяшие сообщения
     * @param $u1
     * @param bool $isStd
     * @return static[]
     */
    public function getInputMessages(){
        $extraQuery = $this->isStudent ? <<<SQL
              UNION
                SELECT um.* from UM
                  INNER JOIN gr on (gr1=um8)
                  INNER JOIN std on (std2={$this->u1} and std3=gr1)
                  where um8>0 and um7=0 and um9=0 and STD11 in (0,5,6,8) and (STD7 is null)
              UNION
                SELECT um.* from UM
                  INNER JOIN sg on (sg1=um9)
                  inner join gr on (sg.sg1 = gr.gr2)
                  INNER JOIN std on (std2={$this->u1} and std3=gr1)
                  where um8=0 and um7=0 and um9>0 and STD11 in (0,5,6,8) and (STD7 is null)
SQL
            : '';

        return Um::model()->findAllBySql(
            <<<SQL
              select * from (
                SELECT um.* from UM
                  where um7=:id
                {$extraQuery}
              ) order by um3 desc
SQL
            , array(
                ':id' => $this->u1
            )
        );
    }
}
