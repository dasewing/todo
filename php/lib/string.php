<?PHP
/**
 * 字符串处理
 * @package Class
 * 
 * @author peter
 */
class STRING_CLASS {
	/**
	 * 格式化字符串
	 *
	 * 主要用于将输入字符串进行不同的编码，以适应不同场合下的安全需要。
	 *
	 * @param string $string input var
	 * @param string $type filer type
	 * @return string
	 * @access public
	 */	
	public static function formatString($string, $type = "ENCODE") {
		if(!empty($string)) {
			switch(strtoupper($type)) {
				case "ENCODE":
				case "TEXT":
				case "TEXTAREA": {
					$chars = array(
					'&' => '&#38;',
					'"' => '&quot;',
					"'" => '&#039;',
					"<" => '&lt;',
					">" => '&gt;',
					"{" => '&#123;',
					"}" => '&#125;',
					"\\" => '&#92;'
					);
					$string = strtr($string, $chars);
					break;
				}
				case "DECODE": {
					$chars = array(
					'&quot;' => '"',
					'&#039;' => "'",
					'&lt;' => "<",
					'&gt;' => ">",
					'&#123;' => "{",
					'&#125;' => "}",
					'&#92;' => "\\",
					'&#38;' => '&',
					'&amp;' => '&'
					);
					$string = strtr($string, $chars);
					break;
				}
				case "GET":
				case "POST": {
					$string = str_replace("\\\\" , "\\" , $string);
					$string = ereg_replace("[\]'" , "'" , $string);
					$string = ereg_replace("[\]\"" , "\"" , $string);
					break;
				}
				case "POSTAGAIN": {
					$string = str_replace("\\\\" , "\\" , $string);
					$string = ereg_replace("[\]'" , "'" , $string);
					$string = ereg_replace("[\]\"" , "\"" , $string);
					$string = str_replace("\\\\" , "\\" , $string);
					$string = ereg_replace("[\]'" , "&#039;" , $string);
					$string = ereg_replace("[\]\"" , "&quot;" , $string);
					$string = ereg_replace('<','&lt;', $string);
					$string = ereg_replace(">","&gt;", $string);
					break;
				}
			}
			return trim($string);
		}
	}

	/**
	 * 截取字符串，中文英文都当做是一位
	 *
	 * @param string $str 		输入的字符串
	 * @param int $length 		需要截取的长度
	 * @param int $start  		截取的起始位置
	 * @param string $charset	字符串的编码(utf-8、gb2312、gbk、big5)
	 * @param boolean $suffix	是否要在未尾加上 ...
	 * @return string  截取的字符串内容
	 * @author peter
	 * @version 1.0
	 */
	public static function stringSubstr($str, $length, $start=0, $charset="utf-8", $suffix=true) {
		if(function_exists("mb_substr")) {
			return mb_substr($str, $start, $length, $charset);
		}
		$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
		$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
		$re['gbk']  = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
		$re['big5']  = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
		preg_match_all($re[$charset], $str, $match);
		$slice = join("",array_slice($match[0], $start, $length));
		if($suffix and ($start+$length) < (count($match[0]))) {
			return $slice."…";
		}
		return $slice;
	}

	/**
	 * 截取字符串，中文英文都当做是一位
	 *
	 * @param string $str 		输入的字符串
	 * @param int $length 		需要截取的长度
	 * @param int $start  		截取的起始位置
	 * @param string $charset	字符串的编码(utf-8、gb2312、gbk、big5)
	 * @param boolean $suffix	是否要在未尾加上 ...
	 * @return string  截取的字符串内容
	 * @author peter
	 * @version 1.0
	 */
	public static function stringLength($str, $charset="utf-8") {
		$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
		$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
		$re['gbk']  = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
		$re['big5']  = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
		preg_match_all($re[$charset], $str, $match);
		return count($match[0]);
	}

