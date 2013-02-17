<?php

require_once("key/k.php");
require_once("lib/sql.php");

$userID = $_COOKIE[COOKIE_PRIFIX_TODOS."id"];
$wanna = $_POST["wanna"];

switch ($wanna) {

	case "get" :
		$sqlConn = SQL::getConn();

		$sql = "select * from `todo_user`";
		$result = $sqlConn -> fetchAll($sql);
		
		require_once("translater.php");
		
		$result = Translater::timeToDate($result,array("add_time","last_time"),"Y-m-d H:i");

		echo json_encode($result);

		break;

	case "login" :
		$sqlConn = SQL::getConn();

		$login_name = $_POST["lname"];
		$login_pwd = $_POST["lpass"];

		$login_pwdMd5 = md5($login_pwd);

		$result = $sqlConn->fetchRow(
			"SELECT * FROM `todo_user` WHERE `login_name` = :name",
			array('name' => $login_name)
		);
		echo $result["login_pwd"] == $login_pwdMd5 ? $result["id"] : false;

		break;

	case "register" :
		
		$login_name = $_POST["rname"];
		$login_pwd = $_POST["rpass"];
		$nickName = $_POST["rnick"];
		
		$sqlConn = SQL::getConn();
		$data = array ( 
			"login_name" => $login_name ,
			"login_pwd" => md5($login_pwd) ,
			"name" => $nickName ,
			"add_time" => time() ,
			"last_time" => time() ,
			"ip" => empty($_SERVER['REMOTE_ADDR']) ? "0.0.0.0" :  $_SERVER['REMOTE_ADDR'] ,
		 );

		$rows_affected = $sqlConn->insert("todo_user", $data);
		$last_insert_id = $sqlConn->lastInsertId();
		
		if ($last_insert_id>0) {
			$sqlConn->insert("todo_user_info", array(
				"user_id" => $last_insert_id,
				"slogan" => "您的签名...",
			));
		}
		
		echo $last_insert_id;
		break;
		
	case "editSlogan":
		$slogan = $_POST["slogan"];
		$sqlConn = SQL::getConn();
		echo $sqlConn -> update("todo_user_info",array(
			"slogan" => $slogan,
		),"user_id = {$userID}");
		break;
		
	case "getSlogan":
		$sqlConn = SQL::getConn();
		$sql = "select slogan from todo_user_info where user_id = {$userID}";
		echo $sqlConn -> fetchOne($sql);
		break;
}

?>