<?php

class YiiUsersModule extends CWebModule {
	public $enabledIdentities = array(
			'StandardIdentity',
			'LoginzaIdentity' => array(
					'widgetId' => '[WIDGET_ID]',
					'apiSignature' => '[API_SIGNATURE]',
				),
		);
	public $registrationEnabled = true;

	public $ssoEnabled = true;

	public $registrationUrl = array("/YiiUsers/user/registration");
	public $loginUrl = array("/YiiUsers/user/login");
	public $profileUpdateUrl = array('/YiiUsers/user/profileUpdate');

	public function init() {
		Yii::import("zii.behaviors.CTimestampBehavior");

		Yii::import("application.modules.YiiUsers.models.*");
		Yii::import("application.modules.YiiUsers.components.*");

		return true;
	}

	public function isIdentityEnabled($identity) {
		foreach ($this->enabledIdentities as $key=>$value) {
			if (is_array($value) && $key === $identity) {
				return true;
			} else {
				if ($value === $identity) {
					return true;
				}
			}
		}
		return false;
	}
}