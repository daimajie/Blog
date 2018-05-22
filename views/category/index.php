<?php
use app\assets\home\AppAsset;

AppAsset::addCss($this,'static/home/css/topics.css');
?>

<section class="breadcrumb">
    <div class="layui-container margin-t-10 margin-b-10">
		<span class="layui-breadcrumb">
		  <a href="/">首页</a>
		  <a><cite>分类列表</cite></a>
		</span>
    </div>
    <hr class="layui-bg-gray">
</section>

<section class="content">
    <div class="layui-container articles">
        <div class="layui-row">
            <div class="layui-col-md9">
                <div class="topics" id="category-wrap"></div>
                <div id="pager"></div>
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

<?php
$token = Yii::$app->request->getCsrfToken();
$strJs = <<<JS
    layui.use(['laypage','laytpl'],function(){
    var laypage = layui.laypage
    ,laytpl = layui.laytpl;
    
    /*分页*/
    laypage.render({
        elem: 'pager'
        ,count: "{$count}"
        ,limit: "{$limit}"
        ,theme: '#1E9FFF'
        ,jump: function(obj, first){
            //请求日记列表
            $.ajax({
                url : UrlManager.createUrl('/category/get-category'),
                type : 'post',
                data : {'_csrf' : "$token", 'curr' : obj.curr, 'limit' : obj.limit},
                success : function(d){
                    var getTpl = $('#category-item').html()
                    ,view = document.getElementById('category-wrap');
                    laytpl(getTpl).render(d, function(html){
                      view.innerHTML = html;
                    });
                    
                }
            });
            
        }
      });
      
          
          
          
    });
JS;
$this->registerJs($strJs);

?>
<script id="category-item" type="text/html">
    {{#  layui.each(d.data, function(index, item){ }}
    <ul>
        <li class="topic-item">
            <strong>
                <i class="fa fa-fire" aria-hidden="true"></i> {{ item.name }}(分类)
            </strong>
        </li>
        {{#  layui.each(item.topics, function(kay, val){ }}
        <li class="topic-item">
            <a href="{{ val.url }}" >{{ val.name }}</a>
        </li>
        {{#  }); }}
        <li class="topic-item">
            <a href="{{ item.url }}" >更多话题...</a>
        </li>
    </ul>
    <hr>
    {{#  }); }}
    {{#  if(d.data.length === 0){ }}
    无数据
    {{#  } }}
</script>
