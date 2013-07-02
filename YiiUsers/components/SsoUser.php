<?php

class SsoUser extends CWebUser {
	public $_user = null;
	public $_profile = null;
	public $cacheLifetime = 3600;

	public function getUser() {
		if (!$this->id) {
			return false;
		}
		if ($this->_user === null) {
			$user = User::model()->with('profile')->cache($this->cacheLifetime,new TagDependency("User", "UserProfile"))->findByPk($this->id);

			$this->_user = $user;
			$this->_profile = $user->profile;
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


}