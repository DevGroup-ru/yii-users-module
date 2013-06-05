<?php $this->widget('bootstrap.widgets.TbAlert'); ?>
<div class="row-fluid">
	<div class="span6">
		<?php $this->renderPartial("loginForm", array('model'=>$loginForm));?>
	</div>

	<?php if (Yii::app()->getModule("YiiUsers")->registrationEnabled):?>
	<div class="span6">
		<?php $this->renderPartial("registrationForm", array('model'=>$registrationForm));?>
	</div>
	<?php endif;?>
</div>

