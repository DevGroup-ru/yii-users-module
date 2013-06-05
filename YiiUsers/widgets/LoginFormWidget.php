<?php

class LoginFormWidget extends CWidget {
	public function run() {
		
		if (Yii::app()->user->isGuest == true || is_object(Yii::app()->getModule("YiiUsers")->user)==false) {
			$model = new LoginForm();
			$this->render("loginForm", array('model'=>$model));
		} else {
			// check if user specified firstName and email
			$user = Yii::app()->getModule("YiiUsers")->user;
			
			$nameRequired = empty($user->profile->firstName);
			$emailRequired = empty($user->profile->email);

			$this->render("userMenu", array(
					'user'=>$user,
					'nameRequired' => $nameRequired,
					'emailRequired' => $emailRequired,
				));

		}
	}
}