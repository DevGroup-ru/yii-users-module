<?php

class AbstractIdentity extends CUserIdentity {
	protected $_id;
	private $_authenticatedWith = false;

	public function authenticate($vars) {
		$module = Yii::app()->getModule("YiiUsers");

		$enabledIdentities = $module->enabledIdentities;
		$identities = array();

		foreach ($enabledIdentities as $key=>$value) {
			if (is_array($value)) {
				$config = $value;
				$config ['class'] = $key;
				$identities [$key] = Yii::createComponent($config);
				foreach ($vars as $variableKey =>$variableValue) {
					if (property_exists($identity [$key], $variableKey)) {
						$identities [$key]->$variableKey = $variableValue;
					}
				}
			} else {
				$identities [$value] = Yii::createComponent($value);
				foreach ($vars as $variableKey=>$variableValue) {
					if (property_exists($identity [$value], $variableKey)) {
						$identities [$value]->$variableKey = $variableValue;
					}
				}
			}
		}

		foreach ($identities as $identity) {
			if ($identity->authenticate()) {
				$this->_authenticatedWith = get_class($identity);
				$this->setId($identity->getId());
				return true;
			}
		}
		return false;
	}

	/**
     * @return integer the ID of the user record
     */
    public function getId() {
        return $this->_id;
    }

    public function setId($id) {
    	$this->_id = $id;
    }
}