	/**
	 * 随机码
	 *
	 * 根据传入密文编码及字符串长度，生成一个随机字符
	 *
	 * @param integer $len var len
	 * @param string $string input code
	 * @return string
	 * @access private
	 */
	public static function randCode($len , $string = ""){
		if(empty($string)) {
			$str = "123456789ABCDEFGHJKLMNPQRSTUVWXYZ";
		} else {
			$str = $string;
		}
		$length = strlen( $str );
		for($i=0; $i<$len; $i++){
			$Passwd .= substr($str, rand(0,$length-1), 1);
		}
		return $Passwd;
	}

	/**
	 * 生成随机码
	 *
	 * 传入一个明文，及一个密文字典，生成一个附带随机编码的字符串，同一明文、密文，每次生成返回值都不同
	 *
	 * @param integer $ID
	 * @param string $keyWord
	 * @return string
	 * @access public
	 */
	public static function encodeIDUrl($ID, $keyWord = ""){
		if(!empty($keyWord)) {
			$key = $keyWord.STRING_CLASS::randCode(3, "ABCDEFGHJKLMNPQRSTUVWXYZ");
		} else {
			$key = STRING_CLASS::randCode(4, "ABCDEFGHJKLMNPQRSTUVWXYZ");
		}
		return $ID.$key.md5($ID.$key);
	}

	/**
	 * 解密随机码
	 *
	 * 传入一个密文，返回明文
	 *
	 * @param integer $string
	 * @param string $type
	 * @return string
	 * @access public
	 */
	public static function decodeIDUrl($string, $type = "id"){
		preg_match("/(\d+)(.*)/i", $string, $matches);
		if(substr($string, 0, 1) == "-") {
			$ID		= $matches[1] * (-1);
		} else {
			$ID		= $matches[1];
		}
		$key	= substr($matches[2], 0, 4);
		if(substr($matches[2], 4) == md5($ID.$key)) {
			if($type == "id") {
				return $ID;
			} else if($type == "key") {
				return substr($key, 0, 1);
			}
		} else {
			return false;
		}
	}

	/**
     * 把xxxx-xx-xx形式的日期转换为unix时间戳
     *
     * @param unknown_type $dateStr
     * @return unknown
     */    
	public static function getTimeStamp($dateStr){
		if(!preg_match("/^\d{4}-\d{2}-\d{2}$/",$dateStr)){
			return -1;
		}
		$dateA	=explode("-",$dateStr);
		if($dateA[0]>2100 || $dateA[0]<1800){
			return -2;
		}
		if($dateA[1]>12 || $dateA[1]<1){
			return -3;
		}
		if($dateA[2]>31 || $dateA[2]<1){
			return -4;
		}
		return mktime(0,0,0,$dateA[1],$dateA[2],$dateA[0]);
	}

	/**
    * 格式化html内容中的无用的内容
    *
    * @param String $fileContent	需要格式化的内容
    */
	public static function formatHtmlContent($fileContent) {
		$fileContent = str_replace("\r\n", "\n", $fileContent);

		//格式化内容
		$searchArray = array("/<!-- (.*) -->/",  "/\n\n/", "/\r\n\n/");
		$replaceArray = array("",  "\n", "\n");
		$fileContent = preg_replace($searchArray, $replaceArray, $fileContent);
		return $fileContent;
	}


	/**
	 * 把地址参数串格式化
	 *
	 * @param string $char	需要处理的参数，以","号为分隔符	
	 * @param string $type	处理类型,delete:去除指定的参数;  hold:保留指定的参数
	 * @return 格式化后的地址参数串
	 */
	public static function formatQuery($char , $type = "delete") {
		if(!empty($char)) {
			parse_str(QUERY_STRING, $outPut);
			$characterElements = explode("," , $char);
			if($type == "delete") {			//去除指定的参数
				while (list ($key, $val) = @each ($outPut)){
					if(!in_array ($key, $characterElements) and $key != '') {
						$newQueryString .= "&".$key."=".urlencode(STRING_CLASS::formatString($val,1));
					}
				}
			} elseif($type == "hold") {		//保留指定的参数
				while (list ($key, $val) = each ($outPut)) {
					if(in_array ($key, $characterElements)) {
						$newQueryString .= "&".$key."=".urlencode(STRING_CLASS::formatString($val,1));
					}
				}
			}
			return $newQueryString;
		} else {
			return QUERY_STRING;
		}
	}
	
