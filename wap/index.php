<?php

require_once("key/k.php");
require_once(ROOT_PATH."todos/php/lib/sql.php");

$userID = $_COOKIE[COOKIE_PRIFIX_TODOS."id"];

if (empty($userID)) {
	require_once("login.php");
} else {
	require_once("list.php");
}

?>