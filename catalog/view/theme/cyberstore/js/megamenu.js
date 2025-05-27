/*!
 * Masonry PACKAGED v4.2.2
 * Cascading grid layout library
 * https://masonry.desandro.com
 * MIT License
 * by David DeSandro
 */

!function(t,e){"function"==typeof define&&define.amd?define("jquery-bridget/jquery-bridget",["jquery"],function(i){return e(t,i)}):"object"==typeof module&&module.exports?module.exports=e(t,require("jquery")):t.jQueryBridget=e(t,t.jQuery)}(window,function(t,e){"use strict";function i(i,r,a){function h(t,e,n){var o,r="$()."+i+'("'+e+'")';return t.each(function(t,h){var u=a.data(h,i);if(!u)return void s(i+" not initialized. Cannot call methods, i.e. "+r);var d=u[e];if(!d||"_"==e.charAt(0))return void s(r+" is not a valid method");var l=d.apply(u,n);o=void 0===o?l:o}),void 0!==o?o:t}function u(t,e){t.each(function(t,n){var o=a.data(n,i);o?(o.option(e),o._init()):(o=new r(n,e),a.data(n,i,o))})}a=a||e||t.jQuery,a&&(r.prototype.option||(r.prototype.option=function(t){a.isPlainObject(t)&&(this.options=a.extend(!0,this.options,t))}),a.fn[i]=function(t){if("string"==typeof t){var e=o.call(arguments,1);return h(this,t,e)}return u(this,t),this},n(a))}function n(t){!t||t&&t.bridget||(t.bridget=i)}var o=Array.prototype.slice,r=t.console,s="undefined"==typeof r?function(){}:function(t){r.error(t)};return n(e||t.jQuery),i}),function(t,e){"function"==typeof define&&define.amd?define("ev-emitter/ev-emitter",e):"object"==typeof module&&module.exports?module.exports=e():t.EvEmitter=e()}("undefined"!=typeof window?window:this,function(){function t(){}var e=t.prototype;return e.on=function(t,e){if(t&&e){var i=this._events=this._events||{},n=i[t]=i[t]||[];return-1==n.indexOf(e)&&n.push(e),this}},e.once=function(t,e){if(t&&e){this.on(t,e);var i=this._onceEvents=this._onceEvents||{},n=i[t]=i[t]||{};return n[e]=!0,this}},e.off=function(t,e){var i=this._events&&this._events[t];if(i&&i.length){var n=i.indexOf(e);return-1!=n&&i.splice(n,1),this}},e.emitEvent=function(t,e){var i=this._events&&this._events[t];if(i&&i.length){i=i.slice(0),e=e||[];for(var n=this._onceEvents&&this._onceEvents[t],o=0;o<i.length;o++){var r=i[o],s=n&&n[r];s&&(this.off(t,r),delete n[r]),r.apply(this,e)}return this}},e.allOff=function(){delete this._events,delete this._onceEvents},t}),function(t,e){"function"==typeof define&&define.amd?define("get-size/get-size",e):"object"==typeof module&&module.exports?module.exports=e():t.getSize=e()}(window,function(){"use strict";function t(t){var e=parseFloat(t),i=-1==t.indexOf("%")&&!isNaN(e);return i&&e}function e(){}function i(){for(var t={width:0,height:0,innerWidth:0,innerHeight:0,outerWidth:0,outerHeight:0},e=0;u>e;e++){var i=h[e];t[i]=0}return t}function n(t){var e=getComputedStyle(t);return e||a("Style returned "+e+". Are you running this code in a hidden iframe on Firefox? See https://bit.ly/getsizebug1"),e}function o(){if(!d){d=!0;var e=document.createElement("div");e.style.width="200px",e.style.padding="1px 2px 3px 4px",e.style.borderStyle="solid",e.style.borderWidth="1px 2px 3px 4px",e.style.boxSizing="border-box";var i=document.body||document.documentElement;i.appendChild(e);var o=n(e);s=200==Math.round(t(o.width)),r.isBoxSizeOuter=s,i.removeChild(e)}}function r(e){if(o(),"string"==typeof e&&(e=document.querySelector(e)),e&&"object"==typeof e&&e.nodeType){var r=n(e);if("none"==r.display)return i();var a={};a.width=e.offsetWidth,a.height=e.offsetHeight;for(var d=a.isBorderBox="border-box"==r.boxSizing,l=0;u>l;l++){var c=h[l],f=r[c],m=parseFloat(f);a[c]=isNaN(m)?0:m}var p=a.paddingLeft+a.paddingRight,g=a.paddingTop+a.paddingBottom,y=a.marginLeft+a.marginRight,v=a.marginTop+a.marginBottom,_=a.borderLeftWidth+a.borderRightWidth,z=a.borderTopWidth+a.borderBottomWidth,E=d&&s,b=t(r.width);b!==!1&&(a.width=b+(E?0:p+_));var x=t(r.height);return x!==!1&&(a.height=x+(E?0:g+z)),a.innerWidth=a.width-(p+_),a.innerHeight=a.height-(g+z),a.outerWidth=a.width+y,a.outerHeight=a.height+v,a}}var s,a="undefined"==typeof console?e:function(t){console.error(t)},h=["paddingLeft","paddingRight","paddingTop","paddingBottom","marginLeft","marginRight","marginTop","marginBottom","borderLeftWidth","borderRightWidth","borderTopWidth","borderBottomWidth"],u=h.length,d=!1;return r}),function(t,e){"use strict";"function"==typeof define&&define.amd?define("desandro-matches-selector/matches-selector",e):"object"==typeof module&&module.exports?module.exports=e():t.matchesSelector=e()}(window,function(){"use strict";var t=function(){var t=window.Element.prototype;if(t.matches)return"matches";if(t.matchesSelector)return"matchesSelector";for(var e=["webkit","moz","ms","o"],i=0;i<e.length;i++){var n=e[i],o=n+"MatchesSelector";if(t[o])return o}}();return function(e,i){return e[t](i)}}),function(t,e){"function"==typeof define&&define.amd?define("fizzy-ui-utils/utils",["desandro-matches-selector/matches-selector"],function(i){return e(t,i)}):"object"==typeof module&&module.exports?module.exports=e(t,require("desandro-matches-selector")):t.fizzyUIUtils=e(t,t.matchesSelector)}(window,function(t,e){var i={};i.extend=function(t,e){for(var i in e)t[i]=e[i];return t},i.modulo=function(t,e){return(t%e+e)%e};var n=Array.prototype.slice;i.makeArray=function(t){if(Array.isArray(t))return t;if(null===t||void 0===t)return[];var e="object"==typeof t&&"number"==typeof t.length;return e?n.call(t):[t]},i.removeFrom=function(t,e){var i=t.indexOf(e);-1!=i&&t.splice(i,1)},i.getParent=function(t,i){for(;t.parentNode&&t!=document.body;)if(t=t.parentNode,e(t,i))return t},i.getQueryElement=function(t){return"string"==typeof t?document.querySelector(t):t},i.handleEvent=function(t){var e="on"+t.type;this[e]&&this[e](t)},i.filterFindElements=function(t,n){t=i.makeArray(t);var o=[];return t.forEach(function(t){if(t instanceof HTMLElement){if(!n)return void o.push(t);e(t,n)&&o.push(t);for(var i=t.querySelectorAll(n),r=0;r<i.length;r++)o.push(i[r])}}),o},i.debounceMethod=function(t,e,i){i=i||100;var n=t.prototype[e],o=e+"Timeout";t.prototype[e]=function(){var t=this[o];clearTimeout(t);var e=arguments,r=this;this[o]=setTimeout(function(){n.apply(r,e),delete r[o]},i)}},i.docReady=function(t){var e=document.readyState;"complete"==e||"interactive"==e?setTimeout(t):document.addEventListener("DOMContentLoaded",t)},i.toDashed=function(t){return t.replace(/(.)([A-Z])/g,function(t,e,i){return e+"-"+i}).toLowerCase()};var o=t.console;return i.htmlInit=function(e,n){i.docReady(function(){var r=i.toDashed(n),s="data-"+r,a=document.querySelectorAll("["+s+"]"),h=document.querySelectorAll(".js-"+r),u=i.makeArray(a).concat(i.makeArray(h)),d=s+"-options",l=t.jQuery;u.forEach(function(t){var i,r=t.getAttribute(s)||t.getAttribute(d);try{i=r&&JSON.parse(r)}catch(a){return void(o&&o.error("Error parsing "+s+" on "+t.className+": "+a))}var h=new e(t,i);l&&l.data(t,n,h)})})},i}),function(t,e){"function"==typeof define&&define.amd?define("outlayer/item",["ev-emitter/ev-emitter","get-size/get-size"],e):"object"==typeof module&&module.exports?module.exports=e(require("ev-emitter"),require("get-size")):(t.Outlayer={},t.Outlayer.Item=e(t.EvEmitter,t.getSize))}(window,function(t,e){"use strict";function i(t){for(var e in t)return!1;return e=null,!0}function n(t,e){t&&(this.element=t,this.layout=e,this.position={x:0,y:0},this._create())}function o(t){return t.replace(/([A-Z])/g,function(t){return"-"+t.toLowerCase()})}var r=document.documentElement.style,s="string"==typeof r.transition?"transition":"WebkitTransition",a="string"==typeof r.transform?"transform":"WebkitTransform",h={WebkitTransition:"webkitTransitionEnd",transition:"transitionend"}[s],u={transform:a,transition:s,transitionDuration:s+"Duration",transitionProperty:s+"Property",transitionDelay:s+"Delay"},d=n.prototype=Object.create(t.prototype);d.constructor=n,d._create=function(){this._transn={ingProperties:{},clean:{},onEnd:{}},this.css({position:"absolute"})},d.handleEvent=function(t){var e="on"+t.type;this[e]&&this[e](t)},d.getSize=function(){this.size=e(this.element)},d.css=function(t){var e=this.element.style;for(var i in t){var n=u[i]||i;e[n]=t[i]}},d.getPosition=function(){var t=getComputedStyle(this.element),e=this.layout._getOption("originLeft"),i=this.layout._getOption("originTop"),n=t[e?"left":"right"],o=t[i?"top":"bottom"],r=parseFloat(n),s=parseFloat(o),a=this.layout.size;-1!=n.indexOf("%")&&(r=r/100*a.width),-1!=o.indexOf("%")&&(s=s/100*a.height),r=isNaN(r)?0:r,s=isNaN(s)?0:s,r-=e?a.paddingLeft:a.paddingRight,s-=i?a.paddingTop:a.paddingBottom,this.position.x=r,this.position.y=s},d.layoutPosition=function(){var t=this.layout.size,e={},i=this.layout._getOption("originLeft"),n=this.layout._getOption("originTop"),o=i?"paddingLeft":"paddingRight",r=i?"left":"right",s=i?"right":"left",a=this.position.x+t[o];e[r]=this.getXValue(a),e[s]="";var h=n?"paddingTop":"paddingBottom",u=n?"top":"bottom",d=n?"bottom":"top",l=this.position.y+t[h];e[u]=this.getYValue(l),e[d]="",this.css(e),this.emitEvent("layout",[this])},d.getXValue=function(t){var e=this.layout._getOption("horizontal");return this.layout.options.percentPosition&&!e?t/this.layout.size.width*100+"%":t+"px"},d.getYValue=function(t){var e=this.layout._getOption("horizontal");return this.layout.options.percentPosition&&e?t/this.layout.size.height*100+"%":t+"px"},d._transitionTo=function(t,e){this.getPosition();var i=this.position.x,n=this.position.y,o=t==this.position.x&&e==this.position.y;if(this.setPosition(t,e),o&&!this.isTransitioning)return void this.layoutPosition();var r=t-i,s=e-n,a={};a.transform=this.getTranslate(r,s),this.transition({to:a,onTransitionEnd:{transform:this.layoutPosition},isCleaning:!0})},d.getTranslate=function(t,e){var i=this.layout._getOption("originLeft"),n=this.layout._getOption("originTop");return t=i?t:-t,e=n?e:-e,"translate3d("+t+"px, "+e+"px, 0)"},d.goTo=function(t,e){this.setPosition(t,e),this.layoutPosition()},d.moveTo=d._transitionTo,d.setPosition=function(t,e){this.position.x=parseFloat(t),this.position.y=parseFloat(e)},d._nonTransition=function(t){this.css(t.to),t.isCleaning&&this._removeStyles(t.to);for(var e in t.onTransitionEnd)t.onTransitionEnd[e].call(this)},d.transition=function(t){if(!parseFloat(this.layout.options.transitionDuration))return void this._nonTransition(t);var e=this._transn;for(var i in t.onTransitionEnd)e.onEnd[i]=t.onTransitionEnd[i];for(i in t.to)e.ingProperties[i]=!0,t.isCleaning&&(e.clean[i]=!0);if(t.from){this.css(t.from);var n=this.element.offsetHeight;n=null}this.enableTransition(t.to),this.css(t.to),this.isTransitioning=!0};var l="opacity,"+o(a);d.enableTransition=function(){if(!this.isTransitioning){var t=this.layout.options.transitionDuration;t="number"==typeof t?t+"ms":t,this.css({transitionProperty:l,transitionDuration:t,transitionDelay:this.staggerDelay||0}),this.element.addEventListener(h,this,!1)}},d.onwebkitTransitionEnd=function(t){this.ontransitionend(t)},d.onotransitionend=function(t){this.ontransitionend(t)};var c={"-webkit-transform":"transform"};d.ontransitionend=function(t){if(t.target===this.element){var e=this._transn,n=c[t.propertyName]||t.propertyName;if(delete e.ingProperties[n],i(e.ingProperties)&&this.disableTransition(),n in e.clean&&(this.element.style[t.propertyName]="",delete e.clean[n]),n in e.onEnd){var o=e.onEnd[n];o.call(this),delete e.onEnd[n]}this.emitEvent("transitionEnd",[this])}},d.disableTransition=function(){this.removeTransitionStyles(),this.element.removeEventListener(h,this,!1),this.isTransitioning=!1},d._removeStyles=function(t){var e={};for(var i in t)e[i]="";this.css(e)};var f={transitionProperty:"",transitionDuration:"",transitionDelay:""};return d.removeTransitionStyles=function(){this.css(f)},d.stagger=function(t){t=isNaN(t)?0:t,this.staggerDelay=t+"ms"},d.removeElem=function(){this.element.parentNode.removeChild(this.element),this.css({display:""}),this.emitEvent("remove",[this])},d.remove=function(){return s&&parseFloat(this.layout.options.transitionDuration)?(this.once("transitionEnd",function(){this.removeElem()}),void this.hide()):void this.removeElem()},d.reveal=function(){delete this.isHidden,this.css({display:""});var t=this.layout.options,e={},i=this.getHideRevealTransitionEndProperty("visibleStyle");e[i]=this.onRevealTransitionEnd,this.transition({from:t.hiddenStyle,to:t.visibleStyle,isCleaning:!0,onTransitionEnd:e})},d.onRevealTransitionEnd=function(){this.isHidden||this.emitEvent("reveal")},d.getHideRevealTransitionEndProperty=function(t){var e=this.layout.options[t];if(e.opacity)return"opacity";for(var i in e)return i},d.hide=function(){this.isHidden=!0,this.css({display:""});var t=this.layout.options,e={},i=this.getHideRevealTransitionEndProperty("hiddenStyle");e[i]=this.onHideTransitionEnd,this.transition({from:t.visibleStyle,to:t.hiddenStyle,isCleaning:!0,onTransitionEnd:e})},d.onHideTransitionEnd=function(){this.isHidden&&(this.css({display:"none"}),this.emitEvent("hide"))},d.destroy=function(){this.css({position:"",left:"",right:"",top:"",bottom:"",transition:"",transform:""})},n}),function(t,e){"use strict";"function"==typeof define&&define.amd?define("outlayer/outlayer",["ev-emitter/ev-emitter","get-size/get-size","fizzy-ui-utils/utils","./item"],function(i,n,o,r){return e(t,i,n,o,r)}):"object"==typeof module&&module.exports?module.exports=e(t,require("ev-emitter"),require("get-size"),require("fizzy-ui-utils"),require("./item")):t.Outlayer=e(t,t.EvEmitter,t.getSize,t.fizzyUIUtils,t.Outlayer.Item)}(window,function(t,e,i,n,o){"use strict";function r(t,e){var i=n.getQueryElement(t);if(!i)return void(h&&h.error("Bad element for "+this.constructor.namespace+": "+(i||t)));this.element=i,u&&(this.$element=u(this.element)),this.options=n.extend({},this.constructor.defaults),this.option(e);var o=++l;this.element.outlayerGUID=o,c[o]=this,this._create();var r=this._getOption("initLayout");r&&this.layout()}function s(t){function e(){t.apply(this,arguments)}return e.prototype=Object.create(t.prototype),e.prototype.constructor=e,e}function a(t){if("number"==typeof t)return t;var e=t.match(/(^\d*\.?\d*)(\w*)/),i=e&&e[1],n=e&&e[2];if(!i.length)return 0;i=parseFloat(i);var o=m[n]||1;return i*o}var h=t.console,u=t.jQuery,d=function(){},l=0,c={};r.namespace="outlayer",r.Item=o,r.defaults={containerStyle:{position:"relative"},initLayout:!0,originLeft:!0,originTop:!0,resize:!0,resizeContainer:!0,transitionDuration:"0.4s",hiddenStyle:{opacity:0,transform:"scale(0.001)"},visibleStyle:{opacity:1,transform:"scale(1)"}};var f=r.prototype;n.extend(f,e.prototype),f.option=function(t){n.extend(this.options,t)},f._getOption=function(t){var e=this.constructor.compatOptions[t];return e&&void 0!==this.options[e]?this.options[e]:this.options[t]},r.compatOptions={initLayout:"isInitLayout",horizontal:"isHorizontal",layoutInstant:"isLayoutInstant",originLeft:"isOriginLeft",originTop:"isOriginTop",resize:"isResizeBound",resizeContainer:"isResizingContainer"},f._create=function(){this.reloadItems(),this.stamps=[],this.stamp(this.options.stamp),n.extend(this.element.style,this.options.containerStyle);var t=this._getOption("resize");t&&this.bindResize()},f.reloadItems=function(){this.items=this._itemize(this.element.children)},f._itemize=function(t){for(var e=this._filterFindItemElements(t),i=this.constructor.Item,n=[],o=0;o<e.length;o++){var r=e[o],s=new i(r,this);n.push(s)}return n},f._filterFindItemElements=function(t){return n.filterFindElements(t,this.options.itemSelector)},f.getItemElements=function(){return this.items.map(function(t){return t.element})},f.layout=function(){this._resetLayout(),this._manageStamps();var t=this._getOption("layoutInstant"),e=void 0!==t?t:!this._isLayoutInited;this.layoutItems(this.items,e),this._isLayoutInited=!0},f._init=f.layout,f._resetLayout=function(){this.getSize()},f.getSize=function(){this.size=i(this.element)},f._getMeasurement=function(t,e){var n,o=this.options[t];o?("string"==typeof o?n=this.element.querySelector(o):o instanceof HTMLElement&&(n=o),this[t]=n?i(n)[e]:o):this[t]=0},f.layoutItems=function(t,e){t=this._getItemsForLayout(t),this._layoutItems(t,e),this._postLayout()},f._getItemsForLayout=function(t){return t.filter(function(t){return!t.isIgnored})},f._layoutItems=function(t,e){if(this._emitCompleteOnItems("layout",t),t&&t.length){var i=[];t.forEach(function(t){var n=this._getItemLayoutPosition(t);n.item=t,n.isInstant=e||t.isLayoutInstant,i.push(n)},this),this._processLayoutQueue(i)}},f._getItemLayoutPosition=function(){return{x:0,y:0}},f._processLayoutQueue=function(t){this.updateStagger(),t.forEach(function(t,e){this._positionItem(t.item,t.x,t.y,t.isInstant,e)},this)},f.updateStagger=function(){var t=this.options.stagger;return null===t||void 0===t?void(this.stagger=0):(this.stagger=a(t),this.stagger)},f._positionItem=function(t,e,i,n,o){n?t.goTo(e,i):(t.stagger(o*this.stagger),t.moveTo(e,i))},f._postLayout=function(){this.resizeContainer()},f.resizeContainer=function(){var t=this._getOption("resizeContainer");if(t){var e=this._getContainerSize();e&&(this._setContainerMeasure(e.width,!0),this._setContainerMeasure(e.height,!1))}},f._getContainerSize=d,f._setContainerMeasure=function(t,e){if(void 0!==t){var i=this.size;i.isBorderBox&&(t+=e?i.paddingLeft+i.paddingRight+i.borderLeftWidth+i.borderRightWidth:i.paddingBottom+i.paddingTop+i.borderTopWidth+i.borderBottomWidth),t=Math.max(t,0),this.element.style[e?"width":"height"]=t+"px"}},f._emitCompleteOnItems=function(t,e){function i(){o.dispatchEvent(t+"Complete",null,[e])}function n(){s++,s==r&&i()}var o=this,r=e.length;if(!e||!r)return void i();var s=0;e.forEach(function(e){e.once(t,n)})},f.dispatchEvent=function(t,e,i){var n=e?[e].concat(i):i;if(this.emitEvent(t,n),u)if(this.$element=this.$element||u(this.element),e){var o=u.Event(e);o.type=t,this.$element.trigger(o,i)}else this.$element.trigger(t,i)},f.ignore=function(t){var e=this.getItem(t);e&&(e.isIgnored=!0)},f.unignore=function(t){var e=this.getItem(t);e&&delete e.isIgnored},f.stamp=function(t){t=this._find(t),t&&(this.stamps=this.stamps.concat(t),t.forEach(this.ignore,this))},f.unstamp=function(t){t=this._find(t),t&&t.forEach(function(t){n.removeFrom(this.stamps,t),this.unignore(t)},this)},f._find=function(t){return t?("string"==typeof t&&(t=this.element.querySelectorAll(t)),t=n.makeArray(t)):void 0},f._manageStamps=function(){this.stamps&&this.stamps.length&&(this._getBoundingRect(),this.stamps.forEach(this._manageStamp,this))},f._getBoundingRect=function(){var t=this.element.getBoundingClientRect(),e=this.size;this._boundingRect={left:t.left+e.paddingLeft+e.borderLeftWidth,top:t.top+e.paddingTop+e.borderTopWidth,right:t.right-(e.paddingRight+e.borderRightWidth),bottom:t.bottom-(e.paddingBottom+e.borderBottomWidth)}},f._manageStamp=d,f._getElementOffset=function(t){var e=t.getBoundingClientRect(),n=this._boundingRect,o=i(t),r={left:e.left-n.left-o.marginLeft,top:e.top-n.top-o.marginTop,right:n.right-e.right-o.marginRight,bottom:n.bottom-e.bottom-o.marginBottom};return r},f.handleEvent=n.handleEvent,f.bindResize=function(){t.addEventListener("resize",this),this.isResizeBound=!0},f.unbindResize=function(){t.removeEventListener("resize",this),this.isResizeBound=!1},f.onresize=function(){this.resize()},n.debounceMethod(r,"onresize",100),f.resize=function(){this.isResizeBound&&this.needsResizeLayout()&&this.layout()},f.needsResizeLayout=function(){var t=i(this.element),e=this.size&&t;return e&&t.innerWidth!==this.size.innerWidth},f.addItems=function(t){var e=this._itemize(t);return e.length&&(this.items=this.items.concat(e)),e},f.appended=function(t){var e=this.addItems(t);e.length&&(this.layoutItems(e,!0),this.reveal(e))},f.prepended=function(t){var e=this._itemize(t);if(e.length){var i=this.items.slice(0);this.items=e.concat(i),this._resetLayout(),this._manageStamps(),this.layoutItems(e,!0),this.reveal(e),this.layoutItems(i)}},f.reveal=function(t){if(this._emitCompleteOnItems("reveal",t),t&&t.length){var e=this.updateStagger();t.forEach(function(t,i){t.stagger(i*e),t.reveal()})}},f.hide=function(t){if(this._emitCompleteOnItems("hide",t),t&&t.length){var e=this.updateStagger();t.forEach(function(t,i){t.stagger(i*e),t.hide()})}},f.revealItemElements=function(t){var e=this.getItems(t);this.reveal(e)},f.hideItemElements=function(t){var e=this.getItems(t);this.hide(e)},f.getItem=function(t){for(var e=0;e<this.items.length;e++){var i=this.items[e];if(i.element==t)return i}},f.getItems=function(t){t=n.makeArray(t);var e=[];return t.forEach(function(t){var i=this.getItem(t);i&&e.push(i)},this),e},f.remove=function(t){var e=this.getItems(t);this._emitCompleteOnItems("remove",e),e&&e.length&&e.forEach(function(t){t.remove(),n.removeFrom(this.items,t)},this)},f.destroy=function(){var t=this.element.style;t.height="",t.position="",t.width="",this.items.forEach(function(t){t.destroy()}),this.unbindResize();var e=this.element.outlayerGUID;delete c[e],delete this.element.outlayerGUID,u&&u.removeData(this.element,this.constructor.namespace)},r.data=function(t){t=n.getQueryElement(t);var e=t&&t.outlayerGUID;return e&&c[e]},r.create=function(t,e){var i=s(r);return i.defaults=n.extend({},r.defaults),n.extend(i.defaults,e),i.compatOptions=n.extend({},r.compatOptions),i.namespace=t,i.data=r.data,i.Item=s(o),n.htmlInit(i,t),u&&u.bridget&&u.bridget(t,i),i};var m={ms:1,s:1e3};return r.Item=o,r}),function(t,e){"function"==typeof define&&define.amd?define(["outlayer/outlayer","get-size/get-size"],e):"object"==typeof module&&module.exports?module.exports=e(require("outlayer"),require("get-size")):t.Masonry=e(t.Outlayer,t.getSize)}(window,function(t,e){var i=t.create("masonry");i.compatOptions.fitWidth="isFitWidth";var n=i.prototype;return n._resetLayout=function(){this.getSize(),this._getMeasurement("columnWidth","outerWidth"),this._getMeasurement("gutter","outerWidth"),this.measureColumns(),this.colYs=[];for(var t=0;t<this.cols;t++)this.colYs.push(0);this.maxY=0,this.horizontalColIndex=0},n.measureColumns=function(){if(this.getContainerWidth(),!this.columnWidth){var t=this.items[0],i=t&&t.element;this.columnWidth=i&&e(i).outerWidth||this.containerWidth}var n=this.columnWidth+=this.gutter,o=this.containerWidth+this.gutter,r=o/n,s=n-o%n,a=s&&1>s?"round":"floor";r=Math[a](r),this.cols=Math.max(r,1)},n.getContainerWidth=function(){var t=this._getOption("fitWidth"),i=t?this.element.parentNode:this.element,n=e(i);this.containerWidth=n&&n.innerWidth},n._getItemLayoutPosition=function(t){t.getSize();var e=t.size.outerWidth%this.columnWidth,i=e&&1>e?"round":"ceil",n=Math[i](t.size.outerWidth/this.columnWidth);n=Math.min(n,this.cols);for(var o=this.options.horizontalOrder?"_getHorizontalColPosition":"_getTopColPosition",r=this[o](n,t),s={x:this.columnWidth*r.col,y:r.y},a=r.y+t.size.outerHeight,h=n+r.col,u=r.col;h>u;u++)this.colYs[u]=a;return s},n._getTopColPosition=function(t){var e=this._getTopColGroup(t),i=Math.min.apply(Math,e);return{col:e.indexOf(i),y:i}},n._getTopColGroup=function(t){if(2>t)return this.colYs;for(var e=[],i=this.cols+1-t,n=0;i>n;n++)e[n]=this._getColGroupY(n,t);return e},n._getColGroupY=function(t,e){if(2>e)return this.colYs[t];var i=this.colYs.slice(t,t+e);return Math.max.apply(Math,i)},n._getHorizontalColPosition=function(t,e){var i=this.horizontalColIndex%this.cols,n=t>1&&i+t>this.cols;i=n?0:i;var o=e.size.outerWidth&&e.size.outerHeight;return this.horizontalColIndex=o?i+t:this.horizontalColIndex,{col:i,y:this._getColGroupY(i,t)}},n._manageStamp=function(t){var i=e(t),n=this._getElementOffset(t),o=this._getOption("originLeft"),r=o?n.left:n.right,s=r+i.outerWidth,a=Math.floor(r/this.columnWidth);a=Math.max(0,a);var h=Math.floor(s/this.columnWidth);h-=s%this.columnWidth?0:1,h=Math.min(this.cols-1,h);for(var u=this._getOption("originTop"),d=(u?n.top:n.bottom)+i.outerHeight,l=a;h>=l;l++)this.colYs[l]=Math.max(d,this.colYs[l])},n._getContainerSize=function(){this.maxY=Math.max.apply(Math,this.colYs);var t={height:this.maxY};return this._getOption("fitWidth")&&(t.width=this._getContainerFitWidth()),t},n._getContainerFitWidth=function(){for(var t=0,e=this.cols;--e&&0===this.colYs[e];)t++;return(this.cols-t)*this.columnWidth-this.gutter},n.needsResizeLayout=function(){var t=this.containerWidth;return this.getContainerWidth(),t!=this.containerWidth},i});


