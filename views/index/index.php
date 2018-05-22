

<!-- content -->
<div class="layui-clear"></div>
<section class="content">
    <div class="layui-container articles">
        <div class="layui-row">
            <div class="layui-col-md9">
                <ul class="artlist" id="article-wrap"></ul>
                <div style="width: 216px; margin: auto;">
                    <button id="load-btn" class="layui-btn layui-btn-fluid">加载更多</button>
                </div>
            </div>
            <div class="layui-col-md3">
                <div class="sidebar">
                    <!-- 头像 -->
                    <div class="prefer layui-hide-xs layui-hide-sm layui-show-md-block">
                        <fieldset class="layui-elem-field">
                            <!-- <legend>广告</legend> -->
                            <div class="layui-field-box">
                                <a href="#" class="pic"></a>
                                <a href="#" class="headImg">
                                    <img src="/static/home/img/photo.jpg" alt="#">
                                </a>
                                <div class="info">
                                    <a href="#" class="info-title">虽虽酱</a>
                                    <p><i class="fa fa-star" aria-hidden="true"></i>9645</p>
                                    <p>擅长领域: <b>Q版</b></p>
                                </div>
                            </div>
                        </fieldset>
                    </div>

                    <!-- 用户信息 -->
                    <div class="user-info layui-hide-xs layui-hide-sm layui-show-md-block">
                        <fieldset class="layui-elem-field">
                            <legend>关于我</legend>
                            <div style="padding: 5px 0 0 15px;">
                                <p><i class="fa fa-star" aria-hidden="true"></i> 歌手 演员</p>
                                <p><i class="fa fa-map-marker" aria-hidden="true"></i> 北京 故宫</p>
                                <p><i class="fa fa-map-signs" aria-hidden="true"></i> 我是歌手兼演员</p>
                                <p><i class="fa fa-user-circle-o" aria-hidden="true"></i> 张杰，1982年12月20日出生于四川成都，毕业于四川师范大学，内地流行男歌手，行星文化（音乐厂牌）... <a href="#">详情»</a></p>
                            </div>
                        </fieldset>
                    </div>

                    <!-- 个人相册 -->
                    <div class="user-pics  layui-hide-xs layui-hide-sm layui-show-md-block">
                        <fieldset class="layui-elem-field">
                            <legend>我的相册</legend>
                            <div style="padding: 5px;">
                                <img width="100%" src="/static/home/img/pics.png" alt="">
                            </div>
                        </fieldset>
                    </div>

                    <div class="firend-links">
                        <fieldset class="layui-elem-field">
                            <legend>友情连接</legend>
                            <div class="layui-field-box">
                                <ul class="firend">
                                    <li class="firend-item"><a href="#">aasda</a></li>
                                    <li class="firend-item"><a href="#">dsfsd</a></li>
                                    <li class="firend-item"><a href="#">qwe</a></li>
                                    <li class="firend-item"><a href="#">rweyh</a></li>
                                    <li class="firend-item"><a href="#">dfasd</a></li>
                                    <li class="firend-item"><a href="#">ewqrwe</a></li>
                                    <li class="firend-item"><a href="#">ghf</a></li>
                                    <li class="firend-item"><a href="#">gdfh</a></li>
                                    <li class="firend-item"><a href="#">werqwreqa</a></li>
                                </ul>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /content -->
<?php
$token =  \Yii::$app->request->getCsrfToken();
$strJs = <<<JS
    layui.use(['laytpl'],function(){
        var laytpl = layui.laytpl;
    
        var obj = {curr : 1, limit : {$pageSize}};
        $('#load-btn').on('click',function(){
            var that = $(this);
            that.html('loading...');//loading...
            
            $.ajax({
                url :  UrlManager.createUrl('/index/get-articles'),
                type : 'post',
                data : {'_csrf' : "$token", 'curr' : obj.curr, 'limit' : obj.limit},
                success : function(d){
                    var getTpl = $('#article-item').html()
                    ,view = document.getElementById('article-wrap');
                    laytpl(getTpl).render(d, function(html){
                      $(view).append(html);
                    });
                    
                    that.html('加载更多');
                    obj.curr++;
                    
                    
                }
            });
            
            
        }).click();
      
      
    });
    

JS;
$this->registerJs($strJs);
?>



<script id="article-item" type="text/html">
        {{#  layui.each(d.data, function(index, item){ }}
        <li class="artitem">
            <div class="contop">
                <div class="photo float-l">
                    <a href="javascript:;"><img src="{{ item.user.photo }}" alt="{{ item.user.username }}"></a>
                </div>
                <div class="info float-l">
                    <p class="layui-word-aux font-bold font-14">{{ item.user.username }}</p>
                    <p class="layui-word-aux font-14">{{ item.created_at }}</p>
                </div>
            </div>
            <div class="conmid">
                <p class="font-16 font-bold margin-b-10"><a href="#">{{ item.title }}</a></p>
                <p class="margin-b-10">{{ item.brief }}</p>
            </div>
            <div class="conlow layui-clear">
                <span class="float-l">
                    <span class="layui-badge-rim" style="color: #FF5722;border-color: #FF5722;">{{ item.topic.name }}</span>
                </span>
                <span class="float-r layui-word-aux">
                    <i class="fa fa-comment-o" aria-hidden="true"></i>
                    &nbsp;回复&nbsp;{{ item.comment }}次&nbsp;
                    <i class="fa fa-eye" aria-hidden="true"></i>
                    &nbsp;浏览&nbsp;{{ item.visited }}次&nbsp;
                    <i class="fa fa-heart-o" aria-hidden="true"></i>
                    &nbsp;点赞&nbsp;{{ item.likes }}次&nbsp;
                </span>
            </div>
        </li>
        {{#  }); }}
</script>
