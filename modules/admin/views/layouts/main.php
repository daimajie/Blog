<?php
use yii\helpers\Html;
use app\assets\admin\AppAsset;

AppAsset::register($this);

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
<body class="layui-layout-body" id="root">
<?php $this->beginBody() ?>
<div class="layui-layout layui-layout-admin">
    <!-- header -->
    <div class="layui-header" id="header">
        <!--logo-->
        <div class="layui-logo"><?= Html::encode(Yii::$app->name) ?></div>

        <!--controller-->
        <ul class="header-contr layui-layout-left">
            <li class="header-icon">
                <a href="javascript:;" class="admin-side-toggle">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                </a>
            </li>
            <li class="header-icon">
                <a href="javascript:;" class="admin-side-full">
                    <i class="fa fa-arrows" aria-hidden="true"></i>
                </a>
            </li>
            <li class="header-icon">
                <a href="javascript:;" class="admin-side-refresh">
                    <i class="fa fa-refresh" aria-hidden="true"></i>
                </a>
            </li>
        </ul>

        <!--user-->
        <ul class="layui-nav float-r" id="user-center">
            <li class="layui-nav-item">
                <a href=""><i class="layui-icon layui-icon-notice"></i></a>
            </li>
            <li class="layui-nav-item">
                <a href="javascript:;"><i class="layui-icon layui-icon-theme"></i></a>
            </li>
            <li class="layui-nav-item">
                <a href=""><i class="layui-icon layui-icon-note"></i></a>
            </li>
            <li class="layui-nav-item">
                <a href="">Admin</a>
                <dl class="layui-nav-child">
                    <dd><a href="javascript:;">修改信息</a></dd>
                    <dd><a href="javascript:;">安全管理</a></dd>
                    <dd><a href="javascript:;">退了</a></dd>
                </dl>
            </li>
            <li class="layui-nav-item">
                <a href=""><i class="layui-icon layui-icon-more-vertical"></i></a>
            </li>
        </ul>
    </div>

    <!--left-->
    <div class="layui-side layui-bg-black" id="left">
        <div class="layui-side-scroll">
            <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
            <ul class="layui-nav layui-nav-tree"  lay-filter="left-nav" id="nav-container">
                <!-- 菜单 -->
                <li data-name="home" class="layui-nav-item">
                    <a href="javascript:;" lay-tips="主页">
                        <i class="layui-icon layui-icon-home"></i>
                        <cite>主页</cite>
                        <span class="layui-nav-more"></span>
                    </a>
                    <dl class="layui-nav-child">
                        <dd data-name="console" class="layui-this">
                            <a lay-href="console.html">控制台</a>
                        </dd>
                    </dl>
                </li>


            </ul>
        </div>
    </div>

    <!--body-->
    <div class="layui-body" id="body">
        <div class="layadmin-pagetabs">
            <div id="tab-container" class="layui-tab" lay-allowClose="true" lay-filter="main-tab">
                <!-- tab-head -->
                <div class="layui-icon layadmin-tabs-control layui-icon-prev"></div>
                <div class="layui-icon layadmin-tabs-control layui-icon-next"></div>
                <div class="layui-icon layadmin-tabs-control layui-icon-down">
                    <dl class="down-nav">
                        <dd id="delTab"><a href="javascript:;">关闭当前页</a></dd>
                        <dd id="refTab"><a href="javascript:;">刷新当前页</a></dd>
                        <dd id="delAll"><a href="javascript:;">关闭所有页</a></dd>
                    </dl>
                </div>
                <ul class="layui-tab-title" id="app_tabsheader">
                    <li class="layui-this">
                        <i class="layui-icon layui-icon-home"></i>
                    </li>
                </ul>
                <!--content-->
                <?= $content?>
                <!--/content-->
            </div>
        </div>
    </div>

    <!--footer-->
    <div class="layui-footer" id="footer">
        <!-- 底部固定区域 -->
        admin_template - 底部固定区域
    </div>
</div>

<?php $this->endBody() ?>
<script>
    layui.config({
        base: 'static/admin/js/'
    }).use(['jquery','app'], function(){

    });
</script>
</body>
</html>
<?php $this->endPage() ?>