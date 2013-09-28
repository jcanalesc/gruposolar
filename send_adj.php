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
	// revisar cantidad (cuantos quedan) y si me alcanzan, me wiño
	mysql_query("lock tables acciones write, lotes write, users, productos");
	$idl = mysql_real_escape_string($_POST['lote']);

	$qwery = "select f.cantidad, IF(acciones.monto is null, f.precio_min, acciones.monto) as precio from (select id_lote, orden, cantidad, precio_min, descripcion from lotes join productos using (id_producto) where id_remate = $idr) as f left join acciones using (id_lote) where (acciones.tipo = 'Adjudicacion' or acciones.tipo is null) and f.id_lote = $idl group by id_lote";

	list($total, $preciof) = mysql_fetch_row(mysql_query($qwery,dbConn::$cn));
	list($ocupados) = mysql_fetch_row(mysql_query("select sum(cantidad) from acciones where tipo = 'Adjudicacion' and id_lote = {$_POST['lote']}", dbConn::$cn));
   
	if (esAdmin() || ($total - $ocupados > 0 && !isset($_SESSION['adjudicado'][$_POST['lote']]) || $_SESSION['adjudicado'][$_POST['lote']] == false))
	{
		if (!esAdmin())
      {
         $_POST['cantidad'] = ($total - $ocupados >= $_POST['cantidad'] ? $_POST['cantidad'] : $total - $ocupados);
         $_SESSION['adjudicado'][$_POST['lote']] = true;
      }
      
		// comprobar si ya me adjudique este lote
		$res = mysql_query("select id_accion from acciones where tipo = 'Adjudicacion' and id_lote = {$_POST['lote']} and rut = $rut_rev", dbConn::$cn);
		$aq = "";
		if (mysql_num_rows($res) > 0)
		{
			list($id_acc) = mysql_fetch_row($res);
			$aq = "update acciones set cantidad = cantidad+{$_POST['cantidad']} where id_accion = {$id_acc}";
		}
		else
		{
			$aq = "insert into acciones (rut, id_lote, monto, tipo, cantidad) values ($rut_rev,{$_POST['lote']},$preciof,'Adjudicacion',{$_POST['cantidad']})";
		}
		echo $aq;
		mysql_query($aq, dbConn::$cn) or dbConn::dbError($aq);
		rematelog("El usuario $rut_rev ha adjudicado {$_POST['cantidad']} unidades del lote {$_POST['lote']} a \$$preciof");
		echo "yes";
	}
	else
		echo "no";
	mysql_query("unlock tables");	
?>
