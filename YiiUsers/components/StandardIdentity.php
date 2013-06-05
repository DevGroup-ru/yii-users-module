<?php

class StandardIdentity extends CUserIdentity {
	protected $_id;

    /**
     * Authenticates a user.
     * @return boolean whether authentication succeeds.
     */
    public function authenticate() {
            $user=User::model()->findByAttributes(array('username'=>$this->username));
            if($user===null) {
            	$this->errorCode=self::ERROR_USERNAME_INVALID;
                
            }
            else if(!$user->validatePassword($this->password)) {
            	
            	$this->errorCode=self::ERROR_PASSWORD_INVALID;
              
            }
            else {
                $this->_id=$user->id;
                $this->username=$user->username;
                $this->errorCode=self::ERROR_NONE;

            }
            return !$this->errorCode;
    }

    /**
     * @return integer the ID of the user record
     */
    public function getId() {
            return $this->_id;
    }

}