<?php
namespace app\assets\admin;

use yii\web\AssetBundle;


class CommonAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'static/admin/css/common.css',
    ];
    public $js = [
        //'static/admin/js/data.js'
    ];


    public $depends = [
        'app\assets\LayuiAsset',
    ];
}
