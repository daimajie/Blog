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
                                <a href="javascript:;">
                                    <img src="<?= !empty($identity)?$identity->pic:Yii::$app->params['guest'];?>" alt="">
                                </a>
                            </div>
                        </label>

                        <div class="layui-input-block" id="publish">
                            <?php if(Yii::$app->user->isGuest):?>
                            <div id="pane"><p><a href="<?= Url::to(['/index/login'])?>"><b>登录后发表评论</b></a></p></div>
                            <?php endif;?>
                            <textarea id="comment-content" placeholder="请输入内容" class="layui-textarea"></textarea>
                            <br>
                            <button id="comment-btn" class="layui-btn float-r layui-btn-sm"><i class="layui-icon">&#xe609; 发射</i>   </button>
                        </div>
                    </div>

                    <!-- 评论 -->
                    <fieldset class="layui-elem-field layui-field-title">
                        <legend>最新评论 - <?= $article['comment']?></legend>
                    </fieldset>
                    <ul class="citems" id="comment-wrap">
                        <!--<li class="citem" data-id="">
                            <div class="layui-row">
                                <div class="uarea float-l layui-col-xs1">
                                    <div class="uphoto">
                                        <img src="/static/home/img/photo.jpg" alt="user">
                                    </div>
                                </div>
                                <div class="ucont float-l layui-col-xs11 font-height-24">
                                    <p class="layui-word-aux">先谋生 6小时前</p>
                                    <p class="utext">蒲苇韧如丝，但后文还有“蒲苇一时韧，便作旦夕间”这是我说过最真诚的一句话，有感而发以后也不会再说了这是我说过最真诚的一句话，有感而发以后也不会再说了</p>
                                    <p class="layui-word-aux">
                                        <span class="float-l">
                                            <span class="layui-link reply">回复</span>
                                            <span class="layui-link del">删除</span>
                                        </span>
                                        <span class="float-r">
                                            <span class="layui-link likes">点赞</span> 23
                                        </span>
                                    </p>
                                    <div class="reply">
                                        <ul>
                                            <li class="citem" data-id="">
                                                <div class="layui-row">
                                                    <div class="uarea float-l layui-col-xs1">
                                                        <div class="uphoto">
                                                            <img src="/static/home/img/photo.jpg" alt="user">
                                                        </div>
                                                    </div>
                                                    <div class="ucont float-l layui-col-xs11 font-height-24">
                                                        <p class="layui-word-aux">先谋生 6小时前</p>
                                                        <p class="utext">蒲苇韧如丝，但后文还有“蒲苇一时韧，便作旦夕间”</p>
                                                        <p class="layui-word-aux">
                                                            <span class="float-l">
                                                                <span class="layui-link reply">回复</span>
                                                                <span class="layui-link del">删除</span>
                                                            </span>
                                                                                <span class="float-r">
                                                                <span class="layui-link likes">点赞</span> 23
                                                            </span>
                                                        </p>
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
                                                        <p class="layui-word-aux">
                                                            <span class="float-l">
                                                                <span class="layui-link reply">回复</span>
                                                                <span class="layui-link del">删除</span>
                                                            </span>
                                                                                <span class="float-r">
                                                                <span class="layui-link likes">点赞</span> 23
                                                            </span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <hr class="layui-bg-gray">
                        </li>-->
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
layui.define(['layer','laypage','laytpl'], function (exports) {
    var layer = layui.layer
    ,laypage = layui.laypage
    ,laytpl = layui.laytpl;
    
    var obj = {
        layer : function(){
            /*分页*/
            laypage.render({
                elem: 'pager'
                ,count: "$commentCount"
                ,limit: "$limit"
                ,theme: '#1E9FFF'
                ,jump: function(obj, first){
                    //评论数据
                    $.ajax({
                        url : UrlManager.createUrl('/article/get-comments'),
                        type : 'post',
                        data : {'_csrf' : "$token", 'curr' : obj.curr, 'limit' : obj.limit, 'article_id':"$article->id"},
                        success : function(d){
                            var getTpl = $('#comment-all').html()
                            ,view = document.getElementById('comment-wrap');
                            laytpl(getTpl).render(d.data, function(html){
                              view.innerHTML = html;
                            });
                            
                        }
                    });
                    
                }
            });
        }
    };
    
    /*分页*/
    obj.layer();
    
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
    
    
    /*提交评论*/
    $('#comment-btn').on('click', function(){
        var content = $('#comment-content').val();
        if(content.length === 0)
            return;
        //取消收藏
        $.ajax({
            url : UrlManager.createUrl('/article/comment'),
            type : 'post',
            data : {'content':content, 'article_id' : "$article->id", '_csrf': "$token"},
            success : function(d){
                if(d.errno === 0){
                    var getTpl = $('#comment-item').html()
                    ,view = document.getElementById('comment-wrap');
                    laytpl(getTpl).render(d.data, function(html){
                      $(view).prepend(html);
                    });
                    $('#comment-content').val('');
                    
                }
                layer.msg(d.message);
            }
        });
        
    });
    
    /*删除评论*/
    $('#comment-wrap').on('click','.del',function(){
        var that = $(this);
        layer.confirm('您确定要删除该评论吗？', {
          btn: ['是的','取消'] //按钮
        }, function(index){
            layer.close(index);
            //删除
            
            var reply = that.closest('li').data('reply')
            ,id = that.closest('li').data('id')
            ,article_id = "$article->id";
            
            $.ajax({
                url : UrlManager.createUrl('/article/del-comment'),
                type : 'post',
                data : {'article_id' : article_id, 'reply': reply, 'id': id, '_csrf': "$token"},
                success : function(d){
                    if(d.errno === 0){
                        //删除成功
                        that.closest('li').remove();//删除DOM
                    }
                    layer.msg(d.message);
                }
            });
        });
        
    });
    
    /*评论点赞*/
    var likes = true;
    $('#comment-wrap').on('click', '.likes', function(e){
        var that = $(this);
        var hasLike = that.hasClass('like')
            ,reply = that.closest('li').data('reply')
            ,id = that.closest('li').data('id')
            ,article_id = "$article->id";
        if(likes && !hasLike){
            likes = false;
            //点赞
            $.ajax({
                url : UrlManager.createUrl('/comment/add-likes'),
                type : 'post',
                data : {'article_id' : article_id, 'replay': reply, 'id': id, '_csrf': "$token"},
                success : function(d){
                    if(d.errno === 0){
                        //累加
                        var val = parseInt(that.find('b').text());
                        that.find('b').text( 1 + val);
                        that.addClass('like');
                    }
                    layer.msg(d.message);
                    likes = true;
                }
            });
        }else if(likes && hasLike){
            likes = false;
            //取消点赞
            $.ajax({
                url : UrlManager.createUrl('/comment/del-likes'),
                type : 'post',
                data : {'article_id' : article_id, 'replay': reply, 'id': id, '_csrf': "$token"},
                success : function(d){
                    if(d.errno === 0){
                        //减去1
                        var val = parseInt(that.find('b').text());
                        that.find('b').text( val - 1 );
                        that.removeClass('like');
                    }
                    layer.msg(d.message);
                    likes = true;
                }
            });
        }
        
    });
    
    /*显示回复框*/
    $('#comment-wrap').on('click', 'span.reply', function(e){
        var that = $(this);
        var getTpl = $('#input-bar').html()
        ,view = that.closest('div.ucont').find('.input-bar')[0];
        //清空其它
        $('.input-bar').empty();
        laytpl(getTpl).render([], function(html){
            view.innerHTML = html;
        });
        //阻止冒泡
         e.stopPropagation();
    });
    
    /*提交回复*/
    $('#comment-wrap').on('click','a.reply-btn',function(){
        var that = $(this)
        ,oInput = that.closest('.reply-wrap').find('input')
        ,content = oInput.val();
        
        var article_id = "$article->id"
        ,comment_id = that.closest('li.comment-item').data('id');
        
        $.ajax({
            url : UrlManager.createUrl('/article/comment'),
            type : 'post',
            data : {
                'content':content, 
                'comment_id': comment_id,
                'article_id' : article_id, 
                '_csrf': "$token"
                },
            success : function(d){
                if(d.errno === 0){
                    //提交成功
                    that.closest('.input-bar').empty();
                    /*分页*/
                    obj.layer();
                    
                }
                layer.msg(d.message);
            }
        })
        
    });
    
    


});
JS;

