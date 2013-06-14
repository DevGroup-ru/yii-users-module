<?php

class ReceiveTokenAction extends CAction {
	private $_client = null;

	public function run() {
		if (isset($_GET['token'], $_GET['returnUrl'])) {
			// get token form sso server and auth user
			$time = time();
			$hash = md5(Yii::app()->authManager->privateHash.":".$time);
			$publicHash = Yii::app()->authManager->publicHash;
			Yii::trace("Getting User by Token");
			$userInfo = $this->getClient()->getUserByToken($_GET['token'], $hash, $publicHash, $time);

			if (is_array($userInfo)) {
				// login user!
				$identity = new SsoIdentity('','');

				$identity->userInfo = $userInfo;
				$identity->setId ($userInfo['user']['id']);
				if ($identity->authenticate()) {

					//! @todo Make configurable
					//$duration=$this->rememberMe ? 3600*24*30 : 3600*24*7; // 30 days
					
					Yii::app()->user->login($identity, 3600*24*7);

					Yii::app()->controller->redirect($_GET['returnUrl']);
				} else {
					throw new CException("Error authenticating", 1);
				}
			} else {
				throw new CException("Error Processing Request - bad user info", 1);
				
			}
		}
	}

	private function getClient() {
		if ($this->_client === null) {
			$this->_client = new SoapClient(Yii::app()->authManager->ssoServer."soap");
		}
		return $this->_client;
	}

}