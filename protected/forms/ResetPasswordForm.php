<?php

/**
 * ResetPasswordForm class.
 * ResetPasswordForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class ResetPasswordForm extends CFormModel
{
	public $password , $repeatPassword;
	public $verifyCode;
	/**
	 * @var Users
	 */
	private $_user;
	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// password are required
			array('password, repeatPassword', 'required','on'=>'reset-password'),
			array('password', 'match', 'pattern'=>'/^[a-zA-Z0-9-_\.,\/$|]{7,}$/','message'=>tt('В password могут быть только строчные и прописные латинские буквы, цифры, спецсимволы. Минимум 8 символов')),
			array('password', 'compare', 'compareAttribute'=>'repeatPassword','on'=>'reset-password'),
			// verifyCode needs to be entered correctly
			array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'verifyCode'=>'Verification Code',
			'password'=>'Password',
			'repeatPassword'=>'Repeat passowrd'
		);
	}

	public function __construct($token, $config = array())
	{
		if (empty($token) || !is_string($token)) {
			throw new Exception('Password reset token cannot be blank.');
		}
		$this->_user = Users::model()->findByAttributes(array('u10'=>$token));
		if (!$this->_user) {
			throw new Exception('Wrong password reset token.');
		}
		parent::__construct($config);
	}

	public function resetPassword()
	{
		$user = $this->_user;
		$user->u3=$this->password;
		$user->removePasswordResetToken();

		return $user->save(false);
	}

	public function isValid($token){
		return $this->_user->isPasswordResetTokenValid($token);
	}

	public function isValidKey($key){
		return $this->_user->isValidKey($key);
	}
}