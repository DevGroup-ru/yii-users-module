<?php /** @var BootActiveForm $form */
	$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'=>'Form-User',
		'type'=>'horizontal',
	)); ?>
 
    <fieldset>
 
        <?php echo $form->textFieldRow($model, 'username',
        array('hint'=>'Used as login name')); ?>
        <?php echo $form->datepickerRow($model, 'createTime',
        array(
        	'hint'=>'When user was created',
        	'prepend'=>'<i class="icon-calendar"></i>',
        	'options'=>array(
        		'format' => 'yyyy-mm-dd H:i:s',
        		'language'=>'ru',
		        'autoclose'=>'true',
		        'weekStart'=>1,
		        'keyboardNavigation'=>true
        	),
        )); ?>
      
        <?php echo $form->checkboxRow($model, 'active'); ?>
       
 
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