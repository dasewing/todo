<?

require_once("key/k.php");
require_once(ROOT_PATH."todos/php/lib/sql.php");

$userID = $_COOKIE[COOKIE_PRIFIX_TODOS."id"];

$cotrollerParams = $_POST["cp"];
$cotrollerParamsSplit = explode("_",$_POST["cp"]);

if (is_file("{$cotrollerParamsSplit[0]}.php")) {
	$paramCase = $cotrollerParamsSplit[1];	
	require_once("{$cotrollerParamsSplit[0]}.php");
}

?>