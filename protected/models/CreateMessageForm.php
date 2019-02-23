<?php

/**
 * Class CreateMessageForm
 */
class CreateMessageForm extends CFormModel
{
	public $body;

	public $notification;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('body', 'required'),
            array('notification', 'boolean'),
            array('text','filter','filter'=>array($obj=new CHtmlPurifier(),'purify')),
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
            'body'=> tt('Текст сообщения'),
            'notification' => tt('Уведомление')
        );
	}
}