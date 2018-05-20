<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

?>

    <div class="layui-fliud">
        <div class="layui-row">

            <!--content-->
            <div class="content">
                <div class="layui-row">
                    <div class="layui-btn-group float-l">
                        <a href="javascript:;" class="layui-btn cate_add">添加友链</a>
                        <a href="javascript:history.go(-1);" class="layui-btn">返回</a>
                        <a href="javascript:;" class="layui-btn batch-del">批量删除</a>
                    </div>
                    <!--<div class="float-r">
                        <div class="layui-input-inline">
                            <input type="text" name="name"  placeholder="请输入友链名称" class="layui-input">
                        </div>
                        <button class="layui-btn layui-btn-primary">搜索</button>
                    </div>-->

                </div>


                <table class="layui-table" lay-data="{url:'<?= Url::to(['get-table-data']);?>', page:true, id:'idTest'}" lay-filter="table-data">
                    <thead>
                    <tr>
                        <th lay-data="{type:'checkbox', fixed: 'left'}"></th>
                        <th lay-data="{field:'id', width:120, sort: true, fixed: true}">ID</th>
                        <th lay-data="{field:'name', width:180}">友链名称</th>
                        <th lay-data="{field:'url'}">站点地址</th>
                        <th lay-data="{field:'sort'}">排序</th>
                        <th lay-data="{fixed: 'right', width:178, align:'center', toolbar: '#barDemo'}"></th>
                    </tr>
                    </thead>
                </table>

            </div>
            <!--content-->


        </div>
    </div>
    <script type="text/html" id="barDemo">
        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
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
                        url : UrlManager.createUrl('/admin/friend/index/delete',{id:data.id}),
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
                layer.open({
                  type: 2,
                  title: '编辑友链',
                  shadeClose: true,
                  shade: 0.8,
                  area: ['80%', '380px'],
                  content: UrlManager.createUrl('/admin/friend/index/update', {id:data.id}),
                  end: function(){ //此处用于演示
                    window.location.reload();
                  }
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
                    url : UrlManager.createUrl('/admin/friend/index/batch-del'),
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
        
        $('.cate_add').on('click', function(){
            layer.open({
              type: 2,
              title: '创建友链',
              shadeClose: true,
              shade: 0.8,
              area: ['80%', '380px'],
              content: UrlManager.createUrl('/admin/friend/index/create'),
              end: function(){ //此处用于演示
                window.location.reload();
              }
            }); 
        });
        
        
    });

JS;

$this->registerJs($jsStr);
?>