<?php
namespace app\modules\admin\modules\notebook\controllers;
use app\modules\admin\controllers\BaseController;
use Yii;

class IndexController extends BaseController
{
    /**
     * 日记首页
     */
    public function actionIndex(){

        return $this->render('index');
    }

    /**
     * 日记删除
     */
    public function actionDelete($id){

    }


}