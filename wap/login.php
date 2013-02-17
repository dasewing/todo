<?php



?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html >
<head>
<title> todo~S Demo </title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css" >
body {
	min-width:320px; overflow-x:hidden; margin:0; padding:0;
}
</style>
</head>
<body>

<form method="post" action="/todos/wap/c/" >

	<input type="hidden" name="cp" value="u_login" />

	<p>
		<label for="userAccount">用户名：</label>
		<input id="userAccount" name="userAccount" type="text" />
	</p> 
	
	<p>
		<label for="userPassword">密　码：</label>
		<input id="userPassword" name="userPassword" type="password" />
	</p>
	
	<p>
		<input type="submit" value="提交" />
	</p> 

</form>

</body>
</html>