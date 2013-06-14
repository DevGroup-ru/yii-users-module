<?php

class UserPanelWidget extends CWidget {

	public $user = null;
	
	public $receiveTokenUrl = null;


	public function run() {

		if ($this->receiveTokenUrl === null) {
			$this->receiveTokenUrl = Yii::app()->request->getHostInfo()."/Site/ReceiveToken";
		}

		if (Yii::app()->user->isGuest) {
			$model = new LoginForm;

			$model->returnUrl = Yii::app()->request->url;
 			$model->receiveTokenUrl = $this->receiveTokenUrl;
			$this->render(
				"UserPanel-Login", 
				array(
					'model'=>$model, 
				)
			);
		} else {
			// get user here
		}
	}
}