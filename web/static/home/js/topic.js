// 入口文件
layui.define(['layer', 'element', 'crossfade', 'laypage', 'randcolor'], function (exports) {
    var layer = layui.layer
    ,element = layui.element
    ,laypage = layui.laypage
    ,randcolor = layui.randcolor;

  	//layer.msg('Hello World');


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

    /*标签随即色*/
    randcolor.allocateColor("li.tag-item");

    exports('topic', null);
});