// 入口文件
layui.define(['layer', 'element', 'waterfull'], function (exports) {
    var layer = layui.layer
    ,element = layui.element
    ,waterfull = layui.waterfull;

  	//layer.msg('Hello World');

    //模拟数据
    var data = {"data" : [
        {"src" : "static/img/P_01.jpg"},
        {"src" : "static/img/P_02.jpg"},
        {"src" : "static/img/P_03.jpg"},
        {"src" : "static/img/P_04.jpg"},
        {"src" : "static/img/P_05.jpg"},
        {"src" : "static/img/P_06.jpg"},
        {"src" : "static/img/P_07.jpg"},
        {"src" : "static/img/P_08.jpg"},
        {"src" : "static/img/P_09.jpg"},
        {"src" : "static/img/P_010.jpg"},
    ]};
    waterfull.init(data);
    

    exports('album', null);
});
/*
$(window).on('load', function(){
    waterfull();

    //模拟数据
    var data = {"data" : [
        {"src" : "static/img/P_01.jpg"},
        {"src" : "static/img/P_02.jpg"},
        {"src" : "static/img/P_03.jpg"},
        {"src" : "static/img/P_04.jpg"},
        {"src" : "static/img/P_05.jpg"},
        {"src" : "static/img/P_06.jpg"},
        {"src" : "static/img/P_07.jpg"},
        {"src" : "static/img/P_08.jpg"},
        {"src" : "static/img/P_09.jpg"},
        {"src" : "static/img/P_010.jpg"},
    ]};

    $(window).on('scroll', function(){
        if(checkScrollSlide()){
            var oBox, oPic;
            $.each(data.data, function(key, val){

                oBox = $('<div>').addClass('box').appendTo('#pics');
                oPic = $('<div>').addClass('pic').appendTo($(oBox));
                $('<img>').attr('src', $(val).attr('src')).appendTo($(oPic));
            });
            waterfull();
        }
    });
});

function waterfull(){
    var $boxs = $('#pics>div')
        ,w = $boxs.eq(0).outerWidth()
        ,wrap = $('#wrap')
        ,cols = Math.floor(wrap.width()/w);

        $('#pics').width(cols * w).css('margin', '0 auto');

        var hArr = []
        ,h = 0
        ,minH = 0
        ,minIndex = 0;

        $boxs.each(function(index, ele){
            h = $boxs.eq(index).outerHeight();
            if(index < cols){
                hArr[index] = h;
            }else{
                minH = Math.min.apply(null, hArr);
                minIndex = $.inArray(minH, hArr);
                $(ele).css({
                    'position' : 'absolute',
                    'top' : minH + 'px',
                    'left' : (minIndex * w) + 'px',

                });
                hArr[minIndex] += $boxs.eq(index).outerHeight();
                //console.log($boxs.eq(index).outerHeight());
            }

        });
}

function checkScrollSlide(){
    var $lastBox = $('#pics>div').last()
    ,lastBoxDis = $lastBox.offset().top + Math.floor($lastBox.outerHeight()/2)
    ,scrollTop = $(window).scrollTop()
    ,documentH = $(window).height();
    return (lastBoxDis < scrollTop + documentH) ? true : false;

}*/