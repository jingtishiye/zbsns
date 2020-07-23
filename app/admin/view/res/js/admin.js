
var num=0,oUl=$("#min_title_list"),hide_nav=$("#Hui-tabNav");

/*获取顶部选项卡总长度*/
function tabNavallwidth(){
	var taballwidth=0,
		$tabNav = hide_nav.find(".acrossTab"),
		$tabNavWp = hide_nav.find(".Hui-tabNav-wp"),
		$tabNavitem = hide_nav.find(".acrossTab li"),
		$tabNavmore =hide_nav.find(".task-changebt");

	if (!$tabNav[0]){return}
	$tabNavitem.each(function(index, element) {
        taballwidth += Number(parseFloat($(this).width()));

    });

	$tabNav.width(taballwidth);
	var w = $tabNavWp.width();
	if(taballwidth>w){
		$tabNavmore.show();
		$tabNav.css('marginLeft',w-taballwidth);
	}
	else{
		$tabNavmore.hide();
		$tabNav.css('marginLeft',0);
	}
}
function getmenuheight(){
	var height;
	height = $(window).height()-100;
	$('#iframe_box').height(height+50);
	
	var showBox=$('#iframe_box').find('.show_iframe:visible');
	$('#iframe_box').find('.show_iframe').height(height+50);
	//$(showBox).find("iframe").contents().find(".layui-layer-iframe").height(height);
	
	$('#nav-wrapper').height(height).css('overflow','auto');

}

/*菜单导航*/
function Hui_admin_tab(obj){
	
	var bStop = false,
		bStopIndex = 0,
		href = $(obj).attr('data-href'),
		title = $(obj).attr("data-title"),
		topWindow = $(window.parent.document),
		show_navLi = topWindow.find("#min_title_list li"),
		iframe_box = topWindow.find("#iframe_box");
	
	if(!href||href==""){
		alert("data-href不存在，v2.5版本之前用_href属性，升级后请改为data-href属性");
		return false;
	}if(!title){
		alert("v2.5版本之后使用data-title属性");
		return false;
	}
	if(title==""){
		alert("data-title属性不能为空");
		return false;
	}
	show_navLi.each(function() {
		
		if($(this).find('a').data("href")==href){
			bStop=true;
			bStopIndex=show_navLi.index($(this));

			return false;
		}
	});
	
	if(!bStop){
		creatIframe(href,title);
		min_titleList();
	}
	else{
		show_navLi.removeClass("active").eq(bStopIndex).addClass("active");			
		//iframe_box.find(".show_iframe").hide().eq(bStopIndex).show().find("iframe").attr("src",href);
		iframe_box.find(".show_iframe").hide().eq(bStopIndex).show();
	}	
}

/*最新tab标题栏列表*/
function min_titleList(){
	var topWindow = $(window.parent.document),
		show_nav = topWindow.find("#min_title_list"),
		aLi = show_nav.find("li");
}

/*创建iframe*/
function creatIframe(href,titleName){
	var topWindow=$(window.parent.document),
		show_nav=topWindow.find('#min_title_list'),
		iframe_box=topWindow.find('#iframe_box'),
		iframeBox=iframe_box.find('.show_iframe'),
		$tabNav = topWindow.find(".acrossTab"),
		$tabNavWp = topWindow.find(".Hui-tabNav-wp"),
		$tabNavmore =topWindow.find(".task-changebt");
	var taballwidth=0;
		
		
		
	show_nav.find('li').removeClass("active");
		
	show_nav.append('<li class="cmf-component-tabitem active" ><a data-href="'+href+'" class="cmf-tabs-item-text" title="'+titleName+'">'+titleName+'</a><span class="cmf-component-tabclose" href="javascript:void(0)" title="点击关闭标签"><span></span><b class="cmf-component-tabclose-icon">×</b></span></li>');
	if('function'==typeof $('#min_title_list li').contextMenu){
		$("#min_title_list li").contextMenu('Huiadminmenu', {
			bindings: {
				'closethis': function(t) {
					var $t = $(t);	
					
					if($t.find("i")){
						$t.find("i").trigger("click");
					}
				},
				'closeall': function(t) {
					$("#min_title_list li i").trigger("click");
				},
			}
		});
	}else {
		
	}	
	var $tabNavitem = topWindow.find(".acrossTab li");
	if (!$tabNav[0]){return}
	$tabNavitem.each(function(index, element) {
        taballwidth+=Number(parseFloat($(this).width()))
    });

	$tabNav.width(taballwidth);
	var w = $tabNavWp.width();
	if(taballwidth>w){
		$tabNavmore.show();
		$tabNav.css('marginLeft',w-taballwidth);
	}
	else{
		$tabNavmore.hide();
		$tabNav.css('marginLeft',0);
	}	
	iframeBox.hide();
	iframe_box.append('<div class="show_iframe"><div class="loading"></div><iframe frameborder="0" src='+href+'></iframe></div>');
	var showBox=iframe_box.find('.show_iframe:visible');
	
	showBox.find('iframe').load(function(){
		showBox.find('.loading').hide();
	});
}



/*关闭iframe*/
function removeIframe(){
	var topWindow = $(window.parent.document),
		iframe = topWindow.find('#iframe_box .show_iframe'),
		tab = topWindow.find(".acrossTab li"),
		showTab = topWindow.find(".acrossTab li.active"),
		showBox=topWindow.find('.show_iframe:visible'),
		i = showTab.index();
	tab.eq(i-1).addClass("active");
	tab.eq(i).remove();
	iframe.eq(i-1).show();	
	iframe.eq(i).remove();
}

