<?php 

if(isset($_REQUEST['updates'])){

	$xml = $_REQUEST['updates'];
	$connect = mysql_connect("localhost","root","bitnami");
	if (!$connect)
	{
	  die('Could not connect: ' . mysql_error());
	}

	mysql_select_db("fitbit", $connect);
	$sql = "INSERT INTO Updates(message)
			VALUES ('". mysql_real_escape_string($xml) ."')";
	if (!mysql_query($sql,$connect))
	{
	  die('Error: ' . mysql_error());

	}

	mysql_close($connect);
}

	header('HTTP/1.0 204 No Content');
	header('Content-Length: 0',true);
	header('Content-Type: text/html',true);
	flush();

 ?>