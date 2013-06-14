<div class="UserPanel-Login">
	<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'type'=>'inline',
		'htmlOptions'=>array('class'=>'UserPanel-SimpleLogin-Form'),
		'action' => Yii::app()->authManager->ssoServer . "LoginSso",
	)); ?>
 
	<?php echo $form->textFieldRow($model, 'username', array('class'=>'input-small')); ?>
	<?php echo $form->passwordFieldRow($model, 'password', array('class'=>'input-small')); ?>
	<?php echo $form->checkboxRow($model, 'rememberMe', array('class'=>'input-small')); ?>
	<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>Yii::t('YiiUsers', 'Log in'))); ?>

 	<?php echo $form->hiddenField($model, 'returnUrl'); ?>
 	<?php echo $form->hiddenField($model, 'receiveTokenUrl'); ?>
	<?php $this->endWidget(); ?>
</div>
<script>
$(function(){
	YiiUsers.checkLoggedIn()
})
</script>