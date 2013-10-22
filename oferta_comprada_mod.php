<?
	include("header.php");
	include("DB.inc.php");
	header("Content-type: application/json");

	if (!adminGeneral()) die("false");

	$ido = mysql_real_escape_string($_GET['ido']);
	$rut = mysql_real_escape_string($_GET['rut']);
	$param = mysql_real_escape_string($_GET['param']);
	$val = mysql_real_escape_string($_GET['val']);

	if ($param != "pagado")
		$val = "'$val'";

	$q = "update ofertas_compradas set $param = $val where id_oferta = $ido and rut_usuario = $rut";

	$res = $db->query($q);

	if ($res !== false)
		die("true");
	else
		die("false");


?>