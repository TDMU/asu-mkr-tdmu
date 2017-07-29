<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    protected $_id = null;
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */

	public function authenticate()
	{
        $params = array(
            //':USERNAME' => mb_strtolower($this->username),
            ':USERNAME' => $this->username,
            ':EMAIL'    => mb_strtolower($this->username),
            #':PASSWORD' => $this->password,
        );

        $criteria = new CDbCriteria;
        //$criteria->addCondition('LOWER(U2)=:USERNAME OR LOWER(U4)=:EMAIL');
        $criteria->addCondition('u2=:USERNAME OR LOWER(U4)=:EMAIL');
        $criteria->params = $params;

        $user = Users::model()->find($criteria);

        if (empty($user))
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        elseif (!$user->validatePassword($this->password)) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        }
        else {
            $this->errorCode=self::ERROR_NONE;
            $this->_id = $user->u1;
        }

        return !$this->errorCode;
	}

    public function getId()
    {
        return $this->_id;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }    

}