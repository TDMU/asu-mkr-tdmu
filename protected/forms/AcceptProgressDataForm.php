<?php

/**
 * Форма согласия на обработку персональных данных
 * Class AcceptProgressDataForm
 */
class AcceptProgressDataForm extends CFormModel
{
    /**
     * @var boolean
     */
    public $accept;

    /**
     * @var Users
     */
    protected $_user;

    /**
     * AcceptProgressDataForm constructor.
     * @param $user Users
     * @param string $scenario
     */
    public function __construct($user, $scenario = '')
    {
        parent::__construct($scenario);
        $this->_user = $user;

        $this->accept = !empty($user->u16);
    }

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return array(
            array('accept', 'boolean','allowEmpty'=>false, 'message' => tt('Вы должны дать согласие на обработку ваших персональных данных')),
            array('accept','compare','compareValue'=>true, 'message' => tt('Вы должны дать согласие на обработку ваших персональных данных')),
        );
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'accept' => tt('Я даю согласие на обработку моих персональных данных')
        ];
    }

    /**
     * Сохрарение
     * @return bool
     * @throws CDbException
     */
    public function save(){
        if(!$this->validate())
            return false;

        return $this->_user->saveAttributes([
            'u16' =>  date('Y-m-d H:i:s')
        ]);
    }
}