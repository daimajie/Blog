// 入口文件
layui.define(['layer', 'element', 'crossfade', 'layedit', 'laypage'], function (exports) {
    var layer = layui.layer
    ,element = layui.element
    ,layedit = layui.layedit
    ,laypage = layui.laypage;

  	//layer.msg('Hello World');

    /*构建一个编辑器*/
    var index = layedit.build('editor', {
        height: 180
    });

    /*分页*/
    laypage.render({
        elem: 'pager'
        ,count: 100
        ,theme: '#1E9FFF'
      });

    //jquery扩展插件
    /*图片模糊*/
    $('.crossfade').crossfade({
        start: 'static/img/01.jpg',
        end: 'static/img/01-blur.jpg',
        threshold: 0.3
    });

    exports('note', null);
});