function toggle_menu_items(info_total_sections,current_menu_section,total_sections,items_widths,items,show_more_btn_prev,total_width) {
	info_total_sections.html(current_menu_section + '/' + total_sections);
	var arr, widths = $.extend(true, [], items_widths), section;
	arr = items;
	section = current_menu_section;
	if(current_menu_section == 1){
		show_more_btn_prev.addClass('off-prev-m');
		var sum = 0;
		arr.each(function(i) {
			var $item = $(this);
			show_menu_item($item);

			sum += widths[i];
			if(section == 1){
				$item.removeClass('item_menu_hidden');
			}
			if(section > 1){
				$item.addClass('item_menu_hidden');
			}

			if (sum > total_width) {
				section += 1;
				sum = 0;
				sum += widths[i];
				$item.addClass('item_menu_hidden');
				$item.addClass('section_' + section);
			} else {
				$item.addClass('section_' + section);
			}

		});
	 }

}

function show_menu_item(elem) {
	elem.removeClass('item_menu_hidden');
	elem.removeClass(function (index, className) {
		return (className.match (/(^|\s)section_\S+/g) || []).join(' ');
	});
}

function cs__menu_init(selector) {

	$(selector + 'nav div').removeAttr('style');
	$(selector + '.dop-menu-show_more').hide();

	var container = $(selector);

	var menu = $('.nav.navbar-nav', container);

	menu.css('visibility', 'hidden');


	var wrapper = menu.parent();
	var show_more_btn = $(selector +' .dop-menu-show_more');
	var show_more_btn_prev = $(selector +' .dop-menu-show_more .prev-m');
	var show_more_btn_next = $(selector +' .dop-menu-show_more .next-m');
	var info_total_sections = $(selector +' .dop-menu-show_more .total-sections');
	var items = menu.children();

	show_more_btn.hide();
	var sum = 0;
	var items_widths = [];
	var total_width = 0;
	total_width = container.innerWidth() - show_more_btn.outerWidth();

	show_menu_item(items);
	items.css('width', 'auto');
	items.each(function(i) {
		var item = $(this);
		items_widths[i] = item.get(0).getBoundingClientRect().width;
		item.css('width', item.get(0).getBoundingClientRect().width);
		sum += items_widths[i];

	});
	var total_sections = 1
	total_sections = Math.ceil(sum / total_width);
	if (total_sections > 1) {
		wrapper.css('width', total_width);
		show_more_btn.css('display', 'inline-block');
	} else {
		wrapper.css('width', '');
		show_more_btn.hide();
	}
	var current_menu_section = 1;

	toggle_menu_items(info_total_sections,current_menu_section,total_sections,items_widths,items,show_more_btn_prev,total_width);
	menu.removeAttr('style');
	show_more_btn_next.removeClass('off-next-m');
	show_more_btn.off('click');
	show_more_btn_prev.click(function() {
		if(current_menu_section === 1) {
			current_menu_section = 1;
			show_more_btn_prev.addClass('off-prev-m');
		} else {
			current_menu_section -= 1;
			if(current_menu_section === 1){
				show_more_btn_prev.addClass('off-prev-m');
				if(total_sections == 2){
					show_more_btn_next.removeClass('off-next-m');
				}
			} else {
				show_more_btn_prev.removeClass('off-prev-m');
				show_more_btn_next.removeClass('off-next-m');
			}
			info_total_sections.html(current_menu_section + '/' + total_sections);
			$(selector +' .section_' + (current_menu_section + 1)).addClass('item_menu_hidden');
			$(selector +' .section_' + current_menu_section).removeClass('item_menu_hidden');
		}
	});
	show_more_btn_next.click(function() {
		if (current_menu_section === total_sections) {
			current_menu_section = total_sections;
			show_more_btn_next.addClass('off-next-m');
		} else {
			current_menu_section += 1;
			if(current_menu_section === total_sections){
				show_more_btn_next.addClass('off-next-m');
			} else {
				show_more_btn_next.removeClass('off-next-m');
			}
			if(current_menu_section >= 1){
				show_more_btn_prev.removeClass('off-prev-m');
			}
			info_total_sections.html(current_menu_section + '/' + total_sections);
			$(selector +' .section_' + current_menu_section).removeClass('item_menu_hidden');
			$(selector +' .section_' + (current_menu_section - 1)).addClass('item_menu_hidden');
		}
	});
}

