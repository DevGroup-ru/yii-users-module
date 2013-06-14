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
$this->widget('bootstrap.widgets.TbJsonGridView', array(
	'dataProvider' => $model->search(),
	'filter' => $model,
	'type' => 'striped bordered condensed',
	'summaryText' => false,
	'cacheTTL' => 10, // cache will be stored 10 seconds (see cacheTTLType)
	'cacheTTLType' => 's', // type can be of seconds, minutes or hours
	'columns' => array(
		'id',
		'username',
		array(
			'name' => 'createTime',
			//'type' => 'datetime'
		),
		array(
			'header' => Yii::t('ses', 'Edit'),
			'class' => 'bootstrap.widgets.TbJsonButtonColumn',
			'template' => '{view} {delete}',
		),
	),
));
?>