<h1><?php echo Yii::t("YiiUsers", "Auth item edit");?></h1>

<?php /** @var BootActiveForm $form */
	$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'=>'Form-Rbac',
		'type'=>'horizontal',
	)); ?>
 
    <fieldset>
 		<legend><?php echo Yii::t('YiiUsers', 'Auth item');?></legend>
        <?php echo $form->textFieldRow($model, 'name'); ?>
        <?php echo $form->textAreaRow($model, 'description'); ?>
        <?php 
        echo $form->dropDownListRow($model, 'type', array(
        	0 => Yii::t("YiiUsers", "Operation"), 
	        1 => Yii::t("YiiUsers", "Task"), 
	        2 => Yii::t("YiiUsers", "Role"),
        )); ?>
       
 
    </fieldset>

    <fieldset>
		<legend><?php echo Yii::t('YiiUsers', 'Child auth items');?></legend>
		<?php $this->widget('application.modules.YiiUsers.widgets.AsmSelect', array('target'=>$model->name)); ?>
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