function nsmenu_menu(){
	if ($(".menu-header-box").hasClass('cont-mw') == true) {
		$(".nsmenu-bigblock").css('width',$(".menu-header-box").outerWidth());
		var menu_offset = $('.menu-header-box').offset();
		var menu_ow = $('.menu-header-box').outerWidth();
	} else {
		$(".nsmenu-bigblock").css('width',$(".full-mw .container").outerWidth() - 20);
		var menu_offset = $('.full-mw .container').offset();
		var menu_ow = $('.full-mw .container').outerWidth() - 10;
	}
	$('#horizontal-menu .dropdown-menu').each(function() {
		var dropdown = $(this).parent().offset();
		var i = (dropdown.left + $(this).outerWidth()) - (menu_offset.left + menu_ow);

		if (i > 0) {
			$(this).css('margin-left', '-' + i + 'px');
		}
		var l=$(this).outerWidth();
		$(this).find(".nsmenu-ischild-simple").css('left',l);

	});
}
function additional_menu(){
	if($('.menu-header-box').hasClass('full-mw')){
		$(".nsmenu-bigblock-additional").css('width',$(".menu-header-box .container").outerWidth()-20);
	} else {
		$(".nsmenu-bigblock-additional").css('width',$(".menu-header-box").outerWidth());
	}

	$('#additional-menu .dropdown-menu').each(function() {
		if ($(".dop-menu-header").hasClass('dopmenu-center') == true) {

			var menu = $('#additional-menu').offset();
			var dropdown = $(this).parent().offset();
			drop_mc = (dropdown.left - 10) - menu.left / 2;
			if($(this).hasClass('nsmenu-bigblock-additional') == true){
				$(this).css('margin-left', '-' + (drop_mc + 1) + 'px');
			}
		} else if($('.menu-header-box .row.dflex').hasClass('h-type-2-st') == true) {
			var menu = $('#additional-menu').offset();
			var dropdown = $(this).parent().offset();
			var menu_offset = $('.menu-header-box .container ').offset();
			drop_mc = (dropdown.left - 20) - menu_offset.left + 10;
			if($(this).hasClass('nsmenu-bigblock-additional') == true){
				$(this).css('left', '-' + (drop_mc + 1) + 'px');
			}
			var l=$(this).outerWidth()-2;
			$(this).find(".nsmenu-type-category-simple").css('left',l);
		} else {

			var menu = $('#additional-menu').offset();
			var dropdown = $(this).parent().offset();

			var i = (dropdown.left + $(this).outerWidth()) - (menu.left + $('#additional-menu').outerWidth());
			if (i > 0) {
				$(this).css('margin-left', '-' + (i) + 'px');
			}
			var l=$(this).outerWidth()-2;
			$(this).find(".nsmenu-ischild-simple").css('left',l);
		}
	});
}

