<?
	include("header.php");
	include("DB.inc.php");

	ini();

	$rut = $_SESSION['rut'];

	$rows = $db->query("select autorizado_rsm from users where rut = $rut");

	header("Content-type: application/json");

	if ($rows[0]['autorizado_rsm'] == "1")
		exit("true");
	else
		exit("false");

?>