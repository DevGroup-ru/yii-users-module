<?php

class SsoUser extends CWebUser {
	public $_user = null;
	public $_profile = null;
	private $_client = null;

	public function getUser() {
		if (!$this->id) {
			return false;
		}
		if ($this->_user === null) {
			$userInfo = Yii::app()->cache->get("AuthenticatedUserInfo:id:".$this->id);
			if (!is_array($userInfo)) {
				// get user info by id
				$time = time();
				$hash = md5(Yii::app()->authManager->privateHash.":".$time);
				$publicHash = Yii::app()->authManager->publicHash;
				Yii::trace("Getting User by ID");
				$userInfo = $this->getClient()->getUserById($this->id, $hash, $publicHash, $time);

				if (!is_array($userInfo)) {
					throw new CException("Unable to get info form SSO server");
				}

				Yii::app()->cache->set("AuthenticatedUserInfo:id:".$this->id, $userInfo, 60*15); // cache for 15 minutes
			}

			$this->_user = $userInfo['user'];
			$this->_profile = $userInfo['profile'];
		} else {
			Yii::trace("Getting User from Cache");
		}
		return $this->_user;
	}

	public function getProfile() {
		if (!$this->id) {
			return false;
		}

		$this->getUser();
		return $this->_profile;
	}

	public function setUserInfo($userInfo) {
		Yii::app()->cache->set("AuthenticatedUserInfo:id:".$this->id, $userInfo, 60*15); // cache for 15 minutes
		$this->_user = $userInfo['user'];
		$this->_profile = $userInfo['profile'];
	}

	private function getClient() {
		if ($this->_client === null) {
			$this->_client = new SoapClient(Yii::app()->authManager->ssoServer."soap");
		}
		return $this->_client;
	}
}