function cs_menu_aim(){
	$('#menu #menu-list').menuAim({
		activateCallback: activateSubmenu,
		deactivateCallback: deactivateSubmenu,
	});
	function activateSubmenu(row) {
		if($(row).hasClass('dropdown')){
			$(row).addClass('menu-open');
			if (viewport().width >= 992 && ($(row).find('.row-masonry').length == 1)) {
				$(row).find('.row-masonry').masonry({
					itemSelector: '.nsmenu-parent-block',
					transitionDuration: 0,
					horizontalOrder: false
				});
			}
		}
	}
	function deactivateSubmenu(row) {$(row).removeClass('menu-open');}
	function exitMenu(row) {return true;}
	$('.dropdown-menu-simple .nsmenu-haschild').menuAim({
		activateCallback: activateSubmenu2level,
		deactivateCallback: deactivateSubmenu2level,
	});
	function activateSubmenu2level(row) {
		if($(row).hasClass('nsmenu-issubchild')){
			$(row).addClass('menu-open-2level');
		}
	}
	function deactivateSubmenu2level(row) {$(row).removeClass('menu-open-2level');}
	function exitMenu2level(row) {return true;}
	$('.dropdown-menu-simple .nsmenu-ischild-simple .list-unstyled').menuAim({
		activateCallback: activateSubmenu4level,
		deactivateCallback: deactivateSubmenu4level,
	});
	function activateSubmenu4level(row) {
		$(row).addClass('menu-open-4level');
	}
	function deactivateSubmenu4level(row) {$(row).removeClass('menu-open-4level');}
	function exitMenu4level(row) {return true;}
	$('#additional-menu li.dropdown').hover(function() {
		additional_menu();

		$(this).find('.dropdown-menu').stop(true, true).delay(10);
		$(this).addClass('open');

		if (viewport().width >= 992 && ($(this).find('.row-masonry').length == 1)) {
			$(this).find('.row-masonry').masonry({
				itemSelector: '.nsmenu-parent-block',
				transitionDuration: 0,
				horizontalOrder: false
			});
		}
		$('#maskMenuDop').addClass('open');
		$(this).find('.dropdown-toggle').attr('aria-expanded', 'true');
	}, function() {
		$(this).find('.dropdown-menu').stop(true, true).delay(10);
		$(this).removeClass('open');
		$('#maskMenuDop').removeClass('open');
		$(this).find('.dropdown-toggle').attr('aria-expanded', 'false')
	});
	if($(document).width()>991){
		$('#horizontal-menu li.dropdown').hover(function() {
			$(this).find('.dropdown-menu').stop(true, true).delay(10);
			$(this).addClass('open');
			if ($(this).find('.row-masonry').length == 1) {
				$(this).find('.row-masonry').masonry({
					itemSelector: '.nsmenu-parent-block',
					transitionDuration: 0,
					horizontalOrder: false
				});
			}
			$('#maskMenuHor').addClass('open');
			$(this).find('.dropdown-toggle').attr('aria-expanded', 'true');
		}, function() {
			$(this).find('.dropdown-menu').stop(true, true).delay(10);
			$(this).removeClass('open');
			$('#maskMenuHor').removeClass('open');
			$(this).find('.dropdown-toggle').attr('aria-expanded', 'false')
		});
	}

	$('.nsmenu-type-manufacturer a[data-toggle="tooltip"]').tooltip({
		animated: 'fade',
		placement: 'top',
		template: '<div class="tooltip tooltip-manufacturer" role="tooltip"><div class="arrow"></div><div class="tooltip-inner tooltip-manufacturer-inner"></div></div>',
		html: true
	});

	$(".ns-dd").hover(function() {$(this).parent().find('.parent-link').toggleClass('hover');});
	$(".nsmenu-issubchild").hover(function() {
		$(this).parent().parent().toggleClass('hide-border-radius-top-right');
	});
	$(".ischild_4level_simple").hover(function() {
		$(this).parent().parent().toggleClass('hide-border-radius-top-right');
	});
	$(".child-box").hover(function() {$(this).parent().find('.with-child').toggleClass('hover');});
	$(".nsmenu-ischild.nsmenu-ischild-simple").hover(function() {$(this).parent().find('> a').toggleClass('hover');});
	$(".child_4level_simple").hover(function() {$(this).parent().find('> a').toggleClass('hover');});

	$('#menu #menu-list .toggle-child').on('click', function(e) {
		e.stopPropagation();
		$(this).toggleClass('open');
		$(this).next().next().slideToggle(0);
	});

	$( "#additional-menu a.dropdown-toggle" ).bind( "click", function() {
		if(($(this).attr('href')!="javascript:void(0);")&&($( document ).width()>767))
		{
		window.document.location=$(this).attr('href');
		}
	});
	$( "#menu a.dropdown-toggle" ).bind( "click", function() {
		if(($(this).attr('href')!="javascript:void(0);")&&($( document ).width()>767))
		{
		window.document.location=$(this).attr('href');
		}
	});
	$( "#horizontal-menu a.dropdown-toggle" ).bind( "click", function(e) {
		if($(this).attr('href') !='javascript:void(0);'){
			window.document.location=$(this).attr('href');
		} else {
			e.stopPropagation();
			e.preventDefault();
		}

	});
	$('#horizontal-menu a .toggle-child').bind('click', function(e) {
		e.stopPropagation();
		e.preventDefault();
		$(this).parent().parent().toggleClass('open');
	});
	$('#horizontal-menu li.dropdown').hover(function() {
		nsmenu_menu();
	});
	$(".nsmenu-issubchild").hover(function() {
		$(this).parent().parent().toggleClass('hide-border-radius');}
	);
}

