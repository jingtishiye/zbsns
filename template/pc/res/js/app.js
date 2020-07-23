/**
 * 
 * @authors 5iSNS实验室 (admin@5isns.com)
 * @date    2019-04-09 18:54:10
 */
if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
  var msViewportStyle = document.createElement('style')
  msViewportStyle.appendChild(
    document.createTextNode(
      '@-ms-viewport{width:auto!important}'
    )
  )
  document.querySelector('head').appendChild(msViewportStyle)
}
$(function(){
   $(".go-top").click(function(){
        $('html,body').animate({scrollTop:0}, 200);
   });//返回顶部
	 //鼠标滑动后显示下拉菜单
	 $(".dropdown").mouseover(function () {
        $(this).addClass("open");
    });

    $(".dropdown").mouseleave(function(){
        $(this).removeClass("open");
    });

  //搜索框
  $('#searchinput').focus(function(){
    $(this).closest('.search-form').addClass('searching');
  }).blur(function(){
    $(this).closest('.search-form').removeClass('searching');
  });
  $('[data-toggle="tooltip"]').tooltip();
//弹窗表单类，用于编辑、添加等数据操作

$('.ajaxform').on('submit', function() {
    var postdata = $(this).serialize();
    
    
    var jsubmit = $(this).find('button[type="submit"]');
    jsubmit.button('loading').button('disabled');
    var _this = $(this);
    $.xpost($(this).attr('action'), postdata, function(code, ret) {
     
      if (code == 0) {
        if(!xn.empty(ret.url)){
              $.sns5i_notify(ret.message,'success',ret.url);
           }else{
           
             $.sns5i_notify(ret.message,'success','',1000,'',function(){
              jsubmit.button('reset');

             });
           }

        if(!xn.empty(ret.reset)){
         $(_this)[0].reset();

        }
        
        
        
      } else if(code==-1) {
        $.sns5i_notify(ret.message,'danger');
        jsubmit.button('reset');
      }else{

         $(_this).find('[name="'+code+'"]').alert(ret.message).focus();
         jsubmit.button('reset');
      }
    });
    return false;
  });
$(document).on("click",".openformdialog",function(){
var width,height;
width = !xn.empty($(this).data('width'))? $(this).data('width') :'100%';
height = !xn.empty($(this).data('height'))? $(this).data('height') :'100%';

var async = !$(this).data('async')? $(this).data('async') :true;

var title = $(this).data('title');
var id = $(this).data('id');
var formid = $(this).data('formid');
var url = $(this).data('url');
    layer.open({
      type: 1,
      title: title,
      maxmin: false,
      shadeClose: true, //点击遮罩关闭层
      area : [width , height],
      content: $('#'+id).html(),
      success: function(layero, index){

         
$('.layerform').on('submit', function() {
    var postdata = $(this).serialize();
    var jsubmit = $(this).find('button[type="submit"]');
    jsubmit.button('loading').button('disabled');

   $.xpost_async(url, postdata, function(code, ret) {
     
      if (code == 0) {

        
        if(!xn.empty(ret.url)){

         window.open(ret.url);

        }
        if(!xn.empty(ret.html)){

          if(ret.method=='alipay'){
  const newTab = window.open();
  const div=document.createElement('divform');
  div.innerHTML=ret.html;
  newTab.document.body.appendChild(div);

  newTab.document.forms.alipaysubmit.submit();
   layer.close(index); 
          }else{
$(layero).html(ret.html);
          }



        }else{
        $.sns5i_notify(ret.message,'success','',1000,'',function(){

           layer.close(index); 
        });
        }
       
        
      } else {
        $.sns5i_notify(ret.message,'danger');
        jsubmit.button('reset');
      }
    },async);

  /*$.ajax({
    type: 'POST',
    url: url,
    data: postdata,
    dataType: 'text',
    async:async,
    success: function(r){
      
      if(!r){
        $.sns5i_notify('Server Response Empty!','danger','',1000,'',function(){
          layer.close(index);
         });
      }
      var s = xn.json_decode(r);
      
      if(!s || s.code === undefined){

         $.sns5i_notify('Server Response Not JSON：'+r,'danger','',1000,'',function(){
          layer.close(index);
         });
      } 
      if(s.code == 0) {
         $.sns5i_notify(s.message,'success','',1000,'',function(){
       


        });
     if(!xn.empty(s.url)){
   
  
          window.open(s.url); 
       
         
     }
     layer.close(index);      
      } else {
       
         $.sns5i_notify(s.message,'danger');
        jsubmit.button('reset');
      }
    },
    error: function(xhr, type) {

      $.sns5i_notify("xhr.responseText:"+xhr.responseText+', type:'+type,'danger','',1000,'',function(){
          layer.close(index);
      });
    }
  });*/
    return false;
  });
      }
    });
  });

//用于交互操作，例如点赞、退出等操作后只需要弹出消息的
$(document).on("click",".focus-action",function(){
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
       if(xn.isset(ret.icon)){
        title = '<i class="fa fa-'+ret.icon+'"></i> '+title;
       }
       $($this).attr('data-title',$.trim($($this).text()));
       $($this).html(title);
   }else{
     if(xn.isset(ret.icon)){
        title = '<i class="fa fa-'+ret.icon+'"></i> ';
        $($this).html(title);
       }
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

//带确认框的弹窗操作，确定后进行操作。
$(document).on("click",".confirm-dialog",function(){
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
   if(name){
     $('#'+name).remove();
   }
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