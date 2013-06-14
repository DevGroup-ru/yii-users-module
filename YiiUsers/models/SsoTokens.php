<?php

class SsoTokens extends CActiveRecord { 


	/**
	 * Returns the static model of the specified AR class.
	 * @return Config the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'SsoTokens';
	}

	public function attributeLabels() {
		return array(
			'ip' => Yii::t('YiiUsers', 'IP'),
			

			);
	}

	public function rules() {
		return array(
				array('expires, token, userId, ip', 'required'),
                
                
			);
	}


	public function relations() {
		return array(
				
			);
	}

	public static function makeToken() {
		$model = new SsoTokens;
		$model->expires = time() + 3600;
		$req = Yii::app()->request;
		$model->token = md5(time() . ":" . $req->getUserAgent() . ":" . $req->userHostAddress . ":".rand(0,getrandmax()));
		$model->userId = Yii::app()->user->id;
		$model->ip = $req->userHostAddress;
		$model->save();
		return $model;
	}
}