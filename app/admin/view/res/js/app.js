function bootstrap_Switch(){
    //开关类操作，用于某个字段的0和1操作
$(".switch").bootstrapSwitch({

onSwitchChange:function(event, state){
   var url=$(this).data('url');
   var field=$(this).data('field');
   var trueval=$(this).data('trueval');
   var mess=$(this).data('mess');
   var falsemess=$(this).data('falsemess');
   var falseval=$(this).data('falseval');
   var value,message;
                if (state == true) {
                   value=trueval;
                   message = mess;
                } else {
                   value=falseval;
                   message = falsemess;
                }  
var $this=this;
$.xpost(url,{'field':field,'value':value,'message':message,'state':state},function(code,ret){
    if(code==0){

            $.sns5i_notify(ret.message,'success');


         }else{
           
            $.sns5i_notify(ret.message,'danger');
            $($this).bootstrapSwitch('state',!state,true);
         }
});
}

});
}
$(function(){

$(document).on("click",".allChoose",function(){

var id = $(this).data('id');
var subcheck = $(this).closest('table').find('.'+id);
if($(this).is(':checked')){
$(subcheck).prop("checked",true);
}else{
$(subcheck).prop("checked",false);
}

});
//弹窗表单类，用于编辑、添加等数据操作
$(document).on("click",".openformdialog",function(){
var width,height;
var url = $(this).data('url');
width = !xn.empty($(this).data('width'))? $(this).data('width') :'100%';
height = !xn.empty($(this).data('height'))? $(this).data('height') :'100%';

var title = $(this).data('title');

var id = $(this).data('id');
var index = 	layer.open({
      type: 2,
      title: title,
      maxmin: false,
      shadeClose: true, //点击遮罩关闭层
      area : [width , height],
      btn: ['确定', '取消']
  ,yes: function(index, layero){
    
      
        var body = layer.getChildFrame('body', index);
        var jform = $(body).find('#'+id);
		var postdata = jform.serialize();

		
		$.xpost(jform.attr('data-url'), postdata, function(code, ret) {
			
			if (code == 0) {
				
				$.sns5i_notify(ret.message,'success','',1000,'',function(){
          window.location.reload();
        });

				//layer.close(index);
				
        
			}else {
        
        if(!xn.empty(ret.key)){
          
          jform.find('[name="' + ret.key + '"]').alert(ret.message).focus();
        }
        

				$.sns5i_notify(ret.message,'danger');
			}
		});
	

  }
  ,btn2: function(index, layero){
    layer.close(layer.index);
  }
  ,cancel: function(){ 
   
  },
  content: url
    });


    //layer.full(index);
	});
//用于交互操作，例如点赞、退出等操作后只需要弹出消息的
$(document).on("click",".jhcz",function(){
	var url = $(this).data('url');
  var classname = $(this).attr('data-classname');
  var removeclassname = $(this).attr('data-removeclassname');
  var title = $(this).attr('data-title');
  var val = xn.isset($(this).attr('data-val'))?$(this).attr('data-val'):1;
 
  var $this = this;

	$.xpost(url,{'val':val},function(code,ret){

         if(code==0){
if(!xn.empty(ret.url)){
   $.sns5i_notify(ret.message,'success',ret.url);
}else{
 
   if(!xn.empty(classname)){
      if(!xn.empty(removeclassname)){
         if($($this).hasClass(removeclassname)){
         $($this).removeClass(removeclassname);
      }else{
         $($this).addClass(removeclassname)
      }
      }


      if($($this).hasClass(classname)){
         $($this).removeClass(classname);
      }else{
         $($this).addClass(classname)
      }
   }
   if(!xn.empty(title)){
    
       $($this).attr('data-title',$.trim($($this).text()));
       $($this).text(title);
   }

   
   if(xn.isset(ret.val)){
  
       $($this).attr('data-val',ret.val);
       
   }
   $.sns5i_notify(ret.message,'success');
}
            

         }else{

         	  $.sns5i_notify(ret.message,'danger');
         }
         
	});

});

//带确认框的弹窗操作，如删除操作，确定后进行操作。
$(document).on("click",".confirm_btn",function(){
  var url = $(this).data('url');
  
  var name = $(this).data('name');
  var title = $(this).data('title');
 
  var $this = this;
layer.confirm(title, {icon: 3, title:'温馨提示'}, function(index){
    $.xget(url,function(code,ret){

         if(code==0){

            $.sns5i_notify(ret.message,'success','',2000,'',function(){

                    window.location.reload();

            });        

         }else{

            $.sns5i_notify(ret.message,'danger');
         }
         
  });
  
  layer.close(index);
});


});

$(document).on("click",".deletebtn",function(){
  var url = $(this).data('url');
  
  var name = $(this).data('name');
  var title = $(this).data('title');
 
  var $this = this;
layer.confirm(title, {icon: 3, title:'温馨提示'}, function(index){
    $.xget(url,function(code,ret){

         if(code==0){
if(!xn.empty(ret.url)){
   $.sns5i_notify(ret.message,'success',ret.url);
}else{
   $('#'+name).remove();
   $.sns5i_notify(ret.message,'success');
}
            

         }else{

            $.sns5i_notify(ret.message,'danger');
         }
         
  });
  
  layer.close(index);
});


});


});
