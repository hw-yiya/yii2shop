<?php
namespace frontend\assets;

use yii\web\AssetBundle;

class GoodsAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'style/base.css',
        'style/global.css',
        'style/header.css',
        'style/home.css',
        'style/list.css',
        'style/order.css',
        'style/common.css',
        'style/bottomnav.css',
        'style/footer.css',
        'style/address.css',
        'style/home.css',
        'style/goods.css',

    ];
    public $js = [
        'js/header.js',
        'js/list.js',
        'js/home.js',
        'js/goods.js',
        'js/jquery-1.8.3.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}