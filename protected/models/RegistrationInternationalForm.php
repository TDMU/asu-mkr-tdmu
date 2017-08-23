<?php

class RegistrationInternationalForm extends CFormModel
{
	public $serial;
    public $number;
	public $email;
	public $username;
	public $password;
	public $password2;

    //public $verifyCode;

    private $_u5;
    private $_u6;
    private $_fio;

	public function rules()
	{
		return array(
            array('number', 'required'),
            array('serial', 'required','on'=>'step-2'),
			array('serial, email, username, password, password2, number', 'required','on'=>'step-3'),
            //array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements(),'on'=>'step-3'),
            array('serial', 'checkSerial'),
            array('number', 'checkNumber'),
            array('email', 'email'),
            array('email', 'unique', 'className'=>'Users', 'attributeName'=>'u4'),
            array('username', 'unique', 'className'=>'Users', 'attributeName'=>'u2'),
            array('password', 'compare', 'compareAttribute'=>'password2'),
            //array('username', 'match', 'pattern'=>'/^[A-z][\w]+$/','message'=>tt('В Логине могут быть только латинские символы')),
            array('username', 'match', 'pattern'=>'/^[a-zA-Z][a-zA-Z0-9]{7,30}$/','message'=>tt('В Логине могут быть только латинские символы и цифры, длиной от 8 до 30 символов. Первый символ обязательно буква')),
            array('password', 'match', 'pattern'=>'/^[a-zA-Z0-9-_\.,$|]{7,}$/','message'=>tt('В password могут быть только строчные и прописные латинские буквы, цифры, спецсимволы. Минимум 8 символов')),
            //array('password', 'match', 'pattern'=>'/^[A-z][\w]+$/','message'=>tt('В password могут быть только латинские символы')),
            array('password2', 'length', 'min' => 8),
            array('email', 'length', 'max'=>100),

		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
            'number' => tt('Номер паспорта'),
            'serial' => tt('Серия паспорта'),
            'email' => tt('Эл. почта'),
            //'username' => tt('Имя пользователя'),
            'username' => tt('Логин (регистрозависимый)'),
            'password' => tt('Пароль'),
            'password2' => tt('Повторите пароль'),
		);
	}

    /*public function checkExistence($attribute,$params)
    {
        $st = St::model()->findAll('st15=:ID', array(':ID'=>$this->$attribute));
        $p  = P::model()->findAll('p13=:ID', array(':ID'=>$this->$attribute));

        $thereIsNotSuchId = count($st) + count($p) == 0;
        if ($thereIsNotSuchId)
            $this->addError($attribute, tt('Идентификационный код не зарегистрирован!'));

        $thereIsDuplicate = count($st) + count($p) > 1;
        if ($thereIsDuplicate)
            $this->addError($attribute, tt('Идентификационный код зарегистрирован несколько раз!'));

        if (! $this->hasErrors($attribute)) {

            //$this->_u5 = $st ? 0 : 1;
            //$this->_u6 = $st ? $st[0]->st1 : $p[0]->p1;
            $this->_u5 = $p ? 1 : 0;
            $this->_u6 = $p ? $p[0]->p1 : $st[0]->st1;
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
    }*/

    public function checkNumber($attribute,$params)
    {
        $count = St::model()->count(new CDbCriteria(array
        (
            'condition' => 'ST18 = :NUMBER and st63 > 0',
            'params' => array(':NUMBER'=>$this->number)
        )));

        if($count==0)
            $this->addError($attribute, tt('Не найдены иност. граждане с таким номером паспорта'));
    }

    public function checkSerial($attribute,$params)
    {
        if (empty($this->number))
            $this->addError($attribute, tt('Пустой номер паспорта'));

        $students = St::model()->findAll(new CDbCriteria(array
        (
            'condition' => 'ST18 = :NUMBER AND  ST17 =:SERIAL and st63 > 0',
            'params' => array(
                ':NUMBER'=>$this->number,
                ':SERIAL'=>$this->serial
            ),
            'order'=>'st1 DESC'
        )));

        if(count($students)==0)
            $this->addError($attribute, tt('Не найдены иност. граждане с таким номером и серией паспорта'));
        else{
            $student = $students[0];

            $this->_u5 = 0;
            $this->_u6 = $student->st1;

            $this->_fio = $student->st2.' '.$student->st3.' '.$student->st4;

            $alreadyRegistered = 1 <= Users::model()->countByAttributes(
                    array('u5'=>$this->_u5,'u6'=>$this->_u6),
                    array(
                        'condition'=>'u2 != :U2',
                        'params'=>array(':U2'=>"")
                    ));
            if ($alreadyRegistered)
                $this->addError($attribute, tt('Иност. гражданин с таким номером и серией паспорта уже зарегистрирован!'));
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
}
