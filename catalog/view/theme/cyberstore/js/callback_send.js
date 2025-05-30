(function(t,e){"object"==typeof exports?module.exports=e():"function"==typeof define&&define.amd?define(e):t.Spinner=e()})(this,function(){"use strict";function t(t,e){var i,n=document.createElement(t||"div");for(i in e)n[i]=e[i];return n}function e(t){for(var e=1,i=arguments.length;i>e;e++)t.appendChild(arguments[e]);return t}function i(t,e,i,n){var o=["opacity",e,~~(100*t),i,n].join("-"),r=.01+100*(i/n),a=Math.max(1-(1-t)/e*(100-r),t),s=l.substring(0,l.indexOf("Animation")).toLowerCase(),u=s&&"-"+s+"-"||"";return f[o]||(c.insertRule("@"+u+"keyframes "+o+"{"+"0%{opacity:"+a+"}"+r+"%{opacity:"+t+"}"+(r+.01)+"%{opacity:1}"+(r+e)%100+"%{opacity:"+t+"}"+"100%{opacity:"+a+"}"+"}",c.cssRules.length),f[o]=1),o}function n(t,e){var i,n,o=t.style;if(void 0!==o[e])return e;for(e=e.charAt(0).toUpperCase()+e.slice(1),n=0;d.length>n;n++)if(i=d[n]+e,void 0!==o[i])return i}function o(t,e){for(var i in e)t.style[n(t,i)||i]=e[i];return t}function r(t){for(var e=1;arguments.length>e;e++){var i=arguments[e];for(var n in i)void 0===t[n]&&(t[n]=i[n])}return t}function a(t){for(var e={x:t.offsetLeft,y:t.offsetTop};t=t.offsetParent;)e.x+=t.offsetLeft,e.y+=t.offsetTop;return e}function s(t){return this===void 0?new s(t):(this.opts=r(t||{},s.defaults,p),void 0)}function u(){function i(e,i){return t("<"+e+' xmlns="urn:schemas-microsoft.com:vml" class="spin-vml">',i)}c.addRule(".spin-vml","behavior:url(#default#VML)"),s.prototype.lines=function(t,n){function r(){return o(i("group",{coordsize:l+" "+l,coordorigin:-u+" "+-u}),{width:l,height:l})}function a(t,a,s){e(f,e(o(r(),{rotation:360/n.lines*t+"deg",left:~~a}),e(o(i("roundrect",{arcsize:n.corners}),{width:u,height:n.width,left:n.radius,top:-n.width>>1,filter:s}),i("fill",{color:n.color,opacity:n.opacity}),i("stroke",{opacity:0}))))}var s,u=n.length+n.width,l=2*u,d=2*-(n.width+n.length)+"px",f=o(r(),{position:"absolute",top:d,left:d});if(n.shadow)for(s=1;n.lines>=s;s++)a(s,-2,"progid:DXImageTransform.Microsoft.Blur(pixelradius=2,makeshadow=1,shadowopacity=.3)");for(s=1;n.lines>=s;s++)a(s);return e(t,f)},s.prototype.opacity=function(t,e,i,n){var o=t.firstChild;n=n.shadow&&n.lines||0,o&&o.childNodes.length>e+n&&(o=o.childNodes[e+n],o=o&&o.firstChild,o=o&&o.firstChild,o&&(o.opacity=i))}}var l,d=["webkit","Moz","ms","O"],f={},c=function(){var i=t("style",{type:"text/css"});return e(document.getElementsByTagName("head")[0],i),i.sheet||i.styleSheet}(),p={lines:12,length:7,width:5,radius:10,rotate:0,corners:1,color:"#000",direction:1,speed:1,trail:100,opacity:.25,fps:20,zIndex:2e9,className:"spinner",top:"auto",left:"auto",position:"relative"};s.defaults={},r(s.prototype,{spin:function(e){this.stop();var i,n,r=this,s=r.opts,u=r.el=o(t(0,{className:s.className}),{position:s.position,width:0,zIndex:s.zIndex}),d=s.radius+s.length+s.width;if(e&&(e.insertBefore(u,e.firstChild||null),n=a(e),i=a(u),o(u,{left:("auto"==s.left?n.x-i.x+(e.offsetWidth>>1):parseInt(s.left,10)+d)+"px",top:("auto"==s.top?n.y-i.y+(e.offsetHeight>>1):parseInt(s.top,10)+d)+"px"})),u.setAttribute("role","progressbar"),r.lines(u,r.opts),!l){var f,c=0,p=(s.lines-1)*(1-s.direction)/2,h=s.fps,m=h/s.speed,g=(1-s.opacity)/(m*s.trail/100),v=m/s.lines;(function y(){c++;for(var t=0;s.lines>t;t++)f=Math.max(1-(c+(s.lines-t)*v)%m*g,s.opacity),r.opacity(u,t*s.direction+p,f,s);r.timeout=r.el&&setTimeout(y,~~(1e3/h))})()}return r},stop:function(){var t=this.el;return t&&(clearTimeout(this.timeout),t.parentNode&&t.parentNode.removeChild(t),this.el=void 0),this},lines:function(n,r){function a(e,i){return o(t(),{position:"absolute",width:r.length+r.width+"px",height:r.width+"px",background:e,boxShadow:i,transformOrigin:"left",transform:"rotate("+~~(360/r.lines*u+r.rotate)+"deg) translate("+r.radius+"px"+",0)",borderRadius:(r.corners*r.width>>1)+"px"})}for(var s,u=0,d=(r.lines-1)*(1-r.direction)/2;r.lines>u;u++)s=o(t(),{position:"absolute",top:1+~(r.width/2)+"px",transform:r.hwaccel?"translate3d(0,0,0)":"",opacity:r.opacity,animation:l&&i(r.opacity,r.trail,d+u*r.direction,r.lines)+" "+1/r.speed+"s linear infinite"}),r.shadow&&e(s,o(a("#000","0 0 4px #000"),{top:"2px"})),e(n,e(s,a(r.color,"0 0 1px rgba(0,0,0,.1)")));return n},opacity:function(t,e,i){t.childNodes.length>e&&(t.childNodes[e].style.opacity=i)}});var h=o(t("group"),{behavior:"url(#default#VML)"});return!n(h,"transform")&&h.adj?u():l=n(h,"animation"),s});

