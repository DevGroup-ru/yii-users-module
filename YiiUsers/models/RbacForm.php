<?php

class RbacForm extends CFormModel {
	public $name;
	public $description;
	public $type;

	public function rules() {
		return array(
				array('name', 'required'),
				array('description', 'length', 'max'=>255),
				array('type', 'numerical', 'integerOnly'=>true),
			);
	}
}