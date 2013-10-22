<?php
	include ("../DB.inc.php");
	header("Content-type: application/json");
	$query = sprintf("select * from chat where id > %u", mysql_real_escape_string($_GET['ts']));
	$chats = $db->query($query);

	$msgarray = array();

	foreach ($chats as $row)
	{
		$msgarray[] = array(
			"ts" => $row["id"],
			"from" => $row["sender"],
			"msg" => $row["msg"]
			);
	}

	exit(json_encode(array("messages" => $msgarray, "error" => false)));
?>