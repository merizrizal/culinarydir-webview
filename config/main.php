<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-webview',
    'language' => 'id',
    'name' => 'Asikmakan',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'webview\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-webview-asikmakan-synctech',
        ],
        'user' => [
            'identityClass' => 'core\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-webview-asikmakan-synctech', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the core
            'name' => 'webview-asikmakan',
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
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<controller:\w+>/<action:\w+>/<id:\w+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ]
        ],
    ],
    'params' => $params,
];