!function(t,e,i){var o,n,r,h,s,a,l,d,c,g,u,p,f,m,w,v,x,y,b,T,C,H,k,E,W,L,S,M,R,F,I,K,P,B={html:!1,photo:!1,iframe:!1,inline:!1,transition:"elastic",speed:300,fadeOut:300,width:!1,initialWidth:"600",innerWidth:!1,maxWidth:!1,height:!1,initialHeight:"450",innerHeight:!1,maxHeight:!1,scalePhotos:!0,scrolling:!0,opacity:.9,preloading:!0,className:!1,overlayClose:!0,escKey:!0,arrowKey:!0,top:!1,bottom:!1,left:!1,right:!1,fixed:!1,data:void 0,closeButton:!0,fastIframe:!0,open:!1,reposition:!0,loop:!0,slideshow:!1,slideshowAuto:!0,slideshowSpeed:2500,slideshowStart:"start slideshow",slideshowStop:"stop slideshow",photoRegex:/\.(gif|png|jp(e|g|eg)|bmp|ico|webp|jxr|svg)((#|\?).*)?$/i,retinaImage:!1,retinaUrl:!1,retinaSuffix:"@2x.$1",current:"image {current} of {total}",previous:"previous",next:"next",close:"close",xhrError:"This content failed to load.",imgError:"This image failed to load.",returnFocus:!0,trapFocus:!0,onOpen:!1,onLoad:!1,onComplete:!1,onCleanup:!1,onClosed:!1,rel:function(){return this.rel},href:function(){return t(this).attr("href")},title:function(){return this.title}},O="colorbox",_="cbox",D=_+"Element",j=_+"_open",A=_+"_load",N=_+"_complete",z=_+"_cleanup",U=_+"_closed",$=_+"_purge",q=t("<a/>"),G="div",Q=0,J={};function V(i,o,n){var r=e.createElement(i);return o&&(r.id=_+o),n&&(r.style.cssText=n),t(r)}function X(){return i.innerHeight?i.innerHeight:t(i).height()}function Y(e,i){i!==Object(i)&&(i={}),this.cache={},this.el=e,this.value=function(e){var o;return void 0===this.cache[e]&&(void 0!==(o=t(this.el).attr("data-cbox-"+e))?this.cache[e]=o:void 0!==i[e]?this.cache[e]=i[e]:void 0!==B[e]&&(this.cache[e]=B[e])),this.cache[e]},this.get=function(e){var i=this.value(e);return t.isFunction(i)?i.call(this.el,this):i}}function Z(t){var e=c.length,i=(L+t)%e;return i<0?e+i:i}function tt(t,e){return Math.round((/%/.test(t)?("x"===e?g.width():X())/100:1)*parseInt(t,10))}function et(t,e){return t.get("photo")||t.get("photoRegex").test(e)}function it(t,e){return t.get("retinaUrl")&&i.devicePixelRatio>1?e.replace(t.get("photoRegex"),t.get("retinaSuffix")):e}function ot(t){"contains"in n[0]&&!n[0].contains(t.target)&&t.target!==o[0]&&(t.stopPropagation(),n.focus())}function nt(t){nt.str!==t&&(n.add(o).removeClass(nt.str).addClass(t),nt.str=t)}function rt(i){t(e).trigger(i),q.triggerHandler(i)}var ht=function(){var t,e,i=_+"Slideshow_",o="click."+_;function r(){clearTimeout(e)}function h(){(C.get("loop")||c[L+1])&&(r(),e=setTimeout(K.next,C.get("slideshowSpeed")))}function s(){v.html(C.get("slideshowStop")).unbind(o).one(o,a),q.bind(N,h).bind(A,r),n.removeClass(i+"off").addClass(i+"on")}function a(){r(),q.unbind(N,h).unbind(A,r),v.html(C.get("slideshowStart")).unbind(o).one(o,function(){K.next(),s()}),n.removeClass(i+"on").addClass(i+"off")}function l(){t=!1,v.hide(),r(),q.unbind(N,h).unbind(A,r),n.removeClass(i+"off "+i+"on")}return function(){t?C.get("slideshow")||(q.unbind(z,l),l()):C.get("slideshow")&&c[1]&&(t=!0,q.one(z,l),C.get("slideshowAuto")?s():a(),v.show())}}();function st(r){var g;F||(g=t(r).data("colorbox"),C=new Y(r,g),rel=C.get("rel"),L=0,rel&&"nofollow"!==rel?(c=t("."+D).filter(function(){return new Y(this,t.data(this,O)).get("rel")===rel}),-1===(L=c.index(C.el))&&(c=c.add(C.el),L=c.length-1)):c=t(C.el),M||(M=R=!0,nt(C.get("className")),n.css({visibility:"hidden",display:"block"}),u=V(G,"LoadedContent","width:0; height:0; overflow:hidden; visibility:hidden"),h.css({width:"",height:""}).append(u),H=s.height()+d.height()+h.outerHeight(!0)-h.height(),k=a.width()+l.width()+h.outerWidth(!0)-h.width(),E=u.outerHeight(!0),W=u.outerWidth(!0),C.w=tt(C.get("initialWidth"),"x"),C.h=tt(C.get("initialHeight"),"y"),u.css({width:"",height:C.h}),K.position(),rt(j),C.get("onOpen"),T.add(m).hide(),n.focus(),C.get("trapFocus")&&e.addEventListener&&(e.addEventListener("focus",ot,!0),q.one(U,function(){e.removeEventListener("focus",ot,!0)})),C.get("returnFocus")&&q.one(U,function(){t(C.el).focus()})),o.css({opacity:parseFloat(C.get("opacity")),cursor:C.get("overlayClose")?"pointer":"auto",visibility:"visible"}).show(),C.get("closeButton")?b.html(C.get("close")).appendTo(h):b.appendTo("<div/>"),function(){var o,n,r,h=K.prep,s=++Q;R=!0,S=!1,rt($),rt(A),C.get("onLoad"),C.h=C.get("height")?tt(C.get("height"),"y")-E-H:C.get("innerHeight")&&tt(C.get("innerHeight"),"y"),C.w=C.get("width")?tt(C.get("width"),"x")-W-k:C.get("innerWidth")&&tt(C.get("innerWidth"),"x"),C.mw=C.w,C.mh=C.h,C.get("maxWidth")&&(C.mw=tt(C.get("maxWidth"),"x")-W-k,C.mw=C.w&&C.w<C.mw?C.w:C.mw);C.get("maxHeight")&&(C.mh=tt(C.get("maxHeight"),"y")-E-H,C.mh=C.h&&C.h<C.mh?C.h:C.mh);o=C.get("href"),I=setTimeout(function(){f.show()},100),C.get("inline")?(r=V(G).hide().insertBefore(t(o)[0]),q.one($,function(){r.replaceWith(u.children())}),h(t(o))):C.get("iframe")?h(" "):C.get("html")?h(C.get("html")):et(C,o)?(o=it(C,o),S=e.createElement("img"),t(S).addClass(_+"Photo").bind("error",function(){h(V(G,"Error").html(C.get("imgError")))}).one("load",function(){var e;s===Q&&(t.each(["alt","longdesc","aria-describedby"],function(e,i){var o=t(C.el).attr(i)||t(C.el).attr("data-"+i);o&&S.setAttribute(i,o)}),C.get("retinaImage")&&i.devicePixelRatio>1&&(S.height=S.height/i.devicePixelRatio,S.width=S.width/i.devicePixelRatio),C.get("scalePhotos")&&(n=function(){S.height-=S.height*e,S.width-=S.width*e},C.mw&&S.width>C.mw&&(e=(S.width-C.mw)/S.width,n()),C.mh&&S.height>C.mh&&(e=(S.height-C.mh)/S.height,n())),C.h&&(S.style.marginTop=Math.max(C.mh-S.height,0)/2+"px"),c[1]&&(C.get("loop")||c[L+1])&&(S.style.cursor="pointer",S.onclick=function(){K.next()}),S.style.width=S.width+"px",S.style.height=S.height+"px",setTimeout(function(){h(S)},1))}),setTimeout(function(){S.src=o},1)):o&&p.load(o,C.get("data"),function(e,i){s===Q&&h("error"===i?V(G,"Error").html(C.get("xhrError")):t(this).contents())})}())}function at(){!n&&e.body&&(P=!1,g=t(i),n=V(G).attr({id:O,class:!1===t.support.opacity?_+"IE":"",role:"dialog",tabindex:"-1"}).hide(),o=V(G,"Overlay").hide(),f=t([V(G,"LoadingOverlay")[0],V(G,"LoadingGraphic")[0]]),r=V(G,"Wrapper"),h=V(G,"Content").append(m=V(G,"Title"),w=V(G,"Current"),y=t('<button type="button"/>').attr({id:_+"Previous"}),x=t('<button type="button"/>').attr({id:_+"Next"}),v=V("button","Slideshow"),f),b=t('<button type="button"/>').attr({id:_+"Close"}),r.append(V(G).append(V(G,"TopLeft"),s=V(G,"TopCenter"),V(G,"TopRight")),V(G,!1,"clear:left").append(a=V(G,"MiddleLeft"),h,l=V(G,"MiddleRight")),V(G,!1,"clear:left").append(V(G,"BottomLeft"),d=V(G,"BottomCenter"),V(G,"BottomRight"))).find("div div").css({float:"left"}),p=V(G,!1,"position:absolute; width:9999px; visibility:hidden; display:none; max-width:none;"),T=x.add(y).add(w).add(v),t(e.body).append(o,n.append(r,p)))}t.colorbox||(t(at),(K=t.fn[O]=t[O]=function(i,r){var h=this;if(i=i||{},t.isFunction(h))h=t("<a/>"),i.open=!0;else if(!h[0])return h;return h[0]?(at(),function(){function i(t){t.which>1||t.shiftKey||t.altKey||t.metaKey||t.ctrlKey||(t.preventDefault(),st(this))}return!!n&&(P||(P=!0,x.click(function(){K.next()}),y.click(function(){K.prev()}),b.click(function(){K.close()}),o.click(function(){C.get("overlayClose")&&K.close()}),t(e).bind("keydown."+_,function(t){var e=t.keyCode;M&&C.get("escKey")&&27===e&&(t.preventDefault(),K.close()),M&&C.get("arrowKey")&&c[1]&&!t.altKey&&(37===e?(t.preventDefault(),y.click()):39===e&&(t.preventDefault(),x.click()))}),t.isFunction(t.fn.on)?t(e).on("click."+_,"."+D,i):t("."+D).live("click."+_,i)),!0)}()&&(r&&(i.onComplete=r),h.each(function(){var e=t.data(this,O)||{};t.data(this,O,t.extend(e,i))}).addClass(D),new Y(h[0],i).get("open")&&st(h[0])),h):h}).position=function(e,i){var o,c,u,p=0,f=0,m=n.offset();function w(){s[0].style.width=d[0].style.width=h[0].style.width=parseInt(n[0].style.width,10)-k+"px",h[0].style.height=a[0].style.height=l[0].style.height=parseInt(n[0].style.height,10)-H+"px"}if(g.unbind("resize."+_),n.css({top:-9e4,left:-9e4}),c=g.scrollTop(),u=g.scrollLeft(),C.get("fixed")?(m.top-=c,m.left-=u,n.css({position:"fixed"})):(p=c,f=u,n.css({position:"absolute"})),!1!==C.get("right")?f+=Math.max(g.width()-C.w-W-k-tt(C.get("right"),"x"),0):!1!==C.get("left")?f+=tt(C.get("left"),"x"):f+=Math.round(Math.max(g.width()-C.w-W-k,0)/2),!1!==C.get("bottom")?p+=Math.max(X()-C.h-E-H-tt(C.get("bottom"),"y"),0):!1!==C.get("top")?p+=tt(C.get("top"),"y"):p+=Math.round(Math.max(X()-C.h-E-H,0)/2),n.css({top:m.top,left:m.left,visibility:"visible"}),r[0].style.width=r[0].style.height="9999px",o={width:C.w+W+k,height:C.h+E+H,top:p,left:f},e){var v=0;t.each(o,function(t){o[t]===J[t]||(v=e)}),e=v}J=o,e||n.css(o),n.dequeue().animate(o,{duration:e||0,complete:function(){w(),R=!1,r[0].style.width=C.w+W+k+"px",r[0].style.height=C.h+E+H+"px",C.get("reposition")&&setTimeout(function(){g.bind("resize."+_,K.position)},1),i&&i()},step:w})},K.resize=function(t){var e;M&&((t=t||{}).width&&(C.w=tt(t.width,"x")-W-k),t.innerWidth&&(C.w=tt(t.innerWidth,"x")),u.css({width:C.w}),t.height&&(C.h=tt(t.height,"y")-E-H),t.innerHeight&&(C.h=tt(t.innerHeight,"y")),t.innerHeight||t.height||(e=u.scrollTop(),u.css({height:"auto"}),C.h=u.height()),u.css({height:C.h}),e&&u.scrollTop(e),K.position("none"===C.get("transition")?0:C.get("speed")))},K.prep=function(i){if(M){var o,r="none"===C.get("transition")?0:C.get("speed");u.remove(),(u=V(G,"LoadedContent").append(i)).hide().appendTo(p.show()).css({width:(C.w=C.w||u.width(),C.w=C.mw&&C.mw<C.w?C.mw:C.w,C.w),overflow:C.get("scrolling")?"auto":"hidden"}).css({height:(C.h=C.h||u.height(),C.h=C.mh&&C.mh<C.h?C.mh:C.h,C.h)}).prependTo(h),p.hide(),t(S).css({float:"none"}),nt(C.get("className")),o=function(){var i,o,h=c.length;function s(){!1===t.support.opacity&&n[0].style.removeAttribute("filter")}M&&(o=function(){clearTimeout(I),f.hide(),rt(N),C.get("onComplete")},m.html(C.get("title")).show(),u.show(),h>1?("string"==typeof C.get("current")&&w.html(C.get("current").replace("{current}",L+1).replace("{total}",h)).show(),x[C.get("loop")||L<h-1?"show":"hide"]().html(C.get("next")),y[C.get("loop")||L?"show":"hide"]().html(C.get("previous")),ht(),C.get("preloading")&&t.each([Z(-1),Z(1)],function(){var i=c[this],o=new Y(i,t.data(i,O)),n=o.get("href");n&&et(o,n)&&(n=it(o,n),e.createElement("img").src=n)})):T.hide(),C.get("iframe")?("frameBorder"in(i=e.createElement("iframe"))&&(i.frameBorder=0),"allowTransparency"in i&&(i.allowTransparency="true"),C.get("scrolling")||(i.scrolling="no"),t(i).attr({src:C.get("href"),name:(new Date).getTime(),class:_+"Iframe",allowFullScreen:!0}).one("load",o).appendTo(u),q.one($,function(){i.src="//about:blank"}),C.get("fastIframe")&&t(i).trigger("load")):o(),"fade"===C.get("transition")?n.fadeTo(r,1,s):s())},"fade"===C.get("transition")?n.fadeTo(r,0,function(){K.position(0,o)}):K.position(r,o)}},K.next=function(){!R&&c[1]&&(C.get("loop")||c[L+1])&&(L=Z(1),st(c[L]))},K.prev=function(){!R&&c[1]&&(C.get("loop")||L)&&(L=Z(-1),st(c[L]))},K.close=function(){M&&!F&&(F=!0,M=!1,rt(z),C.get("onCleanup"),g.unbind("."+_),o.fadeTo(C.get("fadeOut")||0,0),n.stop().fadeTo(C.get("fadeOut")||0,0,function(){n.add(o).css({opacity:1,cursor:"auto"}).hide(),rt($),u.remove(),setTimeout(function(){F=!1,rt(U),C.get("onClosed")},1)}))},K.remove=function(){n&&(n.stop(),t.colorbox.close(),n.stop().remove(),o.remove(),F=!1,n=null,t("."+D).removeData(O).removeClass(D),t(e).unbind("click."+_))},K.element=function(){return t(C.el)},K.settings=B)}(jQuery,document,window);