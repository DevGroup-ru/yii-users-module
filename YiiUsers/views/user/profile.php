<h1><?php echo Yii::t("YiiUsers", "Your profile");?></h1>
<?php $flash = Yii::app()->user->getFlash('profile');
if (!empty($flash)):?>
<div class="alert alert-success">
        <?php echo $flash; ?>
</div>
<?php endif; ?>

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		        'id'=>'profileForm',
		        'enableAjaxValidation'=>true,
		        'clientOptions' => array(
      				'validateOnSubmit'=>true,
      			),
      			'type'=>'horizontal',
		        'htmlOptions'=>array('class'=>'form-horizontal',),
		        'action'=>array('/User/user/profileUpdate'),
		));?>
	
		<?php echo $form->textFieldRow($model, "firstName"); ?>
		<?php echo $form->textFieldRow($model, "lastName"); ?>
		<?php echo $form->textFieldRow($model, "email"); ?>
		<div class="control-group">

			<div class="controls">
				<?php echo CHtml::submitButton(Yii::t("YiiUsers", "Save"), array('class'=>'btn'));?>
			</div>
		</div>
		
<?php $this->endWidget(); ?>