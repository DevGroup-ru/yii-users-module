<?php

class UserController extends CController {
	public function actions()
    {
        return array(
            'soap'=>array(
                'class'=>'CWebServiceAction',
            ),
        );
    }

    /**
     * @param string item name
     * @param mixed userId
     * @param array params
     * @return bool
     * @soap
     */
    public function checkAccess($itemName, $userId, $params = array()){
    	return Yii::app()->authManager->checkAccess($itemName, $userId, $params);
    }

    /**
     * @param string Token
     * @param string Hash
     * @param string publicHash
     * @param string Time
     * @return array
     * @soap
     */
    public function getUserByToken($token, $hash, $publicHash, $time) {
    	$ssoClient = SsoClients::model()->cache(86400)->findByAttributes(array('publicHash'=>$publicHash));

    	if ($ssoClient === null) {
    		throw new CHttpException(403);
    	}

    	$checkHash = md5($ssoClient->privateHash . ":" . $time);
    	if ($checkHash !== $hash) {
    		throw new CHttpException(403, "Bad hash: ".var_export($time,true));
    	}

    	$token = SsoTokens::model()->findByAttributes(array('token'=>$token));
    	if ($token === null) {
    		throw new CHttpException(404);
    	}

    	$user = User::model()->with('profile')->findByPk($token->userId);
    	return array(
    			'user' => $user->getAttributes(),
    			'profile' => $user->profile->getAttributes(),
    		);
    }

    /**
     * @param int Id
     * @param string Hash
     * @param string publicHash
     * @param string Time
     * @return array
     * @soap
     */
    public function getUserById($id, $hash, $publicHash, $time) {
    	$ssoClient = SsoClients::model()->cache(86400)->findByAttributes(array('publicHash'=>$publicHash));

    	if ($ssoClient === null) {
    		throw new CHttpException(403);
    	}

    	$checkHash = md5($ssoClient->privateHash . ":" . $time);
    	if ($checkHash !== $hash) {
    		throw new CHttpException(403, "Bad hash: ".var_export($time,true));
    	}

    	$user = User::model()->with('profile')->findByPk($id);
    	return array(
    			'user' => $user->getAttributes(),
    			'profile' => $user->profile->getAttributes(),
    		);
    }

    public function actionJs($hash, $publicHash, $time) {
    	header("Content-Type: application/javascript");
    	// first find our record
    	$ssoClient = SsoClients::model()->cache(86400)->findByAttributes(array('publicHash'=>$publicHash));
    	if ($ssoClient === null) {
    		throw new CHttpException(403);
    	}

    	$checkHash = md5($ssoClient->privateHash . ":" . $time . ":" . Yii::app()->request->userHostAddress);
    	if ($checkHash !== $hash) {
    		throw new CHttpException(403, "Bad hash");
    	}

    	//ok, hash is ok, all's secured
    	$this->renderPartial("js", array('ssoClient'=>$ssoClient));
    }

    public function actionCheckLoggedIn() {
    	header("Content-Type: application/javascript");
    	$logged = !Yii::app()->user->isGuest;
    	$result = array('logged'=>$logged);
    	if ($logged) {
    		//create token!
    		$token = SsoTokens::makeToken();
    		$result ['token'] = $token->token;
    	}
    	echo "YiiUsers.checkLoggedInCallback(" . CJSON::encode($result).");";
    	
    }

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

	public function actionLoginSso() {
		
		$model = new LoginForm();

		if (Yii::app()->request->isPostRequest) {
			if (isset($_POST['LoginForm'])) {
				$model->attributes = $_POST['LoginForm'];
				if (isset($_POST['ajax'])) {
					echo CActiveForm::validate($model);
					Yii::app()->end();
				}
				if ($model->validate() && $model->login()) {
					$token = SsoTokens::makeToken();

					$this->redirect($model->receiveTokenUrl . "?token=".$token->token . "&returnUrl=".$model->returnUrl);
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
		if (isset($_GET['returnUrl'])) 
			$this->redirect($_GET['returnUrl']);
		else
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
                        'ESetReturnUrlFilter - login, logout, redirect, registration, js, checkLoggedIn',
                    ),
            );
    }
}