/*关闭所有iframe*/
function removeIframeAll(){
	var topWindow = $(window.parent.document),
		iframe = topWindow.find('#iframe_box .show_iframe'),
		tab = topWindow.find(".acrossTab li");
		
	for(var i=0;i<tab.length;i++){
		
		if(tab.eq(i).find("b").length>0){
			tab.eq(i).remove();
			iframe.eq(i).remove();
		}
	}
	tab.eq(0).addClass("active");
	iframe.eq(0).show();	
}



$(function(){

 getmenuheight();
 $("[data-toggle='tooltip']").tooltip();
        $("li.dropdown").hover(function () {
            $(this).addClass("open");
           
        }, function () {
            $(this).removeClass("open");

        });
	
	var resizeID;
	$(window).resize(function(){
		
		
		
		
		
		
		clearTimeout(resizeID);
		resizeID = setTimeout(function(){
		getmenuheight();	
       

		},200);
	});

    $('.nav-list a.dropdown-toggle').on('click', function() {

        var parent_li = $(this).closest('li');
if($(parent_li).hasClass('open')){
$(this).find('.arrow').addClass('fa-angle-right').removeClass('fa-angle-down');
}else{
$(this).find('.arrow').removeClass('fa-angle-right').addClass('fa-angle-down');	
}
        $(parent_li).toggleClass("open").find('ul.submenu').eq(0).slideToggle(150);
        $(parent_li).siblings().removeClass('open').find('ul.submenu').hide();
$(parent_li).siblings().find('b.fa-angle-down').removeClass('fa-angle-down').addClass('fa-angle-right');


        
 
			setTimeout(function(){
				
		
			
			},200);
			
    });
	/*选项卡导航*/
	$(".nav-list li a:not('.dropdown-toggle')").on("click",function(){
		Hui_admin_tab(this);
		
	});
	$(".sidebar-shortcuts a.jscript").on("click",function(){
		Hui_admin_tab(this);
		
	});
	    $("#refresh-wrapper").click(function () {
        var $currentIframe = $("#iframe_box iframe:visible");
        //$loading.show();
       
       
        	$currentIframe[0].contentWindow.location.reload();
        
        
        return false;
    });
	$("#close-wrapper").click(function () {
			var topWindow=$(window.parent.document),
		$tabNavmore =topWindow.find(".task-changebt");
       $tabNavmore.hide(); 
       removeIframeAll();
    });
	$(document).on("click","#min_title_list li",function(){
		var bStopIndex=$(this).index();
		var iframe_box=$("#iframe_box");
		$("#min_title_list li").removeClass("active").eq(bStopIndex).addClass("active");
		iframe_box.find(".show_iframe").hide().eq(bStopIndex).show();
	});
	$(document).on("click","#min_title_list li b",function(){
		var aCloseIndex=$(this).parents("li").index();
		var iframe_box=$("#iframe_box");
		$(this).parent().parent().remove();
		$('#iframe_box').find('.show_iframe').eq(aCloseIndex).remove();	
		num==0?num=0:num--;
		$("#min_title_list li").removeClass("active").eq(aCloseIndex-1).addClass("active");
		iframe_box.find(".show_iframe").hide().eq(aCloseIndex-1).show();
		tabNavallwidth();
	});
	$(document).on("dblclick","#min_title_list li",function(){
		var aCloseIndex=$(this).index();
		var iframe_box=$("#iframe_box");
		if(aCloseIndex>0){
			$(this).remove();
			$('#iframe_box').find('.show_iframe').eq(aCloseIndex).remove();	
			num==0?num=0:num--;
			$("#min_title_list li").removeClass("active").eq(aCloseIndex-1).addClass("active");
			iframe_box.find(".show_iframe").hide().eq(aCloseIndex-1).show();
			tabNavallwidth();
		}else{
			return false;
		}
	});
$(document).on("click",".openformdialog",function(){


var url = $(this).data('url');
var width = $(this).data('width');
var height = $(this).data('height');
var title = $(this).data('title');
		layer.open({
      type: 2,
      title: title,
      maxmin: false,
      shadeClose: true, //点击遮罩关闭层
      area : [width , height],
      btn: ['确定', '取消']
  ,yes: function(index, layero){
    
      
        var body = layer.getChildFrame('body', index);
        var jform = $(body).find('#changepwdform');
		var postdata = jform.serialize();

		
		$.xpost(jform.attr('data-url'), postdata, function(code, ret) {
			
			if (code == 0) {
				
				$.sns5i_notify(ret.message,'success');

				layer.close(index);
				
			}else {
				$.sns5i_notify(ret.message,'danger');
			}
		});
	

  }
  ,btn2: function(index, layero){
    layer.close(layer.index);
  }
  ,cancel: function(){ 
   
  },
  content: [url,'no']
    });
	});
$(document).on("click",".jhcz",function(){
	var url = $(this).data('url');
	$.xget(url,function(code,ret){
         if(code==0){
            $.sns5i_notify(ret.message,'success',ret.url);

         }else{
         	$.sns5i_notify(ret.message,'danger');
         }
         
	});

});




	tabNavallwidth();
	
	$('#task-next').click(function(){
		
        var marginLeft = parseInt(oUl.css('marginLeft'));
        var oUlwidth = parseInt(oUl.width());


        if(oUlwidth+marginLeft>870){
           num==oUl.find('li').length-1?num=oUl.find('li').length-1:num++;
           toNavPos();
        }

	});
	$('#task-pre').click(function(){
		num==0?num=0:num--;

		toNavPos();
	});
	
	function toNavPos(){
		
        	oUl.stop().animate({'marginLeft':-num*100},100);
       

		
	}

}); 
