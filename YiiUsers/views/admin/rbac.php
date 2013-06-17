<h1><?php echo Yii::t("YiiUsers", "RBAC admin");?></h1>
<div class="row-fluid">
	<div class="span10">
	<?php
	$this->widget('bootstrap.widgets.TbButtonGroup', array(
	    'buttons'=>array(
		    array('label'=>'List', 'url'=>'Index'),
		    array('label'=>'RBAC', 'url'=>'Rbac'),
		    
	    ),
	));

	?>
	<br><br>
	</div>
	<div class="span2">
		<?php echo CHtml::link("<i class='icon-plus-sign'></i> ".Yii::t("YiiUsers", "Add RBAC record"), array('/YiiUsers/Admin/EditRbac'), array('class'=>'btn btn-primary'));?>
	</div>
</div>
<?php $this->widget('bootstrap.widgets.TbAlert'); ?>

<?php 
$this->widget('bootstrap.widgets.TbTabs', array(
    'type'=>'tabs', // 'tabs' or 'pills'
    'tabs'=>array(
		array(
			'label'=>Yii::t("YiiUsers", "Operations"),
			'content'=>$this->renderPartial(
				'_rbacGrid',
				array(
					'model'=>$operations,
					),
				true,
				false
				),
			'active'=>1,
		),
		array(
			'label'=>Yii::t("YiiUsers", "Tasks"),
			'content'=>$this->renderPartial(
				'_rbacGrid',
				array(
					'model'=>$tasks,
					),
				true,
				false
				),
		),
		array(
			'label'=>Yii::t("YiiUsers", "Roles"),
			'content'=>$this->renderPartial(
				'_rbacGrid',
				array(
					'model'=>$roles,
					),
				true,
				false
				),
		),
	),
)); ?>