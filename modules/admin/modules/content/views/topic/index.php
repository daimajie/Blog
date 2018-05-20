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
                        <a href="javascript:;" class="layui-btn topic_add">添加话题</a>
                        <a href="javascript:history.go(-1);" class="layui-btn">返回</a>
                        <a href="javascript:;" class="layui-btn batch-del">批量删除</a>
                    </div>
                    <div class="float-r">
                        <div class="layui-input-inline">
                            <input type="text" name="name"  placeholder="请输入分类名称" class="layui-input">
                        </div>
                        <button class="layui-btn layui-btn-primary">搜索</button>
                    </div>
                </div>

                <table class="layui-table nosnap" lay-data="{url:'<?= Url::to(['get-table-data']);?>', page:true, id:'idTest'}" lay-filter="table-data">
                    <thead>
                    <tr>
                        <th lay-data="{type:'checkbox', fixed: 'left'}"></th>
                        <th lay-data="{field:'id', width:120, sort: true, fixed: true}">ID</th>
                        <th lay-data="{field:'name', width:150}">专题名称</th>
                        <th lay-data="{field:'category', width:150}">所属分类</th>
                        <th lay-data="{field:'tags', templet: '#tagsTpl'}">标签(点击标签可对其进行编辑)</th>
                        <th lay-data="{field:'created_at', width:150}">创建时间</th>
                        <th lay-data="{fixed: 'right', width:150, align:'center', toolbar: '#barDemo'}"></th>
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
    <script type="text/html" id="tagsTpl">
        <div data-topicid="{{d.id}}">
        {{#  layui.each(d.tags, function(index, item){ }}
        <span data-id="{{ item.id }}">
            <a class="layui_link tag_name">{{ item.name }}</a>
        </span>
        {{#  }); }}
        <a class="layui-btn layui-btn-xs layui-btn-primary tag_add_btn">+</a>
        </div>
    </script>
<?php
$token =  \Yii::$app->request->getCsrfToken();
/*表格操作*/
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
                        url : UrlManager.createUrl('/admin/content/topic/delete',{id:data.id}),
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
                  title: '编辑分类',
                  shadeClose: true,
                  shade: 0.8,
                  area: ['80%', '450px'],
                  content: UrlManager.createUrl('/admin/content/topic/update', {id:data.id}),
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
                    url : UrlManager.createUrl('/admin/content/topic/batch-del'),
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
        
        /*创建话题*/
        $('.topic_add').on('click', function(){
            layer.open({
              type: 2,
              title: '创建分类',
              shadeClose: true,
              shade: 0.8,
              area: ['80%', '450px'],
              content: UrlManager.createUrl('/admin/content/topic/create'),
              end: function(){ //此处用于演示
                window.location.reload();
              }
            }); 
        });
        
        /*创建标签*/
        var oTable = $('.layui-table');
        oTable.on('click', '.tag_name', function(){
            var tagId = $(this).closest('span').data('id');
            
            //询问框
            layer.confirm('选择对标签的操作', {
              btn: ['编辑','删除'] //按钮
            }, function(){
                  layer.open({
                      type: 2,
                      title: '编辑标签',
                      shadeClose: true,
                      shade: 0.8,
                      area: ['80%', '450px'],
                      content: UrlManager.createUrl('/admin/content/tag/update',{id : tagId}),
                      end: function(){ //此处用于演示
                        window.location.reload();
                      }
                  }); 
            }, function(){
              layer.confirm('您确定要删除该标签吗?',{
                  btn: ['确定','取消']
              }, function(){
                  $.ajax({
                    url : UrlManager.createUrl('/admin/content/tag/delete',{id : tagId}),
                    type : 'GET',
                    success : function(d){
                        
                        if(d.errno === 0){
                            window.location.reload();
                        }else
                            layer.msg(d.message);
                    }
                  });
              });
            });
        });
        oTable.on('click', '.tag_add_btn', function(){
            var topic_id = $(this).closest('div').data('topicid');  
            layer.open({
                  type: 2,
                  title: '创建标签',
                  shadeClose: true,
                  shade: 0.8,
                  area: ['80%', '450px'],
                  content: UrlManager.createUrl('/admin/content/tag/create',{topic_id : topic_id}),
                  end: function(){ //此处用于演示
                    window.location.reload();
                  }
            }); 
        });
        
        
        
        
    });

JS;

$this->registerJs($jsStr);

?>