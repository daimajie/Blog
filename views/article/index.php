<?php
use app\assets\home\AppAsset;
use yii\helpers\Url;
use app\components\View;

AppAsset::addCss($this,'static/home/css/content.css');
?>
<section class="breadcrumb">
    <div class="layui-container margin-t-10 margin-b-10">
    <span class="layui-breadcrumb">
      <a href="/">首页</a>
      <a><cite>文章详情</cite></a>
    </span>
    </div>
    <hr class="layui-bg-gray">
</section>
<section class="content">
    <div class="layui-container articles">
        <div class="layui-row">
            <div class="layui-col-md9">
                <div class="content">
                    <h1 class="margin-b-10 margin-t-10"><?= $article['title']?></h1>
                    <div class="info">
                        <p class="margin-b-10">
                            <span class="layui-word-aux"><?= $article['user']['username']?></span>
                            <span class="layui-word-aux"><?= View::timeFormat($article['created_at'])?></span>

                            <span class="layui-badge layui-bg-green"><?= $article['type']?></span>
                        </p>
                        <p class="margin-b-10 layui-word-aux">
                            <i class="fa fa-comment-o" aria-hidden="true"></i>
                            &nbsp;回复&nbsp;<?= $article['comment']?>次&nbsp;
                            <i class="fa fa-eye" aria-hidden="true"></i>
                            &nbsp;浏览&nbsp;<?= $article['visited']?>次&nbsp;
                            <i class="fa fa-heart-o" aria-hidden="true"></i>
                            &nbsp;点赞&nbsp;<?= $article['likes']?>次&nbsp;
                        </p>


                    </div>
                    <div class="text">
                        <!-- 内容 -->
                        <?= $article['content']['content']?>
                        <!-- /内容 -->
                    </div>
                    <div class="zan margin-t-10 margin-b-10">
                        <div class="margin-b-10">
                            <a id="likes-btn" href="javascript:;" class="layui-btn <?= !empty($likes)?'layui-btn-disabled':'layui-btn-primary';?>">
                                <i class="layui-icon" style="font-size: 28px; color: #009688;">
                                    &#xe6c6;
                                </i><b>点赞</b>
                            </a>
                            <a id="collect-btn" href="javascript:;" class="layui-btn <?= !empty($collect)?'layui-btn-disabled':'layui-btn-primary';?>">
                                <i class="layui-icon" style="font-size: 28px; color: #FFB800;">
                                    &#xe600;
                                </i><b>收藏</b>
                            </a>

                        </div>
                        <div>
                            <p class="float-r">
								<span>
									<i>标签：</i>
                                    <?php
                                    if(!empty($article['tags'])):
                                        foreach($article['tags'] as $val):
                                    ?>
									<i><a href="<?= Url::to(['/topic/index','topic_id'=>$article['topic_id'],'tag_id'=>$val['id']])?>"><?= $val['name']?></a></i>
                                    <?php
                                        endforeach;
                                    endif;
                                    ?>
								</span>
                                &nbsp;&nbsp;|&nbsp;&nbsp;
                                <span>
									<i>话题：</i>
									<i><a href="<?= Url::to(['/topic/index','topic_id'=>$article['topic_id']])?>"><?= $article['topic']['name']?></a></i>
								</span>
                            </p>
                        </div>
                        <div class="layui-clear"></div>
                    </div>
                    <br>
                    <p class="margin-b-5">
                        <a href="<?= $prevAndNext['prev']['url']?>" class="layui-btn layui-btn-sm layui-btn-normal">
                            上一篇
                        </a>
                        <span><a href="<?= $prevAndNext['prev']['url']?>"><?= $prevAndNext['prev']['title']?></a></span>
                    </p>

                    <p>
                        <a href="<?= $prevAndNext['next']['url']?>" class="layui-btn layui-btn-sm layui-btn-normal">
                            下一篇
                        </a>
                        <span><a href="<?= $prevAndNext['next']['url']?>"><?= $prevAndNext['next']['title']?></a></span>
                    </p>
                    <br>
                </div>
                <hr class="layui-bg-gray">
                <div class="comment">
                    <!-- 编辑器 -->
                    <div class="layui-form-item layui-form-text marg-0 ">
                        <label class="layui-form-label">
                            <div class="photo">
                                <a href="#">
                                    <img src="<?= $identity->pic?>" alt="<?= $identity->username?>">
                                </a>
                            </div>
                        </label>
