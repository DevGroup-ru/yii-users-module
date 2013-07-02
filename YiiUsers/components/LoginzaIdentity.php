<?php

class LoginzaIdentity extends CUserIdentity {
	public $widgetId = null;
	public $apiSignature = null;
	private $_id;


	public function authenticate() {
		
		if (isset($_POST['token'])) {
			$url = "http://loginza.ru/api/authinfo?token=".$_POST['token'];
			if (isset($this->widgetId, $this->apiSignature)) {
				$url .= "&id=[WIDGET_ID]&sig=[API_SIGNATURE]";
			}

			$authData = @json_decode(@file_get_contents($url));
			
			if (!is_object($authData)) {
				return false;
			}
			if (isset($authData->error_messages) || isset($authData->error_type)) {
				return false;
			}

			$module = Yii::app()->getModule("YiiUsers");

			if (isset($authData->identity, $authData->provider)) {

				// check, if user exists
				$auth = UserAuth::model()->find("identity=:identity", array('identity'=>$authData->identity));
				if (is_object($auth)) {
					// existing user
					$this->errorCode = self::ERROR_NONE;
					$this->_id = $auth->userId;
					
					return true;
				} elseif ($module->registrationEnabled) {
					
					$auth = new UserAuth();
					$auth->provider = $authData->provider;
					$auth->identity = $authData->identity;
					$auth->password = "";
					$auth->additionalData = json_encode($authData);
					$auth->identityClass = "LoginzaIdentity";

					$model = new User();
					$model->active = 0;
					
					$profile = new UserProfile();
					$standardAuth = new UserAuth();
					$standardAuth->identityClass = "StandardIdentity";
					$standardAuth->password = substr(md5(rand().rand()), 0, 8);


					$usernames = array();
					if (isset($authData->email)) {
						$usernames[] = $authData->email;
						$profile->email = $authData->email;
					}
					if (isset($authData->nickname)) {
						$usernames[] = $authData->nickname;
					}
					if (isset($authData->name->full_name)) {
						$usernames[] = $authData->name->full_name;
						$name_parts = explode(' ', $authData->name->full_name);
						$profile->firstName = $name_parts[0];
						if (isset($name_parts[1])) {
							$profile->lastName = $name_parts[1];
						}
					}
					if (isset($authData->name->first_name, $authData->name->last_name)) {
						$usernames[] = $authData->name->first_name . " " . $authData->name->last_name;
						$profile->firstName = $authData->name->first_name;
						$profile->lastName = $authData->name->last_name;
					}

					foreach ($usernames as $username) {
						$userExists = User::model()->cache(3600)->find("username=:username", array('username'=>$username));
						if (!is_object($userExists)) {
							$model->username = $username;
							break;
						}
					}
					if (empty($model->username)) {
						$model->username = ""; // or anonymous??
					} else {
						$model->active = 1;
					}
					$model->save(false);
					// @todo throw outside event for onUserCreate -- shop must create UserBalance record!
					// and rbac must create rights here!!! not outside.
					$profile->userId = $model->id;
					$profile->save(false);

					$auth->userId = $model->id;
					$auth->save(); // save loginza auth

					$standardAuth->userId = $model->id;
					$standardAuth->save();

					$this->_id = $model->id;
					$this->errorCode = self::ERROR_NONE;

					return true;
				}

			} else {

				return false;

			}

		
		} else {
			return false;
		}
	}

	public function getId() {
		return $this->_id;
	}

	public function getPersistentStates() {
	 	return array();
	}

	public function getName() {
		$user = Yii::app()->getModule("YiiUsers")->user;
		return is_object($user) ? $user->username : "%anonymous%";
	}
}