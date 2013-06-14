<?php


return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Yii Users Module demo',
	'theme' => 'classic',
	'preload'=>array('log','bootstrap',),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'ext.set-return-url-filter.*', // required by YiiUsers module
		'application.modules.YiiUsers.models.*',
	),

	'modules'=>array(
		'YiiUsers' => array(
			'enabledIdentities' => array(
				'StandardIdentity',
				'LoginzaIdentity' => array(
						'widgetId' => '[WIDGET_ID]',
						'apiSignature' => '[API_SIGNATURE]',
					),
			),
		),

		// uncomment the following to enable the Gii tool
		/*
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'Enter Your Password Here',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		*/
	),

	// application components
	'components'=>array(
		'user'=>array(
			'class' => 'application.modules.YiiUsers.components.SsoUser',
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		'authManager'=>array(
            'class'=>'CDbAuthManager',
            'connectionID'=>'db',
        ),

		'bootstrap' => array(
		    'class' => 'ext.YiiBooster.components.Bootstrap',
		    'responsiveCss' => true,
		),


		// uncomment the following to enable URLs in path-format
		
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName' => false,
			'rules'=>array(
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
				'<module:\w>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
			),
		),
		
		
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=yiiuser',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => 'root',
			'charset' => 'utf8',
		),
		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				
				// array(
				// 	'class'=>'CWebLogRoute',
				// ),
				
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
);