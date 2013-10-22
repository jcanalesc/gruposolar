<?

	// oferta_history.php
	// recibo el ido, retorna arreglo de objetos
	include("header.php");
	include("DB.inc.php");

	header("Content-type: application/json");

	$ido = mysql_real_escape_string($_GET['ido']);


	$lineas = $db->query("select rut_usuario as ganador, comment, pagado from ofertas_compradas where id_oferta = $ido");
	if ($lineas !== false)
		echo json_encode($lineas);
	else
		echo "null";

?>