$(function() {

	setTimeout(function () {
		cs__menu_init('.dop-menu-box .navbar.hmenu_type');
		cs__menu_init('.dop-menu-header .navbar.hmenu_type');
		cs__menu_init('#horizontal-menu');
	}, 400);

	$(window).resize(function() {
		$('.dop-menu-box .navbar.hmenu_type .navbar-nav').css('visibility','hidden');
		$('.dop-menu-header .navbar.hmenu_type .navbar-nav').css('visibility','hidden');
		$('#horizontal-menu .navbar-nav').css('visibility','hidden');
		$('.dop-menu-box .navbar.hmenu_type .nav.navbar-nav').parent().removeAttr('style');
		$('.dop-menu-header .navbar.hmenu_type .nav.navbar-nav').parent().removeAttr('style');
		$('#horizontal-menu .nav.navbar-nav').parent().removeAttr('style');
		$('.dop-menu-show_more').hide();
		setTimeout(function () {
			cs__menu_init('.dop-menu-box .navbar.hmenu_type');
			cs__menu_init('.dop-menu-header .navbar.hmenu_type');
			cs__menu_init('#horizontal-menu');
		}, 700);

	});
	cs_menu_aim();
});

//MOBILE MENU

$(function() {
	var width_dev_ns = viewport().width;
	if (width_dev_ns <= 991) {
		$('#top-fixed-mobile').addClass('sticky-header-mobile');
		$('#top').addClass('hidden-xs');

		$('#mob-info-block-inner .box-account-mob').prepend( $('.box-account > *') );
		$('#mob-info-block-inner .box-other-mob').append( $('#language') );
		$('#mob-info-block-inner .box-other-mob').append( $('#currency') );
		$('#mob-info-block-inner .box-other-mob').append( $('.header-nav-links') );
		$('#mob-search-block-inner').prepend( $('.box-search #search') );
		$('#top-fixed-mobile .compare-h-fix > .mob-icon-box').hide();
		$('#top-fixed-mobile .wishlist-h-fix > .mob-icon-box').hide();
		$('#top-fixed-mobile .cart_fix_mob > .mob-icon-box').hide();
		$('#top-fixed-mobile .compare-h-fix').prepend( $('.compare-h') );
		$('#top-fixed-mobile .wishlist-h-fix').prepend( $('.wishlist-h') );
		$('#top-fixed-mobile .cart_fix_mob').prepend( $('.box-cart .shopping-cart') );
	}

	$(window).resize(function() {
		var width_dev_ns = viewport().width;
		if (width_dev_ns <= 991) {
			$('#top-fixed').remove();
			setTimeout(function () {
				$('#top-fixed').remove();
				$('#top').addClass('hidden-xs');
				$('#top-fixed-mobile').addClass('sticky-header-mobile');
				$('#mob-info-block-inner .box-account-mob').prepend( $('.box-account > *') );
				$('#mob-info-block-inner .box-other-mob').append( $('#language') );
				$('#mob-info-block-inner .box-other-mob').append( $('#currency') );
				$('#mob-info-block-inner .box-other-mob').append( $('.header-nav-links') );
				$('#mob-search-block-inner').prepend( $('.box-search #search') );
				$('#top-fixed-mobile .compare-h-fix > .mob-icon-box').hide();
				$('#top-fixed-mobile .wishlist-h-fix > .mob-icon-box').hide();
				$('#top-fixed-mobile .cart_fix_mob > .mob-icon-box').hide();
				$('#top-fixed-mobile .compare-h-fix').prepend( $('.compare-h'));
				$('#top-fixed-mobile .wishlist-h-fix').prepend( $('.wishlist-h') );
				$('#top-fixed-mobile .cart_fix_mob').prepend( $('.box-cart .shopping-cart') );
			}, 500);
		} else {
			setTimeout(function () {
				$('#top-fixed').remove();
				$('#top').removeClass('hidden-xs');
				$('#top-fixed-mobile').removeClass('sticky-header-mobile');
				$('.box-currency').prepend( $('#currency') );
				$('.box-language').prepend( $('#language') );
				$('.box-question').prepend( $('#mob-info-block-inner .header-nav-links') );
				$('.box-account').prepend( $('#mob-info-block-inner .box-account-mob > *') );
				$('.box-search #searchtop').prepend( $('#mob-search-block-inner #search') );
				$('#top-fixed-mobile .compare-h-fix > .mob-icon-box').show();
				$('#top-fixed-mobile .wishlist-h-fix > .mob-icon-box').show();
				$('#top-fixed-mobile .cart_fix_mob > .mob-icon-box').show();
				$('.box-cart').prepend( $('#top-fixed-mobile .cart_fix_mob .shopping-cart') );
				$('.box-cart').prepend( $('#top-fixed-mobile .wishlist-h') );
				$('.box-cart').prepend( $('#top-fixed-mobile .compare-h') );
			}, 500);
		}
	});
});

