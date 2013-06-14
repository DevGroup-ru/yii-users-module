<?php

class LogoutAction extends CAction {
	

	public function run() {
		Yii::app()->user->logout();
		Yii::app()->controller->redirect(
			Yii::app()->authManager->ssoServer . 'logout?returnUrl=' . urlencode(Yii::app()->user->returnUrl)
			);
	}


}