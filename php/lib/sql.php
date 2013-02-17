<?PHP
require_once(ZF_PATH."Zend/Db.php");

class SQL {

	/**
	 * 创建过的连接
	 *
	 * @var array
	 */

	private static $connectedLinks=array();

	/**
	 * 生成连接
	 *
	 * @param string $dbName
	 * @param array $dbParam
	 * @return resource
	 */
	public static function getConn($dbName="", $dbParam=array()){
		
		$dbLocal=array(
			"dbServer"		=> DB_HOST,		//数据库主机
			"dbUser"		=> DB_UESR_NAME,		//数据库用户
			"dbPassword"	=> DB_UESR_PASSWORD,		//数据库密码
			"dbType" 		=> "pdo_mysql",	//数据库连接类型
			"dbDefault"		=> DB_NAME_DEFAULT	//默认数据库
		);

		$dbParamString=!empty($dbParam)?"dbParam":"dbLocal";
		
		if(!is_array($$dbParamString)){
			die("DB参数错误!");
		}

		$currentParam=$$dbParamString;
		$dbName=!empty($dbName)?$dbName:$currentParam["dbDefault"];

		if(!isset($connectedLinks[$dbParamString])){
			$connectedLinks[$dbParamString]=self::connect($dbName,$currentParam);
		}
		return $connectedLinks[$dbParamString];
	}

	/**
	 * 连接数据库
	 *
	 * @param string $dbName
	 * @param array $currentParam
	 * @return object resource
	 */

	private static function connect($dbName,array $currentParam){
		
		for ($i=0;$i<3;$i++){
			try {
				$params = array (
				'host' => 		$currentParam["dbServer"],
				'username' => 	$currentParam["dbUser"],
				'password' => 	$currentParam["dbPassword"],
				'dbname' => 	$dbName
				);
				$db = Zend_Db::factory($currentParam["dbType"], $params);
				break;
			} catch (Exception $e) {
				sleep(10);
			}
		}
		if(empty($db)){
			if(SQL_DEBUG===true){
				print_r($params);
				print_r($e);
			}
			die("DB连接失败!");
		}
		$db->query("set NAMES 'utf8'");
		return $db;
	}

}

?>