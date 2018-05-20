layui.define(['jquery'], function(exports){ //提示：模块也可以依赖其它模块，如：layui.define('layer', callback);
  var $ = layui.jquery;
  var obj = {
        /*随机分配颜色*/
        allocateColor: function(oList){
            $(oList).each(function(index, el){
                var randColor = obj.changeColor();
                $(this).css({
                    'border-color': '#'+randColor,
                    'color': '#'+randColor
                });
            });
        },
        /*生成颜色*/
        changeColor: function(){
            var r = parseInt(Math.random() * 225);
            var g = parseInt(Math.random() * 225);
            var b = parseInt(Math.random() * 225);
            var colorHex = r.toString(16) + g.toString(16) + b.toString(16);
            return colorHex;
        }
  };
  
  //输出test接口
  exports('randcolor', obj);
});   
