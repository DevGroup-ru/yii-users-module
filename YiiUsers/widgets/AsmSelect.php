<?php

class AsmSelect extends CWidget
{

	public $visible=true;
	public $target = -1;

	public function init()
	{
		if(!$this->visible)
			return;
		
	}

	public function run()
	{
		if(!$this->visible)
			return;
		$this->renderContent();
		
		
	}

	protected function renderContent()
	{
	   	$auth         = Yii::app()->authManager;
		$RoleName     = ''; // string(rolename)
		$arrTypeMap   = array(
			"0"=>Yii::t('YiiUsers', 'Operation'), 
			"1"=>Yii::t('YiiUsers', 'Task'), 
			"2"=>Yii::t('YiiUsers', 'Role')
		);   
		$arrOptGroups = array();
		$arrSelected  = array();
		
		// get all authItems and push it to a optgroup depending on its type.
		$authItems = $auth->getAuthItems();
		array_multisort($authItems);
		foreach($authItems as $authItem)
		{
			if($authItem->name != $RoleName)
			if ($authItem->description != "")
				$arrOptGroups[$arrTypeMap[$authItem->type]][$authItem->name]=$authItem->description;
			else
				$arrOptGroups[$arrTypeMap[$authItem->type]][$authItem->name]=$authItem->name;
		}
		
		// get all assigned authItems for the current role.
		if (is_object($this->target))
		{
			$childs = $auth->getAuthItems(null,$this->target->id);
			foreach($childs as $child)
					array_push($arrSelected, $child->name);
		}
		else
		{
			if (!empty($this->target)) {
				$childs = $auth->getItemChildren($this->target);
				
				foreach(array_keys($childs) as $child)
					array_push($arrSelected, $child);
			}
		}

		// and now we hand the obtained informations to a asmselectex instance
		$this->widget('ext.asmselectex.EasmSelectEx',array(
				'name'=>'assignmentList',
				'values'=>$arrOptGroups,
				'selected'=>$arrSelected,
				'attributes'=>array('title'=>Yii::t('YiiUsers', 'Choose rights to assign')),
				'scriptOptions'=>"addItemTarget: 'bottom', animate: true, highlight: true,
						sortable: false, removeLabel: '".Yii::t('YiiUsers', 'Remove').
						"', highlightAddedLabel: '" . Yii::t('YiiUsers', 'Added').
						": ', 
						highlightRemovedLabel: '".Yii::t('YiiUsers', 'Removed').": '"
		));
		
	}
}