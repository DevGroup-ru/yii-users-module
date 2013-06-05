<?php

class UserController extends CController {

	// @todo add META NOINDEX,NOFOLLOW HERE, just for the case
	public function actionLogin() {
		
		$model = new LoginForm();

		if (Yii::app()->request->isPostRequest) {
			if (isset($_POST['LoginForm'])) {
				$model->attributes = $_POST['LoginForm'];
				if (isset($_POST['ajax'])) {
					echo CActiveForm::validate($model);
					Yii::app()->end();
				}
				if ($model->validate() && $model->login()) {
					$this->redirect(Yii::app()->user->returnUrl);
				}
			}
		}

		$registrationForm = new RegistrationForm();
		$this->render("login", array(
			'loginForm'=>$model,
			'registrationForm'=>$registrationForm,
			));
	}

	public function actionRegistration() {

		$module = Yii::app()->getModule("YiiUsers");
		if ($module->registrationEnabled == false) {
			throw new CHttpException(404);
		}

		$registrationForm = new RegistrationForm();
		if (isset($_POST['RegistrationForm'])) {
			$registrationForm->attributes = $_POST['RegistrationForm'];
			if (isset($_POST['ajax'])) {
				echo CActiveForm::validate($registrationForm);
				Yii::app()->end();
			}

			if ($registrationForm->validate()) {
				$user = new User();
				$user->username = $registrationForm->username;
				
				$user->active = 1;
				$user->save();
				$userProfile = new UserProfile();
				$userProfile->email = $registrationForm->email;
				$userProfile->userId = $user->id;
				$userProfile->save();
				$userAuth = new UserAuth();
				$userAuth->identityClass = 'StandardIdentity';
				$userAuth->userId = $user->id;
				$userAuth->password = $registrationForm->password;
				$userAuth->save();

				$loginForm = new LoginForm();
				$loginForm->username = $user->username;
				$loginForm->password = $registrationForm->password;
				$loginForm->rememberMe = 1;
				$loginForm->validate();
				$loginForm->login('StandardIdentity');

				$this->raiseEvent("onAfterUserRegistered", new CEvent($this, array('user'=>&$user)));

				$this->redirect(Yii::app()->user->returnUrl);
			}
		}
		$this->render("registrationForm", array(
			'model'=>$registrationForm,
			));
	}

	public function onAfterUserRegistered($event) {

	}

	public function actionLogout() {
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->user->returnUrl);
	}

	public function actionProfileUpdate() {
		if (Yii::app()->user->isGuest == true) {
			throw new CHttpException(403);
		}

		if (is_object($this->module->user)==false){
			$this->redirect(array("/YiiUsers/user/login"));
		}

		$model = $this->module->user->profile;
		if (isset($_POST['UserProfile'])) {
			$model->attributes = $_POST['UserProfile'];
			if (isset($_POST['ajax'])) {
				echo CActiveForm::validate($model);
				Yii::app()->end();
			}
		
			try {
				$model->save();
				Yii::app()->user->setFlash('profile', Yii::t('YiiUsers', 'Your profile was updated.'));
			} catch (Exception $e) {};
		}
		$this->render("profile", array('model'=>$model,));
		
	}



	public function filters() {
        return array(
                array(
                        'ESetReturnUrlFilter - login, logout, redirect, registration',
                    ),
            );
    }
}