<?

switch ($paramCase) {

	case "login" :
		$sqlConn = SQL::getConn();

		$login_name = $_POST["userAccount"];
		$login_pwd = $_POST["userPassword"];

		$login_pwdMd5 = md5($login_pwd);

		$result = $sqlConn->fetchRow(
			"SELECT * FROM `todo_user` WHERE `login_name` = :name",
			array('name' => $login_name)
		);
		
		if ($result["login_pwd"] == $login_pwdMd5) {
			setcookie(COOKIE_PRIFIX_TODOS."id",$result["id"],time()+3600*2400,"/",COOKIE_DOMAIN);
		}

		break;
		
}

header("location: /todos/wap/");

?>