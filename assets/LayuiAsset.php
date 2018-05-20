<?php
namespace app\assets;

use yii\web\AssetBundle;


class LayuiAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'static/libs/font-awesome/css/font-awesome.min.css',
        'static/libs/layui/css/layui.css',
    ];

    public $js = [
        'static/libs/layui/layui.js'
    ];


}
