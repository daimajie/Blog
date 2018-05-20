layui.define(['jquery'], function(exports){
	  var $ = layui.jquery;

	  var obj = {
        init : function(data){
            obj.waterfull();
            
            $(window).on('scroll', function(){
                if(obj.checkScrollSlide()){
                    var oBox, oPic;
                    $.each(data.data, function(key, val){

                        oBox = $('<div>').addClass('box').appendTo('#pics');
                        oPic = $('<div>').addClass('pic').appendTo($(oBox));
                        $('<img>').attr('src', $(val).attr('src')).appendTo($(oPic));
                    });
                    obj.waterfull();
                }
            });
        },
        waterfull : function(){
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
        },
        /*检测是否要加载图片*/
        checkScrollSlide : function(){
            var $lastBox = $('#pics>div').last()
            ,lastBoxDis = $lastBox.offset().top + Math.floor($lastBox.outerHeight()/2)
            ,scrollTop = $(window).scrollTop()
            ,documentH = $(window).height();
            return (lastBoxDis < scrollTop + documentH) ? true : false;
        }
      };
	  
	  //输出test接口
	  exports('waterfull', obj);
})