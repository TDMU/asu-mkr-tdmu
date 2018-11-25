<?php

/**
 * CreateOprrezForm class.
 * Форма для создания и валидации ответа студента (опросник)
 */
class CreateOprrezForm extends CFormModel
{
	public $opr1;
	public $st1;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('opr1, st1', 'required'),
            array('opr1, st1', 'numerical', 'integerOnly'=>true),
            array('opr1', 'exist', 'className'=>'Opr', 'attributeName'=>'opr1'),
            array('st1', 'exist', 'className'=>'St', 'attributeName'=>'st1'),
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

		);
	}

    /**
     * сохранение ответа студента (опросник)
     * @return bool
     */
	public function saveOprrez(){

	    $model = Oprrez::model()->findByAttributes(array('oprrez2' => $this-> st1));
        if(empty($model))
	        $model = new Oprrez();

        $model->oprrez1 = new CDbExpression('GEN_ID(GEN_OPRREZ, 1)');
	    $model->oprrez2 = $this->st1;
	    $model->oprrez3 = $this->opr1;
        $model->oprrez4 =  date('Y-m-d H:i:s');
	    $model->oprrez5 = Yii::app()->user->id;

	    return $model->save();
    }

    /**
     * Удаление ответа студента (опросник)
     * @return int
     */
    public function deleteOprrez(){
	    return Oprrez::model()->deleteAllByAttributes(array('oprrez2' => $this->st1));
    }
}