//layui.use(['jquery','element','layer'], function(exports){
layui.define(['jquery','nav','element','layer'],function(exports){
  var $ 		= layui.jquery
  	  ,element  = layui.element
  	  ,layer 	= layui.layer;

  var selected = null
  	  ,index = 0
      ,oLiArr //列表集合
      ,wrapW;

  var fun = {
  		/*计算应该移动的距离*/
		leftMigration : function(oArr, start, wrap){
			var count = 0;
			var index = start;
			var flag = false;
			
			$.each(oArr, function(key, val){
			    if(index > key){
			      return;
			    }

			    count += $(val).outerWidth();

			    if(Math.floor(count) > wrap){
			      flag = true;
			      return false;
			    }

			    index++;
			});
			return flag ? index : start;
		},
		/*右移动*/
		rightMigration : function(oArr, start, wrap){
			var count = 0;
			var index = start;

			for(var i = oArr.length - 1; i >= 0; i--){
			    if(i >= start){
			      continue;
			    }
			    
			    count += $(oArr[i]).outerWidth();

			    
			    if(Math.floor(count) >= wrap){
			      break;
			    }
			    index = i; //因为是倒序所以要写在前边
			}
			return index;
		}
  };

  var app = {
		//判断是否是小屏幕
		_isSmall : function(){
			return $(window).width() <= 970;
		},

		//变窄
		_narrow : function(){
		    $('#body').animate({
		        left: '55'
		    });
		    $('#footer').animate({
		        left: '55'
		    });
		    $('#left').animate({
		        width: '55'
		    });

		    $('.layui-nav-item cite').fadeOut();//文字隐藏

		    selected = $('.layui-nav-itemed');
		    selected.removeClass('layui-nav-itemed');

		    //如果不是小屏幕 绑定tips弹窗
		    if(!app._isSmall()){
		          $('#left')
		          .on('mouseenter', 'a[lay-tips]', function(){
		            	layer.tips($(this).attr('lay-tips'), $(this).find('i'));
		          })
		          .on('mouseleave', 'a[lay-tips]', function(){
		                layer.tips(false);
		          });
		    }
		},

		//变宽
		_wide : function(){

		    //清空tips
		    layer.tips(false);

		    $('#body').animate({
		        left: '200px'
		    });
		    $('#footer').animate({
		        left: '200px'
		    });
		    $('#left').animate({
		        width: '200px'
		    });

		    $('.layui-nav-item cite').fadeIn(function(){
                selected.addClass('layui-nav-itemed');//保持打开状态
			});//显示文字



		    //解绑事件
		    $('#left').off();
		},

		//隐藏
		_hidden : function(){
		    $('#body').animate({
		        left: '1'
		    });
		    $('#footer').animate({
		        left: '1'
		    });
		    $('#left').animate({
		        width: '1'
		    });

		    $('.layui-nav-item cite').fadeOut();//文字隐藏

		    selected = $('.layui-nav-itemed');
		    selected.removeClass('layui-nav-itemed');
		},

		//添加标签
		_addTab : function(title, url, name){
			element.tabAdd('main-tab', {
			  title: title,
			  content: '<iframe tab-id="'+name+'" frameborder="0" src="'+url+'" scrolling="yes" class="x-iframe" ></iframe>',
			  id: name
			});
		},

		//切换到指定Tab项
		_chaTab : function(id){
			element.tabChange('main-tab', id); //切换到：用户管理
		},

		_refLis : function(){
			oLiArr = $('#app_tabsheader').find('li');
		}

  };
  var event = {
  		// 全屏
	    fullScreen : function(selector){
	        $(selector).on('click', function () {
	            var docElm = document.documentElement;
	            //W3C  
	            if (docElm.requestFullscreen) {
	                docElm.requestFullscreen();
	            }
	            //FireFox  
	            else if (docElm.mozRequestFullScreen) {
	                docElm.mozRequestFullScreen();
	            }
	            //Chrome等  
	            else if (docElm.webkitRequestFullScreen) {
	                docElm.webkitRequestFullScreen();
	            }
	            //IE11
	            else if (elem.msRequestFullscreen) {
	                elem.msRequestFullscreen();
	            }
	            layer.msg('按Esc即可退出全屏');
	        });
	    },
	    //刷新
	    refresh : function(selector){
	        $(selector).on('click', function(){
	          window.location.reload();
	        });
	    },
	    //侧边栏
	    sidebar : function(selector){
	        //var selNav;  //记录展开的选项
	        $(selector).on('click', function () {
	            var sideWidth = $('#left').width();
	            
	            if (sideWidth === 200) {
	                if(app._isSmall()){
	                    app._hidden(); //如果是小屏幕就隐藏侧边栏
	                }else{
	                    app._narrow(); //否侧仅显示图标
	                }
	            } else {
	                app._wide();
	            }
	        });
	    },
	    //点击展开
	    stretch : function(){
	        $('#left li').on('click',function(){
	            var sideWidth = $('#left').width();
	            if (sideWidth !== 200) {
	                app._wide();
	            }
	        });
	    },
	    //左滑动 右滑动
	    leftBtn : function(){
	          //左移动
	          $('.layui-icon-prev').on('click', function(){
	          	//刷新li列表
	          	app._refLis();

	            wrapW = $('.layui-tab').width();
	            //获取偏移的索引
	            index = fun.leftMigration(oLiArr, index, wrapW);
	            
	            //如果索引有效
	            if(oLiArr.eq(index).length === 1){

	                $('.layui-tab-title').animate({
	                  left : -(oLiArr.eq(index).position().left) + 41 + 'px'
	                });
	            }  
	          });
	    },
	    rightBtn : function(){
	          //右移
	          $('.layui-icon-next').on('click', function(){
	          	//刷新li列表
	          	app._refLis();

	            wrapW = $('.layui-tab').width();
	            //移除最后一个无用索引
	            index = fun.rightMigration(oLiArr, index, wrapW);
	            
	            //如果索引有效
	            if(oLiArr.eq(index).length === 1){

	                //计算当前索引距ul距离
	                var pos = oLiArr.eq(index).position().left;

	                $('.layui-tab-title').animate({
	                  left : -pos + 41 + 'px'
	                });
	            }  
	          });
    	},

    	//下拉菜单
    	drop : function(){
    		$('.layui-icon-down').hover(function(){
    			$('.down-nav').stop()
	            .css({left : -100 + 'px'})
	            .animate({
	              top : 40 + 'px',
	              opacity : 1,
	            },'fast');
    		},function(){
    			$('.down-nav').stop().css({
	              top : 66  + 'px',
	              opacity : 0,
	              left : 40 + 'px'
	            });
    		});
    	},
    	dropEvent : function(){
    		//关闭当前页
			$('#delTab').on('click', function(){
				  var oLi = $('#app_tabsheader .layui-this')
					  ,val = oLi.attr('lay-id');
				  if(val !== 'console'){
				    element.tabDelete('main-tab', val);
				  }
			});
    		//刷新当前页
    		$('#refTab').on('click', function(){
    				var oLi = $('#app_tabsheader .layui-this')
						,val = oLi.attr('lay-id')
						,iframe = $('iframe[tab-id='+ val +']');

				      	if(iframe[0]){
						    iframe[0].src = iframe[0].src;
				      	}
    		});
    		//关闭所有页
    		$('#delAll').on('click', function(){
				var oLi = $('#app_tabsheader li:first').siblings()
				    ,val = '';
				$.each(oLi, function(key, ele){
					  val = $(ele).attr('lay-id');
					  element.tabDelete('main-tab', val);
				});
				  
    		});
    	},

    	/*选项卡添加*/
		tabAdd: function(){
			$('#nav-container .layui-nav-child > dd').on('click', function(){
			    var that = $(this);

			    var url = that.children('a').attr('lay-href');
			    var title = that.children('a').html();
			    var name = that.data('name');

			    //判断是否存在
			    var data,flag=true;
			    var oLis = $('#app_tabsheader > li');
			    
			    //判断是否已达上限
			    if(oLis.length >= 35){
			    	layer.msg('选项卡已达上限,请删除几个吧，亲！');
			    	return;
			    };

			    $.each(oLis, function(key, ele){
			    	data = $(ele).attr('lay-id');
			    	if(name === data){
			    		app._chaTab(name);
			    		flag = false;
			    		return false;
			    	}
			    });
			    if(flag){
				    app._addTab(title, url, name);
				    app._chaTab(name);
			    	
			    }
			});
		},

  }

  //iframe 高度自适应
  // event._iframeH();

  event.fullScreen('.admin-side-full');
  event.refresh('.admin-side-refresh');
  event.sidebar('.admin-side-toggle');
  event.stretch(); //点击展开侧边栏

  /*左右滑动*/
  event.leftBtn();
  event.rightBtn();

  /*下拉框*/
  event.drop();
  event.dropEvent();

  /*添加选项卡*/
  event.tabAdd();



  var obj = {

  };


  exports('app', null);
});
