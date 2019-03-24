<?php

$mainConfig = include dirname(__FILE__) . '/main.php';

$config = array(
	'basePath' => $mainConfig['basePath'],
	'name'     => $mainConfig['name'],
	'import'   => $mainConfig['import'],
	
	'preload' => array('log', 'shortcodes'),

	'components' => array(
		'cache' => $mainConfig['components']['cache'],
		'db'    => $mainConfig['components']['db'],
        'db2'    => $mainConfig['components']['db2'],
		'log'   => $mainConfig['components']['log'],
        	'shortcodes'   => $mainConfig['components']['shortcodes'],
		'Smtpmail'=>array_merge(
		    array(
				'class'=>'application.extensions.smtpmail.PHPMailer',
				'SMTPAuth'=>true,
                'Mailer' => 'smtp',
		    ),
            getSettingsArrayFromFile(dirname(__FILE__) .'/mail.inc')
        ),
	),
	
	'commandMap' => array(
		'migrate' => array(
			'class'        => 'application.commands.ChMigrateCommand',
			'templateFile' => 'application.migrations.template',
		),
        'message' => array(
            'class' => 'application.commands.ttMessageCommand'
        ),
        'consoleGetGSuiteUsersInfo' => array(
            'class' => 'application.commands.ConsoleGetGSuiteUsersInfoCommand'
        ),
        'consoleCreateGSuiteUsers' => array(
            'class' => 'application.commands.ConsoleCreateGSuiteUsersCommand'
        )
	),

	'params' => $mainConfig['params'],
);

$config['components']['db']['connectionString'] .= ';role=RDB$ADMIN';
$config['components']['db2']['connectionString'] .= ';role=RDB$ADMIN';

return $config;