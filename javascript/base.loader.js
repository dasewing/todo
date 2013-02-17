/**
 * base.loader.js 
 * 
 * @classDescription 最基本的loader，用于类的动态载入和函数的执行。
 * @author david
 * @version 2007/08/23
 **/

var base = {
	
	/**
	 * 系统JS文件所在路径
	 * 
	 * @type {String}
	 */
	_script_path : "/todos/javascript/",

	/**
	 * 类名前缀
	 * 
	 * @type {Array}
	 */
	_classesArray : ["base","system"],

	/**
	 * @constructor
	 * 
	 */
	__construct : function() { },
	
	/**
	 * 获取一个JS文件，并动态创建一个<script>标签
	 * 
	 * @param {String} path 文件路径（包含文件名）
	 */
	load_script : function(path) { 
		$.getScript(path, function(){
			alert("todo it.");
		});
	},
	
	/**
	 * 用Ajax取JS
	 * 
	 * @param {String} path 文件路径（包含文件名）
	 */
	_get_script : function(path) {

	},

	confi : function() {
		tb_show("Sure Exit ?","#TB_inline?height=50&width=200&inlineId=confirm_layer&modal=false",false);
	},
	

	/**
	 * 执行一段函数代码，如果类不存在则动态载入，存在则直接执行
	 * 
	 * @example 
	 * 	eval_func("baseCookie.getCookie","name",true);
	 * 
	 * @param {String} funcString 要调用函数
	 * @param {Object} params 参数（有几个写几个）
	 */
	eval_func : function(funcString,params) {
		
		var splitedArray = funcString.split(".");
		var className = splitedArray[0];
		var classFileName = className.toLowerCase();
		var funcName = splitedArray[1];
		
		var i = 1;
		var arg = [];
		var evalParamArray = [];
		
		while(typeof arguments[i] != "undefined") {
			arg[i] = arguments[i];
			evalParamArray.push("arg["+(i++)+"]");
		}
		
		var evalParamString = evalParamArray.join(",");
		
		if (eval("typeof "+className) == "undefined") {
			
			var ca = this._classesArray;
			
			for (var i = 0 ; i < ca.length ; i++ ) 
				if (classFileName.indexOf(ca[i]) != -1) 
					var fileName = ca[i] + "." + classFileName.replace(ca[i],"") + ".js";

			$.ajax({
				type: "GET",
				url: this._script_path+fileName,
				dataType: "script",
				async: false, // synchronization get , avoid 
				success:  function(msg) {
						eval(msg);
					}
			});
		}
		
		return eval(funcString+"("+evalParamString+")");
	},

	/**
	 * @destuct
	 */
	__destruct : function() { }

};