	/**
	 * 格式化查询关键字
	 *
	 * @param string $key	需要格式化的关键字
	 * @return string		格式化后的关键字
	 */
	public static function formatSearchKey($key) {
		if(!empty($key)) {
			$bakWord = array(",", "\\",  ";",  "\"", "!", "~", "`", "^", "?", "\t", "\n", "'", "\r", "\r\n", "$", "&", "%", "#", "@", "=", "[", "]", "：", "）", "（", "．", "。", "，", "！", "；", "“", "”", "‘", "’", "［", "］", "、", "—",  "－", "…","(", ")", "“", "”", "　", "*", "×", "-");
			//替换所有的分割符为空
			$key  = str_replace($bakWord, ' ', $key);
			$key  = str_replace("：", ':', $key);
			$key  = str_replace("所有", ' ', $key);
			$key  = str_replace("+", ' ', $key);
			return trim($key);
		} else {
			return "";
		}
	}
	
	/**
	 * 字符串显示长度
	 *
	 * @param string $str
	 * @param string $charset="utf-8"
	 */
	
	public static function stringDisplayLength($str,$charset="utf-8"){
		$re['utf-8']   = "/[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
		$re['gb2312'] = "/[\xb0-\xf7][\xa0-\xfe]/";
		$re['gbk']  = "/[\x81-\xfe][\x40-\xfe]/";
		$re['big5']  = "/[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
		$ascii="/[\x01-\x7f]/";

		$strLength=preg_match_all($ascii,(string)$str,$match);
		$doubleCharCount=preg_match_all($re[$charset],(string)$str,$match);
				
		return $strLength+$doubleCharCount*2;
	}
	
	/**
	 * 截取字符串
	 *
	 * @param string $str
	 * @param int $length	总的的长度
	 * @param string $followedSign="..."
	 */
	
	public static function stringLimit($str,$length,$followedSign="…",$charset="utf-8"){
		//todo:效率较低，需要优化
		
		if(self::stringDisplayLength($str,$charset)<=$length){
			return $str;
		}
		$length=$length<6?6:$length;
		
		$re['utf-8']   = "/[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
		$re['gb2312'] = "/[\xb0-\xf7][\xa0-\xfe]/";
		$re['gbk']  = "/[\x81-\xfe][\x40-\xfe]/";
		$re['big5']  = "/[\x81-\xfe]([\x40-\x7e]|[\xa1-\xfe])/";
		
		$ascii="/[\x01-\x7f]/";
		
		$strLength=preg_match_all($ascii,$str,$match);
		$doubleCharCount=preg_match_all($re[$charset],$str,$match); 
		$strLength=$strLength+$doubleCharCount;

		$nowLength=0;
		$followedSignLen=self::stringDisplayLength(strip_tags($followedSign),$charset);
		$result="";
		
		for ($i=0;$i<$strLength;$i++){
			$nowChar=mb_substr($str,$i,1,$charset);
			$charLength=preg_match($re[$charset],$nowChar)?2:1;
			if($nowLength+$charLength<=$length-$followedSignLen){
				$result.=$nowChar;
				$nowLength+=$charLength;
			}else{
				$result.=$followedSign;
				break;
			}
		}
		
		return $result;
	}	
	
	/**
	 * 生成n位随机码
	 *
	 * @param unknown_type $length
	 * @param unknown_type $rndCharTemplate
	 * @return unknown
	 */
	public static function makeRndCode($length,$rndCharTemplate=""){
		if(empty($rndCharTemplate)){
			$rndCharTemplate="QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm1234567890";
		}
		for ($i=0;$i<$length;$i++){
			$rndChar.=$rndCharTemplate[rand(0,strlen($rndCharTemplate)-1)];
		}
		return $rndChar;
	}	
	
}
//测试内容
//echo String::stringSubstr("我是abcABC的人们",11)."\n";
//echo STRING_CLASS::formatSearchKey("我是的(人们我-- 是的人们)我是的人们",11);

?>