<?php
/**
 * Created by PhpStorm.
 * User: neffa
 * Date: 05.03.2019
 * Time: 13:13
 */

class CreateRequestPaymentForm extends CFormModel
{
    public $lessons = array();

    public function rules()
    {
        return array(
            array('lessons', 'required'),
            array('lessons', 'arrayOfInt', 'allowEmpty' => false),
        );
    }

    public function arrayOfInt($attributeName, $params)
    {
        $allowEmpty = false;
        if (isset($params['allowEmpty']) and is_bool($params['allowEmpty'])) {
            $allowEmpty = $params['allowEmpty'];
        }
        if (!is_array($this->$attributeName)) {
            $this->addError($attributeName, "$attributeName must be array.");
        }
        if (empty($this->$attributeName) && !$allowEmpty) {
            $this->addError($attributeName, "$attributeName cannot be empty array.");
        }
        foreach ($this->$attributeName as $key => $value) {
            if (!is_numeric($value)) {
                $this->addError($attributeName, "$attributeName contains invalid value: $value.");
            }
        }
    }

    /**
     * @return bool
     */
    public function validateLessons(){
        if(empty($this->lessons))
            return false;

        if(!$this->validate())
            return false;

        $passList = $this->_getPass();

        var_dump($passList);

        if(count($passList)!=count($this->lessons))
            return false;

        foreach ($passList as $pass){
            if($pass['otrabotal'] == 1)
                return false;

            if(!empty($pass['RPSPR0']))
                return false;
        }

        return true;
    }

    /**
     * Пропуски студента (для расширеной регитсрации пропусков / карточка студента)
     * @return array
     * @throws CHttpException
     */
    public function _getPass(){

        $passListStr = implode(', ', $this->lessons);

        $sql=<<<SQL
            SELECT * from EL_GURNAL_STUD_PROP(:st1, :year, :sem) where elgp0 in ($passListStr)
SQL;

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':st1', Yii::app()->user->dbModel->st1);
        $command->bindValue(':year', Yii::app()->session['year']);
        $command->bindValue(':sem', Yii::app()->session['sem']);
        $passes = $command->queryAll();

        return $passes;
    }
}