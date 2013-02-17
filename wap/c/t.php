<?

switch ($paramCase) {

	case "add" :
		
		$title 		= $_POST["title"];
		$type 		= empty($_POST["type"]) ? "note" : $_POST["type"];
		$content 	= empty($_POST["contents"]) ? "RT" : $_POST["contents"];
		
		if (!empty($title)) {
		
		$sqlConn = SQL::getConn();
		$data = array ( 
			"userID" 	=> $userID ,
			"title" 	=> $title ,
			"type" 		=> $type ,
			"content" 	=> $content ,
			"addTime" 	=> time() ,
			'lastModifyTime' => time(),
			"level" 	=> 0 ,
			"status" 	=> "on" ,
			"isDel" 	=> 0 ,
			"other" 	=> "black" ,
		);

		$rows_affected = $sqlConn->insert("todo_info", $data);
		
		}

		break;
		
}

header("location: /todos/wap/");

?>