评论及回复
                        <div class="layui-input-block" id="publish">
                            <?php if(Yii::$app->user->isGuest):?>
                            <div id="pane"><p><a href="<?= Url::to(['/index/login'])?>"><b>登录后发表评论</b></a></p></div>
                            <?php endif;?>
                            <textarea placeholder="请输入内容" class="layui-textarea"></textarea>
                            <br>
                            <a href="javascript:void();">Ctrl + Enter 发布</a>
                            <button class="layui-btn float-r layui-btn-sm"><i class="layui-icon">&#xe609; 发射</i>   </button>
                        </div>
                    </div>

                    <!-- 评论 -->
                    <fieldset class="layui-elem-field layui-field-title">
                        <legend>最新评论 - <?= $article['comment']?></legend>
                    </fieldset>
                    <ul class="citems">
                        <li class="citem">
                            <div class="layui-row">
                                <div class="uarea float-l layui-col-xs1">
                                    <div class="uphoto">
                                        <img src="/static/home/img/photo.jpg" alt="user">
                                    </div>
                                </div>
                                <div class="ucont float-l layui-col-xs11 font-height-24">
                                    <p class="layui-word-aux">先谋生 6小时前</p>
                                    <p class="utext">蒲苇韧如丝，但后文还有“蒲苇一时韧，便作旦夕间”这是我说过最真诚的一句话，有感而发以后也不会再说了这是我说过最真诚的一句话，有感而发以后也不会再说了</p>
                                    <p class="layui-word-aux"><span>回复</span><span class="float-r">点赞 23</span></p>
                                    <div class="reply">
                                        <ul>
                                            <li class="citem">
                                                <div class="layui-row">
                                                    <div class="uarea float-l layui-col-xs1">
                                                        <div class="uphoto">
                                                            <img src="/static/home/img/photo.jpg" alt="user">
                                                        </div>
                                                    </div>
                                                    <div class="ucont float-l layui-col-xs11 font-height-24">
                                                        <p class="layui-word-aux">先谋生 6小时前</p>
                                                        <p class="utext">蒲苇韧如丝，但后文还有“蒲苇一时韧，便作旦夕间”</p>
                                                        <p class="layui-word-aux"><span>回复</span><span class="float-r">点赞 23</span></p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="citem">
                                                <div class="layui-row">
                                                    <div class="uarea float-l layui-col-xs1">
                                                        <div class="uphoto">
                                                            <img src="/static/home/img/photo.jpg" alt="user">
                                                        </div>
                                                    </div>
                                                    <div class="ucont float-l layui-col-xs11 font-height-24">
                                                        <p class="layui-word-aux">先谋生 6小时前</p>
                                                        <p class="utext">蒲苇韧如丝，但后文还有“蒲苇一时韧，便作旦夕间”</p>
                                                        <p class="layui-word-aux"><span>回复</span><span class="float-r">点赞 23</span></p>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <hr class="layui-bg-gray">
                        </li>
                        <li class="citem">
                            <div class="layui-row">
                                <div class="uarea float-l layui-col-xs1">
                                    <div class="uphoto">
                                        <img src="/static/home/img/photo.jpg" alt="user">
                                    </div>
                                </div>
                                <div class="ucont float-l layui-col-xs11 font-height-24">
                                    <p class="layui-word-aux">先谋生 6小时前</p>
                                    <p class="utext">蒲苇韧如丝，但后文还有“蒲苇一时韧，便作旦夕间”</p>
                                    <p class="layui-word-aux"><span>回复</span><span class="float-r">点赞 23</span></p>
                                </div>
                            </div>
                            <hr class="layui-bg-gray">
                        </li>
                        <li class="citem">
                            <div class="layui-row">
                                <div class="uarea float-l layui-col-xs1">
                                    <div class="uphoto">
                                        <img src="/static/home/img/photo.jpg" alt="user">
                                    </div>
                                </div>
                                <div class="ucont float-l layui-col-xs11 font-height-24">
                                    <p class="layui-word-aux">先谋生 6小时前</p>
                                    <p class="utext">蒲苇韧如丝，但后文还有“蒲苇一时韧，便作旦夕间”</p>
                                    <p class="layui-word-aux"><span>回复</span><span class="float-r">点赞 23</span></p>
                                </div>
                            </div>
                        </li>
                        <hr class="layui-bg-gray">
                    </ul>


                </div>
                <div id="pager"></div>
                <hr class="layui-bg-gray">
            </div>
            <div class="layui-col-md3">
                <div class="sidebar">
                    <div class="prefer layui-hide-xs layui-hide-sm layui-show-md-block">
                        <fieldset class="layui-elem-field">
                            <!-- <legend>广告</legend> -->
                            <div class="layui-field-box">
                                <a href="javascript:;" class="pic"></a>
                                <a href="javascript:;" class="headImg">
                                    <img src="<?= $article['user']['photo']?>" alt="<?= $article['user']['username']?>">
                                </a>
                                <div class="info">
                                    <a href="javascript:;" class="info-title"><?= $article['user']['username']?></a>
                                    <p>
                                        <i class="layui-icon">&#xe62c;</i>
                                        文章:
                                        <b><span class="layui-badge-rim"><?= $article['user']['total']?></span></b>
                                    </p>
                                    <p>
                                        <i class="layui-icon">&#xe66f;</i>
                                        角色:
                                        <b><?= $article['user']['author']?></b>
                                    </p>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="related">
                        <fieldset class="layui-elem-field">
                            <!-- <legend>广告</legend> -->
                            <div class="layui-field-box">
                                相关文章
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
$token = Yii::$app->request->getCsrfToken();
$strJs = <<<JS
layui.define(['layer','laypage'], function (exports) {
    var layer = layui.layer
    ,laypage = layui.laypage;
    
    /*分页*/
    laypage.render({
        elem: 'pager'
        ,count: 100
        ,theme: '#1E9FFF'
      });
    
    
    /*点赞收藏*/
    var flag = true;
    $('#likes-btn').on('click',function(){
        var that = $(this);
        var disabled = that.hasClass('layui-btn-disabled');
        if(flag){
            flag = false;
            if(disabled){
                //取消喜欢
                $.ajax({
                    url : UrlManager.createUrl('/article/del-likes'),
                    type : 'post',
                    data : {'article_id' : "$article->id", '_csrf': "$token"},
                    success : function(d){
                        if(d.errno === 0){
                            //点赞成功
                            that
                            .removeClass('layui-btn-disabled')
                            .addClass('layui-btn-primary');
                        }
                        layer.msg(d.message);
                        flag = true;
                    }
                });
                
            }else{
                //添加喜欢
                $.ajax({
                    url : UrlManager.createUrl('/article/add-likes'),
                    type : 'post',
                    data : {'article_id' : "$article->id", '_csrf': "$token"},
                    success : function(d){
                        if(d.errno === 0){
                            //点赞成功
                            that
                            .removeClass('layui-btn-primary')
                            .addClass('layui-btn-disabled');
                        }
                        layer.msg(d.message);
                        flag = true;
                    }
                });
            }
        }
        
        
        
        
        
    });
    
    $('#collect-btn').on('click', function(){
         var that = $(this);
         var disabled = that.hasClass('layui-btn-disabled');
         if(flag){
             flag = false;
             if(disabled){
                 //取消收藏
                 $.ajax({
                        url : UrlManager.createUrl('/article/del-collect'),
                        type : 'post',
                        data : {'article_id' : "$article->id", '_csrf': "$token"},
                        success : function(d){
                            if(d.errno === 0){
                                
                                that
                                .removeClass('layui-btn-disabled')
                                .addClass('layui-btn-primary');
                            }
                            layer.msg(d.message);
                            flag = true;
                        }
                    });
             }else{
                 //添加收藏
                 $.ajax({
                    url : UrlManager.createUrl('/article/add-collect'),
                    type : 'post',
                    data : {'article_id' : "$article->id", '_csrf': "$token"},
                    success : function(d){
                        if(d.errno === 0){
                            //点赞成功
                            that
                            .removeClass('layui-btn-primary')
                            .addClass('layui-btn-disabled');
                        }
                        layer.msg(d.message);
                        flag = true;
                    }
                 });
             }
         }
    });


});
JS;

$this->registerJs($strJs);
?>