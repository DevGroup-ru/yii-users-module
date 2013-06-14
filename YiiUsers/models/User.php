<?php

class User extends CActiveRecord { 

	/**
	 * @return array Model behaviors.
	 */
	public function behaviors() {
		return array(
				'CTimestampBehavior' => array(
					'class'=>'zii.behaviors.CTimestampBehavior',
					'createAttribute'=>'createTime',
					'updateAttribute'=>null,
				),
			);
	}

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
		return 'User';
	}

	public function attributeLabels() {
		return array(
			'username' => Yii::t('YiiUsers', 'Username'),
			'createTime' => Yii::t('YiiUsers', 'Registration date'),
			'active' => Yii::t('YiiUsers', 'Active'),

			);
	}

	public function rules() {
		return array(
				array('username', 'length', 'max'=>45,),
				array('active', 'numerical', 'integerOnly'=>true,),
			);
	}


	public function relations() {
		return array(
				'profile' => array(self::HAS_ONE, 'UserProfile', 'userId'),
				'auth' => array(self::HAS_MANY, 'UserAuth', 'userId'),
			);
	}

	/**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
            // Warning: Please modify the following code to remove attributes that
            // should not be searched.
            
            $criteria=new CDbCriteria;
            
            return new CActiveDataProvider($this, array(
                    'criteria'=>$criteria,
            ));
    }

	/**
     * Checks if the given password is correct.
     * @param string the password to be validated
     * @return boolean whether the password is valid
     */
    public function validatePassword($password)
    {
        foreach ($this->auth as $auth) {
                if (isset($auth->password)) {
        
                        if ($auth->hashPassword($password,$auth->salt)===$auth->password) {
                                return true;
                        }
                }
        }
        return false;
    }

}