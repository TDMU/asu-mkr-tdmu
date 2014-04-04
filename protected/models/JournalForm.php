<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class JournalForm extends CFormModel
{
	public $discipline;
	public $group;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('discipline, group', 'required'),
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
			'discipline'=> tt('Дисциплина'),
			'group'=> tt('Группа'),
		);
	}
}