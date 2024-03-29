<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'搜商网 - 11ok.net',
	'language'=>'zh_cn',
	'timeZone' => 'Asia/Shanghai',
	'homeUrl'=>'/',
    //'defaultController'=>'site',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
			'application.models.*',
			'application.components.*',
			'ext.DController',
			'application.extensions.yiidebugtb.*',
	),

	// application components
	'components'=>array(
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error,info',
				),
				array(
						'class'=>'XWebDebugRouter',
						'config'=>'alignLeft, opaque, runInDebug, fixedPos, collapsed, yamlStyle',
						'levels'=>'error, warning, trace, profile, info',
				),
				/**
				array(
					'class'=>'CWebLogRoute',
				),
				array(
					'class'=>'CProfileLogRoute',
					'enabled'=>true,
				),
                /**/
			),
		),
		'user'=>array(
			// enable cookie-based authentication
			// 'allowAutoLogin'=>true,
		),
		// uncomment the following to set up database
		'db'=>array(
				'class'=>'CDbConnection',
				'connectionString'=>'mysql:host=localhost;dbname=11ok_yii',
				'charset'=>'utf8',
				'username'=>'webuser',
				'password'=>'webuser',
				'tablePrefix'=>'y11ok_',
				//'enableProfiling'=>true,
				'enableParamLogging'=>true,
				'schemaCachingDuration'=>3600,
		),
		'urlManager'=>array(
				//'class'=>'ext.DUrlManager',//应该是加上博客所有人的,放到控制器初始化里面去做了
				'urlFormat'=>'path',
				//'appendParams'=>false,
				//'urlSuffix'=>'.html',
				'showScriptName'=>false,
				'caseSensitive'=>false,
				'rules'=>array(
					//'http://<domain:\w+>/<_c:>/<_a:>' => '<_c>/<_a>',
					'/artlist/<gid:\d+>' => 'site/artlist',
					'/search/<bcid:\d+>' => 'site/search',
					'/<_c:(site)>/<_a>'=>'<_c>/<_a>',
					'/<username:\w+>' => 'blog/index',
					'/article/<uid:\d+>/<aid:\d+>' =>'blog/article',
					'/<username:\w+>/article/<aid:\d+>' =>'blog/article',
					'/<username:\w+>/articles/<acid:\d+>' =>'blog/articles',
					'/<username:\w+>/galleries/<gaid:\d+>' =>'blog/galleries',
					'/<username:\w+>/gallery/<gid:\d+>' =>'blog/gallery',
					'/<username:\w+>/<_a:(index|articles|article|galleryAlbums|galleries|gallery|guestbook|addFriend|addSms)>' =>'blog/<_a>',
					'/<username:\w+>/<_c:>/<_a:>' => '<_c>/<_a>',
					'<_c:>/<_a:>' => '<_c>/<_a>',
					//'blog/article-<aid:\d+>-<uid:\d+>' => 'blog/article',
					//'/<username:\w+>/<_c:(blogs|articles)>/<id:\d+>/<_a:(create|update|delete)>' => '<_c>/<_a>',
				),
		),
		/**
		'cache'=>array(
				'class'=>'system.caching.CFileCache',
				'directoryLevel'=>0,
		),
		/**/
		'mailer' => array(
				'class' => 'application.extensions.mailer.EMailer',
				//'pathViews' => 'application.views.email',
				//'pathLayouts' => 'application.views.email.layouts'
		),

		'thumb'=>array(
                        'class'=>'ext.phpthumb.EasyPhpThumb',
		),
		'CFile'=>array(
                        'class'=>'ext.CFile',
		),
		'DRedirect'=>array(
                        'class'=>'ext.DRedirect',
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>require(dirname(__FILE__).'/params.php'),
);