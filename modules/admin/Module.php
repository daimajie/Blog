<?php

namespace app\modules\admin;
use yii\web\ForbiddenHttpException;

/**
 * admin module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\admin\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        \Yii::configure($this, require __DIR__ . '/config.php');
    }

    public function beforeAction($action)
    {
        if(parent::beforeAction($action)){
            if(!\Yii::$app->user->isGuest && \Yii::$app->user->identity->author === 1){
                return true;
            }
            throw new ForbiddenHttpException('您无权访问。');

        }
    }
}
