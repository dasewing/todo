/**
 * system.todo.js
 * 
 * @classDescription 系统日程类，用于用户查看、添加、修改、删除自己的日程的操作。
 * @author david
 * @version 2007/08/23
 **/

var systemTodo = {
	
	/**
	 * @constructor
	 * 
	 */
	__construct : function() {
		this.targetPHP = "php/todo.php";
	 },
	
	targetPHP : "php/todo.php",
	
	/**
	 * 取得添加日程表单Html，并插入页面
	 */
	getTodoAddForm : function() {
		$.get("html/todo_add.html", function(data){
			$(data).appendTo("#add_layer");
		});
	},
	
	/**
	 * 取得当前用户日程列表
	 * 
	 * @param {String} type
	 */
	getTodosData : function(type) {
		var todoHtml = "";
		var rightNow = new Date();
		var pageObj = base.eval_func("basePager.get");
		
		if (!type) {
			type = $("ul.todo_sidebar > li.onselect").get(0).id;
		}
		
		note_type = $("ul.todo_typebar > li.onselect").get(0).id;
		
		$.ajax({
			 type: "GET",
			 url: this.targetPHP,
			 data: "get=todo&timeTag="+rightNow.getTime()+"&type="+type+"&note_type="+note_type+"&nowPage="+pageObj.nowPage+"&perPage="+pageObj.perPage,
			 dataType: "json",
			 success: function(data) {
			 	if (!data[0]) {
			 		$(".todo_list").hide().html(" no data !")
			 		return false;
			 	}
			 	if (!data[0]["title"]) {
			 		$(".todo_list").hide().html(" no data !")
			 		return false;
			 	}
				for (var i in data ) {
					if (!parseInt(i)&&i!=0) continue;
					todoHtml += "<dt>";
					todoHtml += "<span class=\"no\">#";
					todoHtml += (parseInt(i)+1)+"</span>";
					todoHtml += type=="del" ? " " : "<input type=\"checkbox\" name=\"del_id\" value=\""+data[i]["id"]+"\" title=\"标记您希望操作的！\" />";
					todoHtml += "<span>["+data[i]["type"]+"] "+data[i]["title"]+"</span> <a class=\"line_edit\" href=\"javascript://\" onclick=\"base.eval_func('systemTodo.todo_edit',"+data[i]["id"]+");\" >修改</a>";
					todoHtml += "　<span class=\"time\"><span class='cornflowerblue'>"+data[i]["addtime"]+"</span>";
					todoHtml += type=="finish" && data[i]["closetime"] != "" ? " <span class=\"gray\">|</span> <span class='coral'>"+data[i]["closetime"]+"</span></span>" : "</span>";
					todoHtml += "</dt>";
					todoHtml += "<dd class=\""+(type=="run" ? "" : "hidden" ) +"\">";
					todoHtml += data[i]["content"];
					todoHtml += "</dd>";
				}
				base.eval_func("basePager.__construct",pageObj.nowPage,pageObj.perPage,data["totalPage"],data["count"]);
				$(".todo_list").hide().html(todoHtml).show(); //slideDown("slow");
				$(".todo_list dt span").click(function() {
					$(this).parent().next().slideToggle("200");	
				});
			}
		});
	},

	/**
	 * 提交一条日程记录
	 */
	todo_add : function() {
		var todo_id = $("#TB_ajaxContent #todo_id").val();
		var title = $("#TB_ajaxContent #title").val();
		var type = $("#TB_ajaxContent #type").val();
		var content = $("#TB_ajaxContent #content").val();
		if (todo_id > 0) {
			var wanna = "edit&id="+todo_id;
		} else {
			var wanna = "add";
		}
		if (!title) {
			alert("no !");
			return false;
		}
		if (!content) {
			content = "RT";
		}
		$.ajax({
			 type: "POST",
			 url: this.targetPHP,
			 data: "wanna="+wanna+"&type="+type+"&title="+title+"&content="+content,
			 success: function(msg) {
				tb_remove();
				systemTodo.getTodosData("run");
				$(".todo_add form").get(0).reset();
			}
		});
	},

	/**
	 * 弹出编辑表单
	 */
	todo_edit : function(id) {
		tb_show("Add Todo~s","#TB_inline?height=400&width=300&inlineId=add_layer&modal=false",false);
		var rightNow = new Date();
		$.ajax({
			 type: "GET",
			 url: this.targetPHP,
			 data: "wanna=row&timeTag="+rightNow.getTime()+"&id="+id,
			 dataType: "json",
			 success: function(data) {
			 	$("#TB_window .todo_add #title").val(data.title);
			 	$("#TB_window .todo_add #content").val(data.content);
			 	$("#TB_window .todo_add #todo_id").val(data.id);
			 	$("#TB_window .todo_add #type").val( data.type );
			}
		});
	},

	/**
	 * 关闭选中的日程记录
	 */
	todo_close : function() {
		var del_id_array = [];
		$(".todo_list input[@name=del_id]").each(function(i) {
			if (this.checked)	 del_id_array.push($(this).val());
		});
		var del_id_string = del_id_array.join(",");
		del_id_string 
		? 
		this._ajax_close(del_id_string)
		:	alert("no choose ! ");

	},

	_ajax_close : function(del_id_string) {
			$.ajax({
				 type: "POST",
				 url: this.targetPHP,
				 data: "wanna=close&ids="+del_id_string,
				 success: function(msg) {
					systemTodo.getTodosData();
				}
			});
	},

	/**
	 * 删除选中的日程记录
	 */
	todo_del : function() {
		var del_id_array = [];
		$(".todo_list input[@name=del_id]").each(function(i) {
			if (this.checked)	 del_id_array.push($(this).val());
		});
		var del_id_string = del_id_array.join(",");
		del_id_string 
		? 
		this._ajax_del(del_id_string)
		:	alert("no choose ! ");
	
	},

	_ajax_del : function(del_id_string) {
		$.ajax({
			 type: "POST",
			 url: this.targetPHP,
			 data: "wanna=del&ids="+del_id_string,
			 success: function(msg) {
			 systemTodo.getTodosData();
		}
		});
	},

	__destruct : function() { }

};