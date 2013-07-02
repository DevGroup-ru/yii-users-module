<h2><?php echo Yii::t("YiiUsers", "Login as:");?></h2>
<?php 
$module = Yii::app()->getModule("YiiUsers");
$form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'loginFullForm',
    'type'=>'horizontal',
    'enableAjaxValidation'=>true,
    'clientOptions' => array(
			'validateOnSubmit'=>true,
		),
    'action'=>$module->loginUrl,
));

?>

<?php echo $form->textFieldRow($model, 'username'); ?>
<?php echo $form->passwordFieldRow($model, 'password'); ?>
<?php echo $form->checkBoxRow($model, 'rememberMe'); ?>


<div class="form-actions">
    <?php echo CHtml::submitButton(Yii::t("YiiUsers", "Log in")); ?>
</div>


<?php $this->endWidget(); ?>

<?php if (Yii::app()->getModule("YiiUsers")->isIdentityEnabled("LoginzaIdentity")):?>
<h3><?php echo Yii::t("YiiUsers", "Or login with:");?></h2>
<div class="loginzaLoginBlock">
	
	<script src="http://loginza.ru/js/widget.js" type="text/javascript"></script>
	<iframe src="http://loginza.ru/api/widget?overlay=loginza&amp;token_url=<?php echo urlencode(Yii::app()->request->getHostInfo()) . CHtml::normalizeUrl($module->loginUrl);?>" 
	style="width:100%;height:100%" scrolling="no" frameborder="no">
	</iframe>
</div>


<?php endif;?>