$(document).on('click', '.box-fm-r .shopping-cart .dropdown-toggle', function () {
	$('#mob-search-block').hide();
});

$(document).on('click', '#info-btn-mob', function () {
	$('#menu-mobile-ns .navbar-type2-collapse').removeClass('in');
	$('body').removeClass('no-scroll-html');
	$('#mob-info-block').removeClass('hidden');
	setTimeout(function () {
		$('#mob-info-block').addClass('active');
	}, 50);
	$('html').toggleClass('no-scroll-html');
	$('.mobm-menu-blur').addClass('active-blur');
	$('#mob-search-block').hide();
});

$(document).on('click', '#search-btn-mob', function () {
	$('#menu-mobile-ns .navbar-type2-collapse').removeClass('in');
	$('body').removeClass('no-scroll-html');
	$('#mob-search-block').slideToggle(300);
	$('html').toggleClass('no-scroll-html');
});

$(document).on('click', '.mob-block-close', function () {
	$(".mob-block-fix").removeClass('active');
	$('html').removeClass('no-scroll-html');
	$('.mobm-menu-blur').removeClass('active-blur');
});

$(document).on('click', '#menu-mobile-ns .show-sc-mobile', function () {
	$(this).siblings(".collapsible").toggle();
	$(this).toggleClass("open-sc-mobile");
	$(this).parent().toggleClass("active-menu");
	$(this).parent().next().toggleClass("in");
});

