<?php
/**
 * Created by PhpStorm.
 * User: NEFF
 * Date: 16.11.2017
 * Time: 17:07
 */

/**
 * Class SingUpOldDistEducationForm
 * @property $params array Массив параметров для привязки к сущетвующей учетке
 * @property $universityId int Код вуза
 */
abstract class SingUpOldDistEducationForm extends CFormModel implements ISingUpOldDistEducationForm
{
    /**
     * @var string
     */
    public $email;

    //public $verifyCode;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return array(
            // name, email, subject and body are required
            array('email', 'required'),

            array('email', 'filter', 'filter'=> 'trim'),
            // email has to be a valid email address
            array('email', 'email'),

            //array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
            array('email', 'application.validators.EmailValidator', 'validateDomen'=> true, 'universityCode' => Yii::app()->core->universityCode),

            array('email', 'unique', 'className'=>'Stdist', 'attributeName'=>'stdist2'),

            array('email', 'validateEmail'),
        );
    }

    /**
     *  валидатор для коюча validateKey
     */
    public function validateEmail($attribute,$params)
    {
        if(!$this->hasErrors()) {
            $connector = SH::getDistEducationConnector(Yii::app()->core->universityCode);

            if($connector == null)
                $this->addError($attribute, tt('Ошибка создания конектора' ));
            else{
                if(!$connector->validateEmail(trim($this->$attribute)))
                    $this->addError($attribute, tt('Некорректный Email или ошибка проверки' ));
            }
        }
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels()
    {
        return array(
            'email'=>tt('Email (существующей учетной записи дистанционного образования)'),
            'verifyCode'=>'Verification Code',
        );
    }

    /**
     * @var int Код вуза
     */
    protected $_universityId;

    /**
     * Код вуза
     * @return int
     */
    public function getUniversityId()
    {
        return $this->_universityId;
    }

    /**
     * Массив параметров для привязки к сущетвующей учетке
     * @return array
     */
    public function getParams()
    {
        return $this->_getParams();
    }

    /**
     * Массив параметров для привязки к сущетвующей учетке
     * @return array
     */
    protected function _getParams(){
        return array(
            'email' => $this->email
        );
    }

    /**
     * SingUpOldDistEducationForm constructor.
     * @param int $universityId
     */
    public function __construct($universityId)
    {
        $this->_universityId = $universityId;
        parent::__construct($universityId);
    }

    /**
     * Полядля формі ввода
     * @param $form TbActiveForm
     * @return string
     */
    public function getFormHtml($form){
        return $this->_getFormHtml($form);
    }

    /**
     * Полядля формі ввода
     * @param $form TbActiveForm
     * @return string
     */
    protected function _getFormHtml($form){
        $html = '';

        $html.=<<<HTML
                <label>
                <span class="block input-icon input-icon-right">
                    {$form->textFieldRow($this, 'email', array('class' => 'span12', 'placeholder' => tt('Email')))}
                    <i class="icon-envelope"></i>
                </span>
        </label>
HTML;
        return $html;
    }
}