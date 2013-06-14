<?php

class AdminController extends CController {
	public function actionIndex() {
		$model = new User;
		$this->render('index', array(
			'model' => $model,
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

		$this->render('view', array(
			'model' => $model,
		));
	}
}