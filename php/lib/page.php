<?PHP

class Page {

	public static function makePageTurner($nowPage,$perPage,$count) {
		
		$nowHref = $_SERVER["SCRIPT_NAME"]."?";
		
		foreach ($_GET as $k => $v) {
			$nowHref .= $k=="nowPage"||$k=="perPage" ? "" : "&$k=".urlencode($v);
		}
		
		$totalPage = ceil((float)$count/(float)$perPage);
		
		$returnHTML = "一共<strong>$count</strong>条 分为<strong>$totalPage</strong>页 每页<strong>$perPage</strong>条 [<strong>$nowPage</strong>/$totalPage]  ";
		$returnHTML .= $nowPage == 1 ? "上页 " : "<a href='$nowHref&nowPage=".($nowPage-1)."&perPage=".($perPage)."'>上页</a> ";
		$returnHTML .= $nowPage == $totalPage ? "下页 " : "<a href='$nowHref&nowPage=".($nowPage+1)."&perPage=".($perPage)."'>下页</a> ";
		
		$returnHTML .= "去 <select name='gotoPage' nowHref='$nowHref&perPage=".($perPage)."' onchange='gotoPage(this)'>";
		
		for ($i = 1 ; $i <= $totalPage ; $i++) {
			
			$returnHTML .= $nowPage == $i ? "<option selected=true value='$i'><strong>$i</strong></option>" : "<option value='$i'>$i</option>";
		}
		
		$returnHTML .= "</select> 页";

		return $returnHTML;
		
	}

}

?>