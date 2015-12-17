<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'parsers' => [
                'text/xml' => 'bobchengbin\Yii2XmlRequestParser\XmlRequestParser',
                'application/xml' => 'bobchengbin\Yii2XmlRequestParser\XmlRequestParser',
                'application/json' => 'yii\web\JsonParser',
                'text/json' => 'yii\web\JsonParser',
            ],
        ],
//        'response'=>[
//            'class' => 'api\libs\Response',
//            'newheaders'=>[
//                'Access-Control-Allow-Origin'=>'*',
//            ],
//        ],
//        'response'=>[
//            'format' => yii\web\Response::FORMAT_JSON,
//            'charset' => 'UTF-8',
//        ],
        'user' => [
            'identityClass' => 'api\models\UserAccount',
            'enableAutoLogin' => true,
            'enableSession' => false,
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
            'dsn' => 'mysql:host=localhost;dbname=recruitment',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
        ],
        'urlManager'=>[
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules'=>[
                'login' => 'site/login',
                'logout' => 'site/logout',
                ['class' => 'yii\rest\UrlRule', 'controller' => ['accounts'],]
            ]
        ],

    ],
    'params' => $params,
];
