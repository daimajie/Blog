<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\components\View;
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
                    <!-- 信息 -->
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
                    <!-- 相册 -->
                    <div class="user-pics layui-hide-xs layui-hide-sm layui-show-md-block">
                        <fieldset class="layui-elem-field">
                            <legend>我的相册</legend>
                            <div style="padding: 5px;">
                                <img width="100%" src="/static/home/img/pics.png" alt="">
                            </div>
                        </fieldset>
                    </div>
                    <!-- 友链 -->
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

<?php
$strJs = <<<STR
layui.use(['index', 'laypage'],function(){
    var laypage = layui.laypage;



    
    
    /*分页*/
    laypage.render({
        elem: 'pager'
        ,count: "$pagination->totalCount"
        ,limit: "$pagination->pageSize"
        ,theme: '#1E9FFF'
        ,jump: function(obj, first){
            //obj包含了当前分页的所有参数，比如：
            console.log(obj.curr); //得到当前页，以便向服务端请求对应页的数据。
            console.log(obj.limit); //得到每页显示的条数
            
            //首次不执行
            if(!first){
              //do something
            }
        }
      });
      
      
      
});
STR;
$this->registerJs($strJs);
?>
<script id="note-item" type="text/html">
    {{#  layui.each(d.list, function(index, item){ }}
    <li class="artitem">
        <div class="contop">
            <div class="photo float-l">
                <a href="#"><img src="{{ item.user.photo }}" alt="{{ item.user.username }}"></a>
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
    {{#  if(d.list.length === 0){ }}
    无数据
    {{#  } }}
</script>
