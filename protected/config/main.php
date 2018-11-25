<?php
@include dirname(__FILE__) . '/universities.php';
// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
//активность сервиса
define('MENU_ELEMENT_VISIBLE', 'visible');
//Видимость в меню
define('MENU_ELEMENT_VISIBLE_MENU', 'visible_menu');
//доступ без авториазции
define('MENU_ELEMENT_NEED_AUTH', 'need_auth');
define('MENU_ELEMENT_AUTH_STUDENT', 'need_auth_std');
define('MENU_ELEMENT_AUTH_TEACHER', 'need_auth_tch');
define('MENU_ELEMENT_AUTH_PARENT', 'need_auth_prnt');
define('MENU_ELEMENT_AUTH_DOCTOR', 'need_auth_doctor');

Yii::setPathOfAlias('bootstrap', dirname(__FILE__).'/../extensions/bootstrap');

$params = require(dirname(__FILE__).'/params.php');
//разрешена ли у нас авторизация через соцсети
$params['enableEAuth'] = false;
$paramsEAuth = require(dirname(__FILE__) . '/paramsEAuth.php');
if (isset($paramsEAuth['enable']))
    $params['enableEAuth'] = $paramsEAuth['enable'];

$config = array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'АСУ',

	'preload'=>array('log', 'shortcodes'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
        'application.validators.*',
		'application.extensions.bootstrap.*',
		'application.extensions.behaviors.*',
		'ext.elFinder.*',
		'ext.EScriptBoost.*',
		'ext.LangPick.*',
        'application.components.DistEducation.*',
        'application.components.DistEducation.forms.*',
	),

	'modules'=>array(
		'admin',
	),

	// application components
	'components'=>array(
		'request'=>array(
				'enableCookieValidation'=>true,
				//'enableCsrfValidation'=>true,
				//'csrfTokenName'=>'csrf-mkr'
		),
		/*'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),*/
		'session'=>array(
			'timeout' => 1440,
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
		/*'assetManager' => array(
			'class' => 'ext.EAssetManagerBoostGz',
			'minifiedExtensionFlags' => array('min.js', 'minified.js', 'packed.js'),
		),*/
		'clientScript'=>array(
			'packages' => array(
				'jquery' => array( // jQuery CDN - provided by (mt) Media Temple
					'baseUrl' => 'js/',
					'js' => array(YII_DEBUG ? 'jquery-1.11.0.js' : 'jquery-1.11.0.min.js'),
				),
                'chosen' => array(
                    'baseUrl' => 'theme/ace/assets/',
                    'css' => array('css/chosen.css'),
                    'js' => array('js/uncompressed/chosen.jquery.js'),
					//'css' => array('chosen.min.css'),
					//'js' => array('chosen.jquery.min.js'),
                    'depends' => array('jquery')
                ),
                'gritter' => array(
                    'baseUrl' => 'theme/ace/assets/',
                    'css' => array('css/jquery.gritter.css'),
                    'js' => array('js/jquery.gritter.min.js'),
                    'depends' => array('jquery')
                ),
				'noty' => array(
						'baseUrl' => '',
						'css' => array('css/mobile/animate.css','css/mobile/buttons.css'),
						'js' => array('js/mobile/noty/packaged/jquery.noty.packaged.min.js','js/mobile/notification_html.js'),
						'depends' => array('jquery')
				),
                'spin' => array(
                    'baseUrl' => 'theme/ace/assets/',
                    'js' => array('js/spin.min.js'),
                    'depends' => array('jquery')
                ),
                'jqgrid' => array(
                    'baseUrl' => 'theme/ace/assets/',
                    'css' => array('css/ui.jqgrid.css'),
                    'js' => array('js/jqGrid/jquery.jqGrid.min.js'),
                    'depends' => array('jquery')
                ),
                'dataTables' => array(
                    'baseUrl' => 'theme/ace/assets/',
                    'js' => array('js/jquery.dataTables.min1.js', 'js/jquery.dataTables.bootstrap.js'),
                    //'css' => array('css/jquery.dataTables2.css'),
                    'depends' => array('jquery')
                ),
                'daterangepicker' => array(
                    'baseUrl' => 'theme/ace/assets/',
                    'css' => array('css/daterangepicker.css'),
                    'js' => array('js/date-time/daterangepicker.min.js', 'js/date-time/moment.min.js'),
                    'depends' => array('jquery')
                ),
                'datepicker' => array(
                    'baseUrl' => 'theme/ace/assets/',
                    'css' => array('css/datepicker.css'),
                    'js' => array('js/date-time/bootstrap-datepicker.min.js', 'js/date-time/locales/bootstrap-datepicker.ru.js'),
                    'depends' => array('jquery')
                ),
				'datepicker-mobile' => array(
						'baseUrl' => '/',
						'css' => array('css/mobile/datepicker/bootstrap-datepicker3.min.css','css/mobile/datepicker/bootstrap-datepicker3.standalone.min.css'),
						'js' => array('js/mobile/datepicker/js/bootstrap-datepicker.min.js'),
						'depends' => array('jquery')
				),
                'autosize' => array(
                    'baseUrl' => 'theme/ace/assets/',
                    'js' => array('js/jquery.autosize-min.js'),
                    'depends'=>array('jquery'),
                ),
                'jquery.ui' => array(
                    'baseUrl' => 'theme/ace/assets/',
                    'css' => array('css/jquery-ui-1.10.3.full.min.css'),
                    'js' => array('js/jquery-ui-1.10.3.full.min.js'),
                    'depends'=>array('jquery'),
                ),
                'jqplot' => array(
                    'baseUrl' => 'theme/ace/assets/',
                    'css' => array('css/jquery.jqplot.css'),
                    'js' => array(
                        'js/jqplot/jquery.jqplot.js',
                        'js/jqplot/jqplot.highlighter.js',
                        'js/jqplot/jqplot.cursor.js',
                        'js/jqplot/jqplot.dateAxisRenderer.js',
                    ),
                    'depends'=>array('jquery'),
                ),
                'nestable' => array(
                    'baseUrl' => 'theme/ace/assets/',
                    'js' => array('js/jquery.nestable.min.js'),
                    'depends'=>array('jquery'),
                ),
                'jquery2' => array(
                    'baseUrl' => 'theme/ace/assets/',
                    'js' => array('js/jquery-2.0.3.min.js'),
                ),
                'form-wizard' => array(
                    'baseUrl' => 'theme/ace/assets/',
                    'css' => array('css/select2.css'),
                    'js' => array(
                        'js/fuelux/fuelux.wizard.min.js',
                        'js/jquery.validate.min.js',
                        'js/additional-methods.min.js',
                        'js/bootbox.min.js',
                        'js/select2.min.js'
                    ),
                    'depends'=>array('jquery'),
                ),
                'autocomplete' => array(
                    'baseUrl' => 'js/',
                    'js' => array('jquery.autocomplete.js'),
                    'depends'=>array('jquery'),
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
		'Smtpmail'=>array(
				'class'=>'application.extensions.smtpmail.PHPMailer',
				'SMTPAuth'=>true,
		)+require(dirname(__FILE__).'/mail.php'),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				/*array(
					'class'=>'application.extensions.yii-debug-toolbar.YiiDebugToolbarRoute',
					//'ipFilters'=>array('77.121.11.10'),
					//'ipFilters'=>array('109.87.233.112'),
					//'ipFilters'=>array('93.78.170.140'),
				),*/
			),
		),
		'mobileDetect' => array(
			'class' => 'ext.MobileDetect.MobileDetect'
		),
        'bootstrap' => array(
            'class' => 'ext.bootstrap.components.Bootstrap',
        ),
        'shortcodes' => array(
            'class'=>'ShortCodes',
        ),
		'user'=>array(
            'class' => 'WebUser',
            'allowAutoLogin'=>true
		),
		'ePdf' => array(
			'class'         => 'ext.yii-pdf.EYiiPdf',
			'params'        => array(
					'mpdf'     => array(
						'librarySourcePath' => 'application.extensions.mpdf.*',
						'constants'         => array(
							'_MPDF_TEMP_PATH' => Yii::getPathOfAlias('application.runtime'),
						),
						'class'=>'mpdf', // the literal class filename to be loaded from the vendors folder
						/*'defaultParams'     => array( // More info: http://mpdf1.com/manual/index.php?tid=184
							'mode'              => '', //  This parameter specifies the mode of the new document.
							'format'            => 'A4', // format A4, A5, ...
							'default_font_size' => 0, // Sets the default document font size in points (pt)
							'default_font'      => '', // Sets the default font-family for the new document.
							'mgl'               => 15, // margin_left. Sets the page margins for the new document.
							'mgr'               => 15, // margin_right
							'mgt'               => 16, // margin_top
							'mgb'               => 16, // margin_bottom
							'mgh'               => 9, // margin_header
							'mgf'               => 9, // margin_footer
							'orientation'       => 'P', // landscape or portrait orientation
						)*/
					),
					'HTML2PDF' => array(
							'librarySourcePath' => 'application.extensions.html2pdf.*',
							'classFile'         => 'html2pdf.class.php', // For adding to Yii::$classMap
						/*'defaultParams'     => array( // More info: http://wiki.spipu.net/doku.php?id=html2pdf:en:v4:accueil
							'orientation' => 'P', // landscape or portrait orientation
							'format'      => 'A4', // format A4, A5, ...
							'language'    => 'en', // language: fr, en, it ...
							'unicode'     => true, // TRUE means clustering the input text IS unicode (default = true)
							'encoding'    => 'UTF-8', // charset encoding; Default is UTF-8
							'marges'      => array(5, 5, 5, 8), // margins by default, in order (left, top, right, bottom)
						)*/
					)
			),
		),
	),

    'sourceLanguage'=>'ru',

	'params'=>
		array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
        'defaultLanguage'=>'ru',
        'siteUrl' => '',
        'code' => null,
        'antiPlagiarism' => array(
            'company_name' => null,
            'apicorp_address' => null,
            'antiplagiat_uri' => null,
        )
    )+$params,
);
//eauth
if($params['enableEAuth']===true){
    $config['import'] = array_merge($config['import'], array(
        'ext.eoauth.*',
        'ext.eoauth.lib.*',
        'ext.lightopenid.*',
        'ext.eauth.*',
        'ext.eauth.services.*'
    ));

    $config['components']['loid'] = array(
        'class' => 'ext.lightopenid.loid'
    );

    $config['components']['eauth'] = array(
        'class' => 'ext.eauth.EAuth',
        'popup' => isset($paramsEAuth['popup']) ? $paramsEAuth['popup'] : true, // Use the popup window instead of redirecting.
        'cache' => false, // Cache component name or false to disable cache. Defaults to 'cache'.
        'cacheExpire' => 0, // Cache lifetime. Defaults to 0 - means unlimited.
        'services' => isset($paramsEAuth['services']) ? $paramsEAuth['services'] : array()
    );
}

// Apply local config modifications
@include dirname(__FILE__) . '/main-local.php';

$config['components']['db2'] = $config['components']['db'];
$config['components']['db2']['connectionString'] = $config['components']['db']['connectionString'].'D';

return $config;
