layui.define(['jquery'], function(exports){
  
  var $ = layui.jquery;

  var nav = {
			init : function(data, container){
			  var container = $(container); //容器
			  $.each(data, function(key, val){
			    if(val.childs){
			      //有子菜单
		          var str  = '<li data-name="'+ val.name +'" class="layui-nav-item">';
		      		 str += '<a href="javascript:;" lay-tips="'+ val.cite +'">';
		      		 str += '<i class="'+ val.icon +'"></i>';
		      		 str += '<cite>'+ val.cite +'</cite>';
		      		 str += '<span class="layui-nav-more"></span></a>';
		      		 str += '<dl class="layui-nav-child">';
		      		 
		      		 $.each(val.childs, function(k, v){
		      		 	 str += '<dd data-name="'+ v.name +'">';
			      		 str += '<a lay-href="'+ v.href +'">'+ v.cite +'</a>';
			      		 str += '</dd>';
		      		 })
			      		 
		      		 str += '</dl></li>';
		      	  container.append($(str));

			    }else{
			      //没有子菜单
			      var str  = '<li data-name="'+val.name+'" class="layui-nav-item">';
			      	  str += '<a href="javascript:;" lay-href="'+ val.url +'" lay-tips="'+ val.cite +'">';
			      	  str += '<i class="'+ val.icon +'"></i>';
			      	  str += '<cite>'+ val.cite +'</cite>';
			      	  str += '</a></li>';
			      container.append($(str));
			    }
			  });
			}
  };

  nav.init(data, '#nav-container');


  
  //输出test接口
  exports('nav', null);
});  
    
