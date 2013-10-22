<?php
	// message, user, ts
	include ("../DB.inc.php");
	$query = sprintf("insert into chat (sender, msg) values ('%s', '%s')", mysql_real_escape_string($_GET['user']), mysql_real_escape_string($_GET['message']));

	$res = $db->query($query);

	$retObj = array("error" => false);

	if ($res === false)
	{
		$retObj["error"] = "No se pudo enviar su mensaje.";
	}

	exit(json_encode($retObj));
?>