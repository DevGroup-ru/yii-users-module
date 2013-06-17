
<?php foreach ($model->auth as $auth): ?>
	<fieldset>
		<legend><?php echo $auth->identityClass;?></legend>
		<?php if ($auth->identityClass === 'StandardIdentity') : ?>
			<?php 
			echo $form->passwordFieldRow(
				$auth, 
				'password', 
				array(
					'value'=>'', 
				'hint'=>Yii::t('YiiUsers', 'Enter only on change'), 
				'name'=>'UserAuth['.$auth->id.'][password]'
				)
			); ?>

		<?php else: ?>
			<?php $this->widget('bootstrap.widgets.BootButton', array(
			    'label'=>Yii::t('YiiUsers', 'Revoke'),
			    'type'=>'warning', 
			    'size'=>'mini', 
			    
			    'htmlOptions'=>array(
			    	'data-authId'=>$auth->id,
			    	'class'=>'revokeAuth',
			    	),
			)); ?>
			<?php echo $form->textFieldRow($auth, 'provider', array('disabled'=>true));?>
			<?php echo $form->textFieldRow($auth, 'identity', array('disabled'=>true));?>
		<?php endif;?>
	</fieldset>
<?php endforeach; ?>

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
