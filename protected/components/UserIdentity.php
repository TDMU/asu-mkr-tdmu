<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    const ERROR_ACCOUNT_LOCKED=3;

    public $_lockout_attempts = 5; //Number of attempts before an account is locked

    public $_lockout_min = 10; //Number of min for lockout

    public $_lockout_min_attempts = 3; //Number of min for attempts

    public $_lockout_enable = false;

    protected $_id = null;

    public function __construct($username,$password){
        parent::__construct($username,$password);

        $this->_lockout_attempts = PortalSettings::model()->getSettingFor(111);
        $this->_lockout_min = PortalSettings::model()->getSettingFor(112);
        $this->_lockout_enable = PortalSettings::model()->getSettingFor(110);
        $this->_lockout_min_attempts = PortalSettings::model()->getSettingFor(113);
    }
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
        elseif($this->isLoginLocked($user)) {
            $this->errorCode=self::ERROR_ACCOUNT_LOCKED;
        }
        elseif (!$user->validatePassword($this->password)) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
            if($this->_lockout_enable) {
                $user->saveNewFail();

                if ($user->getCountFail($this->_lockout_min_attempts) >= $this->_lockout_attempts) {
                    $user->saveAttributes(array('u11' => date('d.m.Y H:i:s')));
                    $this->errorCode = self::ERROR_ACCOUNT_LOCKED;
                }
            }
        }
        else {
            $this->errorCode=self::ERROR_NONE;
            $this->_id = $user->u1;

            $user->saveAttributes(array('u11'=>null));
        }

        return $this->errorCode;
	}

    public function getId()
    {
        return $this->_id;
    }

    private function isLoginLocked($user)
    {
        if(!$this->_lockout_enable)
            return false;
        if(empty($user->u11))
            return false;

        $lastLock = new DateTime($user->u11);
        $currentDatetime = new DateTime(date('Y-m-d H:i:s'));
        $interval = $lastLock->diff($currentDatetime);
        if ($interval->days != 0)
            return false;
        elseif ($interval->i > $this->_lockout_min)
            return false;
        else
            return true;
    }

}