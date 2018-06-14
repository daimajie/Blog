<?php
use yii\helpers\Url;
?>

<!-- content -->
<div class="layui-clear"></div>
<section class="content">
    <div class="layui-container articles">
        <div class="layui-row">
            <div class="layui-col-md9">
                <ul class="artlist" id="article-wrap"></ul>
                <div class="pager" id="laypage"></div>
            </div>
            <div class="layui-col-md3">
                <div class="sidebar">
                    <!--云标签-->
                    <div class="cloud-tags">
                        <fieldset class="layui-elem-field">
                            <legend>云标签</legend>
                            <div class="layui-field-box">
                                <ul class="tags">
                                    <?php
                                    if(!empty($tags)):
                                        foreach($tags as $k => $v):
                                    ?>
                                    <li class="tag-item"><a href="<?= Url::current(['tag_id'=>$v['id']])?>"><?= $v['name']?></a></li>
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
$topic_id = !empty($_GET['topic_id']) ? $_GET['topic_id'] : '';
$tag_id = !empty($_GET['tag_id']) ? $_GET['tag_id'] : '';

$token =  \Yii::$app->request->getCsrfToken();
$strJs = <<<JS
    layui.use(['laytpl','laypage','randcolor'],function(){
        var laytpl = layui.laytpl
        ,laypage = layui.laypage
        ,randcolor = layui.randcolor;
        
        laypage.render({
            elem: 'laypage'
            ,count: "{$count}"
            ,limit: "{$limit}"
            ,theme: '#1E9FFF'
            ,jump: function(obj, first){
                //请求日记列表
                $.ajax({
                    url : UrlManager.createUrl('/topic/get-articles'),
                    type : 'post',
                    data : {
                        '_csrf' : "$token", 
                        'curr' : obj.curr, 
                        'limit' : obj.limit,
                        'topic_id' : "$topic_id",
                        'tag_id' : "$tag_id"
                        },
                    success : function(d){
                        var getTpl = $('#article-item').html()
                        ,view = document.getElementById('article-wrap');
                        laytpl(getTpl).render(d, function(html){
                          view.innerHTML = html;
                        });
                        
                    }
                });
                
            }
        });
    
        /*标签随即色*/
        randcolor.allocateColor("li.tag-item");
      
      
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
                    <a href="javascript:;">
                        <span class="layui-badge-rim" style="color: #FF5722;border-color: #FF5722;">{{ item.topic.name }}</span>
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
