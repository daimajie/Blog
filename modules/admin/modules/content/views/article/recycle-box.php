<?php

use yii\helpers\Url;

?>

    <div class="layui-fliud">
        <div class="layui-row">

            <!--content-->
            <div class="content">
                <div class="layui-row">
                    <div class="layui-btn-group float-l">
                        <a href="javascript:;" class="layui-btn cate_add">添加文章</a>
                        <a href="javascript:history.go(-1);" class="layui-btn">返回</a>
                        <a href="javascript:;" class="layui-btn batch-del">批量删除</a>
                    </div>
                    <div class="layui-btn-group float-l">
                        <a href="#" class="layui-btn layui-btn-normal">草稿箱</a>
                        <a href="#" class="layui-btn layui-btn-danger">回收站</a>
                    </div>
                    <div class="float-r">
                        <div class="layui-input-inline">
                            <input type="text" name="name"  placeholder="按话题搜索" class="layui-input">
                        </div>
                        <div class="layui-input-inline">
                            <input type="text" name="name"  placeholder="按作者搜索" class="layui-input">
                        </div>
                        <button class="layui-btn layui-btn-primary">搜索</button>
                    </div>

                </div>
                <table class="layui-table" lay-data="{url:'<?= Url::to(['get-table-data']);?>', page:true, id:'idTest'}" lay-filter="table-data">
                    <thead>
                    <tr>
                        <th lay-data="{type:'checkbox', fixed: 'left'}"></th>
                        <th lay-data="{field:'id', width:80, sort: true, fixed: true}">ID</th>
                        <th lay-data="{field:'title', width:350}">分类名称</th>
                        <th lay-data="{field:'topicName'}">话题</th>
                        <th lay-data="{field:'type'}">类型</th>
                        <th lay-data="{field:'visited'}">阅读</th>
                        <th lay-data="{field:'comment'}">评论</th>
                        <th lay-data="{field:'likes'}">喜欢</th>
                        <th lay-data="{field:'author'}">作者</th>
                        <th lay-data="{field:'created_at'}">创建时间</th>
                        <th lay-data="{fixed: 'right', width:178, align:'center', toolbar: '#barDemo'}"></th>
                    </tr>
                    </thead>
                </table>



            </div>
            <!--content-->


        </div>
    </div>
    <script type="text/html" id="barDemo">
        <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="view">查看</a>
        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
        <a class="layui-btn layui-btn-warm layui-btn-xs" lay-event="recycle">删除</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">彻底删除</a>
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
                //彻底删除
                layer.confirm('真的要彻底删除行么', function(index){
                    layer.close(index);
                    $.ajax({
                        url : UrlManager.createUrl('/admin/content/article/delete',{id:data.id}),
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
            } else if(obj.event === 'edit'){
                //编辑文章
                window.location.href = UrlManager.createUrl('/admin/content/article/update',{id:data.id});
            } else if(obj.event === 'view'){
                //文章查看
                window.location.href = UrlManager.createUrl('/admin/content/article/view',{id:data.id});
            } else if(obj.event === 'recycle'){
                //放入回收站
                layer.confirm('确定将文章放入回收站？', function(index){
                    layer.close(index);
                    $.ajax({
                        url : UrlManager.createUrl('/admin/content/article/recycle',{id:data.id}),
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
        
        var active = {
            getCheckData: function(){ //获取选中数据
                var checkStatus = table.checkStatus('idTest')
                    ,data = checkStatus.data;
                
                var ids = new Array();
                $.each(data, function(k, v){
                    ids.push(v.id);
                });
                
                $.ajax({
                    url : UrlManager.createUrl('/admin/content/category/batch-del'),
                    data : {'data' : ids, '_csrf': "$token"},
                    type : 'POST',
                    success : function(d){
                        if(d.errno === 0){
                            window.location.reload();
                        }else 
                            layer.msg(d.message);
                        
                    }
                });
            }
        };
        
        $('.batch-del').on('click', function(){
            layer.confirm('真的删除行么', function(index){
                active.getCheckData();
            });
            
        });
        
        /*创建文章*/
        $('.cate_add').on('click', function(){
            window.location.href = UrlManager.createUrl('/admin/content/article/create');
        });
        
        
    });

JS;

$this->registerJs($jsStr);
?>