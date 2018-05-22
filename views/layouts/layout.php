<?php
use yii\helpers\Html;
use app\assets\LayuiAsset;
use app\assets\home\AppAsset;

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
                <ul class="layui-nav float-r" lay-filter="">
                    <li class="layui-nav-item"><a href="">最新活动</a></li>
                    <li class="layui-nav-item layui-this"><a href="">产品</a></li>
                    <li class="layui-nav-item"><a href="">大数据</a></li>
                    <li class="layui-nav-item">
                        <a href="javascript:;">解决方案</a>
                        <dl class="layui-nav-child"> <!-- 二级菜单 -->
                            <dd><a href="">移动模块</a></dd>
                            <dd><a href="">后台模版</a></dd>
                            <dd><a href="">电商平台</a></dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item"><a href="">社区</a></li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- /header -->

<?= $content?>

<section class="flooter margin-t-10">
    <div class="layui-container">
        <div class="links">
            <ul>
                <li><a href="#" target="_blank">网站首页</a></li>
                <li><a href="#" target="_blank">企业合作</a></li>
                <li><a href="#" target="_blank">人才招聘</a></li>
                <li> <a href="#" target="_blank">联系我们</a></li>
                <li> <a href="#" target="_blank">讲师招募</a></li>
                <li> <a href="#" target="_blank">常见问题</a></li>
                <li> <a href="#" target="_blank">意见反馈</a></li>
                <li><a href="#" target="_blank">慕课大学</a></li>
                <li> <a href="#" target="_blank">友情链接</a></li>
                <li><a href="#" target="_blank">合作专区</a></li>
                <li><a href="#" target="_blank">关于我们</a></li>
            </ul>
            <p class="layui-clear">Copyright © 2018 imooc.com All Rights Reserved | 京ICP备 12003892号-11 </p>
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
