<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Sso Client Demo',

	// preloading 'log' component
	'preload'=>array('log', 'authManager', 'bootstrap'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'ext.SsoExtension.*',
		'ext.SsoExtension.components.*',
		'ext.SsoExtension.models.*',
	),

	'modules'=>array(
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
		'cache'=>array(
			'class' => 'CFileCache',

		),
		'user'=>array(
			'class' => 'ext.SsoExtension.components.SsoUser',
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
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
		
		'authManager'=>array(
            'class'=>'SsoAuthManager',
            'ssoServer' => "http://yii-users-module.dev/YiiUsers/user/",
            'privateHash' => '0800fc577294c34e0b28ad2839435945',
            'publicHash' => '4857c94c9d3b65fc53b86ddf22a5eeff',
        ),
        'bootstrap' => array(
		    'class' => 'ext.YiiBooster.components.Bootstrap',
		    'responsiveCss' => true,
		),
		// uncomment the following to use a MySQL database
		/*
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=testdrive',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
		),
		*/
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
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
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