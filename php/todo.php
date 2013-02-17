<?php
require_once("key/k.php");
require_once("lib/sql.php");
require_once("lib/string.php");
require_once("lib/translater.php");

if (isset($_GET["wanna"])) {
	$_POST["wanna"] = $_GET["wanna"];
}

$wanna = isset($_POST["wanna"]) ? $_POST["wanna"] : "get";
$userID = $_COOKIE[COOKIE_PRIFIX_TODOS."id"];

switch ($wanna) {

	case "get" :
		
		switch ($_GET["type"]) {
			case "finish" :
				$condition = "and `isDel`=0 and `status`='off'";
				$orderBy = "closeTime";
				break;
			case "del" :
				$condition = "and `isDel`=1";
				$orderBy = "lastModifyTime";
				break;
			case "run" :
			default :
				$condition = "and `isDel`=0 and `status`='on'";
				$orderBy = "addTime";
				switch ($_GET["note_type"]) {
					case "reminder" :
						$condition .= " and `type` = 'reminder'";
						break;
					case "todo" :
						$condition .= " and `type` = 'todo'";
						break;
					case "note" :
						$condition .= " and `type` = 'note'";
						break;
					default :
						break;
				}
				break;
		}
		
		$nowPage = empty($_GET["nowPage"]) ? 1 : $_GET["nowPage"];
		$perPage = empty($_GET["perPage"]) ? 12 : $_GET["perPage"];
	
		$sqlConn = SQL::getConn();
		
		$sql = "select * from `todo_info` where `userID`={$userID} {$condition} order by `{$orderBy}` DESC LIMIT ".($nowPage-1)*$perPage.",{$perPage}";
		$result = $sqlConn -> fetchAll($sql);
		
		foreach ($result as $k=>$v) {
			$result[$k]["content"] = $v["content"];
		}
		$count_sql = "select count(id) from `todo_info` where `userID`={$userID} {$condition}";
		$count = $sqlConn -> fetchAll($count_sql);
		$count = $count[0]["count(id)"];
		$totalPage = ceil($count/$perPage);
		
		$result = Translater::nlToBr($result,array("content"));
		$result = Translater::timeToDate($result,array("addtime","lastmodifytime","closetime"),"Y/m/d");
		$result["count"]	 = $count;
		$result["totalPage"] = $totalPage;
		$result["nowPage"]	 = $nowPage;
		$result["perPage"]	 = $perPage;
		
		echo json_encode($result);

		break;
		
	case "row":

		$id = intval($_GET["id"]);
		
		if ($id < 1) {
			echo json_encode(array());
		} else {

			$sqlConn = SQL::getConn();
					
			$sql = "select id, title, type, content from `todo_info` where `userID`={$userID} and id={$id} limit 1";
			$result = $sqlConn -> fetchRow($sql);
	
			echo json_encode($result);
		}
	
		break;

	case "add" :
		
		$title 		= $_POST["title"];
		$content 	= $_POST["content"];
		$type 		= $_POST["type"];
		
		$sqlConn = SQL::getConn();
		 $data = array ( 
			"userID" 	=> $userID ,
			"title" 	=> $title ,
			"content" 	=> $content ,
			"addTime" 	=> time() ,
			'lastModifyTime' => time(),
			"level" 	=> 0 ,
			"status" 	=> "on" ,
			"isDel" 	=> 0 ,
			"type" 	=> $type ,
			"other" 		=> "black" ,
		 );

		$rows_affected = $sqlConn->insert("todo_info", $data);
		$last_insert_id = $sqlConn->lastInsertId();
		
		echo $last_insert_id;
		break;

	case "edit" :
		
		$id		= intval($_POST["id"]);
		$title	= $_POST["title"];
		$content= $_POST["content"];
		$type 	= $_POST["type"];
		
		$sqlConn = SQL::getConn();
		$data = array ( 
			"title" 	=> $title ,
			"content" 	=> $content ,
			"type" 	=> $type ,
			'lastModifyTime' => time(),
		);

		$rows_affected = $sqlConn->update("todo_info", $data, "`userID`={$userID} and id={$id}");
		
		echo $rows_affected;
		break;

	case "del" :
			
		$del_id_array = explode(",",$_POST["ids"]);

		$sqlConn = SQL::getConn();
		
		foreach ($del_id_array as $k => $v) {

			$set = array (
				'isDel' => 1,
				'lastModifyTime' => time(),
			);

			$where = $sqlConn->quoteInto('id = ?', $v);

			$rows_affected = $sqlConn->update("todo_info", $set, $where);
		}

		echo $rows_affected;

		break;

	case "close" :
			
		$close_id_array = explode(",",$_POST["ids"]);

		$sqlConn = SQL::getConn();
		
		foreach ($close_id_array as $k => $v) {

			$set = array (
				'status' => 'off',
				'closeTime' => time(),
				'lastModifyTime' => time(),
			);

			$where = $sqlConn->quoteInto('id = ?', $v);

			$rows_affected = $sqlConn->update("todo_info", $set, $where);
		}

		echo $rows_affected;

		break;
}

?>