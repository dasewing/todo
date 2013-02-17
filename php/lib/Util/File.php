<?

class UtilFile {
	
	public static function getDirTree($root) {

		function rec_scandir($dir) {
			$files = array();
			if ( $handle = opendir($dir) ) {
				while ( ($file = readdir($handle)) !== false ) {
					if ( $file != ".." && $file != "." ) {
						if ( is_dir($dir . "/" . $file) ) {
							$files[$file] = rec_scandir($dir . "/" . $file);
						}else {
							$files[] = $file;
						}
					}
				}
				closedir($handle);
				return $files;
			}
		}

		function cmp($a, $b) {
			if ( is_string($a) && is_string($b) ) {
				return strcmp($a, $b) > 0 ? 1 : -1;
			}elseif ( is_int($a) && is_int($b) ) {
				return $a > $b ? 1 : -1;
			}elseif ( is_int($a) && is_string($b) ) {
				return 1;
			}elseif ( is_string($a) && is_int($b) ) {
				return -1;
			}else {
				return 0;
			}
		}

		function array_ukmultisort(&$arr, $func) {
			uksort($arr, $func);
			while ( list($key, $val) = each($arr) ) {
				if ( is_array($val) ) {
					array_ukmultisort($arr[$key], $func);
				}
			}
		}

		$dir = rec_scandir($root);
		array_ukmultisort($dir, "cmp");
		return $dir;
	}

	public static function getAllFileList($rootDir="") {

		function getThisDeepFiles($path,$parentPath) {
			$finalOutput = array();
			foreach ($path as $key => $value) {
				if (strpos($key,".svn")!== false) {
					continue;
				}
				if (is_array($value)) {
					$finalOutput = @array_merge($finalOutput,getThisDeepFiles($value,$parentPath."/".$key));
				} else {
					$finalOutput[] = $parentPath."/".$value;
				}
			}
			return $finalOutput;
		}
		
		$fileTree = self::getDirTree($rootDir);
		$finalOutput = getThisDeepFiles($fileTree,$rootDir);
		
		return $finalOutput;
	}
	
}

?>