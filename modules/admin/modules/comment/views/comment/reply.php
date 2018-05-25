<?php
use yii\helpers\Url;


?>
    <div class="layui-fliud">
        <div class="layui-row">

            <!--content-->
            <div class="content">
                <div class="layui-row">
                    <div class="layui-btn-group float-l">
                        <a href="javascript:history.go(-1);" class="layui-btn">返回</a>
                    </div>
                    <div class="float-r">
                        <div class="layui-input-inline">
                            <input type="text" name="name"  placeholder="请输入友链名称" class="layui-input">
                        </div>
                        <button class="layui-btn layui-btn-primary">搜索</button>
                    </div>

                </div>


                <table class="layui-table" lay-data="{url:'<?= Url::to(['get-replys']);?>', page:true, id:'idTest'}" lay-filter="table-data">
                    <thead>
                    <tr>
                        <th lay-data="{type:'checkbox', fixed: 'left'}"></th>
                        <th lay-data="{field:'id', width:120, sort: true, fixed: true}">ID</th>
                        <th lay-data="{field:'user_id', width:120}">用户</th>
                        <th lay-data="{field:'article_id', width:120}">文章</th>
                        <th lay-data="{field:'comment_id', width:120}">评论</th>
                        <th lay-data="{field:'content'}">回复内容</th>
                        <th lay-data="{fixed: 'right', width:178, align:'center', toolbar: '#barDemo'}"></th>
                    </tr>
                    </thead>
                </table>

            </div>
            <!--content-->


        </div>
    </div>
    <script type="text/html" id="barDemo">
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    </script>
<?php
$token =  \Yii::$app->request->getCsrfToken();
$jsStr = <<<JS
    layui.use(['table','layer', 'jquery'], function(){
        var table = layui.table
            ,$ = layui.jquery;
        
        //监听工具条
        table.on('tool(table-data)', function(obj){
            var data = obj.data;
            if(obj.event === 'del'){
                layer.confirm('真的删除行么', function(index){
                    layer.close(index);
                    $.ajax({
                        url : UrlManager.createUrl('/admin/comment/comment/del-reply',{id:data.id}),
                        type : 'post',
                        data : {'id' : data.id, '_csrf' : "$token"},
                        success : function(d){
                           if(d.errno === 0){
                                obj.del();
                           }
                           layer.msg(d.message);
                        }
                    });
                });
            }
        });
        
        
        
    });

JS;

$this->registerJs($jsStr);
?>