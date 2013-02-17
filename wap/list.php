<?php

require_once(ROOT_PATH."todos/php/lib/string.php");
require_once(ROOT_PATH."todos/php/lib/translater.php");

$condition = "and `isDel`=0 and `status`='on'";
$orderBy = "addTime";
$nowPage = empty($_GET["p"]) ? 1 : $_GET["p"];
$perPage = 12;
$sqlConn = SQL::getConn();

$sql = "select * from `todo_info` where `userID`={$userID} {$condition} order by `{$orderBy}` DESC LIMIT ".($nowPage-1)*$perPage.",{$perPage}";
$result = $sqlConn -> fetchAll($sql);

$count = $sqlConn -> fetchOne("select count(id) from `todo_info` where `userID`={$userID} {$condition}");

function makePager($nowPage, $perPage, $count) {
	$totalPage = ceil($count/$perPage);
	$outHtml = array();
	for ($i = 1 ; $i <= $totalPage ; $i++) {
		$outHtml[] = $nowPage == $i ? "<b>{$i}</b>" : "<a href='?p={$i}'>{$i}</a>";
	}
	return implode(" ",$outHtml);
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html >
<head>
<title> todo~S Demo </title>
<meta content="width=320; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" name="viewport">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<style type="text/css" >
body {	font-family: 'Verdana'; min-width:320px; overflow-x:hidden; margin:0; padding:0; }
dt {
	font-family: Verdana;
	font-size: 120%;
}
dd {
	font-family: Verdana;
	font-size: 110% !important;
}
input, select, option, textarea {
	font-family: Verdana;
	font-size: 90%;
}
dl.t_list dt .time {
	font-size: 80%;
	color: #333;
}
dl.t_list dt  {
	background: #eee;
	padding: 2px 4px;
}
dl.t_list dd  {
	padding: 2px 4px;
	margin: 2px 5px;
	font-size: 12px;
}
dl {
	margin-bottom: 3px;
	border-bottom: 1px solid #bbb;
}
.pager {
	text-align: center;
}
.pager a { font-size: 120%; background: #f90; color: #fff; font-weight: bold; padding: 5px; line-height: 50px; }
#add_form { padding: 5px; }
#add_form p { margin: 5px 0; }
</style>
<body>

<dl class="t_list" >

<?php foreach ($result as $k => $v) { ?>
	
	<dt >
		<?php echo $v["title"]; ?> <span class="time"><?php echo date("Y/m/d H:i",$v["addtime"]); ?></span>
	</dt>
	<dd ><?php echo $v["content"]; ?></dd>
	
<?php}?>

</dl>

<p class="pager"><?php echo makePager($nowPage, $perPage, $count);?></p>

<form id="add_form" method="post" action="/todos/wap/c/" >

	<input type="hidden" name="cp" value="t_add" />

	<p>类型</p>
	<p>	
		<select id="type" name="type" >
			<option value="todo" >todo</option>
			<option value="reminder" >reminder</option>
			<option value="note" selected="selected" >note</option>
		</select>
	</p> 

	<p>
		<label for="title">标题：</label>
	<p>
	</p>
		<input id="title" name="title" type="text" /> 
	</p>
	
	
	
	<p>
		<label for="contents">内容：</label>
	<p>
	</p>
		<textarea id="contents" name="contents" rows="5" style="width: 95%" ></textarea>
	</p>
	
	<p>
		<input type="submit" value="提交" />
	</p> 

</form>

</body>
</html>