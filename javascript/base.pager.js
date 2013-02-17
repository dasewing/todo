/**
 * base.pager.js 
 * 
 * @classDescription pager类，用于对分页的操作。
 * @author david
 * @version 2007/08/24
 **/

var basePager = {
	
	
	pagerTemplate : "",
	
	/**
	 * 构造函数
	 * 
	 * @param {Integer} nowPage
	 * @param {Integer} perPage
	 * @param {Integer} totalPage
	 * @param {Integer} count
	 */
	__construct : function(nowPage,perPage,totalPage,count) {
		
		this.nowPage 	= nowPage || 1;
		this.perPage 	= perPage || 10;
		this.totalPage 	= totalPage || "";
		this.count 		= count || "";
		if (this.totalPage != "") $("#now").html(this.nowPage+"/"+this.totalPage);
	},

	/**
	 * 
	 * 
	 * @return 
	 */
	get : function() {
		if (!this.nowPage) this.__construct();
		return this;		
	},

	go : function(operation) {
		
		switch (operation) {
			case "first":
				to = 1;
				break;
			case "previous":
				to = this.nowPage - 1;
				break;
			case "next":
				to = this.nowPage + 1;
				break;
			case "last":
				to = this.totalPage;
				break;
			default :
				to = this.nowPage;
				break;
		}
		
		if (to < 1 || to > this.totalPage) {
			return false;
		} else {
			this.nowPage = parseInt(to);
			base.eval_func("systemTodo.getTodosData");
		}
	},
	
	__destruct : function() { }

};