<?php /** @var BootActiveForm $form */
	$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'=>'Form-User',
		'type'=>'horizontal',
	)); ?>
 
    <fieldset>
 
        <?php echo $form->textFieldRow($model, 'firstName'); ?>
        <?php echo $form->textFieldRow($model, 'lastName'); ?>
        <?php echo $form->textFieldRow($model, 'email',
        array(
        	'prepend'=>'<i class="icon-envelope"></i>',
        )); ?>
       
 
    </fieldset>
 
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
 
<?php $this->endWidget(); ?>