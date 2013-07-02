<!DOCTYPE html>
<html>
<head>
  	<meta charset="utf-8">
  	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
  	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body style="padding-top: 60px;">
	<?php
		$this->widget('bootstrap.widgets.TbNavbar', array(
			'brand' => Yii::app()->name,
			'items' => array(
				array(
					'class' => 'bootstrap.widgets.TbMenu',
					'items' => array(
						array('label'=>'Home', 'url'=>'/'),
						array('label'=>'Login', 'url'=>array('/YiiUsers/user/login')),
						array('label'=>'Logout', 'url'=>array('/YiiUsers/user/logout')),
						array('label'=>'Register', 'url'=>array('/YiiUsers/user/registration')),
						array('label'=>'Admin', 'url'=>array('/YiiUsers/Admin/Index')),
					)
				)
			)
		));
			
	?>
	<div class="container-fluid">

		<?php echo $content;?>
	</div>
</body>
</html>