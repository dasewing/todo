/**
 * base.style.js 
 * 
 * @classDescription 弹出部分的JS，用于模拟 window.alert() 和 confirm() 之类的弹出框。
 * @author david
 * @version 2007/09/27
 **/

var BasePopup = {

	htmlArray : {
		"blert" : "",
		"donfirm" : ""
	},

	/**
	 * @constructor
	 * 
	 */
	__construct : function() { },
	
	/**
	 * 模拟 alert()
	 * @param {String} title 标题
	 * @param {String} msg 出现的信息
	 * @param {Object} 【OK】的回调函数
	 */
	blert : function(title, msg, okFunc) {
		
	},
	
	/**
	 * 模拟 alert()
	 * @param {String} title 标题
	 * @param {String} msg 出现的信息
	 * @param {Object} 【OK】的回调函数
	 * @param {Object} 【Cancel】的回调函数
	 */
	donfirm : function(title, msg, yesFunc, noFunc) {
			
	},
	
	/**
	 * @destuct
	 */
	__destruct : function() { }
}