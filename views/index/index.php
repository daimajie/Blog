

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




                    <div class="firend-links">
                        <fieldset class="layui-elem-field">
                            <legend>友情连接</legend>
                            <div class="layui-field-box">
                                <ul class="firend">
                                    <?php
                                        if(!empty($friend)):
                                            foreach ($friend as $item):
                                    ?>
                                    <li class="firend-item"><a target="_blank" href="<?= $item['url']?>"><?= $item['name']?></a></li>

                                    <?php
                                            endforeach;
                                        endif;
                                    ?>
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
                <p class="font-16 font-bold margin-b-10"><a href="{{ item.article_url }}">{{ item.title }}</a></p>
                <p class="margin-b-10">{{ item.brief }}</p>
            </div>
            <div class="conlow layui-clear">
                <span class="float-l">
                    <a href="{{ item.topic.topic_url }}">
                        <span class="layui-badge-rim" style="color: #FF5722;border-color: #FF5722;">
                            {{ item.topic.name }}
                        </span>
                    </a>
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
