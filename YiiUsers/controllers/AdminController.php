<?php

class AdminController extends CController {

	public function filters()
    {
        return array(
            'accessControl',
        );
    }

    public function accessRules()
    {
        return array(
            array('allow',
                'actions'=>array('index', 'rbac'),
                'roles'=>array('List SSO users'),
            ),
            array('allow',
                'actions'=>array('view', 'editRbac', 'newUser'),
                'roles'=>array('Edit SSO users'),
            ),
            array('allow',
                'actions'=>array('deleteRbac', 'deleteRbacs'),
                'roles'=>array('Delete SSO users'),
            ),
            array('deny',
                'users'=>array('*'),
            ),
        );
    }

	public function actionIndex() {
		$model = new User('search');
		$model->unsetAttributes();
		if(isset($_GET['User']))
			$model->attributes=$_GET['User'];
		$this->render('index', array(
			'model' => $model,
		));
	}

	public function actionNewUser() {
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

				



				$this->redirect(array("/YiiUsers/Admin/view", 'id'=>$user->id));
			}
		}
		$this->render("registrationForm", array(
			'model'=>$registrationForm,
			));
	}

	public function actionView($id) {
		$model = User::model()->with('profile')->findByPk($id);
		if ($model === null) {
			throw new CHttpException(404);
		}

		if (isset($_POST['User'])) {
			$model->setAttributes($_POST['User']);
			if ($model->save()) {
				Yii::app()->user->setFlash('success', '<strong>Well done!</strong> User information saved.');
			}
		}

		if (isset($_POST['UserProfile'])) {
			$model->profile->setAttributes($_POST['UserProfile']);
			if ($model->profile->save()) {
				Yii::app()->user->setFlash('success', '<strong>Well done!</strong> User profile saved.');
			}
		}
		if (isset($_POST['assignmentList'])) {
			$auth = Yii::app()->authManager;
			$children = $auth->getAuthItems(null,intval($model->id));
			$arrSelected = array();
			if (isset($_POST['assignmentList'])) {
	            foreach($children as $child)
	            {
	                if (!in_array($child->name,$_POST['assignmentList']))
	                        $auth->revoke($child->name, $model->id);
	                else
	                        array_push($arrSelected, $child->name);
	            }
	            foreach ($_POST['assignmentList'] as $assigment)
	            {
	                if (!in_array($assigment,$arrSelected))
	                {
	                        $auth->assign($assigment, $model->id);
	                }
	            }
	            Yii::app()->authManager->save();
        	}
		}
		if (isset($_POST['UserAuth'])) {
			foreach ($model->auth as $key=>$auth) {
				
				if (isset($_POST['UserAuth'][$auth->id])) {

					if (array_key_exists('password', $_POST['UserAuth'][$auth->id])) {

						if (empty($_POST['UserAuth'][$auth->id]['password'])) {
							unset($_POST['UserAuth'][$auth->id]['password']);
						} else {

							$auth->salt = $auth->generateSalt();
							$_POST['UserAuth'][$auth->id]['password'] = $auth->hashPassword($_POST['UserAuth'][$auth->id]['password'], $auth->salt
								);
						}

					}
					
					$auth->attributes = $_POST['UserAuth'][$auth->id];
					
					$auth->userId = $model->id;
					if ($auth->id==-1) {
						$auth->id = null;
					}
					$auth->save();
					
				}
			}
			$model->getRelated('auth', true);
		}

		$this->render('view', array(
			'model' => $model,
		));
	}

	public function actionRbac() {
		$operations = $this->getAuthItems(0);
		$tasks = $this->getAuthItems(1);
		$roles = $this->getAuthItems(2);

		$this->render(
			"rbac",
			array(
				'operations' => $operations,
				'tasks' => $tasks,
				'roles' => $roles,
										
			)
		);
	}

	public function actionEditRbac($rbac="") {
		$form = new RbacForm();

		if (!empty($rbac)) {
			$authItem = Yii::app()->authManager->getAuthItem($rbac);
			$form->name = $authItem->name;
			$form->type = $authItem->type;
			$form->description = $authItem->description;

		}

		if (isset($_POST['RbacForm'])) {
			$form->attributes = $_POST['RbacForm'];
			if ($form->validate()) {
				if ($rbac === "") {
					// we are adding new

					$authItem = Yii::app()->authManager->createAuthItem($form->name, $form->type, $form->description);
					if (isset($_POST['assignmentList'])) {
						foreach ($_POST['assignmentList'] as $assigment)
	                    {
	                        $authItem->addChild($assigment);
	                    }
	                }
					Yii::app()->authManager->save();
					Yii::app()->user->setFlash('success', Yii::t("YiiUsers", 'Auth item added'));
					$this->redirect(array('/YiiUsers/Admin/Rbac'));
				} else {
					$authItem = Yii::app()->authManager->getAuthItem($rbac);
					$authItem->name = $form->name;

					
					$authItem->description = $form->description;
					if ($authItem->type == $form->type) {
						Yii::app()->authManager->saveAuthItem($authItem, $rbac);
					} else {
						Yii::app()->authManager->removeAuthItem($rbac);
						Yii::app()->authManager->createAuthItem($form->name, $form->type, $form->description);
					}

					$children = $authItem->getChildren();
                    $arrSelected = array();
                    foreach(array_keys($children) as $child)
                    {
                        if (!in_array($child,$_POST['assignmentList']))
                                $authItem->removeChild($child);
                        else
                                array_push($arrSelected, $child);
                    }
                    if (isset($_POST['assignmentList'])) {
	                    foreach ($_POST['assignmentList'] as $assigment)
	                    {
	                        if (!in_array($assigment,$arrSelected))
	                        {
	                                $authItem->addChild($assigment);
	                        }
	                    }
	                }
                    Yii::app()->authManager->save();

					Yii::app()->user->setFlash(
						'success', 
						Yii::t("YiiUsers", 'Auth item updated')
						);
					$this->redirect(array('/YiiUsers/Admin/Rbac'));
				}
			}
		}



		$this->render(
			"rbacForm",
			array(
				'model'=>$form,
				)
			);
	}

	public function actionDeleteRbac($rbac) {
		Yii::app()->authManager->removeAuthItem($rbac);
		Yii::app()->user->setFlash('success', Yii::t("YiiUsers", 'Auth item deleted'));
		
		$this->redirect(array('/YiiUsers/Admin/Rbac'));
	}

	public function actionDeleteRbacs() {
		if (isset($_GET['rbacsRows'])) {
			foreach ($_GET['rbacsRows'] as $rbac) {
				Yii::app()->authManager->removeAuthItem($rbac);
			}
			Yii::app()->user->setFlash('success', Yii::t("YiiUsers", 'Auth items deleted'));
			$this->redirect(array('/YiiUsers/Admin/Rbac'));
		} else {
			throw new CHttpException(400, "Bad request.");
		}
	}

	private function getAuthItems($type) {
		$array = array();
		$items = Yii::app()->authManager->getAuthItems($type);
		foreach ($items as $item) {
			$array[] = array('name'=>$item->name, 'description'=>$item->description, 'type'=>$item->type);
		}
		return new CArrayDataProvider(
			$array,
			array(
				'keyField'=>'name',
				'sort'=>array(
						'attributes'=>array(
							'name',
							'description',
							),
					),
				
				'pagination'=>array(
			        'pageSize'=>25,
			    ),
			)
		);
	}
}