(function(t,e){"object"==typeof exports?module.exports=e():"function"==typeof define&&define.amd?define(["spin"],e):t.Ladda=e(t.Spinner)})(this,function(t){"use strict";function e(t){if(t===void 0)return console.warn("Ladda button target must be defined."),void 0;t.querySelector(".ladda-label")||(t.innerHTML='<span class="ladda-label">'+t.innerHTML+"</span>");var e,n=document.createElement("span");n.className="ladda-spinner",t.appendChild(n);var r,a={start:function(){return e||(e=o(t)),t.setAttribute("disabled",""),t.setAttribute("data-loading",""),clearTimeout(r),e.spin(n),this.setProgress(0),this},startAfter:function(t){return clearTimeout(r),r=setTimeout(function(){a.start()},t),this},stop:function(){return t.removeAttribute("disabled"),t.removeAttribute("data-loading"),clearTimeout(r),e&&(r=setTimeout(function(){e.stop()},1e3)),this},toggle:function(){return this.isLoading()?this.stop():this.start(),this},setProgress:function(e){e=Math.max(Math.min(e,1),0);var n=t.querySelector(".ladda-progress");0===e&&n&&n.parentNode?n.parentNode.removeChild(n):(n||(n=document.createElement("div"),n.className="ladda-progress",t.appendChild(n)),n.style.width=(e||0)*t.offsetWidth+45+"px")},enable:function(){return this.stop(),this},disable:function(){return this.stop(),t.setAttribute("disabled",""),this},isLoading:function(){return t.hasAttribute("data-loading")}};return u.push(a),a}function n(t,e){for(;t.parentNode&&t.tagName!==e;)t=t.parentNode;return e===t.tagName?t:void 0}function r(t){for(var e=["input","textarea"],n=[],r=0;e.length>r;r++)for(var a=t.getElementsByTagName(e[r]),i=0;a.length>i;i++)a[i].hasAttribute("required")&&n.push(a[i]);return n}function a(t,a){a=a||{};var i=[];"string"==typeof t?i=s(document.querySelectorAll(t)):"object"==typeof t&&"string"==typeof t.nodeName&&(i=[t]);for(var o=0,u=i.length;u>o;o++)(function(){var t=i[o];if("function"==typeof t.addEventListener){var s=e(t),u=-1;t.addEventListener("click",function(){var e=!0,i=n(t,"FORM");if(i!==void 0)for(var o=r(i),d=0;o.length>d;d++)""===o[d].value.replace(/^\s+|\s+$/g,"")&&(e=!1);e&&(s.startAfter(1),"number"==typeof a.timeout&&(clearTimeout(u),u=setTimeout(s.stop,a.timeout)),"function"==typeof a.callback&&a.callback.apply(null,[s]))},!1)}})()}function i(){for(var t=0,e=u.length;e>t;t++)u[t].stop()}function o(e){var n,r=e.offsetHeight;0===r&&(r=parseFloat(window.getComputedStyle(e).height)),r>32&&(r*=.8),e.hasAttribute("data-spinner-size")&&(r=parseInt(e.getAttribute("data-spinner-size"),10)),e.hasAttribute("data-spinner-color")&&(n=e.getAttribute("data-spinner-color"));var a=12,i=.2*r,o=.6*i,s=7>i?2:3;return new t({color:n||"#fff",lines:a,radius:i,length:o,width:s,zIndex:"auto",top:"auto",left:"auto",className:""})}function s(t){for(var e=[],n=0;t.length>n;n++)e.push(t[n]);return e}var u=[];return{bind:a,create:e,stopAll:i}});

