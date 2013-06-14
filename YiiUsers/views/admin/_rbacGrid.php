<?php 
$id = new CWidget;
$id=$id->getId();

$this->widget('bootstrap.widgets.TbExtendedGridView', array(
    'dataProvider'=>$model,
    'type'=>'condensed',
    'selectableRows'=>2,
    'columns' => array(
    	'name',
    	'description',
    	array(
                'class' => 'bootstrap.widgets.TbButtonColumn',
                
                'buttons'=>array(
                	'update' => array(
                			'label'=>Yii::t('YiiUsers', 'Open'),
                			'icon'=>'icon-circle-arrow-right',
                            'url' => '"EditRbac?rbac=".$data["name"]'
                	),
                    'delete' => array(
                            'label'=>Yii::t('YiiUsers', 'Delete'),
                            'icon'=>'icon-trash',
                            'url' => '"DeleteRbac?rbac=".$data["name"]'
                    ),
                ),
                'template'=>'{update} {delete}',
        ),
    ),
    'bulkActions' => array(
        'actionButtons' => array(
                array(
                    'buttonType' => 'button',
                    'type' => 'primary',
                    'size' => 'small',
                    'label' => 'Delete',
                    'id' => 'BulkDeleteRbacRecords-'.$id,
                    'click' => 'js:bulkDeleteRbacRecords',
                ),
        ),
            // if grid doesn't have a checkbox column type, it will attach
            // one and this configuration will be part of it
        'checkBoxColumnConfig' => array(
            'name' => 'name'
        ),
        
    ),
)); ?>

<script>
bulkDeleteRbacRecords = function(checkboxes) {
    var url = '/YiiUsers/Admin/DeleteRbacs?';
    
    for (i=0;i<checkboxes.length;i++) {
        url += 'rbacsRows[]='+escape($(checkboxes[i]).val())+'&'
    }
    document.location = url;
    console.log(url);
}
</script>