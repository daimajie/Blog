// 入口文件
layui.define(['index', 'layedit', 'laypage'], function (exports) {
    var layer = layui.layer
    ,layedit = layui.layedit
    ,laypage = layui.laypage;


    /*构建一个编辑器*/
    var index = layedit.build('editor', {
        height: 180,
        uploadImage: { url: '/upload/', type: 'post' }
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