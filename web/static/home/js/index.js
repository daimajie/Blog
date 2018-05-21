// 入口文件
layui.define(['layer', 'element', 'crossfade'], function (exports) {
    var layer = layui.layer
    ,element = layui.element;


    //jquery扩展插件
    /*图片模糊*/
    $('.crossfade').crossfade({
        start: '/static/home/img/01.jpg',
        end: '/static/home/img/01-blur.jpg',
        threshold: 0.3
    });

    exports('index', null);
});