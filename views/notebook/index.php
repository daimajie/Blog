<?php

?>
<div class="layui-clear"></div>
<section class="content">
    <div class="layui-container articles">
        <div class="layui-row">
            <div class="layui-col-md9">
                <ul class="artlist" id="notes-wrap"></ul>
                <div id="pager"></div>
            </div>
            <div class="layui-col-md3">
                <div class="sidebar">


                </div>
            </div>
        </div>
    </div>
</section>

<?php
$token =  \Yii::$app->request->getCsrfToken();
$strJs = <<<STR
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
                url : UrlManager.createUrl('/notebook/notes'),
                type : 'post',
                data : {'_csrf' : "$token", 'curr' : obj.curr, 'limit' : obj.limit},
                success : function(d){
                    var getTpl = $('#note-item').html()
                    ,view = document.getElementById('notes-wrap');
                    laytpl(getTpl).render(d, function(html){
                      view.innerHTML = html;
                    });
                    
                }
            });
            
        }
      });
      
      
      
});
STR;
$this->registerJs($strJs);
?>
<script id="note-item" type="text/html">
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
            <p class="margin-b-10">{{ item.content }}</p>
        </div>
    </li>
    {{#  }); }}
    {{#  if(d.data.length === 0){ }}
    无数据
    {{#  } }}
</script>
