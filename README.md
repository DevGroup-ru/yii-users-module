yii-users-module
================

Users module for yii with cross-domain authentication and pluggable UserIdentity

Sorry, this README file is not yet complete.

Requirements
------------

- Yii Framework v 1.1.x
- ESetReturnUrlFilter extension
- YiiBooster extension
- asmselectex extension
- `TagDependency.php` and `TaggableCacheBehavior.php` components (included under libs/ folder)



Setting up YiiUsers module without SSO
======================================

If you have single site you probably don't want to enable Single-sign-on feature.

At first copy YiiUsers module folder to your application modules folder(usually `protected/modules/`).

Put files from libs folder(`TagDependency.php` and `TaggableCacheBehavior.php`) to your components folder(usually `protected/components/`).

Copy `asmselectex` and `set-return-url-filter` into your extensions folder.

Then set up your config.php:
	
	// preload bootstrap component
	'preload'=>array('log','bootstrap',),

	'modules' => array(
		// include YiiUsers module
		'YiiUsers' => array(
			'enabledIdentities' => array(
				'StandardIdentity',
				// you can comment out LoginzaIdentity array if you wish not to enable it
				'LoginzaIdentity' => array(
						'widgetId' => '[WIDGET_ID]',
						'apiSignature' => '[API_SIGNATURE]',
					),
			),
			// disable SSO
			'ssoEnabled'=>false,

			// enable registration
			'registrationEnabled'=>true,
		),
	),

	'components' => array(
		'cache' => array(
            'class' => 'CFileCache',
            'behaviors' => array(
            	// requred for correct working of yii-users-module
            	'TaggableCacheBehavior' => array(
            			'class' => 'TaggableCacheBehavior',
        		),
        	),
        ),

        // override standard user class
        'user'=>array(
			'class' => 'application.modules.YiiUsers.components.SsoUser',
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
			// set loginUrl to our YiiUsers login system
			'loginUrl'=>array('/YiiUsers/User/Login'),
		),

		// here we are using local DB auth manager
		'authManager'=>array(
            'class'=>'CDbAuthManager',
            'connectionID'=>'db',
        ),

		// include bootstrap component
		'bootstrap' => array(
		    'class' => 'ext.YiiBooster.components.Bootstrap',
		    'responsiveCss' => true,
		    'fontAwesomeCss' => true,
		),

		// the rest of your components config
	)

Standard SQL dump for user `admin` with password `admin`:

	
	DROP TABLE IF EXISTS `AuthAssignment`;
	CREATE TABLE IF NOT EXISTS `AuthAssignment` (
	  `itemname` varchar(128) NOT NULL,
	  `userid` varchar(128) NOT NULL,
	  `bizrule` text,
	  `data` text,
	  PRIMARY KEY (`itemname`,`userid`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	INSERT INTO `AuthAssignment` (`itemname`, `userid`, `bizrule`, `data`) VALUES
	('Admin CMS', '1', NULL, 'N;'),
	('Admin SSO', '1', NULL, 'N;');

	DROP TABLE IF EXISTS `AuthItem`;
	CREATE TABLE IF NOT EXISTS `AuthItem` (
	  `name` varchar(128) NOT NULL,
	  `type` int(11) NOT NULL,
	  `description` text,
	  `bizrule` text,
	  `data` text,
	  PRIMARY KEY (`name`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;


	INSERT INTO `AuthItem` (`name`, `type`, `description`, `bizrule`, `data`) VALUES
	('Admin CMS', 1, '', NULL, 'N;'),
	('List SSO users', 0, '', NULL, 'N;'),
	('Edit SSO users', 0, '', NULL, 'N;'),
	('Delete SSO users', 0, '', NULL, 'N;'),
	('Admin SSO', 1, '', NULL, 'N;');

	DROP TABLE IF EXISTS `AuthItemChild`;
	CREATE TABLE IF NOT EXISTS `AuthItemChild` (
	  `parent` varchar(128) NOT NULL,
	  `child` varchar(128) NOT NULL,
	  PRIMARY KEY (`parent`,`child`),
	  KEY `child` (`child`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	INSERT INTO `AuthItemChild` (`parent`, `child`) VALUES
	('Admin SSO', 'Delete SSO users'),
	('Admin SSO', 'Edit SSO users'),
	('Admin SSO', 'List SSO users');

	ALTER TABLE `AuthAssignment`
		ADD CONSTRAINT `AuthAssignment_ibfk_1` FOREIGN KEY (`itemname`) REFERENCES `AuthItem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;
		
	ALTER TABLE `AuthItemChild`
		ADD CONSTRAINT `AuthItemChild_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `AuthItem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
		ADD CONSTRAINT `AuthItemChild_ibfk_2` FOREIGN KEY (`child`) REFERENCES `AuthItem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

	DROP TABLE IF EXISTS `User`;
	CREATE TABLE IF NOT EXISTS `User` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `username` varchar(45) DEFAULT NULL,
	  `createTime` datetime DEFAULT NULL,
	  `active` tinyint(1) NOT NULL DEFAULT '1',
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

	INSERT INTO `User` (`id`, `username`, `createTime`, `active`) VALUES
	(1, 'Admin', '2012-11-14 16:20:22', 1);

	DROP TABLE IF EXISTS `UserAuth`;
	CREATE TABLE IF NOT EXISTS `UserAuth` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `userId` int(10) unsigned NOT NULL,
	  `identityClass` tinytext NOT NULL,
	  `password` varchar(255) NOT NULL,
	  `identity` varchar(255) NOT NULL,
	  `provider` varchar(255) NOT NULL,
	  `additionalData` text NOT NULL,
	  `salt` varchar(128) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

	INSERT INTO `UserAuth` (`id`, `userId`, `identityClass`, `password`, `identity`, `provider`, `additionalData`, `salt`) VALUES
	(1, 1, 'StandardIdentity', 'a1e16f30ce94ba3b192d538d8910cb55', '', '', '', '59a18ca6a327b9.56417899');

	DROP TABLE IF EXISTS `UserProfile`;
	CREATE TABLE IF NOT EXISTS `UserProfile` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `userId` int(10) unsigned NOT NULL,
	  `firstName` tinytext NOT NULL,
	  `lastName` tinytext NOT NULL,
	  `email` varchar(255) NOT NULL,
	  `updateTime` datetime DEFAULT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

	INSERT INTO `UserProfile` (`id`, `userId`, `firstName`, `lastName`, `email`, `updateTime`) VALUES
	(1, 1, '', '', 'tehdir@demispartners.ru', '2013-07-02 12:01:20');



