<?php
/**
 * 导入SQL文件
 * 
 * @author David
 * @date 2007/09/08
 */

require_once("/var/www/virtualhost/rdasewin/dasewing.com/public_html/mr/module/Key/Key.php");
require_once("Util/sql.php");

$i = 1;
$handle = fopen("./export.sql","r");
$sqlConn = SQL::getConn("com_test");

$timeStart = time();

while (!feof($handle)) {
	$buffer = fgets($handle,4096000);
	if (strpos($buffer,"INSERT INTO") !== false) { 
		$sql = $buffer;
	} else {
		
		if (!empty($sql)) {
			if (strpos($buffer,");") === false) {
				$sql .= $buffer;
			} else {
				$sql .= $buffer;
				$sqlConn -> query($sql);
				echo "line ".$i++." ok ~~ <br />\n";
				unset($sql);
			}
		}
	}
	
	if (time() - $timeStart > 5) {
		mysql_close();
		sleep(1);
		ob_flush();
		$sqlConn = SQL::getConn("com_test");
		$timeStart = time();
	}
}
fclose($handle);

?>