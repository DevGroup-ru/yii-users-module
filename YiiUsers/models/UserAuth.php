<?php

class UserAuth extends CActiveRecord { 

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
		return 'UserAuth';
	}

	public function attributeLabels() {
		return array(
			'identityClass' => Yii::t('YiiUsers', 'Identity class'),
			'password' => Yii::t('YiiUsers', 'Password'),
			'identity' => Yii::t('YiiUsers', 'Identity'),
			'provider' => Yii::t('YiiUsers', 'Provider'),
			'salt' => Yii::t('YiiUsers', 'Salt'),

			);
	}

	public function rules() {
		return array(
				array('userId, identityClass', 'required'),
                array('password, identity, provider', 'length', 'max'=>255),
                array('salt', 'length', 'max'=>128),
			);
	}


	public function relations() {
		return array(
				'user' => array(self::BELONGS_TO, 'User', 'userId'),
			);
	}

	public function beforeSave() {
        if ($this->isNewRecord && !empty($this->password)) {
                $this->salt = $this->generateSalt();
                $this->password = $this->hashPassword($this->password, $this->salt);
        }
        return parent::beforeSave();
    }

    /**
     * Generates a salt that can be used to generate a password hash.
     * @return string the salt
     */
    public function generateSalt()
    {
            return uniqid('',true);
    }
            
    
            
    /**
     * Generates the password hash.
     * @param string password
     * @param string salt
     * @return string hash
     */
    public function hashPassword($password,$salt)
    {
            return md5($salt.$password);
    }

}