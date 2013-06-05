<?php

class UserProfile extends CActiveRecord { 


	/**
	 * @return array Model behaviors.
	 */
	public function behaviors() {
		return array(
				'ActiveRecord',
				'CTimestampBehavior' => array(
					'class'=>'zii.behaviors.CTimestampBehavior',
					'createAttribute'=>null,
					'updateAttribute'=>'updateTime',
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
		return '{{UserProfile}}';
	}

	public function attributeLabels() {
		return array(
			'firstName' => Yii::t('YiiUsers', 'First name'),
			'lastName' => Yii::t('YiiUsers', 'Last name'),
			'email' => Yii::t('YiiUsers', 'E-Mail'),

			);
	}

	public function rules() {
		return array(
				array('userId', 'required'),
                array('email', 'length', 'max'=>255),
                array('email', 'email'),
                array('firstName, lastName', 'safe'),
                // The following rule is used by search().
                array('firstName, lastName, email', 'safe', 'on'=>'search'),
			);
	}


	public function relations() {
		return array(
				'user' => array(self::BELONGS_TO, 'User', 'userId'),
			);
	}


	/**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {

            $criteria=new CDbCriteria;

            $criteria->compare('firstName',$this->firstName,true);
            $criteria->compare('lastName',$this->lastName,true);
            $criteria->compare('email',$this->email,true);

            return new CActiveDataProvider($this, array(
                    'criteria'=>$criteria,
            ));
    }
}