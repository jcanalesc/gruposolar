<?
	include("header.php");
	include("DB.inc.php");
	if (!esAdmin()) die();

	$idp = mysql_real_escape_string($_GET['idp']);

	$res = $db->query("update productos set visible = false where id_producto = $idp");
	if ($res !== true)
		die("error");
	else
		die("done");
?>