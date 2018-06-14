<?php
namespace app\controllers;
use Yii;

class MemberController extends BaseController
{
    public $layout = 'layout';
    
    /**
     * 个人中心
     */
    public function actionIndex(){
        
        return $this->render('index');
    }
}