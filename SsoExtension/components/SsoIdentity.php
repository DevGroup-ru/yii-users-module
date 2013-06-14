<?php

class SsoIdentity extends CUserIdentity {
	protected $_id;
	public $userInfo = null;

	public function authenticate() {
		
		Yii::app()->user->setUserInfo($this->userInfo);
		
		
		return true;
		
	}

	/**
     * @return integer the ID of the user record
     */
    public function getId() {
        return $this->_id;
    }

    public function setId($id) {
    	$this->_id = $id;
    }


}