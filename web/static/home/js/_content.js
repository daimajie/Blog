// 入口文件
layui.define(['layer', 'element', 'laypage'], function (exports) {
    var layer = layui.layer
    ,element = layui.element
    ,laypage = layui.laypage;

  	//layer.msg('Hello World');


    /*分页*/
    laypage.render({
        elem: 'pager'
        ,count: 100
        ,theme: '#1E9FFF'
      });



    exports('content', null);
});