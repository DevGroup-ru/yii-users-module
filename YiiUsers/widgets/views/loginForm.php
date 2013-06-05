<div id="loginForm">
<div class="siteLogin">
	<a href="#" class="close"> </a>
	<div class="title"><?php echo Yii::t('YiiUsers', 'Login as:');?></div>
	<?php $form=$this->beginWidget('CActiveForm', array(
	        'id'=>'login-form',
	        'enableAjaxValidation'=>true,
	        'action'=>array('/User/user/login'),
	)); ?>
	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
        <?php echo $form->textField($model,'username'); ?>
        <?php echo $form->error($model,'username'); ?>
	</div>
	<div class="row">
        <?php echo $form->labelEx($model,'password'); ?>
        <?php echo $form->passwordField($model,'password'); ?>
        <?php echo $form->error($model,'password'); ?>
    </div>
    <div class="row rememberMe">
        <?php echo $form->checkBox($model,'rememberMe'); ?>
        <?php echo $form->label($model,'rememberMe'); ?>
        <?php echo $form->error($model,'rememberMe'); ?>
    </div>

    <div class="row submit">
            	
    	<?php echo CHtml::submitButton('Войти'); ?>
    	<?php echo CHtml::link(Yii::t('User', 'Register'), array('/User/user/registration'), array('class'=>'register'));?>
    </div>


	<?php $this->endWidget(); ?>
</div>

<div class="or"><?php echo Yii::t('User', 'or login using:');?></div>
<div class="loginza">
	
	<script src="http://loginza.ru/js/widget.js" type="text/javascript"></script>
	<iframe src="http://loginza.ru/api/widget?overlay=loginza&amp;token_url=http://<?php echo Yii::app()->params['mainHostName'];?>/user/login" 
	style="width:359px;height:200px" scrolling="no" frameborder="no"></iframe>
</div>
<script type="text/javascript">
$("#loginForm .close").click(function(){$("#loginForm").fadeOut()});
</script>
</div>
