/**
 * base.style.js 
 * 
 * @classDescription 样式部分的JS，用于像CSS的动态载入和固定样式的调整。
 * @author david
 * @version 2007/09/15
 **/

var BaseStyle = {
	
	/**
	 * 现有样式文件集
	 * 
	 * @type {Object}
	 */
	cssArray : {
		"gray" : "todo_gray.css",
		"blue" : "todo_blue.css",
		"orange" : "todo_orange.css",
		"simple" : "todo_simple.css",
		"metal" : "todo_metal.css"
	},
	
	/**
	 * 创建<link id="main_stylesheet" rel="stylesheet"
	 * 
	 * @param {String} str CSS文件路径
	 */
	_createStylesheet : function(str) {
			$("head").append('<link id="main_stylesheet" rel="stylesheet" rev="stylesheet" href="'+str+'" type="text/css" media="all" />');
	},
	
	/**
	 * 修改<link id="main_stylesheet" rel="stylesheet" 的链接
	 * 
	 * @param {String} fileName CSS文件名
	 */
	_modifyStylesheetHref : function(fileName) {
		if ($("#main_stylesheet").attr("href")) {
			$("#main_stylesheet").attr("href","css/"+fileName);
		} else {
			this._createStylesheet("css/"+fileName);
		}
	},
	
	/**
	 * 改变样式
	 * 
	 * @param {String} name 样式名称
	 */
	changeStyle : function(name) {
		this._modifyStylesheetHref(this.cssArray[name]);
		base.eval_func("baseCookie.setCookie","style",name,3600000000);
	},
	
	changeLogo : function(thisObj,i) {

	},
	
	changeLogo : function() {
		var logoArray = ["","logo_onion_small.png","logo_onion_big.png","logo_hx_small.png"];
		var n = parseInt(Math.random()*3+1);
		n = n > 3 ? 3 : n;
		n = n < 1 ? 1 : n;
		$(".logo > img").attr('src','img/'+logoArray[n]);
	},
	
	toggleTwoElem : function(fir_selor,sec_selor) {
		$(fir_selor).toggleClass("hidden");
		$(sec_selor).toggleClass("hidden");
	},

	/**
	 * @constructor
	 * 
	 */
	__construct : function() { },
	
	/**
	 * @destuct
	 */
	__destruct : function() { }
}