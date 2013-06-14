<h1>Admin users</h1>

<?php
$this->widget('bootstrap.widgets.TbButtonGroup', array(
    'buttons'=>array(
	    array('label'=>'List', 'url'=>'List'),
	    array('label'=>'RBAC', 'url'=>'Rbac'),
	    
    ),
));

?>

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