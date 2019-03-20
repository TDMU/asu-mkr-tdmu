<?php

/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 13.09.2017
 * Time: 17:02
 */
class AsuEAuthUserIdentity extends CBaseUserIdentity
{
    const ERROR_NOT_AUTHENTICATED = 3;

    /**
     * @var EAuthServiceBase the authorization service instance.
     */
    protected $service;

    /**
     * @var string the unique identifier for the identity.
     */
    protected $id;

    /**
     * @var string the display name for the identity.
     */
    protected $name;
    /**
     * @var string the email for the identity.
     */

    /**
     * Constructor.
     *
     * @param EAuthServiceBase $service the authorization service instance.
     */
    public function __construct($service) {
        $this->service = $service;
    }

    /**
     * Authenticates a user based on {@link service}.
     * This method is required by {@link IUserIdentity}.
     *
     * @return boolean whether authentication succeeds.
     */
    public function authenticate() {
        if ($this->service->isAuthenticated) {
            $email = $this->service->getAttribute('email');

            $user = Users::model()->findByAttributes(array('u4'=>$email));

            if($user!=null) {

                $this->id = $user->u1;
                $this->name = $user->getName();

                $this->setState('id', $user->u1);
                $this->setState('service-id', $this->service->id);
                //$this->setState('name', $this->service->getAttribute('name'));
                $this->setState('service', $this->service->serviceName);

                $this->errorCode = self::ERROR_NONE;
            }else
            {
                $this->errorCode = self::ERROR_UNKNOWN_IDENTITY;
            }
        }
        else {
            $this->errorCode = self::ERROR_NOT_AUTHENTICATED;
        }
        return !$this->errorCode;
    }

    /**
     * Returns the unique identifier for the identity.
     * This method is required by {@link IUserIdentity}.
     *
     * @return string the unique identifier for the identity.
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Returns the display name for the identity.
     * This method is required by {@link IUserIdentity}.
     *
     * @return string the display name for the identity.
     */
    public function getName() {
        return $this->name;
    }

}