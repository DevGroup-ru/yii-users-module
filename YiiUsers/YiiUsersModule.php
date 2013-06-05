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

	public $user = null;

	public $cacheId = 'cache';
	public $cachingDuration = 0;
	
	public function init() {
		Yii::import("zii.behaviors.CTimestampBehavior");

		Yii::import("application.modules.YiiUsers.models.*");
		Yii::import("application.modules.YiiUsers.components.*");
		$this->initUser();
		return true;
	}

	public function initUser() {

		if (Yii::app()->user->isGuest == false) {
			if ($this->cachingDuration > 0 && $this->cacheId !== false && ($cache=Yii::app()->getComponent($this->cacheId))!==null){
				$this->user = $cache->get("User:".Yii::app()->user->id);
			}
			
			
			if (!is_object($this->user)) {
				$this->user = User::model()->with('profile')->together(true)->findByPk(Yii::app()->user->id);
				
				if (isset($cache))
					Yii::app()->cache->set("User:".Yii::app()->user->id, $this->user, 86400);
			}
		}

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