(function(t,e){if(void 0===e)return console.error("jQuery required for Ladda.jQuery");var i=[];e=e.extend(e,{ladda:function(e){"stopAll"===e&&t.stopAll()}}),e.fn=e.extend(e.fn,{ladda:function(n){var r=i.slice.call(arguments,1);return"bind"===n?(r.unshift(e(this).selector),t.bind.apply(t,r)):e(this).each(function(){var i,o=e(this);void 0===n?o.data("ladda",t.create(this)):(i=o.data("ladda"),i[n].apply(i,r))}),this}})})(this.Ladda,this.jQuery);
function sendCallback() {
progress = 0,
laddaLoad = $('.ladda-button').ladda();
laddaLoad.ladda('start');
laddaLoad.ladda('setProgress', 1);
var success = 'false';
		$('#callback_url').val(window.location.href);
		$.ajax({
			url: 'index.php?route=extension/module/cyber_callback',
			type: 'post',
			data: $('#callback_data').serialize() + '&action=send',
			dataType: 'json',
			success: function(json) {
				laddaLoad.ladda('stop');
				$('.alert').remove();
				$('#contact-name').removeClass('error_input');
				$('#contact-phone').removeClass('error_input');
				$('#contact-comment').removeClass('error_input');
			    $('#contact-email').removeClass('error_input');
				if (json['warning']) {
					if (json['warning']['name']) {
						$('#contact-name').attr('placeholder',json['warning']['name']);
						$('#contact-name').addClass('error_input');
					}
					if (json['warning']['phone']) {
						$('#contact-phone').attr('placeholder',json['warning']['phone']);
						$('#contact-phone').addClass('error_input');

					}
					if (json['warning']['comment_buyer']) {
						$('#contact-comment').attr('placeholder',json['warning']['comment_buyer']);
						$('#contact-comment').addClass('error_input');
					}
					if (json['warning']['email_error']) {
						$('#contact-email').attr('placeholder',json['warning']['email_error']);
						$('#contact-email').addClass('error_input');
					}
					if (json['warning']['error_agree']) {
						$('.error_agree').append('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['warning']['error_agree'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}
					$('.popup-center').animate({ scrollTop: 0 }, 'slow');


				}

				if (json['success']){
					$('#modal-callback').modal('hide');
					html  = '<div id="modal-success-callback" class="modal fade">';
					html += '  <div class="modal-dialog">';
					html += '    <div class="modal-content cs-modal-success">';
					html += '      <div class="modal-body"><img class="success-icon" alt="success-icon" src="catalog/view/theme/cyberstore/image/success-icon.svg"> <div class="text-modal-block"> ' + json['success'] + '</div><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button></div>';
					html += '    </div>';
					html += '  </div>';
					html += '</div>';

					$('body').append(html);
					setTimeout(function () {
						$('#modal-success-callback').modal('show');
					}, 700);
				}

			}
		});
}

