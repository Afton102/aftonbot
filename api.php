<title>api</title>
<?php
//header('Content-type: text/html; charset=UTF-8');
if($_GET){
	$_gett=$_GET;
	//echo $_gett['u'];
	echo("<p>");
	foreach($_gett as $key=>$val){
		echo($key." = ".$val."\n");
	}
	unset($value);
	echo("</p>");
}
?>