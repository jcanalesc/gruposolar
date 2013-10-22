<?
	include("header.php");
	// Envia una adjudicacion adicional de parte del usuario de la sesion actual en el lote $_POST['lote']
	ini();
	$rut_rev = "";
	if (isset($_POST['rut']))
	{
		$rut_rev = $_POST['rut'];
		unset($_POST['rut']);
	}
	else
		$rut_rev = $_SESSION['rut'];
	if (!esAdmin())
	{
      if (!isset($_POST['lote']) || !isset($_POST['cantidad']) || count($_POST) > 3 || count($_POST) < 2) die(consts::$mensajes[8]);
      if (!is_numeric($_POST['lote']) || !is_numeric($_POST['cantidad'])) die(consts::$mensajes[8]);
	}

	$q1 = "select id_accion, monto from acciones where rut = $rut_rev and tipo = 'Adjudicacion' and id_lote = {$_POST['lote']}";
	$res = mysql_query($q1, dbConn::$cn); 

	if (mysql_num_rows($res) > 0)
	{
		list($ida, $monto) = mysql_fetch_row($res);
		$cantidad = $_POST['cantidad'];
		$q2 = "update acciones set cantidad = cantidad + $cantidad where id_accion = $ida";
		$res2 = mysql_query($q2, dbConn::$cn);
		if ($res2 !== false) echo "yes"; else echo mysql_error();
	}
	else
	{
		$qwery = "select IF(acciones.monto is null, f.precio_min, acciones.monto) as precio from (select id_lote, orden, cantidad, precio_min, descripcion from lotes join productos using (id_producto) where id_lote = {$_POST['lote']}) as f left join acciones using (id_lote) where (acciones.tipo = 'Adjudicacion' or acciones.tipo is null) group by id_lote";
		$res3 = mysql_query($qwery, dbConn::$cn);
		list($precio) = mysql_fetch_row($res3);
		$q3 = "insert into acciones (rut, id_lote, monto, tipo, cantidad) values ($rut_rev, {$_POST['lote']}, $precio, 'Adjudicacion', {$_POST['cantidad']})";
		$res4 = mysql_query($q3, dbConn::$cn);
		if ($res4 !== false) echo "yes"; else echo mysql_error()."=".$q3;
	}
?>
