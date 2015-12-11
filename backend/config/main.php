<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['debug','log'],
    'modules' => [
        'debug' => 'yii\debug\Module',
    ],
    'components' => [
        'user' => [
            'identityClass' => 'backend\models\UserAdmin',
            'enableAutoLogin' => true,
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=zhaopin',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
        ],
        'urlManager'=>[
            'enablePrettyUrl' => true,
            'showScriptName' => false,

        ],
    ],
    'params' => $params,
];
