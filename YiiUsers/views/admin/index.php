<h1>Admin users</h1>

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
		<?php echo CHtml::link("<i class='icon-plus-sign'></i> ".Yii::t("YiiUsers", "Add new user"), array('/YiiUsers/Admin/NewUser'), array('class'=>'btn btn-primary'));?>
	</div>
</div>
<?php $this->widget('bootstrap.widgets.TbAlert'); ?>

<?php
$this->widget('bootstrap.widgets.TbExtendedGridView', array(
	'dataProvider' => $model->search(),
	'filter' => $model,
	'type' => 'striped bordered condensed',
	'responsiveTable' => true,
	'headerOffset' => 40, // 40px is the height of the main navigation at bootstrap
	'columns' => array(
		'id',
		'username',
		'createTime',
		array(
			'header' => Yii::t('YiiUsers', 'Edit'),
			'class' => 'bootstrap.widgets.TbButtonColumn',
			'template' => '{view} {delete}',
		),
	),
));
?>