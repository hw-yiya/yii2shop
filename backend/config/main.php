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
    'bootstrap' => ['log'],
    'language'=>'zh-CN',//语言
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'backend\models\Admin',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],

        ],
        'qiniu'=>[
            'class'=>\backend\components\Qiniu::className(),
            'up_host'=>'http://up-z2.qiniu.com',
            'accessKey'=>'E1EhuIzWOns2Y1mEMGgG4KJ5wS8CRZyt1nhdG9tz',
            'secretKey'=>'FvNQ8tnG07XOQ8SdMZTNxfzuap2kFpDqaBrEyzxv',
            'bucket'=>'yii2shop',
            'domain'=>'http://or9qr3rur.bkt.clouddn.com/',

        ]
    ],
    'params' => $params,
];
