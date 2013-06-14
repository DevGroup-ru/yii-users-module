<?php

class SsoHelper {
	public function javascript() {
		Yii::app()->clientScript->registerCoreScript("jquery");
		
		$time = time();
		$hash = md5(Yii::app()->authManager->privateHash.":".$time.":".Yii::app()->request->userHostAddress);
		$publicHash = Yii::app()->authManager->publicHash;

		return "<script type=\"text/javascript\" src=\"" .
			Yii::app()->authManager->ssoServer . "js?hash=$hash&publicHash=$publicHash&time=$time\"></script>";
	}
}