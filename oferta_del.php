<?
	include("header.php");
	include("DB.inc.php");

	if (!adminGeneral()) die("error");
	$ido = mysql_real_escape_string($_GET['id']);
	$res = $db->query("delete from ofertas where id_oferta = $ido");
	if ($res)
		die("ok");
	else
		die("error");
?>