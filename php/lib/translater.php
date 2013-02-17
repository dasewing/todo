<?PHP

/**
 * 转换器（各种各样的）
 *
 */
class Translater {

	/**
	 * 将数组中指定字段的time转化为日期格式
	 *
	 * @param array $sourceArray 	待处理的数组
	 * @param array $timeColArray 	需转换的字段名数组
	 * @param string $formatString 	日期格式化参数
	 * @return array 格式化完毕的数组
	 */
	public static function timeToDate($sourceArray,$timeColArray,$formatString="Y-m-d H:i:s") {
		
		if (empty($sourceArray)) return array();
		
		if (is_array($sourceArray[0])) {
			foreach ($sourceArray as $no => $value) {
				foreach ($timeColArray as $k => $v) {
					$sourceArray[$no][$v] = empty($sourceArray[$no][$v]) ? "" : date($formatString,$sourceArray[$no][$v]);
				}
			}
			return $sourceArray;
		}

		foreach ($timeColArray as $k => $v) {
			$sourceArray[$v] = date($formatString,$sourceArray[$v]);
		}

		return $sourceArray;
	}

	/**
	 * 将数组中指定字段的反序列化
	 *
	 * @param array $sourceArray 	待处理的数组
	 * @param array $timeColArray 	需转换的字段名数组
	 * @return array 格式化完毕的数组
	 */
	public static function unserializeCols($sourceArray,$colArray) {
		
		if (empty($sourceArray)) return array();
		
		if (is_array($sourceArray[0])) {
			foreach ($sourceArray as $no => $value) {
				foreach ($colArray as $k => $v) {
					$sourceArray[$no][$v] = unserialize($sourceArray[$no][$v]);
				}
			}
			return $sourceArray;
		}

		foreach ($colArray as $k => $v) {
			$sourceArray[$v] = unserialize($sourceArray[$v]);
		}

		return $sourceArray;
	}
	
	/**
	 * 将数组中指定字段的\n转化为<br />
	 *
	 * @param array $sourceArray 	待处理的数组
	 * @param array $nlColArray 	需转换的字段名数组
	 * @return array 格式化完毕的数组
	 */
	public static function nlToBr($sourceArray,$nlColArray) {
		
		if (empty($sourceArray)) return array();
		
		if (is_array($sourceArray[0])) {
			foreach ($sourceArray as $no => $value) {
				foreach ($nlColArray as $k => $v) {
					$sourceArray[$no][$v] = nl2br($sourceArray[$no][$v]);
				}
			}
			return $sourceArray;
		}

		foreach ($nlColArray as $k => $v) {
			$sourceArray[$v] = nl2br($sourceArray[$v]);
		}

		return $sourceArray;
	}
	
	/**
	 * 选择…… 
	 * <code>return empty($inValue) ? $default : $inValue;</code>
	 * 
	 * @param mixed $inValue
	 * @param mixed $default
	 * @return mixed
	 */
	function thisOR($inValue,$default) {
		return empty($inValue) ? $default : $inValue;
	}
}

?>