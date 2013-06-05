<div id="loginForm"<?php if($emailRequired||$nameRequired) echo ' style="display:inline"';?>>
<div class="siteLogin userMenu">
	<a href="#" class="close"> </a>
	<div class="title">Здравствуйте!</div>
	<div class="row">
		Вы вошли как <?php echo $user->username;?>.
	</div>
	<?php if($emailRequired || $nameRequired): $form=$this->beginWidget('CActiveForm', array(
	        'id'=>'mini-profile-form',
	        'enableAjaxValidation'=>true,
	        //'enableClientValidation'=>true,
	        'action'=>array('/User/user/profileUpdate'),
	)); ?>
	<div class="row">
		Пожалуйста, заполните недостающую информацию.
	</div>
	<?php if ($nameRequired): ?>
	<?php echo BootstrapForm::formTextField($form, $user->profile, "firstName"); ?>
	<!--<div class="row">
		<?php echo $form->labelEx($user->profile,'firstName'); ?>
        <?php echo $form->textField($user->profile,'firstName'); ?>
        <?php echo $form->error($user->profile,'firstName'); ?>
	</div>-->
	<?php endif; ?>
	<?php if ($emailRequired): ?>
	<!--<div class="row">
        <?php echo $form->labelEx($user->profile,'email'); ?>
        <?php echo $form->emailField($user->profile,'email'); ?>
        <?php echo $form->error($user->profile,'email'); ?>
    </div>-->
    <?php echo BootstrapForm::formTextField($form, $user->profile, "email"); ?>
	<?php endif; ?>

    <div class="row submit">
            	<?php echo CHtml::link('Сохранить', array('/User/user/profileUpdate'), array('id'=>'MiniProfileUpdateLink')); ?>
    </div>


	<?php $this->endWidget(); endif; ?>

	<div class="row">
		<ul>
		<li><?php echo CHtml::link("Мой профиль", array("/User/user/profileUpdate"));?></li>
		<li><?php echo CHtml::link("Мои заказы", array("/Shop/orders/my"));?></li>
	</div>

	<?php echo CHtml::link("Выйти", array('/User/user/logout'), array('class'=>'logout')); ?>
</div>

<script type="text/javascript">
$("#loginForm .close").click(function(){$("#loginForm").css('display','none')});
$("#MiniProfileUpdateLink").on('click', function(){$('#mini-profile-form').submit();return false;});
</script>
</div>
