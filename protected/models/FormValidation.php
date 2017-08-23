<?php

/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 09.02.2017
 * Time: 12:57
 */
class FormValidation extends CFormModel
{
    /**
     * имя ключа в сессии
     */
    const SESSION_FIELD_NAME = 'formKey';
    /**
     * имя ключа в куки
     */
    const COOKIE_FIELD_NAME = 'formKeyCookie';
    /**
     * имя ключа в куки
     */
    const COOKIE_KEY = 'formValidation';
    /**
     * время сколько даеться на ввод пароля
     */
    const TIME_TOKEN_VALID = 600;

    protected $_validationKey;
    /**
     * @var bool Была ли ошибка валидации (что бы нельзя в рамках этого конекта уже переопределить токен если уже была ошибка валидации)
     */
    protected $_isFalied = false;

    /**
     * геттер
     * @return string
     */
    public function getValidationKey(){
        return $this->_validationKey;
    }

    /**
     * сеттер
     * @param $value string
     */
    public function setValidationKey($value){
        $this->_validationKey = $value;
    }

    /**
     *  герерация ключа
     * @param array $params
     */
    public function setNewValidationKey($params = array()){
        if($this->_isFalied)/*Была ли ошибка валидации (что бы нельзя в рамках этого конекта уже переопределить токен если уже была ошибка валидации)*/
            return;
        $salt = openssl_random_pseudo_bytes(12);
        $this->_validationKey   = bin2hex($salt). '_'.time();
        //записываем куки
        $nameCookie = $this->_getCookieFiledName();
        $cookie=new CHttpCookie($nameCookie,$this->_getCookieKey());
        //$cookie->httpOnly = true;
        Yii::app()->request->cookies[$nameCookie]=$cookie;
        //записіваем в сессию
        Yii::app()->session[$this->_getSessionFiledName()] = CPasswordHelper::hashPassword($this->_validationKey);
    }

    /**
     * проверка ключа
     * @param array $params
     * @return bool
     */
    public function validateValidationKey($params = array()){

        if (empty($this->_validationKey) || !is_string($this->_validationKey)) {
            return false;
        }
        //проверка на предедущю страницу
        if(!$this->_validateUrlReferrer())
            return false;
        //проверка на совпадении спец ключа в куки
        $cookie=Yii::app()->request->cookies[$this->_getCookieFiledName()];
        if($cookie==null)
            return false;
        if($cookie->value !== $this->_getCookieKey())
            return false;
        //проверка на совпадение ключа в сессии
        /*if(Yii::app()->session[$this->_getSessionFiledName()]!==$this->_validationKey)
            return false;*/
        if(!(CPasswordHelper::verifyPassword($this->_validationKey, Yii::app()->session[$this->_getSessionFiledName()])))
            return false;
        //проверка на время
        if(!$this->_isKeyTimeValid())
            return false;
        return true;
    }

    /**
     * шифровка названиии поля сессии
     * @return string
     */
    protected function _getSessionFiledName(){
        return self::SESSION_FIELD_NAME;
    }

    /**
     * шифровка названиии поля куки
     * @return string
     */
    protected function _getCookieFiledName(){
        return crypt(self::COOKIE_FIELD_NAME, 'mkp'.date('Y-m-d').'portal');

        //return self::COOKIE_FIELD_NAME;
    }

    /**
     * шифровка названиии ключа для куки
     * @return string
     */
    protected function _getCookieKey(){
        //return self::COOKIE_KEY;
        return crypt(self::COOKIE_KEY, 'mkp'.date('Y-m-d').'portal');
    }

    /**
     * валидация на предедущую страницу
     * @return bool
     */
    protected function _validateUrlReferrer(){
        $url = Yii::app()->request->urlReferrer;
        $host = Yii::app()->request->hostInfo;

        $pos = strpos($url, $host);
        if ($pos === false)
            return false;

        return $pos==0;
    }

    /**
     *  валидатор для коюча validateKey
     */
    public function validateKey($attribute,$params)
    {
        if(!$this->hasErrors()) {
            if (!$this->validateValidationKey()) {
                $this->_isFalied = true;/*что бы нельзя в рамках этого конекта уже переопределить токен*/
                $attr = isset($params['fieldError'])?$params['fieldError']:$attribute;
                $this->addError($attr, tt('Ошибка!Проверка на валидность не пройдена! Попробуйте перезагрузить страницу и попробовать снова!' /*. Yii::app()->session[self::SESSION_FIELD_NAME] . ' / ' . $this->_validationKey*/));
            }
        }
    }

    /**
     * проверка токена на время
     * @return bool
     */
    protected function _isKeyTimeValid()
    {
        $token = $this->_validationKey;

        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = self::TIME_TOKEN_VALID;

        return $timestamp + $expire >= time();
    }

}