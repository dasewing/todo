/**
 * system.user.js
 * 
 * @classDescription 系统用户类，用于用户登录、退出的操作。
 * @author david
 * @version 2007/08/23
 **/

var systemUser = {
	
	/**
	 * @constructor
	 * 
	 */
	__construct : function() {
		this.targetPHP = "php/user.php";
	 },
	
	
	targetPHP : "php/user.php",
	
	/**
	 * 用户登录
	 */
	login : function() {
		
		var lname = $(".login input[@name=lname]").val();
		var lpass = $(".login input[@name=lpass]").val();
		if (lname && lpass) {
			
			$.post(
				this.targetPHP,
				{ wanna: "login", lname: lname, lpass: lpass },
				function(data){
					data ? $(".login").slideUp("slow") : alert("有些错误！");
					data ? base.eval_func("baseCookie.setCookie","name",lname,3600000000) : true;
					data ? base.eval_func("baseCookie.setCookie","id",data,3600000000) : true;
					data ? base.eval_func("systemTodo.getTodosData") : true;
					data ? base.eval_func("systemTodo.getTodoAddForm") : true;
					data ? $(".todo_toolbar").show() : true;
					data ? $(".logo").show() : true;
					data ? $(".todo_sidebar").show() : true;
					data ? $(".todo_pagebar").show() : true;
					data ? $(".todo_actionbar").show() : true;
					data ? $(".todo_stylebar").show() : true;
					data ? $(".todo_stylebar").show() : true;
					data ? systemUser.showSlogan() : true;
				}
			);
		}
	},
	
	/**
	 * 用户登录
	 */
	register : function() {
		
		var rname = $(".register input[@name=rname]").val();
		var rnick = $(".register input[@name=rnick]").val();
		var rpass = $(".register input[@name=rpass]").val();
		
		if (rname && rpass) {
			
			$.post(
				this.targetPHP,
				{ wanna: "register", rname: rname, rpass: rpass, rnick: rnick },
				function(data){
					data ? $(".register").slideUp("slow") : alert("有些错误！");
					data ? base.eval_func("baseCookie.setCookie","name",rname,3600000000) : true;
					data ? base.eval_func("baseCookie.setCookie","id",data,3600000000) : true;
					data ? base.eval_func("systemTodo.getTodosData") : true;
					data ? base.eval_func("systemTodo.getTodoAddForm") : true;
					data ? $(".todo_toolbar").show() : true;
					data ? $(".todo_sidebar").show() : true;
					data ? $(".todo_pagebar").show() : true;
					data ? $(".todo_actionbar").show() : true;
					data ? systemUser.showSlogan() : true;
				}
			);
		}
	},
	
	showSlogan : function() {
		var name = base.eval_func("baseCookie.getCookie","name");
		if (name=="") {
			return false;
		}
		$(".logo > strong").html(name);
		$.post(
			this.targetPHP,
			{ wanna: "getSlogan" },
			function(data){
				$(".logo > span").html(data==""?"您的签名...":data);
			}
		);
	},
	
	editSlogan : function() {
		
		if ($(".logo > span > input[@name=slogan]").size()>0) return false;
	
		var oldSlogan = $(".logo > span").html();
		$(".logo > span").html("<input name='slogan' type='text' size='15' value='"+oldSlogan+"' />");
		
		$(".logo > span > input[@name=slogan]").select().blur(function(){
			var slogan = $(this).val();
			$.post(
				systemUser.targetPHP,
				{ wanna: "editSlogan", slogan: slogan },
				function(data){
					systemUser.showSlogan();
					$(".logo > span").bind("click",function(){
						base.eval_func('systemUser.editSlogan');
					});
				}
			);
		});
		
	},

	/**
	 * 用户退出
	 */
	exit : function() {
		base.eval_func("baseCookie.setCookie","name","",3600000);
		base.eval_func("baseCookie.setCookie","id",0,3600000);
		location.reload(true);
	},

	__destruct : function() { }

};