function close_mob_menu(){
	$('#menu-mobile-ns.navmenu-fixed-left').removeClass('active');
	$('body').removeClass('modal-open');
	$('.mobm-menu-blur').removeClass('active-blur');
}
function fm_activeMenu(){
	$('#mob-search-block').hide();
	$('.mob-catalog-left').removeClass('hidden');
	$('body').toggleClass('modal-open');
   $('.mobm-menu-blur').toggleClass('active-blur');
   setTimeout(function () {
		$('#menu-mobile-ns.navmenu-fixed-left').toggleClass('active');
	}, 50);
}
function open_mob_menu_left() {
	if ($(".mob-catalog-left").find('#menu-mobile-ns').length == 0) {
		$('.mob-catalog-left').load('index.php?route=common/cyber_menuvh/load_mob_menu', function() {
		  fm_activeMenu();
		});
	} else {
		fm_activeMenu();
	}
}
$(document).on('click', '.collapse-mt2', function () {
	if ($(".mob-catalog-left").find('#menu-mobile-ns').length == 0) {
		 $('.mob-catalog-left').load('index.php?route=common/cyber_menuvh/load_mob_menu', function() {
			$('.mob-catalog-left').removeClass('hidden');
			$('body').toggleClass('modal-open');
			setTimeout(function () {
				 $('#menu-mobile-ns .navbar-type2-collapse').collapse('toggle');
			}, 50);
		});
	} else {
		$('.mob-catalog-left').removeClass('hidden');
		$('body').toggleClass('modal-open');
		setTimeout(function () {
			 $('#menu-mobile-ns .navbar-type2-collapse').collapse('toggle');
		}, 50);
	}
});
$(document).on('click', '.mobm-menu-blur', function () {
	$('#menu-mobile-ns.navmenu-fixed-left').removeClass('active');
	setTimeout(function () {
		$('.mob-catalog-left').addClass('hidden');
	}, 200);
	$('#mob-info-block').removeClass('active');
	setTimeout(function () {
		$('#mob-info-block').addClass('hidden');
	}, 200);
	$('body').removeClass('modal-open');
	$('html').removeClass('no-scroll-html');
	$('body').removeClass('no-scroll-html');
	$('.mobm-menu-blur').removeClass('active-blur');
});