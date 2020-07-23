/*! layer-v3.1.1 Web弹层组件 MIT License  http://layer.layui.com/  By 贤心 */
 ;!function(e,t){"use strict";var i,n,a=e.layui&&layui.define,o={getPath:function(){var e=document.currentScript?document.currentScript.src:function(){for(var e,t=document.scripts,i=t.length-1,n=i;n>0;n--)if("interactive"===t[n].readyState){e=t[n].src;break}return e||t[i].src}();return e.substring(0,e.lastIndexOf("/")+1)}(),config:{},end:{},minIndex:0,minLeft:[],btn:["&#x786E;&#x5B9A;","&#x53D6;&#x6D88;"],type:["dialog","page","iframe","loading","tips"],getStyle:function(t,i){var n=t.currentStyle?t.currentStyle:e.getComputedStyle(t,null);return n[n.getPropertyValue?"getPropertyValue":"getAttribute"](i)},link:function(t,i,n){if(r.path){var a=document.getElementsByTagName("head")[0],s=document.createElement("link");"string"==typeof i&&(n=i);var l=(n||t).replace(/\.|\//g,""),f="layuicss-"+l,c=0;s.rel="stylesheet",s.href=r.path+t,s.id=f,document.getElementById(f)||a.appendChild(s),"function"==typeof i&&!function u(){return++c>80?e.console&&console.error("common.css: Invalid"):void(1989===parseInt(o.getStyle(document.getElementById(f),"width"))?i():setTimeout(u,100))}()}}},r={v:"3.1.1",ie:function(){var t=navigator.userAgent.toLowerCase();return!!(e.ActiveXObject||"ActiveXObject"in e)&&((t.match(/msie\s(\d+)/)||[])[1]||"11")}(),index:e.layer&&e.layer.v?1e5:0,path:o.getPath,config:function(e,t){return e=e||{},r.cache=o.config=i.extend({},o.config,e),r.path=o.config.path||r.path,"string"==typeof e.extend&&(e.extend=[e.extend]),o.config.path&&r.ready(),e.extend?(a?layui.addcss("modules/layer/"+e.extend):o.link("theme/"+e.extend),this):this},ready:function(e){var t="layer",i="",n=(a?"modules/layer/":"../css/")+"common.css?v="+r.v+i;return a?layui.addcss(n,e,t):o.link(n,e,t),this},alert:function(e,t,n){var a="function"==typeof t;return a&&(n=t),r.open(i.extend({content:e,yes:n},a?{}:t))},confirm:function(e,t,n,a){var s="function"==typeof t;return s&&(a=n,n=t),r.open(i.extend({content:e,btn:o.btn,yes:n,btn2:a},s?{}:t))},msg:function(e,n,a){var s="function"==typeof n,f=o.config.skin,c=(f?f+" "+f+"-msg":"")||"layui-layer-msg",u=l.anim.length-1;return s&&(a=n),r.open(i.extend({content:e,time:3e3,shade:!1,skin:c,title:!1,closeBtn:!1,btn:!1,resize:!1,end:a},s&&!o.config.skin?{skin:c+" layui-layer-hui",anim:u}:function(){return n=n||{},(n.icon===-1||n.icon===t&&!o.config.skin)&&(n.skin=c+" "+(n.skin||"layui-layer-hui")),n}()))},load:function(e,t){return r.open(i.extend({type:3,icon:e||0,resize:!1,shade:.01},t))},tips:function(e,t,n){return r.open(i.extend({type:4,content:[e,t],closeBtn:!1,time:3e3,shade:!1,resize:!1,fixed:!1,maxWidth:210},n))}},s=function(e){var t=this;t.index=++r.index,t.config=i.extend({},t.config,o.config,e),document.body?t.creat():setTimeout(function(){t.creat()},30)};s.pt=s.prototype;var l=["layui-layer",".layui-layer-title",".layui-layer-main",".layui-layer-dialog","layui-layer-iframe","layui-layer-content","layui-layer-btn","layui-layer-close"];l.anim=["layer-anim-00","layer-anim-01","layer-anim-02","layer-anim-03","layer-anim-04","layer-anim-05","layer-anim-06"],s.pt.config={type:0,shade:.3,fixed:!0,move:l[1],title:"&#x4FE1;&#x606F;",offset:"auto",area:"auto",closeBtn:1,time:0,zIndex:19891014,maxWidth:360,anim:0,isOutAnim:!0,icon:-1,moveType:1,resize:!0,scrollbar:!0,tips:2},s.pt.vessel=function(e,t){var n=this,a=n.index,r=n.config,s=r.zIndex+a,f="object"==typeof r.title,c=r.maxmin&&(1===r.type||2===r.type),u=r.title?'<div class="layui-layer-title" style="'+(f?r.title[1]:"")+'">'+(f?r.title[0]:r.title)+"</div>":"";return r.zIndex=s,t([r.shade?'<div class="layui-layer-shade" id="layui-layer-shade'+a+'" times="'+a+'" style="'+("z-index:"+(s-1)+"; ")+'"></div>':"",'<div class="'+l[0]+(" layui-layer-"+o.type[r.type])+(0!=r.type&&2!=r.type||r.shade?"":" layui-layer-border")+" "+(r.skin||"")+'" id="'+l[0]+a+'" type="'+o.type[r.type]+'" times="'+a+'" showtime="'+r.time+'" conType="'+(e?"object":"string")+'" style="z-index: '+s+"; width:"+r.area[0]+";height:"+r.area[1]+(r.fixed?"":";position:absolute;")+'">'+(e&&2!=r.type?"":u)+'<div id="'+(r.id||"")+'" class="layui-layer-content'+(0==r.type&&r.icon!==-1?" layui-layer-padding":"")+(3==r.type?" layui-layer-loading"+r.icon:"")+'">'+(0==r.type&&r.icon!==-1?'<i class="layui-layer-ico layui-layer-ico'+r.icon+'"></i>':"")+(1==r.type&&e?"":r.content||"")+'</div><span class="layui-layer-setwin">'+function(){var e=c?'<a class="layui-layer-min" href="javascript:;"><cite></cite></a><a class="layui-layer-ico layui-layer-max" href="javascript:;"></a>':"";return r.closeBtn&&(e+='<a class="layui-layer-ico '+l[7]+" "+l[7]+(r.title?r.closeBtn:4==r.type?"1":"2")+'" href="javascript:;"></a>'),e}()+"</span>"+(r.btn?function(){var e="";"string"==typeof r.btn&&(r.btn=[r.btn]);for(var t=0,i=r.btn.length;t<i;t++)e+='<a class="'+l[6]+t+'">'+r.btn[t]+"</a>";return'<div class="'+l[6]+" layui-layer-btn-"+(r.btnAlign||"")+'">'+e+"</div>"}():"")+(r.resize?'<span class="layui-layer-resize"></span>':"")+"</div>"],u,i('<div class="layui-layer-move"></div>')),n},s.pt.creat=function(){var e=this,t=e.config,a=e.index,s=t.content,f="object"==typeof s,c=i("body");if(!t.id||!i("#"+t.id)[0]){switch("string"==typeof t.area&&(t.area="auto"===t.area?["",""]:[t.area,""]),t.shift&&(t.anim=t.shift),6==r.ie&&(t.fixed=!1),t.type){case 0:t.btn="btn"in t?t.btn:o.btn[0],r.closeAll("dialog");break;case 2:var s=t.content=f?t.content:[t.content||"http://layer.layui.com","auto"];t.content='<iframe scrolling="'+(t.content[1]||"auto")+'" allowtransparency="true" id="'+l[4]+a+'" name="'+l[4]+a+'" onload="this.className=\'\';" class="layui-layer-load" frameborder="0" src="'+t.content[0]+'"></iframe>';break;case 3:delete t.title,delete t.closeBtn,t.icon===-1&&0===t.icon,r.closeAll("loading");break;case 4:f||(t.content=[t.content,"body"]),t.follow=t.content[1],t.content=t.content[0]+'<i class="layui-layer-TipsG"></i>',delete t.title,t.tips="object"==typeof t.tips?t.tips:[t.tips,!0],t.tipsMore||r.closeAll("tips")}if(e.vessel(f,function(n,r,u){c.append(n[0]),f?function(){2==t.type||4==t.type?function(){i("body").append(n[1])}():function(){s.parents("."+l[0])[0]||(s.data("display",s.css("display")).show().addClass("layui-layer-wrap").wrap(n[1]),i("#"+l[0]+a).find("."+l[5]).before(r))}()}():c.append(n[1]),i(".layui-layer-move")[0]||c.append(o.moveElem=u),e.layero=i("#"+l[0]+a),t.scrollbar||l.html.css("overflow","hidden").attr("layer-full",a)}).auto(a),i("#layui-layer-shade"+e.index).css({"background-color":t.shade[1]||"#000",opacity:t.shade[0]||t.shade}),2==t.type&&6==r.ie&&e.layero.find("iframe").attr("src",s[0]),4==t.type?e.tips():e.offset(),t.fixed&&n.on("resize",function(){e.offset(),(/^\d+%$/.test(t.area[0])||/^\d+%$/.test(t.area[1]))&&e.auto(a),4==t.type&&e.tips()}),t.time<=0||setTimeout(function(){r.close(e.index)},t.time),e.move().callback(),l.anim[t.anim]){var u="layer-anim "+l.anim[t.anim];e.layero.addClass(u).one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend",function(){i(this).removeClass(u)})}t.isOutAnim&&e.layero.data("isOutAnim",!0)}},s.pt.auto=function(e){var t=this,a=t.config,o=i("#"+l[0]+e);""===a.area[0]&&a.maxWidth>0&&(r.ie&&r.ie<8&&a.btn&&o.width(o.innerWidth()),o.outerWidth()>a.maxWidth&&o.width(a.maxWidth));var s=[o.innerWidth(),o.innerHeight()],f=o.find(l[1]).outerHeight()||0,c=o.find("."+l[6]).outerHeight()||0,u=function(e){e=o.find(e),e.height(s[1]-f-c-2*(0|parseFloat(e.css("padding-top"))))};switch(a.type){case 2:u("iframe");break;default:""===a.area[1]?a.maxHeight>0&&o.outerHeight()>a.maxHeight?(s[1]=a.maxHeight,u("."+l[5])):a.fixed&&s[1]>=n.height()&&(s[1]=n.height(),u("."+l[5])):u("."+l[5])}return t},s.pt.offset=function(){var e=this,t=e.config,i=e.layero,a=[i.outerWidth(),i.outerHeight()],o="object"==typeof t.offset;e.offsetTop=(n.height()-a[1])/2,e.offsetLeft=(n.width()-a[0])/2,o?(e.offsetTop=t.offset[0],e.offsetLeft=t.offset[1]||e.offsetLeft):"auto"!==t.offset&&("t"===t.offset?e.offsetTop=0:"r"===t.offset?e.offsetLeft=n.width()-a[0]:"b"===t.offset?e.offsetTop=n.height()-a[1]:"l"===t.offset?e.offsetLeft=0:"lt"===t.offset?(e.offsetTop=0,e.offsetLeft=0):"lb"===t.offset?(e.offsetTop=n.height()-a[1],e.offsetLeft=0):"rt"===t.offset?(e.offsetTop=0,e.offsetLeft=n.width()-a[0]):"rb"===t.offset?(e.offsetTop=n.height()-a[1],e.offsetLeft=n.width()-a[0]):e.offsetTop=t.offset),t.fixed||(e.offsetTop=/%$/.test(e.offsetTop)?n.height()*parseFloat(e.offsetTop)/100:parseFloat(e.offsetTop),e.offsetLeft=/%$/.test(e.offsetLeft)?n.width()*parseFloat(e.offsetLeft)/100:parseFloat(e.offsetLeft),e.offsetTop+=n.scrollTop(),e.offsetLeft+=n.scrollLeft()),i.attr("minLeft")&&(e.offsetTop=n.height()-(i.find(l[1]).outerHeight()||0),e.offsetLeft=i.css("left")),i.css({top:e.offsetTop,left:e.offsetLeft})},s.pt.tips=function(){var e=this,t=e.config,a=e.layero,o=[a.outerWidth(),a.outerHeight()],r=i(t.follow);r[0]||(r=i("body"));var s={width:r.outerWidth(),height:r.outerHeight(),top:r.offset().top,left:r.offset().left},f=a.find(".layui-layer-TipsG"),c=t.tips[0];t.tips[1]||f.remove(),s.autoLeft=function(){s.left+o[0]-n.width()>0?(s.tipLeft=s.left+s.width-o[0],f.css({right:12,left:"auto"})):s.tipLeft=s.left},s.where=[function(){s.autoLeft(),s.tipTop=s.top-o[1]-10,f.removeClass("layui-layer-TipsB").addClass("layui-layer-TipsT").css("border-right-color",t.tips[1])},function(){s.tipLeft=s.left+s.width+10,s.tipTop=s.top,f.removeClass("layui-layer-TipsL").addClass("layui-layer-TipsR").css("border-bottom-color",t.tips[1])},function(){s.autoLeft(),s.tipTop=s.top+s.height+10,f.removeClass("layui-layer-TipsT").addClass("layui-layer-TipsB").css("border-right-color",t.tips[1])},function(){s.tipLeft=s.left-o[0]-10,s.tipTop=s.top,f.removeClass("layui-layer-TipsR").addClass("layui-layer-TipsL").css("border-bottom-color",t.tips[1])}],s.where[c-1](),1===c?s.top-(n.scrollTop()+o[1]+16)<0&&s.where[2]():2===c?n.width()-(s.left+s.width+o[0]+16)>0||s.where[3]():3===c?s.top-n.scrollTop()+s.height+o[1]+16-n.height()>0&&s.where[0]():4===c&&o[0]+16-s.left>0&&s.where[1](),a.find("."+l[5]).css({"background-color":t.tips[1],"padding-right":t.closeBtn?"30px":""}),a.css({left:s.tipLeft-(t.fixed?n.scrollLeft():0),top:s.tipTop-(t.fixed?n.scrollTop():0)})},s.pt.move=function(){var e=this,t=e.config,a=i(document),s=e.layero,l=s.find(t.move),f=s.find(".layui-layer-resize"),c={};return t.move&&l.css("cursor","move"),l.on("mousedown",function(e){e.preventDefault(),t.move&&(c.moveStart=!0,c.offset=[e.clientX-parseFloat(s.css("left")),e.clientY-parseFloat(s.css("top"))],o.moveElem.css("cursor","move").show())}),f.on("mousedown",function(e){e.preventDefault(),c.resizeStart=!0,c.offset=[e.clientX,e.clientY],c.area=[s.outerWidth(),s.outerHeight()],o.moveElem.css("cursor","se-resize").show()}),a.on("mousemove",function(i){if(c.moveStart){var a=i.clientX-c.offset[0],o=i.clientY-c.offset[1],l="fixed"===s.css("position");if(i.preventDefault(),c.stX=l?0:n.scrollLeft(),c.stY=l?0:n.scrollTop(),!t.moveOut){var f=n.width()-s.outerWidth()+c.stX,u=n.height()-s.outerHeight()+c.stY;a<c.stX&&(a=c.stX),a>f&&(a=f),o<c.stY&&(o=c.stY),o>u&&(o=u)}s.css({left:a,top:o})}if(t.resize&&c.resizeStart){var a=i.clientX-c.offset[0],o=i.clientY-c.offset[1];i.preventDefault(),r.style(e.index,{width:c.area[0]+a,height:c.area[1]+o}),c.isResize=!0,t.resizing&&t.resizing(s)}}).on("mouseup",function(e){c.moveStart&&(delete c.moveStart,o.moveElem.hide(),t.moveEnd&&t.moveEnd(s)),c.resizeStart&&(delete c.resizeStart,o.moveElem.hide())}),e},s.pt.callback=function(){function e(){var e=a.cancel&&a.cancel(t.index,n);e===!1||r.close(t.index)}var t=this,n=t.layero,a=t.config;t.openLayer(),a.success&&(2==a.type?n.find("iframe").on("load",function(){a.success(n,t.index)}):a.success(n,t.index)),6==r.ie&&t.IE6(n),n.find("."+l[6]).children("a").on("click",function(){var e=i(this).index();if(0===e)a.yes?a.yes(t.index,n):a.btn1?a.btn1(t.index,n):r.close(t.index);else{var o=a["btn"+(e+1)]&&a["btn"+(e+1)](t.index,n);o===!1||r.close(t.index)}}),n.find("."+l[7]).on("click",e),a.shadeClose&&i("#layui-layer-shade"+t.index).on("click",function(){r.close(t.index)}),n.find(".layui-layer-min").on("click",function(){var e=a.min&&a.min(n);e===!1||r.min(t.index,a)}),n.find(".layui-layer-max").on("click",function(){i(this).hasClass("layui-layer-maxmin")?(r.restore(t.index),a.restore&&a.restore(n)):(r.full(t.index,a),setTimeout(function(){a.full&&a.full(n)},100))}),a.end&&(o.end[t.index]=a.end)},o.reselect=function(){i.each(i("select"),function(e,t){var n=i(this);n.parents("."+l[0])[0]||1==n.attr("layer")&&i("."+l[0]).length<1&&n.removeAttr("layer").show(),n=null})},s.pt.IE6=function(e){i("select").each(function(e,t){var n=i(this);n.parents("."+l[0])[0]||"none"===n.css("display")||n.attr({layer:"1"}).hide(),n=null})},s.pt.openLayer=function(){var e=this;r.zIndex=e.config.zIndex,r.setTop=function(e){var t=function(){r.zIndex++,e.css("z-index",r.zIndex+1)};return r.zIndex=parseInt(e[0].style.zIndex),e.on("mousedown",t),r.zIndex}},o.record=function(e){var t=[e.width(),e.height(),e.position().top,e.position().left+parseFloat(e.css("margin-left"))];e.find(".layui-layer-max").addClass("layui-layer-maxmin"),e.attr({area:t})},o.rescollbar=function(e){l.html.attr("layer-full")==e&&(l.html[0].style.removeProperty?l.html[0].style.removeProperty("overflow"):l.html[0].style.removeAttribute("overflow"),l.html.removeAttr("layer-full"))},e.layer=r,r.getChildFrame=function(e,t){return t=t||i("."+l[4]).attr("times"),i("#"+l[0]+t).find("iframe").contents().find(e)},r.getFrameIndex=function(e){return i("#"+e).parents("."+l[4]).attr("times")},r.iframeAuto=function(e){if(e){var t=r.getChildFrame("html",e).outerHeight(),n=i("#"+l[0]+e),a=n.find(l[1]).outerHeight()||0,o=n.find("."+l[6]).outerHeight()||0;n.css({height:t+a+o}),n.find("iframe").css({height:t})}},r.iframeSrc=function(e,t){i("#"+l[0]+e).find("iframe").attr("src",t)},r.style=function(e,t,n){var a=i("#"+l[0]+e),r=a.find(".layui-layer-content"),s=a.attr("type"),f=a.find(l[1]).outerHeight()||0,c=a.find("."+l[6]).outerHeight()||0;a.attr("minLeft");s!==o.type[3]&&s!==o.type[4]&&(n||(parseFloat(t.width)<=260&&(t.width=260),parseFloat(t.height)-f-c<=64&&(t.height=64+f+c)),a.css(t),c=a.find("."+l[6]).outerHeight(),s===o.type[2]?a.find("iframe").css({height:parseFloat(t.height)-f-c}):r.css({height:parseFloat(t.height)-f-c-parseFloat(r.css("padding-top"))-parseFloat(r.css("padding-bottom"))}))},r.min=function(e,t){var a=i("#"+l[0]+e),s=a.find(l[1]).outerHeight()||0,f=a.attr("minLeft")||181*o.minIndex+"px",c=a.css("position");o.record(a),o.minLeft[0]&&(f=o.minLeft[0],o.minLeft.shift()),a.attr("position",c),r.style(e,{width:180,height:s,left:f,top:n.height()-s,position:"fixed",overflow:"hidden"},!0),a.find(".layui-layer-min").hide(),"page"===a.attr("type")&&a.find(l[4]).hide(),o.rescollbar(e),a.attr("minLeft")||o.minIndex++,a.attr("minLeft",f)},r.restore=function(e){var t=i("#"+l[0]+e),n=t.attr("area").split(",");t.attr("type");r.style(e,{width:parseFloat(n[0]),height:parseFloat(n[1]),top:parseFloat(n[2]),left:parseFloat(n[3]),position:t.attr("position"),overflow:"visible"},!0),t.find(".layui-layer-max").removeClass("layui-layer-maxmin"),t.find(".layui-layer-min").show(),"page"===t.attr("type")&&t.find(l[4]).show(),o.rescollbar(e)},r.full=function(e){var t,a=i("#"+l[0]+e);o.record(a),l.html.attr("layer-full")||l.html.css("overflow","hidden").attr("layer-full",e),clearTimeout(t),t=setTimeout(function(){var t="fixed"===a.css("position");r.style(e,{top:t?0:n.scrollTop(),left:t?0:n.scrollLeft(),width:n.width(),height:n.height()},!0),a.find(".layui-layer-min").hide()},100)},r.title=function(e,t){var n=i("#"+l[0]+(t||r.index)).find(l[1]);n.html(e)},r.close=function(e){var t=i("#"+l[0]+e),n=t.attr("type"),a="layer-anim-close";if(t[0]){var s="layui-layer-wrap",f=function(){if(n===o.type[1]&&"object"===t.attr("conType")){t.children(":not(."+l[5]+")").remove();for(var a=t.find("."+s),r=0;r<2;r++)a.unwrap();a.css("display",a.data("display")).removeClass(s)}else{if(n===o.type[2])try{var f=i("#"+l[4]+e)[0];f.contentWindow.document.write(""),f.contentWindow.close(),t.find("."+l[5])[0].removeChild(f)}catch(c){}t[0].innerHTML="",t.remove()}"function"==typeof o.end[e]&&o.end[e](),delete o.end[e]};t.data("isOutAnim")&&t.addClass("layer-anim "+a),i("#layui-layer-moves, #layui-layer-shade"+e).remove(),6==r.ie&&o.reselect(),o.rescollbar(e),t.attr("minLeft")&&(o.minIndex--,o.minLeft.push(t.attr("minLeft"))),r.ie&&r.ie<10||!t.data("isOutAnim")?f():setTimeout(function(){f()},200)}},r.closeAll=function(e){i.each(i("."+l[0]),function(){var t=i(this),n=e?t.attr("type")===e:1;n&&r.close(t.attr("times")),n=null})};var f=r.cache||{},c=function(e){return f.skin?" "+f.skin+" "+f.skin+"-"+e:""};r.prompt=function(e,t){var a="";if(e=e||{},"function"==typeof e&&(t=e),e.area){var o=e.area;a='style="width: '+o[0]+"; height: "+o[1]+';"',delete e.area}var s,l=2==e.formType?'<textarea class="layui-layer-input"'+a+">"+(e.value||"")+"</textarea>":function(){return'<input type="'+(1==e.formType?"password":"text")+'" class="layui-layer-input" value="'+(e.value||"")+'">'}(),f=e.success;return delete e.success,r.open(i.extend({type:1,btn:["&#x786E;&#x5B9A;","&#x53D6;&#x6D88;"],content:l,skin:"layui-layer-prompt"+c("prompt"),maxWidth:n.width(),success:function(e){s=e.find(".layui-layer-input"),s.focus(),"function"==typeof f&&f(e)},resize:!1,yes:function(i){var n=s.val();""===n?s.focus():n.length>(e.maxlength||500)?r.tips("&#x6700;&#x591A;&#x8F93;&#x5165;"+(e.maxlength||500)+"&#x4E2A;&#x5B57;&#x6570;",s,{tips:1}):t&&t(n,i,s)}},e))},r.tab=function(e){e=e||{};var t=e.tab||{},n="layui-this",a=e.success;return delete e.success,r.open(i.extend({type:1,skin:"layui-layer-tab"+c("tab"),resize:!1,title:function(){var e=t.length,i=1,a="";if(e>0)for(a='<span class="'+n+'">'+t[0].title+"</span>";i<e;i++)a+="<span>"+t[i].title+"</span>";return a}(),content:'<ul class="layui-layer-tabmain">'+function(){var e=t.length,i=1,a="";if(e>0)for(a='<li class="layui-layer-tabli '+n+'">'+(t[0].content||"no content")+"</li>";i<e;i++)a+='<li class="layui-layer-tabli">'+(t[i].content||"no  content")+"</li>";return a}()+"</ul>",success:function(t){var o=t.find(".layui-layer-title").children(),r=t.find(".layui-layer-tabmain").children();o.on("mousedown",function(t){t.stopPropagation?t.stopPropagation():t.cancelBubble=!0;var a=i(this),o=a.index();a.addClass(n).siblings().removeClass(n),r.eq(o).show().siblings().hide(),"function"==typeof e.change&&e.change(o)}),"function"==typeof a&&a(t)}},e))},r.photos=function(t,n,a){function o(e,t,i){var n=new Image;return n.src=e,n.complete?t(n):(n.onload=function(){n.onload=null,t(n)},void(n.onerror=function(e){n.onerror=null,i(e)}))}var s={};if(t=t||{},t.photos){var l=t.photos.constructor===Object,f=l?t.photos:{},u=f.data||[],d=f.start||0;s.imgIndex=(0|d)+1,t.img=t.img||"img";var y=t.success;if(delete t.success,l){if(0===u.length)return r.msg("&#x6CA1;&#x6709;&#x56FE;&#x7247;")}else{var p=i(t.photos),h=function(){u=[],p.find(t.img).each(function(e){var t=i(this);t.attr("layer-index",e),u.push({alt:t.attr("alt"),pid:t.attr("layer-pid"),src:t.attr("layer-src")||t.attr("src"),thumb:t.attr("src")})})};if(h(),0===u.length)return;if(n||p.on("click",t.img,function(){var e=i(this),n=e.attr("layer-index");r.photos(i.extend(t,{photos:{start:n,data:u,tab:t.tab},full:t.full}),!0),h()}),!n)return}s.imgprev=function(e){s.imgIndex--,s.imgIndex<1&&(s.imgIndex=u.length),s.tabimg(e)},s.imgnext=function(e,t){s.imgIndex++,s.imgIndex>u.length&&(s.imgIndex=1,t)||s.tabimg(e)},s.keyup=function(e){if(!s.end){var t=e.keyCode;e.preventDefault(),37===t?s.imgprev(!0):39===t?s.imgnext(!0):27===t&&r.close(s.index)}},s.tabimg=function(e){if(!(u.length<=1))return f.start=s.imgIndex-1,r.close(s.index),r.photos(t,!0,e)},s.event=function(){s.bigimg.hover(function(){s.imgsee.show()},function(){s.imgsee.hide()}),s.bigimg.find(".layui-layer-imgprev").on("click",function(e){e.preventDefault(),s.imgprev()}),s.bigimg.find(".layui-layer-imgnext").on("click",function(e){e.preventDefault(),s.imgnext()}),i(document).on("keyup",s.keyup)},s.loadi=r.load(1,{shade:!("shade"in t)&&.9,scrollbar:!1}),o(u[d].src,function(n){r.close(s.loadi),s.index=r.open(i.extend({type:1,id:"layui-layer-photos",area:function(){var a=[n.width,n.height],o=[i(e).width()-100,i(e).height()-100];if(!t.full&&(a[0]>o[0]||a[1]>o[1])){var r=[a[0]/o[0],a[1]/o[1]];r[0]>r[1]?(a[0]=a[0]/r[0],a[1]=a[1]/r[0]):r[0]<r[1]&&(a[0]=a[0]/r[1],a[1]=a[1]/r[1])}return[a[0]+"px",a[1]+"px"]}(),title:!1,shade:.9,shadeClose:!0,closeBtn:!1,move:".layui-layer-phimg img",moveType:1,scrollbar:!1,moveOut:!0,isOutAnim:!1,skin:"layui-layer-photos"+c("photos"),content:'<div class="layui-layer-phimg"><img src="'+u[d].src+'" alt="'+(u[d].alt||"")+'" layer-pid="'+u[d].pid+'"><div class="layui-layer-imgsee">'+(u.length>1?'<span class="layui-layer-imguide"><a href="javascript:;" class="layui-layer-iconext layui-layer-imgprev"></a><a href="javascript:;" class="layui-layer-iconext layui-layer-imgnext"></a></span>':"")+'<div class="layui-layer-imgbar" style="display:'+(a?"block":"")+'"><span class="layui-layer-imgtit"><a href="javascript:;">'+(u[d].alt||"")+"</a><em>"+s.imgIndex+"/"+u.length+"</em></span></div></div></div>",success:function(e,i){s.bigimg=e.find(".layui-layer-phimg"),s.imgsee=e.find(".layui-layer-imguide,.layui-layer-imgbar"),s.event(e),t.tab&&t.tab(u[d],e),"function"==typeof y&&y(e)},end:function(){s.end=!0,i(document).off("keyup",s.keyup)}},t))},function(){r.close(s.loadi),r.msg("&#x5F53;&#x524D;&#x56FE;&#x7247;&#x5730;&#x5740;&#x5F02;&#x5E38;<br>&#x662F;&#x5426;&#x7EE7;&#x7EED;&#x67E5;&#x770B;&#x4E0B;&#x4E00;&#x5F20;&#xFF1F;",{time:3e4,btn:["&#x4E0B;&#x4E00;&#x5F20;","&#x4E0D;&#x770B;&#x4E86;"],yes:function(){u.length>1&&s.imgnext(!0,!0)}})})}},o.run=function(t){i=t,n=i(e),l.html=i("html"),r.open=function(e){var t=new s(e);return t.index}},e.layui&&layui.define?(r.ready(),layui.define("jquery",function(t){r.path=layui.cache.dir,o.run(layui.$),e.layer=r,t("layer",r)})):"function"==typeof define&&define.amd?define(["jquery"],function(){return o.run(e.jQuery),r}):function(){o.run(e.jQuery),r.ready()}()}(window);
/********************* 对 window 对象进行扩展 ************************/
// 兼容 ie89
if(!Object.keys) {
	Object.keys = function(o) {
		var arr = [];
		for(var k in o) {
			if(o.hasOwnProperty(k)) arr.push(k);
		}
		return arr;
	}
}
if(!Object.values) {
	Object.values = function(o) {
		var arr = [];
		if(!o) return arr;
		for(var k in o) {
			if(o.hasOwnProperty(k)) arr.push(o[k]);
		}
		return arr;
	}
}
Array.values = function(arr) {
	return xn.array_filter(arr);
};

Object.first = function(obj) {
	for(var k in obj) return obj[k];
};
Object.last = function(obj) {
	for(var k in obj);
	return obj[k];
};
Object.length = function(obj) {
	var n = 0;
	for(var k in obj) n++;
	return n;
};
Object.count = function(obj) {
	if(!obj) return 0;
	if(obj.length) return obj.length;
	var n = 0;
	for(k in obj) {
		if(obj.hasOwnProperty(k)) n++;
	}
	return n;
};
Object.sum = function(obj) {
	var sum = 0;
	$.each(obj, function(k, v) {sum += intval(v)});
	return sum;
};
if(typeof console == 'undefined') {
	console = {};
	console.log = function() {};
}

/********************* xn 模拟 php 函数 ************************/

var xn = {}; // 避免冲突，自己的命名空间。
var wi = {}; // 避免冲突，自己的命名空间。

wi.upload_file =function(upload_url,idarr,postdata,callback,progress){
var html;
    html = ' <input type="file" name="'+idarr.fileid+'" id="'+idarr.fileid+'"   style="display: none">';
    if(idarr.id){
    html += '<span id="'+idarr.id+'" ></span>';
   }
    html += '<input type="button" id="'+idarr.inputid+'" value="浏览文件" class="btn btn-primary" >';
    
    $('#'+idarr.divid).html(html);
    $('#'+idarr.divid).on('change','#'+idarr.fileid,function(e){
   
    var file = e.target.files[0];
    
    xn.upload_file(file, upload_url, postdata,callback ,progress);

if(idarr.id){
    $('#'+idarr.id).html($(this).val());
    }
    });

    $('#'+idarr.divid).on('click','#'+idarr.inputid,function(){
    	$('#'+idarr.fileid).click();
    });

}
wi.getRootPath = function (){
        var strFullPath=window.document.location.href;
        
        var strPath=window.document.location.pathname; 
        var pos=strFullPath.indexOf(strPath);
        var prePath=strFullPath.substring(0,pos);
        var postPath=strPath.substring(1,strPath.substr(1).indexOf('/')+1);

        var module_arr = new Array('admin','index');
        
        if($.inArray(postPath,module_arr)!=-1){
          return(prePath);
        }else{
          return(prePath+postPath);	
        }
        
   }

// 针对国内的山寨套壳浏览器检测不准确
xn.is_ie = (!!document.all) ? true : false;// ie6789
xn.is_ie_10 = navigator.userAgent.indexOf('Trident') != -1;
xn.is_ff = navigator.userAgent.indexOf('Firefox') != -1;
xn.in_mobile = ($(window).width() < 1140);
xn.options = {}; // 全局配置
xn.options.water_image_url = wi.getRootPath()+'/public/common/images/water.png';// 默认水印路径

xn.htmlspecialchars = function(s) {
	s = s.replace(/</g, "&lt;");
	s = s.replace(/>/g, "&gt;");
	return s;
};

// 标准的 urlencode()
xn._urlencode = function(s) {
	s = encodeURIComponent(s);
	s = xn.strtolower(s);
	return s;
};

// 标准的 urldecode()
xn._urldecode = function(s) {
	s = decodeURIComponent(s);
	return s;
};

xn.urlencode = function(s) {
	s = encodeURIComponent(s);
	s = s.replace(/_/g, "%5f");
	s = s.replace(/\-/g, "%2d");
	s = s.replace(/\./g, "%2e");
	s = s.replace(/\~/g, "%7e");
	s = s.replace(/\!/g, "%21");
	s = s.replace(/\*/g, "%2a");
	s = s.replace(/\(/g, "%28");
	s = s.replace(/\)/g, "%29");
	//s = s.replace(/\+/g, "%20");
	s = s.replace(/\%/g, "_");
	return s;
};

xn.urldecode = function(s) {
	s = s.replace(/_/g, "%");
	s = decodeURIComponent(s);
	return s;
};

// 兼容 3.0
xn.xn_urlencode = xn.urlencode_safe;
xn.xn_urldecode = xn.urldecode_safe;

xn.nl2br = function(s) {
	s = s.replace(/\r\n/g, "\n");
	s = s.replace(/\n/g, "<br>");
	s = s.replace(/\t/g, "&nbsp; &nbsp; &nbsp; &nbsp; ");
	return s;
};

xn.time = function() {
	return xn.intval(Date.now() / 1000);
};

xn.intval = function(s) {
	var i = parseInt(s);
	return isNaN(i) ? 0 : i;
};

xn.floatval = function(s) {
	if(!s) return 0;
	if(s.constructor === Array) {
		for(var i=0; i<s.length; i++) {
			s[i] = xn.floatval(s[i]);
		}
		return s;
	}
	var r = parseFloat(s);
	return isNaN(r) ? 0 : r;
};

xn.isset = function(k) {
	var t = typeof k;
	return t != 'undefined' && t != 'unknown';
};

xn.empty = function(s) {
	if(s == '0') return true;
	if(!s) {
		return true;
	} else {
		//$.isPlainObject
		if(s.constructor === Object) {
			return Object.keys(s).length == 0;
		} else if(s.constructor === Array) {
			return s.length == 0;
		}
		return false;
	}
};

xn.ceil = Math.ceil;
xn.round = Math.round;
xn.floor = Math.floor;
xn.f2y = function(i, callback) {
	if(!callback) callback = round;
	var r = i / 100;
	return callback(r);
};
xn.y2f = function(s) {
	var r = xn.round(xn.intval(s) * 100);
	return r;
};
xn.strtolower = function(s) {
	s += '';
	return s.toLowerCase();
};
xn.strtoupper = function(s) {
	s += '';
	return s.toUpperCase();
};

xn.json_type = function(o) {
	var _toS = Object.prototype.toString;
	var _types = {
		'undefined': 'undefined',
		'number': 'number',
		'boolean': 'boolean',
		'string': 'string',
		'[object Function]': 'function',
		'[object RegExp]': 'regexp',
		'[object Array]': 'array',
		'[object Date]': 'date',
		'[object Error]': 'error'
	};
	return _types[typeof o] || _types[_toS.call(o)] || (o ? 'object' : 'null');
};

xn.json_encode = function(o) {
	var json_replace_chars = function(chr) {
		var specialChars = { '\b': '\\b', '\t': '\\t', '\n': '\\n', '\f': '\\f', '\r': '\\r', '"': '\\"', '\\': '\\\\' };
		return specialChars[chr] || '\\u00' + Math.floor(chr.charCodeAt() / 16).toString(16) + (chr.charCodeAt() % 16).toString(16);
	};

	var s = [];
	switch (xn.json_type(o)) {
		case 'undefined':
			return 'undefined';
			break;
		case 'null':
			return 'null';
			break;
		case 'number':
		case 'boolean':
		case 'date':
		case 'function':
			return o.toString();
			break;
		case 'string':
			return '"' + o.replace(/[\x00-\x1f\\"]/g, json_replace_chars) + '"';
			break;
		case 'array':
			for (var i = 0, l = o.length; i < l; i++) {
				s.push(xn.json_encode(o[i]));
			}
			return '[' + s.join(',') + ']';
			break;
		case 'error':
		case 'object':
			for (var p in o) {
				s.push('"' + p + '"' + ':' + xn.json_encode(o[p]));
			}
			return '{' + s.join(',') + '}';
			break;
		default:
			return '';
			break;
	}
};

xn.json_decode = function(s) {
	if(!s) return null;
	try {
		// 去掉广告代码。这行代码挺无语的，为了照顾国内很多人浏览器中广告病毒的事实。
		// s = s.replace(/\}\s*<script[^>]*>[\s\S]*?<\/script>\s*$/ig, '}');
		if(s.match(/^<!DOCTYPE/i)) return null;
		var json = $.parseJSON(s);
		return json;
	} catch(e) {
		//alert('JSON格式错误：' + s);
		//window.json_error_string = s;	// 记录到全局
		return null;
	}
};

xn.clone = function(obj) {
        return xn.json_decode(xn.json_encode(obj));
}

// 方便移植 PHP 代码
xn.min = function() {return Math.min.apply(this, arguments);}
xn.max = function() {return Math.max.apply(this, arguments);}
xn.str_replace = function(s, d, str) {var p = new RegExp(s, 'g'); return str.replace(p, d);}
xn.strrpos = function(str, s) {return str.lastIndexOf(s);}
xn.strpos = function(str, s) {return str.indexOf(s);}
xn.substr = function(str, start, len) {
	// 支持负数
	if(!str) return '';
	var end = length;
	var length = str.length;
	if(start < 0) start = length + start;
	if(!len) {
		end = length;
	} else if(len > 0) {
		end = start + len;
	} else {
		end = length + len;
	}
	return str.substring(start, end);
};
xn.explode = function(sep, s) {return s.split(sep);}
xn.implode = function(glur, arr) {return arr.join(glur);}
xn.array_merge = function(arr1, arr2) {return arr1 && arr1.__proto__ === Array.prototype && arr2 && arr2.__proto__ === Array.prototype ? arr1.concat(arr2) : $.extend(arr1, arr2);}
// 比较两个数组的差异，在 arr1 之中，但是不在 arr2 中。返回差异结果集的新数组，
xn.array_diff = function(arr1, arr2) {
	if(arr1.__proto__ === Array.prototype) {
		var o = {};
		for(var i = 0, len = arr2.length; i < len; i++) o[arr2[i]] = true;
		var r = [];
		for(i = 0, len = arr1.length; i < len; i++) {
			var v = arr1[i];
			if(o[v]) continue;
			r.push(v);
		}
		return r;
	} else {
		var r = {};
		for(k in arr1) {
			if(!arr2[k]) r[k] = arr1[k];
		}
		return r;
	}
};
// 过滤空值，可以用于删除
/*
	// 第一种用法：
	var arr = [0,1,2,3];
	delete arr[1];
	delete arr[2];
	arr = array_filter(arr);
	
	// 第二种：
	var arr = [0,1,2,3];
	array_filter(arr, function(k,v) { k == 1} );
*/
xn.array_filter = function(arr, callback) {
	var newarr = [];
	for(var k in arr) {
		var v = arr[k];
		if(callback && callback(k, v)) continue;
		// if(!callback && v === undefined) continue; // 默认过滤空值
		newarr.push(v);
	}
	return newarr;
};
xn.array_keys = function(obj) {
	var arr = [];
	$.each(obj, function(k) {arr.push(k);});
	return arr;
};
xn.array_values = function(obj) {
	var arr = [];
	$.each(obj, function(k, v) {arr.push(v);});
	return arr;
};
xn.in_array = function(v, arr) { return $.inArray(v, arr) != -1;}

xn.rand = function(n) {
	var str = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678';
	var r = '';
	for (i = 0; i < n; i++) {
		r += str.charAt(Math.floor(Math.random() * str.length));
	}
	return r;
};

xn.random = function(min, max) {
	var num = Math.random()*(max-min + 1) + min;
	var r = Math.ceil(num);
	return r;
};

// 所谓的 js 编译模板，不过是一堆效率低下的正则替换，这种东西根据自己喜好用吧。
xn.template = function(s, json) {
	//console.log(json);
	for(k in json) {
		var r = new RegExp('\{('+k+')\}', 'g');
		s = s.replace(r, function(match, name) {
			return json[name];
		});
	}
	return s;
};

xn.is_mobile = function(s) {
	var r = /^\d{11}$/;
	if(!s) {
		return false;
	} else if(!r.test(s)) {
		return false;
	}
	return true;
};

xn.is_email = function(s) {
	var r = /^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/i
	if(!s) {
		return false;
	} else if(!r.test(s)) {
		return false;
	}
	return true;
};

xn.is_string = function(obj) {return Object.prototype.toString.apply(obj) == '[object String]';};
xn.is_function = function(obj) {return Object.prototype.toString.apply(obj) == '[object Function]';};
xn.is_array = function(obj) {return Object.prototype.toString.apply(obj) == '[object Array]';};
xn.is_number = function(obj) {return Object.prototype.toString.apply(obj) == '[object Number]' || /^\d+$/.test(obj);};
xn.is_regexp = function(obj) {return Object.prototype.toString.apply(obj) == '[object RegExp]';};
xn.is_object = function(obj) {return Object.prototype.toString.apply(obj) == '[object Object]';};
xn.is_element = function(obj) {return !!(obj && obj.nodeType === 1);};

xn.lang = function(key, arr) {
	var r = lang[key] ? lang[key] : "lang["+key+"]";
	if(arr) {
		$.each(arr, function(k, v) { r = xn.str_replace("{"+k+"}", v, r);});	
	}
	return r;
};

/* 
	js 版本的翻页函数
*/
// 用例：pages('user-list-{page}.htm', 100, 10, 5);
xn.pages = function (url, totalnum, page, pagesize) {
	if(!page) page = 1;
	if(!pagesize) pagesize = 20;
	var totalpage = xn.ceil(totalnum / pagesize);
	if(totalpage < 2) return '';
	page = xn.min(totalpage, page);
	var shownum = 5;	// 显示多少个页 * 2

	var start = xn.max(1, page - shownum);
	var end = xn.min(totalpage, page + shownum);

	// 不足 $shownum，补全左右两侧
	var right = page + shownum - totalpage;
	if(right > 0) start = xn.max(1, start -= right);
	left = page - shownum;
	if(left < 0) end = xn.min(totalpage, end -= left);

	var s = '';
	if(page != 1) s += '<a href="'+xn.str_replace('{page}', page-1, url)+'">◀</a>';
	if(start > 1) s += '<a href="'+xn.str_replace('{page}', 1, url)+'">1 '+(start > 2 ? '... ' : '')+'</a>';
	for(i=start; i<=end; i++) {
		if(i == page) {
			s += '<a href="'+xn.str_replace('{page}', i, url)+'" class="active">'+i+'</a>';// active
		} else {
			s += '<a href="'+xn.str_replace('{page}', i, url)+'">'+i+'</a>';
		}
	}
	if(end != totalpage) s += '<a href="'+xn.str_replace('{page}', totalpage, url)+'">'+(totalpage - end > 1 ? '... ' : '')+totalpage+'</a>';
	if(page != totalpage) s += '<a href="'+xn.str_replace('{page}', page+1, url)+'">▶</a>';
	return s;
};

xn.parse_url = function(url) {
	if(url.match(/^(([a-z]+):)\/\//i)) {
		var arr = url.match(/^(([a-z]+):\/\/)?([^\/\?#]+)\/*([^\?#]*)\??([^#]*)#?(\w*)$/i);
		if(!arr) return null;
		var r = {
			'schema': arr[2],
			'host': arr[3],
			'path': arr[4],
			'query': arr[5],
			'anchor': arr[6],
			'requesturi': arr[4] + (arr[5] ? '?'+arr[5] : '') + (arr[6] ? '#'+arr[6] : '')
		};
		console.log(r);
		return r;
	} else {
		
		var arr = url.match(/^([^\?#]*)\??([^#]*)#?(\w*)$/i);
		if(!arr) return null;
		var r = {
			'schema': '',
			'host': '',
			'path': arr[1],
			'query': arr[2],
			'anchor': arr[3],
			'requesturi': arr[1] + (arr[2] ? '?'+arr[2] : '')  + (arr[3] ? '#'+arr[3] : '')
		};
		console.log(r);
		return r;
	}
};

xn.parse_str = function (str){
	var sep1 = '=';
	var sep2 = '&';
	var arr = str.split(sep2);
	var arr2 = {};
	for(var x=0; x < arr.length; x++){
		var tmp = arr[x].split(sep1);
		arr2[unescape(tmp[0])] = unescape(tmp[1]).replace(/[+]/g, ' ');
	}
	return arr2;
};

// 解析 url 参数获取 $_GET 变量
xn.parse_url_param = function(url) {
	var arr = xn.parse_url(url);
	var q = arr.path;
	var pos = xn.strrpos(q, '/');
	q = xn.substr(q, pos + 1);
	var r = [];
	if(xn.substr(q, -4) == '.htm') {
		q = xn.substr(q, 0, -4);
		r = xn.explode('-', q);
	// 首页
	} else if (url && url != window.location && url != '.' && url != '/' && url != './'){
		r = ['thread', 'seo', url];
	}

	// 将 xxx.htm?a=b&c=d 后面的正常的 _GET 放到 $_SERVER['_GET']
	if(!xn.empty(arr['query'])) {
		var arr2 = xn.parse_str(arr['query']);
		r = xn.array_merge(r, arr2);
	}
	return r;
};

// 从参数里获取数据
xn.param = function(key) {

};
xn.url = function(u, url_rewrite) {
	var on = window.url_rewrite_on || url_rewrite;
	if(xn.strpos(u, '/') != -1) {
		var path = xn.substr(u, 0, xn.strrpos(u, '/') + 1);
		var query = xn.substr(u, xn.strrpos(u, '/') + 1);
	} else {
		var path = '';
		var query = u;
	}
	var r = '';
	if(!on) {
		r = path  + query + '.htm';
	} else if(on == 1) {
		r = path + query + ".htm";
	} else if(on == 2) {
		r = path  + xn.str_replace('-', '/', query);
	} else if(on == 3) {
		r = path + xn.str_replace('-', '/', query);
	}
	return r;
};

xn.rr_url = function(m,c,a,query, url_rewrite) {
	var on = window.url_rewrite_on || url_rewrite;
	var m = xn.empty(m)?'index':m;
	var r = '';
	if(!on) {
		r = m+'.php';
		if(!xn.empty(c)){
			
            r += '?c='+c;
               if(!xn.empty(a)){
                   r += '&a='+a;
               }
		}
	} else if(on == 1) {
		r = m+'-'+c+'-'+a+'.htm';
	} else if(on == 2) {
		r = m+'-'+c+'-'+a;
	} else if(on == 3) {
		r = m+'-'+c+'-'+a;
	}

	return r;
};

// 将参数添加到 URL
xn.url_add_arg = function(url, k, v) {
	var pos = xn.strpos(url, '.htm');
	if(pos === false) {
		return xn.strpos(url, '?') === false ? url + "&" + k + "=" + v :  url + "?" + k + "=" + v;
	} else {
		return xn.substr(url, 0, pos) + '-' + v + xn.substr(url, pos);
	}
};

// 页面跳转的时间
//xn.jumpdelay = xn.debug ? 20000000 : 2000;


/********************* 对 JQuery 进行扩展 ************************/

$.location = function(url, seconds) {
	if(seconds === undefined) seconds = 1;
	setTimeout(function() {window.location=url;}, seconds * 1000);
};

// 二级数组排序
/*Array.prototype.proto_sort = Array.prototype.sort;
Array.prototype.sort = function(arg) {
	if(arg === undefined) {
		return this.proto_sort();
	} else if(arg.constructor === Function) {
		return this.proto_sort(arg);
	} else if(arg.constructor === Object) {
		var k = Object.first(arg);
		var v = arg[k];
		return this.proto_sort(function(a, b) {return v == 1 ? a[k] > b[k] : a[k] < b[k];});
	} else {
		return this;
	}
}*/

xn.arrlist_values = function(arrlist, key) {
	var r = [];
	arrlist.map(function(arr) { r.push(arr[key]); });
	return r;
};

xn.arrlist_key_values = function(arrlist, key, val, pre) {
	var r = {};
	var pre = pre || '';
	arrlist.map(function(arr) { r[arr[pre+key]] = arr[val]; });
	return r;
};

xn.arrlist_keep_keys = function(arrlist, keys) {
	if(!xn.is_array(keys)) keys = [keys];
	for(k in arrlist) {
		var arr = arrlist[k];
		var newarr = {};
		for(k2 in keys) {
			var key = keys[k2];
			newarr[key] = arr[key];
		}
		arrlist[k] = newarr;
	}
	return arrlist;
}
/*var arrlist = [
	{uid:1, gid:3},
	{uid:2, gid:2},
	{uid:3, gid:1},
];
var arrlist2 = xn.arrlist_keep_keys(arrlist, 'gid');
console.log(arrlist2);*/

xn.arrlist_multisort = function(arrlist, k, asc) {
	var arrlist = arrlist.sort(function(a, b) {
		if(a[k] == b[k]) return 0;
		var r = a[k] > b[k];
		r = asc ? r : !r;
		return r ? 1 : -1;
	});
	return arrlist;
}
/*
var arrlist = [
	{uid:1, gid:3},
	{uid:2, gid:2},
	{uid:3, gid:1},
];
var arrlist2 = xn.arrlist_multisort(arrlist, 'gid', false);
console.log(arrlist2);
*/

// if(xn.is_ie) document.documentElement.addBehavior("#default#userdata");

$.pdata = function(key, value) {
	var r = '';
	if(typeof value != 'undefined') {
		value = xn.json_encode(value);
	}

	// HTML 5
	try {
		// ie10 需要 try 一下
		if(window.localStorage){
			if(typeof value == 'undefined') {
				r = localStorage.getItem(key);
				return xn.json_decode(r);
			} else {
				return localStorage.setItem(key, value);
			}
		}
	} catch(e) {}

	// HTML 4
	if(xn.is_ie && (!document.documentElement || typeof document.documentElement.load == 'unknown' || !document.documentElement.load)) {
		return '';
	}
	// get
	if(typeof value == 'undefined') {
		if(xn.is_ie) {
			try {
				document.documentElement.load(key);
				r = document.documentElement.getAttribute(key);
			} catch(e) {
				//alert('$.pdata:' + e.message);
				r = '';
			}
		} else {
			try {
				r = sessionStorage.getItem(key) && sessionStorage.getItem(key).toString().length == 0 ? '' : (sessionStorage.getItem(key) == null ? '' : sessionStorage.getItem(key));
			} catch(e) {
				r = '';
			}
		}
		return xn.json_decode(r);
	// set
	} else {
		if(xn.is_ie){
			try {
				// fix: IE TEST for ie6 崩溃
				document.documentElement.load(key);
				document.documentElement.setAttribute(key, value);
				document.documentElement.save(key);
				return  document.documentElement.getAttribute(key);
			} catch(error) {/*alert('setdata:'+error.message);*/}
		} else {
			try {
				return sessionStorage.setItem(key, value);
			} catch(error) {/*alert('setdata:'+error.message);*/}
		}
	}
};

// time 单位为秒，与php setcookie, 和  misc::setcookie() 的 time 参数略有差异。
$.cookie = function(name, value, time, path) {
	if(typeof value != 'undefined') {
		if (value === null) {
			var value = '';
			var time = -1;
		}
		if(typeof time != 'undefined') {
			date = new Date();
			date.setTime(date.getTime() + (time * 1000));
			var time = '; expires=' + date.toUTCString();
		} else {
			var time = '';
		}
		var path = path ? '; path=' + path : '';
		//var domain = domain ? '; domain=' + domain : '';
		//var secure = secure ? '; secure' : '';
		document.cookie = name + '=' + encodeURIComponent(value) + time + path;
	} else {
		var v = '';
		if(document.cookie && document.cookie != '') {
			var cookies = document.cookie.split(';');
			for(var i = 0; i < cookies.length; i++) {
				var cookie = $.trim(cookies[i]);
				if(cookie.substring(0, name.length + 1) == (name + '=')) {
					v = decodeURIComponent(cookie.substring(name.length + 1)) + '';
					break;
				}
			}
		}
		return v;
	}
};


// 改变Location URL ?
$.xget = function(url, callback, retry) {
	if(retry === undefined) retry = 1;
	$.ajax({
		type: 'GET',
		url: url,
		dataType: 'text',
		timeout: 15000,
		xhrFields: {withCredentials: true},
		success: function(r){
			if(!r) return callback(-100, 'Server Response Empty!');
			var s = xn.json_decode(r);
			if(!s) {
				return callback(-101, r); // 'Server Response xn.json_decode() failed：'+
			}
			if(s.code === undefined) {
				if($.isPlainObject(s)) {
					return callback(0, s);
				} else {
					return callback(-102, r); // 'Server Response Not JSON 2：'+
				}
			} else if(s.code == 0) {
				return callback(s.code,s);
			//系统错误
			} else if(s.code < 0) {
				return callback(s.code,s);
			//业务逻辑错误
			} else {
				return callback(s.code,s);
			
			}
		},
		// 网络错误，重试
		error: function(xhr, type) {
			if(retry > 1) {
				$.xget(url, callback, retry - 1);
			} else {
				if((type != 'abort' && type != 'error') || xhr.status == 403 || xhr.status == 404) {
					return callback(-1000, "xhr.responseText:"+xhr.responseText+', type:'+type);
				} else {
					return callback(-1001, "xhr.responseText:"+xhr.responseText+', type:'+type);
					console.log("xhr.responseText:"+xhr.responseText+', type:'+type);
				}
			}
		}
	});
};

// ajax progress plugin
(function($, window, undefined) {
	//is onprogress supported by browser?
	var hasOnProgress = ("onprogress" in $.ajaxSettings.xhr());

	//If not supported, do nothing
	if (!hasOnProgress) {
		return;
	}
	
	//patch ajax settings to call a progress callback
	var oldXHR = $.ajaxSettings.xhr;
	$.ajaxSettings.xhr = function() {
		var xhr = oldXHR();
		if(xhr instanceof window.XMLHttpRequest) {
			xhr.addEventListener('progress', this.progress, false);
		}
		
		if(xhr.upload) {
			xhr.upload.addEventListener('progress', this.progress, false);
		}
		
		return xhr;
	};
})(jQuery, window);


$.unparam = function(str) {
	return str.split('&').reduce(function (params, param) {
		var paramSplit = param.split('=').map(function (value) {
			return decodeURIComponent(value.replace('+', ' '));
		});
		params[paramSplit[0]] = paramSplit[1];
		return params;
	}, {});
}
$.xpost_async = function(url, postdata, callback,async,progress_callback) {
	if($.isFunction(postdata)) {
		callback = postdata;
		postdata = null;
	}
	
	$.ajax({
		type: 'POST',
		url: url,
		data: postdata,
		dataType: 'text',
		async:async,
		progress: function(e) {
			if (e.lengthComputable) {
				if(progress_callback) progress_callback(e.loaded / e.total * 100);
				
			}
		},
		success: function(r){
			
			if(!r) return callback(-1, 'Server Response Empty!');
			var s = xn.json_decode(r);
			
			if(!s || s.code === undefined) return callback(-1, 'Server Response Not JSON：'+r);
			if(s.code == 0) {
				return callback(0, s);
			//系统错误
			} else if(s.code < 0) {
				return callback(s.code, s);
			} else {
				return callback(s.code, s);
			}
		},
		error: function(xhr, type) {

			if(type != 'abort' && type != 'error' || xhr.status == 403) {
				return callback(-1000, "xhr.responseText:"+xhr.responseText+', type:'+type);
			} else {
				return callback(-1001, "xhr.responseText:"+xhr.responseText+', type:'+type);
				console.log("xhr.responseText:"+xhr.responseText+', type:'+type);
			}
		}
	});
};
$.xpost = function(url, postdata, callback, progress_callback) {
	if($.isFunction(postdata)) {
		callback = postdata;
		postdata = null;
	}
	
	$.ajax({
		type: 'POST',
		url: url,
		data: postdata,
		dataType: 'text',
		
		timeout: 6000000,
		progress: function(e) {
			if (e.lengthComputable) {
				if(progress_callback) progress_callback(e.loaded / e.total * 100);
				
			}
		},
		success: function(r){
			
			if(!r) return callback(-1, 'Server Response Empty!');
			var s = xn.json_decode(r);
			
			if(!s || s.code === undefined) return callback(-1, 'Server Response Not JSON：'+r);
			if(s.code == 0) {
				return callback(0, s);
			//系统错误
			} else if(s.code < 0) {
				return callback(s.code, s);
			} else {
				return callback(s.code, s);
			}
		},
		error: function(xhr, type) {

			if(type != 'abort' && type != 'error' || xhr.status == 403) {
				return callback(-1000, "xhr.responseText:"+xhr.responseText+', type:'+type);
			} else {
				return callback(-1001, "xhr.responseText:"+xhr.responseText+', type:'+type);
				console.log("xhr.responseText:"+xhr.responseText+', type:'+type);
			}
		}
	});
};

/*
$.xpost = function(url, postdata, callback, progress_callback) {
	//构造表单数据
	if(xn.is_string(postdata)) {
		postdata = xn.is_string(postdata) ? $.unparam(postdata) : postdata;
	}
	var formData = new FormData();
	for(k in postdata) {
		formData.append(k, postdata[k]);
	}
	
	//创建xhr对象 
	var xhr = new XMLHttpRequest();
	
	//设置xhr请求的超时时间
	xhr.timeout = 6000000;
	
	//设置响应返回的数据格式
	xhr.responseType = "text";
	
	//创建一个 post 请求，采用异步
	xhr.open('POST', url, true);
	
	xhr.setRequestHeader("Content_type", "application/x-www-form-urlencoded"); 
	xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest"); 
	
	//注册相关事件回调处理函数
	xhr.onload = function(e) { 
		if(this.status == 200 || this.status == 304) {
			var r = this.response;
			if(!r) return callback(-1, 'Server Response Empty!');
			var s = xn.json_decode(r);
			if(!s || s.code === undefined) return callback(-1, 'Server Response Not JSON：'+r);
			if(s.code == 0) {
				return callback(0, s.message);
			//系统错误
			} else if(s.code < 0) {
				return callback(s.code, s.message);
			} else {
				return callback(s.code, s.message);
			}
		} else {
			console.log(e);
		}
	};
	xhr.ontimeout = function(e) { 
		console.log(e);
		return callback(-1, 'Ajax request timeout:'+url);
		
	};
	xhr.onerror = function(e) { 
		console.log(e);
		return callback(-1, 'Ajax request error');
	};
	xhr.upload.onprogress = function(e) { 
		if (e.lengthComputable) {
			if(progress_callback) progress_callback(xn.intval(e.loaded / e.total * 100));
			//console.log('progress1:'+e.loaded / e.total * 100 + '%');
		}
	};
	
	//发送数据
	xhr.send(formData);
};
*/

/*
	功能：
		异步加载 js, 加载成功以后 callback
	用法：
		$.require('1.js', '2.js', function() {
			alert('after all loaded');
		});
		$.require(['1.js', '2.js' function() {
			alert('after all loaded');
		}]);
*/
// 区别于全局的 node.js require 关键字
$.required = [];
$.require = function() {
	var args = null;
	if(arguments[0] && typeof arguments[0] == 'object') { // 如果0 为数组
		args = arguments[0];
		if(arguments[1]) args.push(arguments[1]);
	} else {
		args = arguments;
	}
	this.load = function(args, i) {
		var _this = this;
		if(args[i] === undefined) return;
		if(typeof args[i] == 'string') {
			var js = args[i];
			// 避免重复加载
			if($.inArray(js, $.required) != -1) {
				if(i < args.length) this.load(args, i+1);
				return;
			}
			$.required.push(js);
			var script = document.createElement("script");
			script.src = js;
			script.onerror = function() {
				console.log('script load error:'+js);
				_this.load(args, i+1);
			};
			if(xn.is_ie) {
				script.onreadystatechange = function() {
					if(script.readyState == 'loaded' || script.readyState == 'complete') {
						_this.load(args, i+1);
						script.onreadystatechange = null;
					}
				};
			} else {
				script.onload = function() { _this.load(args, i+1); };
			}
			document.getElementsByTagName('head')[0].appendChild(script);
		} else if(typeof args[i] == 'function'){
			var f = args[i];
			f();
			if(i < args.length) this.load(args, i+1);
		} else {
			_this.load(args, i+1);
		}
	};
	this.load(args, 0);
};

$.require_css = function(filename) {
	// 判断重复加载
	var tags = document.getElementsByTagName('link');
	for(var i=0; i<tags.length; i++) {
		if(tags[i].href.indexOf(filename) != -1) {
			return false;
		}
	}
	
	var link = document.createElement("link");
	link.rel = "stylesheet";
	link.type = "text/css";
	link.href = filename;
	document.getElementsByTagName('head')[0].appendChild(link);
};

// 在节点上显示 loading 图标
$.fn.loading = function(action) {
	return this.each(function() {
		var jthis = $(this);
		jthis.css('position', 'relative');
		if(!this.jloading) this.jloading = $('<div class="loading"><img src="static/loading.gif" /></div>').appendTo(jthis);
		var jloading = this.jloading.show();
		if(!action) {
			var offset = jthis.position();
			var left = offset.left;
			var top = offset.top;
			var w = jthis.width();
			var h = xn.min(jthis.height(), $(window).height());
			var left = w / 2 - jloading.width() / 2;
			var top = (h / 2 -  jloading.height() / 2) * 2 / 3;
			jloading.css('position', 'absolute').css('left', left).css('top', top);
		} else if(action == 'close') {
			jloading.remove();
			this.jloading = null;
		}
	});
};

// 对图片进行缩略，裁剪，然后 base64 存入 form 隐藏表单，name 与 file 控件相同
// 上传过程中，禁止 button，对图片可以缩略
$.fn.base64_encode_file = function(width, height, action) {
	var action = action || 'thumb';
	var jform = $(this);
	var jsubmit = jform.find('input[type="submit"]');
	jform.on('change', 'input[type="file"]', function(e) {
		var jfile = $(this);
		var jassoc = jfile.data('assoc') ? $('#'+jfile.data('assoc')) : null;
		var obj = e.target;
		jsubmit.button('disabled');
		var file = obj.files[0];

       		// 创建一个隐藏域，用来保存 base64 数据
		var jhidden = $('<input type="hidden" name="'+obj.name+'" />').appendTo(jform);
		obj.name = '';

		var reader = new FileReader();
		reader.readAsDataURL(file);
		reader.onload = function(e) {
			// 如果是图片，并且设置了，宽高，和剪切模式
			if(width && height && xn.substr(this.result, 0, 10) == 'data:image') {
				xn.image_resize(this.result, function(code, message) {
					if(code == 0) {
						if(jassoc) jassoc.attr('src', message.data);
						jhidden.val(message.data); // base64
					} else {

						alert(message);
					}
					jsubmit.button('reset');
				}, {width: width, height: height, action: action});
			} else {
				if(jassoc) jassoc.attr('src', this.result);
				jhidden.val(this.result);
				jsubmit.button('reset');
			}
		}
	});
};

xn.base64_data_image_type = function(s) {
	//data:image/png;base64
	r = s.match(/^data:image\/(\w+);/i);
	return r[1];
};

// 图片背景透明算法 by axiuno@gmail.com，只能处理小图片，效率做过改进，目前速度还不错。
xn.image_background_opacity = function(data, width, height, callback) {
	var x = 0;
	var y = 0;
	//var map = {}; // 图片的状态位： 0: 未检测，1:检测过是背景，2：检测过不是背景
	//var unmap = {}; // 未检测过的 map 
	var checked = {'0-0':1}; // 检测过的点
	var unchecked = {}; // 未检测过的点，会不停得将新的未检测的点放进来，检测过的移动到 checked;
	var unchecked_arr = []; // 用来加速
	// 从四周遍历
	/*
		*************************************
		*                                   *
		*                                   *
		*                                   *
		*                                   *
		*                                   *
		*                                   *
		*                                   *
		*                                   *
		*                                   *
		*                                   *
		*                                   *
		*************************************
	*/
	for(var i = 0; i < width; i++) {
		var k1 = i + '-0';
		var k2 = i + '-' + (height - 1);
		unchecked[k1] = 1;
		unchecked[k2] = 1;
		unchecked_arr.push(k1);
		unchecked_arr.push(k2);
	}
	for(var i = 1; i < height - 1; i++) {
		var k1 ='0-' + i;
		var k2 = (width - 1) + '-' + i;
		unchecked[k1] = 1;
		unchecked[k2] = 1;
		unchecked_arr.push(k1);
		unchecked_arr.push(k2);
	}
	
	var bg = [data[0], data[1], data[2], data[3]];
	// 如果不是纯黑，纯白，则返回。
	if(!((bg[0] == 0 && bg[1] == 0 && bg[2] == 0) || (bg[0] == 255 && bg[1] == 255 && bg[2] == 255))) return;
	// 判断该点是否被检测过。
	/*
	function is_checked(x, y) {
		return checked[x+'-'+y] ? true : false;
	}
	function is_unchecked(x, y) {
		return unchecked[x+'-'+y] ? true : false;
	}*/
	
	function get_one_unchecked() {
		if(unchecked_arr.length == 0) return false;
		var k = unchecked_arr.pop();
		var r = xn.explode('-', k);
		return r;
	}
	function checked_push(x, y) {
		var k = x+'-'+y;
		if(checked[k] === undefined) checked[k] = 1;
	}
	function unchecked_push(x, y) {
		var k = x+'-'+y;
		if(checked[k] === undefined && unchecked[k] === undefined) {
			unchecked[k] = 1;
			unchecked_arr.push(k);
		}
	}
	
	var n = 0;
	while(1) {
		//if(k++ > 100000) break;
		//if(checked.length > 10000) return;
		//(n++ % 10000 == 0) {
			//alert(n);
			//console.log(unchecked_arr);
			//console.log(unchecked);
			//break;
		//}
		// 遍历未检测的区域，并且不在 checked 列表的，放进去。
		var curr = get_one_unchecked();
		//if(unchecked.length > 1000) return;
		// 遍历完毕，终止遍历
		if(!curr) break;
		var x = xn.intval(curr[0]);
		var y = xn.intval(curr[1]);
		
		// 在 data 中的偏移量应该 * 4, rgba 各占一位。
		var pos = 4 * ((y * width) + x);
		var r = data[pos];
		var g = data[pos + 1];
		var b = data[pos + 2];
		var a = data[pos + 3];
		
		if(Math.abs(r - bg[0]) < 2 && Math.abs(g == bg[1]) < 2 && Math.abs(b == bg[2]) < 2) {
			
			if(!callback) {
				data[pos + 0] = 0; // 处理为透明
				data[pos + 1] = 0; // 处理为透明
				data[pos + 2] = 0; // 处理为透明
				data[pos + 3] = 0; // 处理为透明
			} else {
				callback(data, pos);
			}			
		
			// 检测边距
			if(y > 0) unchecked_push(x, y-1);	 // 上
			if(x < width - 1) unchecked_push(x+1, y); // 右
			if(y < height - 1) unchecked_push(x, y+1); // 下
			if(x > 0) unchecked_push(x-1, y); 	// 左
		}
		
		checked_push(x, y); // 保存
	}
};

xn.image_file_type = function(file_base64_data) {
	var pre = xn.substr(file_base64_data, 0, 14);
	if(pre == 'data:image/gif') {
		return 'gif';
	} else if(pre == 'data:image/jpe' || pre == 'data:image/jpg') {
		return 'jpg';
	} else if(pre == 'data:image/png') {
		return 'png';
	}
	return 'jpg';
}

//对图片进行裁切，缩略，对黑色背景，透明化处理
xn.image_resize = function(file_base64_data, callback, options) {
	var thumb_width = options.width || 2560;
	var thumb_height = options.height || 4960;
	var action = options.action || 'thumb';
	var filetype = options.filetype || xn.image_file_type(file_base64_data);//xn.base64_data_image_type(file_base64_data);
	var qulity = options.qulity || 0.9; // 图片质量, 1 为无损
	
	if(thumb_width < 1) return callback(-1, '缩略图宽度不能小于 1 / thumb image width length is less 1 pix');
	if(xn.substr(file_base64_data, 0, 10) != 'data:image') return callback(-1, '传入的 base64 数据有问题 / deformed base64 data');
	// && xn.substr(file_base64_data, 0, 14) != 'data:image/gif' gif 不支持\
	
	var img = new Image();
	img.onload = function() {
		
		var water_img_onload = function(water_on) {
			var canvas = document.createElement('canvas');
			// 等比缩放
			var width = 0, height = 0, canvas_width = 0, canvas_height = 0;
			var dx = 0, dy = 0;
			
			var img_width = img.width;
			var img_height = img.height;
			
			if(xn.substr(file_base64_data, 0, 14) == 'data:image/gif') return callback(0, {width: img_width, height: img_height, data: file_base64_data});
			
			// width, height: 计算出来的宽高（求）
			// thumb_width, thumb_height: 要求的缩略宽高
			// img_width, img_height: 原始图片宽高
			// canvas_width, canvas_height: 画布宽高
			if(action == 'thumb') {
				if(img_width < thumb_width && img_height && thumb_height) {
					width = img_width;
					height = img_height;
				} else {
					// 横形
					if(img_width / img_height > thumb_width / thumb_height) {
						var width = thumb_width; // 以缩略图宽度为准，进行缩放
						var height = Math.ceil((thumb_width / img_width) * img_height);
					// 竖形
					} else {
						var height = thumb_height; // 以缩略图宽度为准，进行缩放
						var width = Math.ceil((img_width / img_height) * thumb_height);
					}
				}
				canvas_width = width;
				canvas_height = height;
			} else if(action == 'clip') {
				if(img_width < thumb_width && img_height && thumb_height) {
					if(img_height > thumb_height) {
						thumb_width = width = img_width;
						// thumb_height = height = thumb_height;
					} else {
						thumb_width = width = img_width;
						thumb_height = height = img_height;
					}
				} else {
					// 横形
					if(img_width / img_height > thumb_width / thumb_height) {
						var height = thumb_height; // 以缩略图宽度为准，进行缩放
						var width = Math.ceil((img_width / img_height) * thumb_height);
						var dx = -((width - thumb_width) / 2);
						var dy = 0;
					// 竖形
					} else {
						var width = thumb_width; // 以缩略图宽度为准，进行缩放
						var height = Math.ceil((img_height / img_width) * thumb_width);
						dx = 0;
						dy = -((height - thumb_height) / 2);
					}
				}
				canvas_width = thumb_width;
				canvas_height = thumb_height;
			}
			canvas.width = canvas_width;
			canvas.height = canvas_height;
			var ctx = canvas.getContext("2d"); 
	
			//ctx.fillStyle = 'rgb(255,255,255)';
			//ctx.fillRect(0,0,width,height);
	
			ctx.clearRect(0, 0, width, height); 			// canvas清屏
			ctx.drawImage(img, 0, 0, img_width, img_height, dx, dy, width, height);	// 将图像绘制到canvas上 
			
			
			
			if(water_on) {
				var water_width = water_img.width;
				var water_height = water_img.height;
				if(img_width > 400 && img_width > water_width && water_width > 4) {
					var x =  img_width - water_width - 16;
					var y = img_height - water_height - 16;
					
					// 参数参考：https://developer.mozilla.org/en-US/docs/Web/API/CanvasRenderingContext2D/drawImage
					ctx.globalAlpha = 0.3; // 水印透明度
					ctx.beginPath();
					ctx.drawImage(water_img, 0, 0, water_width, water_height, x, y, water_width, water_height);	// 将水印图像绘制到canvas上 
					ctx.closePath();
					ctx.save();
				}
			}
			
			
			var imagedata = ctx.getImageData(0, 0, canvas_width, canvas_height);
			var data = imagedata.data;
			// 判断与 [0,0] 值相同的并且连续的像素为背景
	
			//xn.image_background_opacity(data, canvas_width, canvas_height);
	
			// 将修改后的代码复制回画布中
			ctx.putImageData(imagedata, 0, 0);
	
			//filetype = 'png';
			if(filetype == 'jpg') filetype = 'jpeg';
			var s = canvas.toDataURL('image/'+filetype, qulity);
			if(callback) callback(0, {width: width, height: height, data: s});
		
		};
		
		var water_img = new Image();
		water_img.onload = function() {
			water_img_onload(true);
		};
		water_img.onerror = function() {
			water_img_onload(false);
		};
		water_img.src = options.water_image_url || xn.options.water_image_url;
		if(!water_img.src) {
			water_img_onload(false);
		}
	};
	img.onerror = function(e) {
		console.log(e);
		$.sns5i_notify('检测图片是否合法','danger');
	};
	img.src = file_base64_data;
};
xn.form_submit = function(form,complete_callback){

	
 
    var postdata = $(form).serialize();
    
    
    var jsubmit = $(form).find('button[type="submit"]');
    jsubmit.button('loading').button('disabled');
    var _this = form;
    $.xpost($(form).attr('action'), postdata, function(code, ret) {
     
      if (code == 0) {
        if(!xn.empty(ret.url)){
              $.sns5i_notify(ret.message,'success',ret.url);
           }else{
            jsubmit.button('reset');
             $.sns5i_notify(ret.message,'success','',1000,'',complete_callback(ret));
           }

       if(!xn.empty(ret.reset)){
         $(_this)[0].reset();

        }
        
        
        
      } else {
        $.sns5i_notify(ret.message,'danger');
        jsubmit.button('reset');
      }
    });
   
 
};
xn.form_ajax = function(form,complete_callback,confirm,title){
var confirm = confirm || false;
var title = title || '你确定要进行该操作吗？';
$('#'+form).on('submit', function() {
var _this = this;
if(confirm){
layer.confirm(title, {icon: 3, title:'网站提示'}, function(index){
  xn.form_submit(_this,complete_callback);
  layer.close(index);
});
}else{
xn.form_submit(_this,complete_callback);
}

return false;
 });	



};
/*
	用法：
	var file = e.target.files[0]; // 文件控件 onchange 后触发的 event;
	var upload_url = 'xxx.php'; // 服务端地址
	var postdata = {width: 2048, height: 4096, action: 'thumb', filetype: 'jpg'}; // postdata|options 公用，一起传给服务端。
	var progress = function(percent) { console.log('progress:'+ percent); }}; // 如果是图片，会根据此项设定进行缩略和剪切 thumb|clip
	xn.upload_file(file, upload_url, postdata, function(code, json) {
		// 成功
		if(code == 0) {
			console.log(json.url);
			console.log(json.width);
			console.log(json.height);
		} else {
			alert(json);
		}
	}, progress);
*/
xn.upload_file = function(file, upload_url, postdata, complete_callback, progress_callback, thumb_callback) {
	postdata = postdata || {};
	postdata.width = postdata.width || 2560;
	postdata.height = postdata.height || 4960;
	postdata.water = postdata.water || 0;
	var ajax_upload_file = function(base64_data) {
		var ajax_upload = function(upload_url, postdata, complete_callback) {
			$.xpost(upload_url, postdata, function(code, ret) {

				if(code != 0) return complete_callback(code, ret);
				if(complete_callback) complete_callback(0, ret);
			}, function(percent) {
				if(progress_callback) progress_callback(percent);
			});
		};
		
		// gif 直接上传
		// 图片进行缩放，然后上传
		//  && xn.substr(base64_data, 0, 14) != 'data:image/gif'
		if(xn.substr(base64_data, 0, 10) == 'data:image'&&postdata.water==1) {
			var filename = file.name ? file.name : (file.type == 'image/png' ? 'capture.png' : 'capture.jpg');
			xn.image_resize(base64_data, function(code, message) {
				if(code != 0) return alert(message);
				// message.width, message.height 是缩略后的宽度和高度
				postdata.name = filename;
				postdata.data = message.data;
				postdata.width = message.width;
				postdata.height = message.height;
				ajax_upload(upload_url, postdata, complete_callback);
			}, postdata);
		// 文件直接上传， 不缩略
		} else {
			var filename = file.name ? file.name : '';
			postdata.name = filename;
			postdata.data = base64_data;
			postdata.width = 0;
			postdata.height = 0;
			ajax_upload(upload_url, postdata, complete_callback);
		}
	};
		
	// 如果为 base64 则不需要 new FileReader()
	if(xn.is_string(file) && xn.substr(file, 0, 10) == 'data:image') {
		var base64_data = file;
		if(thumb_callback) thumb_callback(base64_data);
		ajax_upload_file(base64_data);
	} else {
		var reader = new FileReader();
			reader.readAsDataURL(file);
			reader.onload = function() {
				var base64_data = this.result;
				if(thumb_callback) thumb_callback(base64_data);
			    ajax_upload_file(base64_data);
			}
	}
	
};

// 从事件对象中查找 file 对象，兼容 jquery event, clipboard, file.onchange
xn.get_files_from_event = function(e) {
	function get_paste_files(e) {
		return e.clipboardData && e.clipboardData.items ? e.clipboardData.items : null;
	}
	function get_drop_files(e) {
		return e.dataTransfer && e.dataTransfer.files ? e.dataTransfer.files : null;
	}
	if(e.originalEvent) e = e.originalEvent;
	if(e.type == 'change' && e.target && e.target.files && e.target.files.length > 0) return e.target.files;
	var files = e.type == 'paste' ? get_paste_files(e) : get_drop_files(e);
	return files;
};

// 获取所有的 父节点集合，一直到最顶层节点为止。, IE8 没有 HTMLElement
xn.nodeHasParent = function(node, topNode) {
	if(!topNode) topNode = document.body;
	var pnode = node.parentNode;
	while(pnode) {
		if(pnode == topNode) return true;
		pnode = pnode.parentNode;
	};
	return false;
};

// 表单提交碰到错误的时候，依赖此处，否则错误会直接跳过，不利于发现错误
window.onerror = function(msg, url, line) {
	if(!window.debug) return;
	alert("error: "+msg+"\r\n line: "+line+"\r\n url: "+url);
	// 阻止所有的 form 提交动作
	return false;
};

// remove() 并不清除子节点事件！！用来替代 remove()，避免内存泄露
$.fn.removeDeep = function() {
	 this.each(function() {
		$(this).find('*').off();
	});
	this.off();
	this.remove();
	return this;
};

// empty 清楚子节点事件，释放内存。
$.fn.emptyDeep = function() {
	this.each(function() {
		$(this).find('*').off();
	});
	this.empty();
	return this;
};

$.fn.son = $.fn.children;

/*
	用来增强 $.fn.val()
	
	用来选中和获取 select radio checkbox 的值，用法：
	$('#select1').checked(1);			// 设置 value="1" 的 option 为选中状态
	$('#select1').checked();			// 返回选中的值。
	$('input[type="checkbox"]').checked([2,3,4]);	// 设置 value="2" 3 4 的 checkbox 为选中状态
	$('input[type="checkbox"]').checked();		// 获取选中状态的 checkbox 的值，返回 []
	$('input[type="radio"]').checked(2);		// 设置 value="2" 的 radio 为选中状态
	$('input[type="radio"]').checked();		// 返回选中状态的 radio 的值。
*/
$.fn.checked = function(v) {
	// 转字符串
	if(v) v = v instanceof Array ? v.map(function(vv) {return vv+""}) : v + "";
	var filter = function() {return !(v instanceof Array) ? (this.value == v) : ($.inArray(this.value, v) != -1)};
	// 设置
	if(v) {
		this.each(function() {
			if(xn.strtolower(this.tagName) == 'select') {
				$(this).find('option').filter(filter).prop('selected', true);
			} else if(xn.strtolower(this.type) == 'checkbox' || strtolower(this.type) == 'radio') {
				// console.log(v);
				$(this).filter(filter).prop('checked', true);
			}
		});
		return this;
	// 获取，值用数组的方式返回
	} else {
		if(this.length == 0) return [];
		var tagtype = xn.strtolower(this[0].tagName) == 'select' ? 'select' : xn.strtolower(this[0].type);
		var r = (tagtype == 'checkbox' ? [] : '');
		for(var i=0; i<this.length; i++) {
			var tag = this[i];
			if(tagtype == 'select') {
				var joption = $(tag).find('option').filter(function() {return this.selected == true});
				if(joption.length > 0) return joption.attr('value');
			} else if(tagtype == 'checkbox') {
				if(tag.checked) r.push(tag.value);
			} else if(tagtype == 'radio') {
				if(tag.checked) return tag.value;
			}
		}
		return r;
	}
};

// 支持连续操作 jsubmit.button(message).delay(1000).button('reset');
$.fn.button = function(status) {
	return this.each(function() {
		var jthis = $(this);
		jthis.queue(function (next) {
			var loading_text = jthis.attr('loading-text') || jthis.data('loading-text');
			if(status == 'loading') {
				jthis.prop('disabled', true).addClass('disabled').attr('default-text', jthis.text());
				jthis.html(loading_text);
			} else if(status == 'disabled') {
				jthis.prop('disabled', true).addClass('disabled');
			} else if(status == 'enable') {
				jthis.prop('disabled', false).removeClass('disabled');
			} else if(status == 'reset') {
				jthis.prop('disabled', false).removeClass('disabled');
				if(jthis.attr('default-text')) {
					jthis.text(jthis.attr('default-text'));
				}
			} else {
				jthis.text(status);
			}
			next();
		});
	});
};

// 支持连续操作 jsubmit.button(message).delay(1000).button('reset').delay(1000).location('http://xxxx');
$.fn.location = function(href) {
	var jthis = this;
	jthis.queue(function(next) {
		if(!href) {
			window.location.reload();
		} else {
			window.location = href;
		}
		next();
	});
};

$.extend({
	sns5i_notify:function(message,code,url,time,close,callback) {

        var time = xn.empty(time)?2000:time;
        var code = xn.empty(code)?'success':code;
        var close = xn.empty(close)?false:close;
        var classname = '';
        var mess = '';
        var icon='';
            if(code=='danger'){
                icon = 'remove';
            }else if(code=='success'){
                icon = 'check';
            }else if(code=='info'){
                icon = 'info';
            }else{
            	icon = 'warning';
            }
             mess = '<i class="fa fa-'+icon+'" ></i> <span class="message">'+message+'</span>';
             classname = 'alert alert-'+code;
	if(close){
	var html = '<span class="layui-layer-setwin"><button type="button" class="close layui-layer-close" ><span aria-hidden="true">×</span></button></span>';	

	}else{
	var html = '';	
	}

layer.msg(html+mess, {

skin: classname,
offset: '10px',
area: ['auto'],
time: time //2秒关闭（如果不配置，默认是3秒）
}, function(){

	if(callback != undefined){
		
        callback(message,code,url);
	}else{
		
		if(!xn.empty(url)){
   	       $.location(url,0);
        }
	}
   
}); 
	}
		
});




$.fn.alert = function(message) {

	var jthis = $(this);
	jpthis = jthis.closest('.form-group');
	jpthis.addClass('has-error').siblings().removeClass('has-error');
	jthis.data('title', message).tooltip({'placement':'right','container':jpthis});
	return this;
};


$.fn.serializeObject = function() {
	var self = this,
		json = {},
		push_counters = {},
		patterns = {
			"validate": /^[a-zA-Z][a-zA-Z0-9_]*(?:\[(?:\d*|[a-zA-Z0-9_]+)\])*$/,
			"key":	  /[a-zA-Z0-9_]+|(?=\[\])/g,
			"push":	 /^$/,
			"fixed":	/^\d+$/,
			"named":	/^[a-zA-Z0-9_]+$/
		};

	this.build = function(base, key, value){
		base[key] = value;
		return base;
	};

	this.push_counter = function(key){
		if(push_counters[key] === undefined){
			push_counters[key] = 0;
		}
		return push_counters[key]++;
	};

	$.each($(this).serializeArray(), function(){

		// skip invalid keys
		if(!patterns.validate.test(this.name)){
			return;
		}

		var k,
			keys = this.name.match(patterns.key),
			merge = this.value,
			reverse_key = this.name;

		while((k = keys.pop()) !== undefined){

			// adjust reverse_key
			reverse_key = reverse_key.replace(new RegExp("\\[" + k + "\\]$"), '');

			// push
			if(k.match(patterns.push)){
				merge = self.build([], self.push_counter(reverse_key), merge);
			}

			// fixed
			else if(k.match(patterns.fixed)){
				merge = self.build([], k, merge);
			}

			// named
			else if(k.match(patterns.named)){
				merge = self.build({}, k, merge);
			}
		}

		json = $.extend(true, json, merge);
	});

	return json;
};


// 批量修改 input name="gid[123]" 中的 123 的值
$.fn.attr_name_index = function(rowid) {
	return this.each(function() {
		var jthis = $(this);
		var name = jthis.attr('name');
		name = name.replace(/\[(\d*)\]/, function(all, oldid) {
			var newid = rowid === undefined ? xn.intval(oldid) + 1 : rowid;
			return '[' + newid + ']';
		});
		jthis.attr('name', name);
	});
};

// 重置 form 状态
$.fn.reset = function() {
	var jform = $(this);
	jform.find('input[type="submit"]').button('reset');
	jform.find('input').tooltip('dispose');
};

// 用来代替 <base href="../" /> 的功能
$.fn.base_href = function(base) {
	function replace_url(url) {
		if(url.match('/^https?:\/\//i')) {
			return url;
		} else {
			return base + url;
		}
	}
	this.find('img').each(function() {
		var jthis = $(this);
		var src = jthis.attr('src');
		if(src) jthis.attr('src', replace_url(src));
	});
	this.find('a').each(function() {
		var jthis = $(this);
		var href = jthis.attr('href');
		if(href) jthis.attr('href', replace_url(href));
	});
	return this;
};

// $.each() 的串行版本，用法：
/*
	$.each_sync(items, function(i, callback) {
		var item = items[i];
		$.post(url, function() {
			// ...
			callback();
		});
	});
*/
$.each_sync = function(array, func, callback){
	async.series((function(){
		var func_arr = [];
		for(var i = 0; i< array.length; i++){
			var f = function(i){
				return function(callback){
					func(i, callback);
					/*
					setTimeout(function() {
						func(i, callback);
					}, 2000);*/
					
				}
			};
			func_arr.push(f(i))
		}
		return func_arr;
	})(), function(error, results) {
		if(callback) callback(null, "complete");
	});
};

// 定位
/*
         11      12      1
        --------------------
     10 |  -11   -12   -1  | 2
        |                  |
      9 |  -9    0     -3  | 3
        |                  |
      8 |  -7    -6    -5  | 4
        --------------------
         7        6       5

     将菜单定位于自己的周围：
     $(this).xn_position($('#menuid'), 6);

*/
// 将菜单定位于自己的周围
$.fn.xn_position = function(jfloat, pos, offset) {
	var jthis = $(this);
	var jparent = jthis.offsetParent();
	var pos = pos || 0;
	var offset = offset || {left: 0, top: 0};
	offset.left = offset.left || 0;
	offset.top = offset.top || 0;
	
	// 如果 menu 藏的特别深，把它移动出来。
	if(jfloat.offsetParent().get(0) != jthis.offsetParent().get(0)) {
		jfloat.appendTo(jthis.offsetParent());
	}
	
	// 设置菜单为绝对定位
	jfloat.css('position', 'absolute').css('z-index', jthis.css('z-index') + 1);
	
	var p = jthis.position();
	p.w = jthis.outerWidth();
	p.h = jthis.outerHeight();
	var m = {left: 0, top: 0};
	m.w = jfloat.outerWidth();
	m.h = jfloat.outerHeight();
	p.margin = {
		left: xn.floatval(jthis.css('margin-left')),
		top: xn.floatval(jthis.css('margin-top')),
		right: xn.floatval(jthis.css('margin-right')),
		bottom: xn.floatval(jthis.css('margin-bottom')),
	};
	p.border = {
		left: xn.floatval(jthis.css('border-left-width')),
		top: xn.floatval(jthis.css('border-top-width')),
		right: xn.floatval(jthis.css('border-right-width')),
		bottom: xn.floatval(jthis.css('border-bottom-width')),
	};
	//alert('margin-top:'+p.margin.top+', border-top:'+p.border.top);
	
	if(pos == 12) {
		m.left = p.left + ((p.w - m.w) / 2);
		m.top = p.top - m.h ;
	} else if(pos == 1) {
		m.left = p.left + (p.w - m.w);
		m.top = p.top - m.h;
	} else if(pos == 11) {
		m.left = p.left;
		m.top = p.top - m.h;
	} else if(pos == 2) {
		m.left = p.left + p.w;
		m.top = p.top;
	} else if(pos == 3) {
		m.left = p.left + p.w;
		m.top = p.top + ((p.h - m.h) / 2);
	} else if(pos == 4) {
		m.left = p.left + p.w;
		m.top = p.top + (p.h - m.h);
	} else if(pos == 5) {
		m.left = p.left + (p.w - m.w);
		m.top = p.top + p.h;
	} else if(pos == 6) {
		m.left = p.left + ((p.w - m.w) / 2);
		m.top = p.top + p.h;
	} else if(pos == 7) {
		m.left = p.left;
		m.top = p.top + p.h;
	} else if(pos == 8) {
		m.left = p.left - m.w;
		m.top = p.top + (p.h - m.h);
	} else if(pos == 9) {
		m.left = p.left - m.w;
		m.top = p.top + ((p.h - m.h) / 2);
	} else if(pos == 10) {
		m.left = p.left - m.w;
		m.top = p.top;
	} else if(pos == -12) {
		m.left = p.left + ((p.w - m.w) / 2);
		m.top = p.top;
	} else if(pos == -1) {
		m.left = p.left + (p.w - m.w);
		m.top = p.top;
	} else if(pos == -3) {
		m.left = p.left + p.w - m.w;
		m.top = p.top + ((p.h - m.h) / 2);
	} else if(pos == -5) {
		m.left = p.left + (p.w - m.w);
		m.top = p.top + p.h - m.h;
	} else if(pos == -6) {
		m.left = p.left + ((p.w - m.w) / 2);
		m.top = p.top + p.h - m.h;
	} else if(pos == -7) {
		m.left = p.left;
		m.top = p.top + p.h - m.h;
	} else if(pos == -9) {
		m.left = p.left;
		m.top = p.top + ((p.h - m.h) / 2);
	} else if(pos == -11) {
		m.left = p.left;
		m.top = p.top - m.h + m.h;
	} else if(pos == 0) {
		m.left = p.left + ((p.w - m.w) / 2);
		m.top = p.top + ((p.h - m.h) / 2);
	}
	jfloat.css({left: m.left + offset.left, top: m.top + offset.top});
};

// 菜单定位
/*
         11        12     1
        --------------------
     10 |                  | 2
        |                  |
      9 |        0         | 3
        |                  |
      8 |                  | 4
        --------------------
         7        6       5

	弹出菜单：
	$(this).xn_menu($('#menuid'), 6);
*/
$.fn.xn_menu = function(jmenu, pos, option) {
	// 生成一个箭头放到菜单的周围
	var jthis = $(this);
	var pos = pos || 6;
	var offset = {};
	var option = option || {hidearrow: 0};
	var jparent = jmenu.offsetParent();
	if(!jmenu.jarrow && !option.hidearrow) jmenu.jarrow = $('<div class="arrow arrow-up" style="display: none;"><div class="arrow-box"></div></div>').insertAfter(jthis);
	if(!option.hidearrow) {
		if(pos == 2 || pos == 3 || pos == 4) {
			jmenu.jarrow.addClass('arrow-left');
			offset.left = 7;
		} else if(pos == 5 || pos == 6 || pos == 7) {
			jmenu.jarrow.addClass('arrow-up');
			offset.top = 7;
		} else if(pos == 8 || pos == 9 || pos == 10) {
			jmenu.jarrow.addClass('arrow-right');
			offset.left = -7;
		} else if(pos == 11 || pos == 12 || pos == 1) {
			jmenu.jarrow.addClass('arrow-down');
			offset.top = -7;
		}
	}
	var arr_pos_map = {2: 10, 3: 9, 4: 8, 5: 1, 6: 12, 7: 11, 8: 4, 8: 3, 10: 2, 11: 7, 12: 6, 1: 5};
	var arr_offset_map = {
		2: {left: -1, top: 10},
		3: {left: -1, top: 0},
		4: {left: -1, top: -10},
		5: {left: -10, top: -1},
		6: {left: 0, top: -1},
		7: {left: 10, top: -1},
		8: {left: 1, top: -10},
		9: {left: 1, top: 0},
		10: {left: 1, top: 10},
		11: {left: 10, top: 1},
		12: {left: 0, top: 1},
		1: {left: -10, top: 1},
	};
	jthis.xn_position(jmenu, pos, offset);
	jmenu.toggle();
	
	// arrow
	var mpos = arr_pos_map[pos];
	if(!option.hidearrow) jmenu.xn_position(jmenu.jarrow, mpos, arr_offset_map[mpos]);
	if(!option.hidearrow) jmenu.jarrow.toggle();
	var menu_hide = function(e) {
		if(jmenu.is(":hidden")) return;
		jmenu.toggle();
		if(!option.hidearrow) jmenu.jarrow.hide();
		$('body').off('click', menu_hide);
	};
	
	$('body').off('click', menu_hide).on('click', menu_hide);
};


$.fn.xn_dropdown = function() {
	return this.each(function() {
		var jthis = $(this);
		var jtoggler = jthis.find('.dropdown-toggle');
		var jdropmenu = jthis.find('.dropdown-menu');
		var pos = jthis.data('pos') || 5;
		var hidearrow = !!jthis.data('hidearrow');
		jtoggler.on('click', function() {
			jtoggler.xn_menu(jdropmenu, pos, {hidearrow: hidearrow});
			return false;
		});
	});
};

$.fn.xn_toggle = function() {
	return this.each(function() {
		var jthis = $(this);
		var jtarget = $(jthis.data('target'));
		var target_hide = function(e) {
			if(jtarget.is(":hidden")) return;
			jtarget.slideToggle('fast');
			$('body').off('click', target_hide);
		};
		jthis.on('click', function() {
			jtarget.slideToggle('fast');
			$('body').off('click', target_hide).on('click', target_hide);
			return false;
		});
	});
};


