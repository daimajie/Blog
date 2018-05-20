<?php
namespace app\assets\admin;

use yii\web\AssetBundle;


class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'static/admin/css/app.css',
    ];
    public $js = [
        'static/admin/js/data.js'
    ];


    public $depends = [
        'app\assets\LayuiAsset',
    ];
}
