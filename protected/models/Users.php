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
 * @property string $u15
 * @property string $u16
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
		return array(
			array('u1', 'required'),
			array('u1, u5, u6, u7,u8', 'numerical', 'integerOnly'=>true),
			array('u6', 'default', 'value'=>0, 'setOnEmpty'=>TRUE),
			array('u10', 'default', 'value'=>'', 'setOnEmpty'=>TRUE),
			array('u3', 'match', 'pattern'=>'/^[a-zA-Z0-9-_\.,\/$|]{7,}$/','message'=>tt('В password могут быть только строчные и прописные латинские буквы, цифры, спецсимволы. Минимум 8 символов')),
			array('u4', 'length', 'max'=>400),
			array('u9, u10, u12', 'length', 'max'=>45),
            array('u15, u13, u16', 'length', 'max'=>20),
            array('u2, u4', 'checkIfUnique'),
            // Логин должен соответствовать шаблону
            array('u2', 'match', 'pattern'=>'/^[a-zA-Z][a-zA-Z0-9-_.]{7,30}$/','message'=>tt('В login могут быть только латинские символы и цифры, а так же символы "." и "_",  длиной от 8 до 30 символов. Также логин должен начинаться с латинской буквы')),
            array('u2, u3, u4, password', 'required', 'on'=>'admin-create,admin-update'),
			array('u2,u4 ,u3, password', 'safe', 'on'=>'change-password'),
			array('u8', 'unsafe', 'on'=>'change-password'),
			array('u2,u4 ,u3, password', 'required', 'on'=>'change-password'),
			array('u3', 'compare', 'compareAttribute'=>'password', 'on'=>'change-password,admin-create,admin-update'),
			array('u5,u6,u7,u9,u10,u11,u12, u13','unsafe')

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
		$salt = '$1$' .$hex;

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
		try { //TDMU - still go on on mail error
            Controller::mail($this->u4, tt('Пароль изменен'), $message);
        } catch (Exception $e) {
//            continue;
        }
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
		$uCod = Yii::app()->core->universityCode;
		return md5(crypt($uCod.'mkp'.$uCod.$this->u2.'mkr',$this->u1.$uCod.$this->u10));
	}

	public function isValidKey($key){
		$uCod = Yii::app()->core->universityCode;
		return $key===md5(crypt($uCod.'mkp'.$uCod.$this->u2.'mkr',$this->u1.$uCod.$this->u10));
	}

	/**
	 * Количество неудачных попыток авторизаций за последнии $countMin минут
	 * @param $countMin int количетво минут
	 * @return int
	 */
	public function getCountFail($countMin){
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

	public function afterLogin(){
		$ps114 = PortalSettings::model()->getSettingFor(114);
		if($ps114==1){
			$this->_generateU12();
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

        $universityCode = Yii::app()->core->universityCode;

        if(empty($this->u4)) {
            Yii::app()->user->setFlash('warning', '<strong>' . tt('Внимание!') . '</strong> ' . tt('Заполните Email!'));
        }
	}

	public function validateLogin(){
		return true;
	}

	protected function _generateU12(){
		$token = openssl_random_pseudo_bytes(10);
		$key   = bin2hex($token);

		$this->saveAttributes(array('u12'=>$key));
	}
    /**
     * @return string
     */
    public function getNameWithDept(){
	    $name = $this->getName();

	    if($this->isStudent){
            $st = St::model()->getInfoForStudentInfoExcel($this->u6);
            $name=$name.' ('.tt('гр.').$st['name'].')';
        }else if($this->isTeacher){
	        $p = P::model()->findByPk($this->u6);
            $chair = $p->getChair();
            $chairName = $chair == null ? '' : ' ('.tt('каф.').$chair->k2.')';
            $name=$name.$chairName;
        }

	    return $name;
    }
    /**
     * Входяшие сообщения
     * @param $period string
     * @return Um[]
     */
    public function getInputMessages($period=Um::TIME_PERIOD_MONTH){

        list($date1, $date2) = $this->_datesByPeriod($period);

        $extraQuery = $this->isStudent ? <<<SQL
              UNION
                SELECT um.* from UM
                  INNER JOIN gr on (gr1=um8)
                  INNER JOIN std on (std2={$this->u6} and std3=gr1)
                  where um8>0 and um7=0 and um9=0 and STD11 in (0,5,6,8) and (STD7 is null)
              UNION
                SELECT um.* from UM
                  INNER JOIN sg on (sg1=um9)
                  inner join gr on (sg.sg1 = gr.gr2)
                  INNER JOIN std on (std2={$this->u6} and std3=gr1)
                  where um8=0 and um7=0 and um9>0 and STD11 in (0,5,6,8) and (STD7 is null)
SQL
            : '';

        return Um::model()->findAllBySql(
            <<<SQL
              select * from (
                SELECT um.* from UM
                  where um7=:id
                {$extraQuery}
              ) where um3 between :DATE1 and :DATE2 order by um3 desc
SQL
            , array(
                ':id' => $this->u1,
                ':DATE1' => $date1,
                ':DATE2' => $date2
            )
        );
    }

    private function _datesByPeriod($period){
        $datetime1 = new DateTime('tomorrow');
        $datetime2 = new DateTime();

        if($period == Um::TIME_PERIOD_MONTH){
            $datetime2 = new DateTime('- 1 month');
        }
        if($period == Um::TIME_PERIOD_YEAR){
            $datetime2 = new DateTime('- 1 year');
        }


        return array($datetime2->format('Y-m-d H:i:s'), $datetime1->format('Y-m-d H:i:s'));
    }
    /**
     * Исходящие сообщения
     * @param $period string
     * @return Um[]
     */
    public function getOutputMessages($period=Um::TIME_PERIOD_MONTH){

        list($date1, $date2) = $this->_datesByPeriod($period);

        return Um::model()->findAllBySql(<<<SQL
    SELECT * FROM um WHERe um2=:um2 and um3 between :DATE1 and :DATE2 ORDER BY um3 DESC
SQL
            , array(
                ':um2'=> $this->u1,
                ':DATE1' => $date1,
                ':DATE2' => $date2
            ));
    }

    /**
     * @param $gr1
     * @return Users[]
     */
    public function getUsersByGroup($gr1){
        $sql = <<<SQL
        SELECT users.* FROM std
            INNER JOIN users on (u5=0 and u6=std2)
            where STD11 in (0,5,6,8) and (STD7 is null) and u4!='' and std3=:GR1
SQL;

        return $this->findAllBySql($sql, array(
            ':GR1' => $gr1
        ));
    }
}
