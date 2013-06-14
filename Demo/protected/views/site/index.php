<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<p>
	isGuest: <?php var_export(Yii::app()->user->isGuest);?> <br>
	User attributes:
	<pre>
<?php
		$user = Yii::app()->user->user;
		if ($user)
		var_export($user->getAttributes());
		?>
	</pre>
</p>