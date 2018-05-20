<?php
namespace app\modules\admin\controllers;
use Yii;
use yii\web\Controller;

class IndexController extends Controller
{
    public $layout = 'main';
    /**
     * 后台首页
     */
    public function actionIndex(){
        return $this->render('index',[

        ]);
    }
}