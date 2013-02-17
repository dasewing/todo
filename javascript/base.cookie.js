/**
 * base.cookie.js 
 * 
 * @classDescription cookie类，用于对cookie的操作。
 * @author david
 * @version 2007/08/23
 **/

var baseCookie = {
	
	/**
	 * cookie名称前缀
	 * 
	 * @type {String}
	 */
	cookie_prefix : "todos_",

	__construct : function() { },
	
	/**
	 * 设定Cookie
	 * 
	 * @param {String} k 			cookie名称
	 * @param {String} v 			cookies值
	 * @param {int} timeDelta 	cookies失效时间
	 * @param {String} domain 	cookie所在域
	 */
	setCookie : function(k,v,timeDelta,domain) {  
		timeDelta=timeDelta||0;
		var d=new Date();
		d.setTime(d.getTime()+timeDelta);
		document.cookie= this.cookie_prefix + k+"="+v+";"+(timeDelta?" ;path=/;expires="+d.toGMTString():"")+(domain?" ;domain="+domain:"");
	},
	
	/**
	 * 取得指定Cookie的值
	 * 
	 * @param {String} s cookie名称
	 * @param {Boolean} autoDecode 是否自动转码[decode]
	 */
	getCookie : function(s,autoDecode) {
		var g=document.cookie.match(new RegExp("(?:;\\s|^)"+ this.cookie_prefix + s+"=(.*?)(?:;|$)","ig"));
		if(g===null) return "";
		r=RegExp.$1;
	
		if(!autoDecode) return r;
		if(r.indexOf("%")!=-1) return decodeURIComponent(r);
		return r;
	},

	__destruct : function() { }

};