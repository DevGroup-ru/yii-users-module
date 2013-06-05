<?php

class AbstractIdentity extends CUserIdentity {
	protected $_id;
	private $_authenticatedWith = false;
	public $vars;

	public function authenticate() {
		$module = Yii::app()->getModule("YiiUsers");

		$enabledIdentities = $module->enabledIdentities;
		$identities = array();

		foreach ($enabledIdentities as $key=>$value) {
			$className = "Dummy";

			if (is_array($value)) {
				$className = $key;
			} else {
				$className = $value;
			}
			$class = new $className('', '');

			foreach ($this->vars as $variableKey=>$variableValue) {
				if (property_exists($class, $variableKey)) {
					$class->$variableKey = $variableValue;
				}
			}
			$identities[$className] = $class;
		}

		foreach ($identities as $identity) {
			if ($identity->authenticate()) {
				$this->_authenticatedWith = $identity;
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

    public function getAuthenticatedWith(){
    	return $this->_authenticatedWith;
    }
}