$this->registerJs($strJs);
?>
<!--回复框-->
<script id="input-bar" type="text/html">
<div class="layui-row reply-wrap">
    <div class="layui-col-xs10">
        <input type="text"  class="layui-input reply-content">
    </div>
    <div class="layui-col-xs2">
        <a class="layui-btn layui-btn-primary reply-btn">提交回复</a>
    </div>
</div>
</script>

<!--评论模板-->
<script id="comment-item" type="text/html">
<li class="citem" data-id="{{ d.id }}" data-reply="{{ d.reply }}">
    <div class="layui-row">
        <div class="uarea float-l layui-col-xs1">
            <div class="uphoto">
                <img src="{{ d.photo }}" alt="user">
            </div>
        </div>
        <div class="ucont float-l layui-col-xs11 font-height-24">
            <p class="layui-word-aux">{{ d.username }} {{ d.created_at }}</p>
            <p class="utext">{{ d.content }}</p>
            <p class="layui-word-aux">
                <span class="float-l">
                    <span class="layui-link del">删除</span>
                </span>
            </p>
        </div>
    </div>
</li>
</script>

<!--评论分页模板-->
<script id="comment-all" type="text/html">
{{#  layui.each(d, function(index, item){ }}
<li class="citem comment-item" data-id="{{ item.id }}" data-reply="{{ item.reply }}">
    <div class="layui-row">
        <div class="uarea float-l layui-col-xs1">
            <div class="uphoto">
                <img src="{{ item.user.photo }}" alt="user">
            </div>
        </div>
        <div class="ucont float-l layui-col-xs11 font-height-24">
            <p class="layui-word-aux">{{ item.user.username }} {{ item.created_at }}</p>
            <p class="utext">{{ item.content }}</p>
            <p class="layui-word-aux">
                <span class="float-l">
                    <span class="layui-link reply">回复</span>
                    {{#  if( item.self ){ }}
                    <span class="layui-link del">删除</span>
                    {{#  } }}
                </span>
                <span class="float-r">
                    <span class="layui-link likes">点赞 <b>{{ item.likes }}</b></span>
                </span>
            </p>
            <p class="input-bar"></p>

            <div class="reply">
                <ul class="reply-ul">
                    {{#  layui.each(item.replys, function(key, val){ }}
                    <li class="citem" data-id="{{ val.id }}" data-reply="{{ val.reply }}">
                        <div class="layui-row">
                            <div class="uarea float-l layui-col-xs1">
                                <div class="uphoto">
                                    <img src="{{ val.user.photo }}" alt="user">
                                </div>
                            </div>
                            <div class="ucont float-l layui-col-xs11 font-height-24">
                                <p class="layui-word-aux">{{ val.user.username }} {{ val.created_at }}</p>
                                <p class="utext">{{ val.content }}</p>
                                <p class="layui-word-aux">
                                    <span class="float-l">
                                        <span class="layui-link reply">回复</span>

                                        {{#  if( val.self ){ }}
                                        <span class="layui-link del">删除</span>
                                        {{#  } }}
                                    </span>
                                    <span class="float-r">
                                        <span class="layui-link likes">点赞 <b>{{ val.likes }}</b></span>
                                    </span>
                                </p>
                                <p class="input-bar"></p>
                            </div>
                        </div>
                    </li>
                    {{#  }); }}
                </ul>
            </div>
        </div>
    </div>
    <hr class="layui-bg-gray">
</li>
{{#  }); }}

</script>