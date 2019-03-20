<?php

class RegistrationForm extends CFormModel
{
	const TYPE_STUDENT = 0;
    const TYPE_TEACHER = 1;
    const TYPE_PARENT = 2;
    const TYPE_DOCTOR = 3;

    public $identityCode;
	public $email;
	public $username;
	public $password;
	public $password2;

    //public $verifyCode;

    public $type = self::TYPE_TEACHER;

    private $_u5;
    private $_u6;

    private $_fio;

	public function rules()
	{
		return array(
			array('identityCode, email, username, password, password2,type', 'required'),
            array('type', 'default', 'value'=>self::TYPE_TEACHER, 'setOnEmpty'=>TRUE),
            array('type', 'in', 'range' => array(self::TYPE_STUDENT, self::TYPE_TEACHER, self::TYPE_PARENT, self::TYPE_DOCTOR)),
            array('identityCode', 'checkExistence'),
            array('email', 'filter', 'filter'=>'trim'),
            array('email', 'email'),
            array('email', 'unique', 'className'=>'Users', 'attributeName'=>'u4'),
            array('username', 'unique', 'className'=>'Users', 'attributeName'=>'u2'),
            array('password', 'compare', 'compareAttribute'=>'password2'),
            //array('username', 'match', 'pattern'=>'/^[A-z][\w]+$/','message'=>tt('В Логине могут быть только латинские символы')),
            array('username', 'match', 'pattern'=>'/^[a-zA-Z][a-zA-Z0-9-_.]{7,30}$/','message'=>tt('В login могут быть только латинские символы и цифры, а так же символы "." и "_",  длиной от 8 до 30 символов. Также логин должен начинаться с латинской буквы')),
            array('password', 'match', 'pattern'=>'/^[a-zA-Z0-9-_\.,$|]{7,}$/','message'=>tt('В password могут быть только строчные и прописные латинские буквы, цифры, спецсимволы. Минимум 8 символов')),
            //array('password', 'match', 'pattern'=>'/^[A-z][\w]+$/','message'=>tt('В password могут быть только латинские символы')),
            //array('username, password', 'length', 'min' => 6,'max'=>50),
            array('email', 'length', 'max'=>100),
            //array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
            'identityCode' => tt('Идентификационный код'),
            'email' => tt('Эл. почта'),
            //'username' => tt('Имя пользователя'),
            'username' => tt('Логин (регистрозависимый)'),
            'password' => tt('Пароль'),
            'password2' => tt('Повторите пароль'),
            'verifyCode'=>'Verification Code',
		);
	}

    public function checkExistence($attribute,$params)
    {
        /*$st = St::model()->with(array(
            'std' => array(
                'join' => 'inner join std on (st1 = std2)',
                'condition' => "std11 in (0,3,5,7,6,8) and (std7 is null)",
            )
        ))->findAll('st15=:ID ORDER BY st1 DESC', );*/

        $st = $p = array();

        if($this->type==self::TYPE_STUDENT||$this->type==self::TYPE_PARENT) {
            $st = St::model()->findAllBySql(<<<SQL
          SELECT st.* FROM st
            inner join std on (st1 = std2)
          WHERE std11 in (0,3,5,7,6,8) and (std7 is null) AND st15=:ID ORDER BY st1 DESC
SQL
                , array(':ID' => $this->$attribute));
        }else {
            $p = P::model()->findAll('p13=:ID', array(':ID' => $this->$attribute));
        }

        $thereIsNotSuchId = count($st) + count($p) == 0;
        if ($thereIsNotSuchId)
            $this->addError($attribute, tt('Идентификационный код не зарегистрирован!'));

        $thereIsDuplicate = count($st) + count($p) > 1;
        if ($thereIsDuplicate)
            $this->addError($attribute, tt('Идентификационный код зарегистрирован несколько раз!'));

        if (! $this->hasErrors($attribute)) {

            $isTeacher = $this->type==self::TYPE_TEACHER || $this->type==self::TYPE_DOCTOR;
            //$this->_u5 = $st ? 0 : 1;
            //$this->_u6 = $st ? $st[0]->st1 : $p[0]->p1;
            $this->_u5 = $this->type;
            $this->_u6 = $isTeacher ? $p[0]->p1 : $st[0]->st1;

            $this->_fio = $isTeacher ? $p[0]->p3.' '.$p[0]->p4.' '.$p[0]->p5 : $st[0]->st2.' '.$st[0]->st3.' '.$st[0]->st4;
            //$alreadyRegistered = 1 <= Users::model()->count('u5=:U5 AND u6=:U6 AND u2!=""',array(':U5'=>$this->_u5,':U6'=>$this->_u6));
            $alreadyRegistered = 1 <= Users::model()->countByAttributes(
                array('u5'=>$this->_u5,'u6'=>$this->_u6),
                array(
                    'condition'=>'u2 != :U2',
                    'params'=>array(':U2'=>"")
                ));
            if ($alreadyRegistered)
                $this->addError($attribute, tt('Пользователь с таким идентификационным кодом уже зарегистрирован!'));
        }
    }

    public function register()
    {
        $user = Users::model()->findByAttributes(array('u5'=>$this->_u5,'u6'=>$this->_u6,'u2'=>""));
        if(empty($user)) {
            $user = new Users;
            $user->u1 = new CDbExpression('GEN_ID(GEN_USERS, 1)');
        }
        $user->u2 = $this->username;
        $user->u3 = $this->password;
        $user->u4 = $this->email;
        $user->u5 = $this->_u5;
        $user->u6 = $this->_u6;
        $user->u10 = '';
        $user->save(false);

        return true;
    }

    public function getFio(){
        return $this->_fio;
    }

    public function getTypes(){
        return array(
            self::TYPE_TEACHER => tt('Сотрудник'),
            self::TYPE_STUDENT => tt('Студент'),
            self::TYPE_PARENT => tt('Родитель'),
            self::TYPE_DOCTOR=> tt('Врач')
        );
    }
}
