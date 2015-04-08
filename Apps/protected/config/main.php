<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Yii Blog Demo',

	// preloading 'log' component
	'preload'=>array('log'),

    'aliases'=>array(
        'bootstrap' => realpath(__DIR__ . '/../modules/admin/extensions/yiistrap'),
        'yiiwheels' => realpath(__DIR__ . '/../modules/admin/extensions/yiiwheels'),
    ),
	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
        'application.modules.admin.models.*',
		'application.components.*',
        'application.modules.admin.components.*',
        'application.vendors.PHPMailer.*',
        'bootstrap.helpers.TbHtml',
        'application.utility.*',
        'application.modules.admin.controllers.AdminBaseController',
        'application.extensions.captchaExtended.CaptchaExtendedAction',
        'application.extensions.captchaExtended.CaptchaExtendedValidator',
	),

    'modules'=>array(  
            // uncomment the following to enable the Gii tool  

            'gii'=>array(  
                    'class'=>'system.gii.GiiModule',  
                    'password'=>'123',  
                    //ipFilters用于所在服务器不在本机的情况需开启    
                    //'ipFilters'=>array('192.168.1.10','::1'),    
                    ),        
            'admin',
     ),

	'defaultController'=>'post',

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
        // YiiStrap components
        'bootstrap' => array(
                'class' => 'bootstrap.components.TbApi',
                ),
        'yiiwheels' => array(
                'class' => 'yiiwheels.YiiWheels',
                ),

		/*
        'db'=>array(
                'connectionString' => 'sqlite:protected/data/blog.db',
                'tablePrefix' => 'tbl_',
                ),
		*/
        'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=wang',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '912913',
			'charset' => 'utf8',
		),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
                'admin'=>'admin/login',
			),
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
	'params'=>require(dirname(__FILE__).'/params.php'),
);
