<?php
use yii\helpers\Html;
use app\assets\admin\CommonAsset;

CommonAsset::register($this);

$this->title = 'Admin-Template';


?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <?= Html::csrfMetaTags() ?>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <title><?= Html::encode(Yii::$app->name) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="layui-layout-body sub-body">
    <?php $this->beginBody() ?>

    <?= $content?>

    <?php $this->endBody() ?>

    <?php
        //alert
        $session = Yii::$app->session;
        $successMsg = $session->hasFlash('success') ?  $session->getFlash('success') : '';
        $failMsg = $session->hasFlash('fail') ? $session->getFlash('fail') : '';


        $alert = '';
        if($successMsg)
            $alert = "layer.msg('{$successMsg}', {time: 3000, icon:1});";
        if($failMsg)
            $alert = "layer.msg('{$failMsg}', {time: 3000, icon:2});";
    ?>
    <script>
        layui.config({
            base: 'static/admin/js/'
        }).use(['layer'],function(){
            <?= $alert?>
        });
    </script>
    </body>
    </html>
<?php $this->endPage() ?>