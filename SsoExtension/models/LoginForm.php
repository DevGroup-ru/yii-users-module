<?php

class LoginForm extends CFormModel {
	public $username;
	public $password;
	public $rememberMe;
	public $returnUrl;
	public $receiveTokenUrl;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('username, password, returnUrl', 'required'),
			// rememberMe needs to be a boolean
			array('rememberMe', 'boolean'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'rememberMe'=>Yii::t('YiiUsers', 'Remember me'),
			'username' => Yii::t('YiiUsers', 'Username'),
			'password' => Yii::t('YiiUsers', 'Password'),

		);
	}
}