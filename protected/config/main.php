<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
Yii::setPathOfAlias('bootstrap', dirname(__FILE__).'/../extensions/bootstrap');

$config = array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'АСУ',

	'preload'=>array('log', 'shortcodes'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.extensions.bootstrap.*',
		'application.extensions.behaviors.*',

		'ext.EScriptBoost.*',
		'ext.LangPick.*',
	),

	'modules'=>array(
		'admin',
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		'urlManager'=>array(
			'showScriptName' => false,
			'urlFormat' => 'path',
			'rules'=>array(
				'' => 'site/index',
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=cleanapp',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
			'schemaCachingDuration' => !YII_DEBUG ? 86400 : 0,
			'enableParamLogging' => YII_DEBUG,
		),
		'cache' => array(
			'class' => 'CFileCache',
		),
		'assetManager' => array(
			'class' => 'ext.EAssetManagerBoostGz',
			'minifiedExtensionFlags' => array('min.js', 'minified.js', 'packed.js'),
		),
		'clientScript'=>array(
			'packages' => array(
				/*'jquery' => array( // Google CDN
					'baseUrl' => 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/',
					'js' => array(YII_DEBUG ? 'jquery.js' : 'jquery.min.js'),
				),*/
				/*'jquery' => array( // Yandex CDN
					'baseUrl' => 'http://yandex.st/jquery/1.7.2/',
					'js' => array(YII_DEBUG ? 'jquery.js' : 'jquery.min.js'),
				),*/
				'jquery' => array( // jQuery CDN - provided by (mt) Media Temple
					'baseUrl' => '/js/',
					'js' => array(YII_DEBUG ? 'jquery-1.11.0.js' : 'jquery-1.11.0.min.js'),
				),
                'chosen' => array(
                    'baseUrl' => '/theme/ace/assets/',
                    'css' => array('css/chosen.css'),
                    'js' => array('js/chosen.jquery.min.js'),
                ),
                'gritter' => array(
                    'baseUrl' => '/theme/ace/assets/',
                    'css' => array('css/jquery.gritter.css'),
                    'js' => array('js/jquery.gritter.min.js'),
                ),
                'spin' => array(
                    'baseUrl' => '/theme/ace/assets/',
                    'js' => array('js/spin.min.js'),
                ),
            ),
			'behaviors' => array(
				array(
					'class' => 'ext.behaviors.localscripts.LocalScriptsBehavior',
					'publishJs' => !YII_DEBUG,
					// Uncomment this if your css don't use relative links
					// 'publishCss' => !YII_DEBUG,
				),
			),
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
			),
		),
        'bootstrap' => array(
            'class' => 'ext.bootstrap.components.Bootstrap',
        ),
        'shortcodes' => array(
            'class'=>'ShortCodes',
        ),
        'user' => array(
            'class' => 'WebUser',
        ),
	),

    'sourceLanguage'=>'ru',

	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
        'defaultLanguage'=>'ru',
        'siteUrl' => '',
    ),
);

// Apply local config modifications
@include dirname(__FILE__) . '/main-local.php';

return $config;
