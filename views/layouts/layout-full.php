<?php
use yii\helpers\Html;
use app\assets\LayuiAsset;
use app\assets\home\AppAsset;
use yii\helpers\Url;
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
    <div class="bg">

        <div class="banner crossfade">
            <?php if(!empty($this->params['star'])):?>
            <div class="user-info">
                <div class="photo">
                    <img src="<?= $this->params['star']['photo']?>" alt="">
                </div>
                <div class="brief">
                    <p class="user-name">
                       今日之星 | <?= $this->params['star']['username']?>
                        <span class="layui-badge layui-inline"><i class="layui-icon">&#xe62c;</i>   <?= $this->params['star']['total']?>+</span>
                    </p>
                    <p class="font-16">签名： 庆幸，向左或向右，我都没有迷失方向っ  ||  还好，向前或向后，我都如愿找到了你っ。</p>
                </div>
            </div>
            <?php endif;?>
        </div>

    </div>
    <div class="sub">
        <div class="">
            <div class="layui-container">
                <?=
                Menu::widget([
                    'activateItems' => true,
                    'activeCssClass' => 'active',
                    'options' => ['class'=>'sub-nav'],
                    'itemOptions' => ['class'=>'sub-nav-item'],
                    'items' => $this->params['cats'],
                ]);
                ?>
            </div>
        </div>
    </div>
</section>
<!-- /header -->

<?= $content?>

<!-- footer -->
<div class="layui-clear"></div>
<section class="flooter margin-t-10">
    <div class="layui-container">
        <div class="links">
            <ul>
                <li><a href="/" target="_blank">网站首页</a></li>
                <li><a href="<?= Url::to(['/notebook/index'])?>" target="_blank">心情日记</a></li>
                <li><a href="<?= Url::to(['/category/index'])?>" target="_blank">文章分类</a></li>
                <li> <a href="<?= Url::to(['/category/index'])?>" target="_blank">热门话题</a></li>
                <li> <a href="<?= Url::to(['/category/index'])?>" target="_blank">友情链接</a></li>
                <li><a href="<?= Url::to(['/category/index'])?>" target="_blank">关于我们</a></li>
            </ul>
            <p class="layui-clear">Copyright © 2018 DAIMAJIE.COM</p>
        </div>
        <div class="followme float-r">
            <a class="followus-weixin" href="#" target="_blank" title="微信"></a>
            <a class="followus-weibo" href="#" target="_blank" title="新浪微博"></a>
            <a class="followus-qzone" href="#" target="_blank" title="QQ空间"></a>
        </div>
    </div>
</section>
<!-- /footer -->
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