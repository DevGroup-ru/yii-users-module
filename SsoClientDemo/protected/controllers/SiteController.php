<?php

class SiteController extends Controller
{

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
                'actions'=>array('check'),
                'roles'=>array('Serpens user - guest'),
            ),
            array('deny',
                'actions'=>array('check'),
                'users'=>array('*'),
            ),
            array('deny',
                'actions'=>array('test'),
                'users'=>array('*'),
            ),

        );
    }

    public function actionTest() {
    	echo "OK";
    }

    public function actionCheck() {
    	var_dump(Yii::app()->user->checkAccess('Serpens user - guest'));
    }

	public function actions() {
		return array(
            'ReceiveToken'=>'ext.SsoExtension.actions.ReceiveTokenAction',
            'Logout'=>'ext.SsoExtension.actions.LogoutAction',
        );
	}
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}


}