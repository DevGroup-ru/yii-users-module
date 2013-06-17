<?php
$this->widget('application.modules.YiiUsers.widgets.AsmSelect', array('target'=>$model));
?>
<div class="form-actions">
    <?php $this->widget(
    	'bootstrap.widgets.TbButton', 
        array(
        	'buttonType'=> 'submit', 
	        'type'=> 'primary', 
	        'label'=> Yii::t('YiiUsers', 'Save'), 
	        'icon'=> 'save',
        )
    ); ?>
</div>