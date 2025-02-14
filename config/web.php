<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => '-G_jGt_atLG-AcctLHgFu_7vju06W5ZW', 
            'enableCsrfValidation' => false, // Deshabilita CSRF para API REST
            'parsers' => [
                'application/json' => 'yii\web\JsonParser', // Soporte JSON
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'], // Agregar 'info' para depuración
                    'logVars' => ['_GET', '_POST', '_FILES', '_COOKIE', '_SESSION', '_SERVER'],
                ],
            ],
        ],
        'db' => $db,
        'mongodb' => [ // Configuración de MongoDB
            'class' => '\yii\mongodb\Connection', // Clase de conexión
            'dsn' => 'mongodb://localhost:27017/Biblioteca', // Conexión a la base de datos Biblioteca
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false, // Deshabilita el modo estricto
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/autor', 'v1/libro'], // Controladores RESTful
                    'pluralize' => false,
                    'extraPatterns' => [ 
                        'GET <id>' => 'view',
                        'PUT <id>' => 'update',
                        'DELETE <id>' => 'delete',
                    ],
                ],
            ],
        ],
        'corsFilter' => [
            'class' => \yii\filters\Cors::class,
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Allow-Headers' => ['*'],
            ],
        ],
    ],
    'modules' => [
        'v1' => [
            'class' => 'app\modules\v1\Module',  // Ruta del módulo para API v1 
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

date_default_timezone_set('America/Guayaquil');  // Configura la zona horaria local

return $config;
