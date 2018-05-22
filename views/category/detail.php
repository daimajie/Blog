<?php
use app\assets\home\AppAsset;
use yii\helpers\Url;
AppAsset::addCss($this,'static/home/css/topics.css');
?>


<section class="breadcrumb">
    <div class="layui-container margin-t-10 margin-b-10">
		<span class="layui-breadcrumb">
		  <a href="/">首页</a>
		  <a><cite>分类详情</cite></a>
		</span>
    </div>
    <hr class="layui-bg-gray">
</section>

<section class="content">
    <div class="layui-container articles">
        <div class="layui-row">
            <div class="layui-col-md9">
                <div class="topics">
                    <blockquote class="layui-elem-quote layui-quote-nm">
                        <h1><?= $category['name']?></h1>
                        <?= $category['desc']?>
                    </blockquote>
                    <ul>
                        <?php
                        if(!empty($topics)):
                        foreach($topics as $item):?>
                        <li class="topic-item">
                            <a href="<?= Url::to(['topic/index','topic_id'=>$item['id']])?>" ><?= $item['name']?></a>
                            <span class="layui-badge-rim layui-btn layui-btn-primary">收录<?= $item['count']?></span>
                        </li>
                        <?php endforeach;
                        endif;
                        ?>
                    </ul>
                </div>
            </div>
            <div class="layui-col-md3">
                <div class="sidebar">
                    <div class="topics layui-hide-xs layui-hide-sm layui-show-md-block">
                        <fieldset class="layui-elem-field">
                            <!-- <legend>广告</legend> -->
                            <div class="layui-field-box">
                                广告区域
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

