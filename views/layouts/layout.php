<?php
use yii\helpers\Html;
use app\assets\LayuiAsset;
use app\assets\home\AppAsset;
use yii\widgets\Menu;

AppAsset::register($this);
LayuiAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <?= Html::csrfMetaTags() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<!-- header -->
<section class="topbar">
    <div class="nav">
        <div class="layui-container">
            <a class="logo layui-hide-xs layui-hide-sm layui-show-md-block" href="/">DAIMAJIE.COM</a>
            <div class="">
                <?=
                Menu::widget([

                    'activateItems' => true,
                    'activeCssClass' => 'layui-this',
                    'options' => [
                        'class'=>'layui-nav float-r',
                        'lay-filter'=>'',

                    ],
                    'itemOptions' => [
                        'class'=>'layui-nav-item',
                    ],


                    'submenuTemplate' => "<dl class='layui-nav-child'>{items}</dl>",
                    'items' => [
                        ['label' => '首页', 'url' => ['/index/index']],
                        ['label' => '日记', 'url' => ['/notebook/index']],
                        ['label' => '分类', 'url' => ['/category/index']],
                        [
                            'label' => Yii::$app->user->isGuest ? 'Guest' : Yii::$app->user->identity->username,
                            'url' => null,
                            'items' => [
                                [
                                    'label' => '个人中心',
                                    'url' => ['/member/index'],
                                    'options' => [
                                        'tag' => 'dd',
                                        'class' => ''
                                    ],
                                ],
                                [
                                    'label' => '写文章',
                                    'url' => ['/admin'],
                                    'options' => [
                                        'tag' => 'dd',
                                        'class' => ''
                                    ],
                                ],
                                [
                                    'label' => '退出',
                                    'url' => ['/index/logout'],
                                    'options' => [
                                        'tag' => 'dd',
                                        'class' => ''
                                    ],
                                ],
                            ],
                            'visible' => !Yii::$app->user->isGuest
                        ],
                        ['label' => '登录', 'url' => ['/index/login'],'visible' => Yii::$app->user->isGuest],
                        ['label' => '注册', 'url' => ['/index/register'],'visible' => Yii::$app->user->isGuest],
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>
</section>
<!-- /header -->

<?= $content?>

<section class="flooter margin-t-10">
    <div class="layui-container">
        <div class="links">
            <p><?= $this->params['metas']['aboutme']?></p>
            <p class="layui-clear">Copyright © 2018 DAIMAJIE.COM</p>
        </div>
        <div class="followme float-r">
            <a class="followus-weixin" href="javascript:;" target="_blank" title="微信">
                <div class="flw-weixin-box"></div>
            </a>
            <a class="followus-weibo" href="#" target="_blank" title="新浪微博"></a>
            <a class="followus-qzone" href="#" target="_blank" title="QQ空间"></a>
        </div>
    </div>
</section>

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
        base: '/static/home/js/'
    }).use(['layer','index'],function(){
        <?= $alert?>
    });
</script>

</body>
</html>
<?php $this->endPage() ?>
