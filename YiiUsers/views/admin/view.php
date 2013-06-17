<h1>User: <?php echo CHtml::encode($model->username);?></h1>
<div class="row-fluid">
    <?php
    $this->widget('bootstrap.widgets.TbButtonGroup', array(
        'buttons'=>array(
    	    array('label'=>'List', 'url'=>array('/YiiUsers/Admin/Index')),
    	    array('label'=>'RBAC', 'url'=>array('/YiiUsers/Admin/Rbac')),
    	    
        ),
    ));

    ?>
    <br>

    <?php
    $this->widget('bootstrap.widgets.TbAlert', array(
        'block'=>true, // display a larger alert block?
        'fade'=>true, // use transitions?
        'closeText'=>'×', // close link text - if set to false, no close link is displayed
        'alerts'=>array( // configurations per alert type
    	    'success'=>array('block'=>true, 'fade'=>true, 'closeText'=>'×'), // success, info, warning, error or danger
        ),
    ));
    ?>

    <br>
</div>
<?php /** @var BootActiveForm $form */
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'Form-UserProfile',
        'type'=>'horizontal',
    )); ?>
<div class="row-fluid">
	<div class="span6">
<?php
$this->widget('bootstrap.widgets.TbBox', array(
    'title' => Yii::t('YiiUsers', 'Base info'),
    'headerIcon' => 'icon-user',
    'content' => $this->renderPartial('_info', array('model'=>$model,'form'=>$form),true),
));

?>
	</div>
	<div class="span6">
<?php
$this->widget('bootstrap.widgets.TbBox', array(
    'title' => Yii::t('YiiUsers', 'Profile'),
    'headerIcon' => 'icon-cogs',
    'content' => $this->renderPartial('_profile', array('model'=>$model->profile,'form'=>$form),true),
));

?>
	</div>
</div>
<div class="row-fluid">
    <div class="span6">
<?php
$this->widget('bootstrap.widgets.TbBox', array(
    'title' => Yii::t('YiiUsers', 'Roles and groups'),
    'headerIcon' => 'icon-lock',
    'content' => $this->renderPartial('_userRbac', array('model'=>$model,'form'=>$form),true)
));

?>
    </div>
    <div class="span6">
<?php
$this->widget('bootstrap.widgets.TbBox', array(
    'title' => Yii::t('YiiUsers', 'User identities'),
    'headerIcon' => 'icon-lock',
    'content' => $this->renderPartial('_userIdentities', array('model'=>$model,'form'=>$form),true)
));

?>
    </div>

</div>
<?php $this->endWidget(); ?>