<?php
use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

?>
    <h2>您在 <b><?= Yii::$app->name?></b> 注册的验证码为 <b><?= $captcha?></b> </h2>
    <p>请在5分钟之内使用，过期无效。</p>