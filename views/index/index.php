<?php
if(!Yii::$app->user->isGuest){
    var_dump(Yii::$app->user->identity->username);

}


?>
首页 <a href="<?= \yii\helpers\Url::to(['logout'])?>">登出</a>

<a href="<?= \yii\helpers\Url::to(['login'])?>">登入</a>

