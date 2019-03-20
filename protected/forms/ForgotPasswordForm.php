<?php

class ForgotPasswordForm extends CFormModel
{
	public $email;

	public function rules()
	{
		return array(
            array('email', 'required'),
            array('email', 'email'),
            array('email', 'length', 'max'=>100),
            array('email', 'exist', 'className'=>'Users', 'attributeName'=>'u4'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
            'email' => tt('Эл. почта'),
		);
	}

}
