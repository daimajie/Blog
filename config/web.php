<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'name' => 'DAIMAJIE.COM', /*站点名称*/
    'language' => 'zh-CN',
    'defaultRoute' => 'index',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log','jsUrlManager'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ],
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'yyDYA-_VkQRm9huBm9pOymre74-kqsm1',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\member\User',
            'loginUrl' => ['index/login'],
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'index/error',
        ],
        'mailer' => [ /*邮箱配置*/
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,

            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.163.com',
                'username' => 'git1314@163.com',
                'password' => 'git1314',
                'port' => '25',
                'encryption' => null,

            ],
            'messageConfig'=>[
                'charset'=>'UTF-8',
                'from'=>['git1314@163.com'=>Yii::$app->name]
            ],

        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
        'assetManager' => [
            'appendTimestamp' => true,
        ],
        'jsUrlManager' => [
            'class' => \dmirogin\js\urlmanager\JsUrlManager::class,
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
