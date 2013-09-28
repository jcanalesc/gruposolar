<?php
	include("header.php");
	include("DB.inc.php");

	header("Content-type: application/json");
	if (!adminGeneral()) die("false");
	if (!isset($_GET['idmr'])) die("false");

	$idmr = mysql_real_escape_string($_GET['idmr']);

	if (isset($_GET['texto']))
	{
		$texto = mysql_real_escape_string($_GET['texto']);
		$res = $db->query("update miniremates set info = '$texto' where id_miniremate = $idmr");
		exit($res ? "true" : "false");
	}

	if(isset($_GET['pago']))
	{
		$pago = mysql_real_escape_string($_GET['pago']);
		$res = $db->query("update miniremates set pagado = $pago where id_miniremate = $idmr");
		exit($res ? "true" : "false");
	}

?>