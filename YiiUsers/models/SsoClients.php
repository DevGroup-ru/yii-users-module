<?php

class SsoClients extends CActiveRecord { 

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
		return 'SsoClients';
	}

	public function attributeLabels() {
		return array(
			'ip' => Yii::t('YiiUsers', 'IP'),
			'privateHash' => Yii::t('YiiUsers', 'Private hash'),
			'publicHash' => Yii::t('YiiUsers', 'Public hash'),

			);
	}

	public function rules() {
		return array(
				array('ip, privateHash, publicHash', 'required'),
                array('privateHash, publicHash', 'length', 'max'=>32),
                
			);
	}


	public function relations() {
		return array(
				
			);
	}


}