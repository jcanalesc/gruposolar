<?
require_once("header.php");
ini();
if (!isset($_POST['auth']) || $_POST['auth'] != md5(consts::$key))
{
   if (!isset($_POST['id_remate'])) die(consts::$mensajes[9]); // llamada invalida
}
if (isset($_POST['auth']))
   $_SESSION['rut'] = "1111";
// post trae el id del lote



$response = array(
                     'ganador' => 0,
                     'precioactual' => 0,
                     'eventos' => array(),
                     'tiempo' => 0, // en segundos
                     'tiempof' => "12:12:12", // en hh:mm:ss
                     'cuantofalta' => "",
                     'activo' => 1, // pa saber si estamos en el lote o aun no empieza
                     'sumo' => 0,
                     'chat' => array(),
                     'loteactual' => -1,
                     'nusers' => 0,
                     'users' => array(),
                     'ofertas' => 0,
                     'finremate' => 0,
                     'trestanteganador' => -1,
                     'habilitar' => 0,
                     'cantidaddisp' => 0,
                     'kickme' => 0
                 );
$r = mysql_query("select banned, disabled from users where rut = {$_SESSION['rut']}", dbConn::$cn);
if ($r)
{
   $row_kick = mysql_fetch_assoc($r);
   if ($row_kick['banned'] == "1" || $row_kick['disabled'] == "1")
      $response['kickme'] = 1;
}

   // Sera martillero?
   // Enviar numero y lista de usuarios conectados
   $idremate = mysql_real_escape_string($_POST['id_remate']);
   $tmp = mysql_fetch_assoc(mysql_query("select factor from remates where id_remate = $idremate", dbConn::$cn));
   
   $factor_remate = ($tmp['factor'] > 0 ? $tmp['factor'] : consts::$factor_usuarios);
   
   $r = mysql_query("insert into conectados_hoy (rut, id_remate) values ({$_SESSION['rut']}, $idremate) on duplicate key update logged = true, fecha = NOW(), id_remate = $idremate", dbConn::$cn);

   
   $query = "select rut from conectados_hoy where logged = true and id_remate = $idremate order by rut";
   $res = mysql_query($query, dbConn::$cn);
   if (!$res)
      rematelog("error: ".mysql_error(dbConn::$cn));
   else
   {
      $response['nusers'] = mysql_num_rows($res);
      if (esAdmin())
      {
         while($row = mysql_fetch_assoc($res))
            $response['users'][] = $row['rut'];
      }
      else
         $response['nusers'] = (int) round($factor_remate*$response['nusers']);
   }
   // con esta pura funcion la idea es saber todo lo que pasa en el remate:
   // precio actual del lote, rut ganador, actualizar los eventos, tiempo restante
   list($haylotes,$curso) = mysql_fetch_row(mysql_query("select IF(lote_actual is null, 0, 1) as haylotes, en_curso from remates where id_remate = $idremate", dbConn::$cn));
   
   if (!$haylotes)
   {
      echo assocArrayToXML("response", $response);
      die();
   }
   $query = "select la.tiempo_pausa, @rightnow:=IF(la.tiempo_pausa IS NULL, NOW(), la.tiempo_pausa) ,lotes.fecha_inicio, IF(la.tipo_puja = 'Sin Minimo', 1, productos.precio_min) as precio_min, TIME_TO_SEC(TIMEDIFF(lotes.fecha_termino, @rightnow)) as res, TIMEDIFF(lotes.fecha_termino, @rightnow) as tiempo, TIMESTAMPDIFF(SECOND, @rightnow, lotes.fecha_inicio) as cuantofaltaf, TIMEDIFF(lotes.fecha_inicio, @rightnow) as cuantofalta, lotes.orden, la.lote_actual from lotes, productos, (select lote_actual, tiempo_pausa, tipo_puja from remates where id_remate = {$_POST['id_remate']}) as la where lotes.id_lote = la.lote_actual and productos.id_producto = lotes.id_producto";
   $res = mysql_query($query, dbConn::$cn);
   if ($res === false || mysql_num_rows($res) < 1)
      dbConn::dbError($query);
   $row = mysql_fetch_assoc($res);
   mysql_free_result($res);
   $remate_pausado = (strlen($row['tiempo_pausa']) > 0 ? true : false);
   $id_lote = $row['lote_actual'];
   $orden_lote = $row['orden'];
   $response['tiempo'] = $t_restante = ($row['res']); // en segundos
   $response['tiempof'] = $row['tiempo']; // de la forma hh:mm:ss
   $response['cuantofalta'] = $row['cuantofalta'];
   $restante = $row['cuantofaltaf'];
   $response['precioactual'] = $row['precio_min'];
   $response['loteactual'] = $id_lote;

   $martillero = (esAdmin() ? " or receive = '".consts::$data[11]."' or sender in (".implode(",", consts::$data[7]).")" : "");
   $query = "select * from chat where (sender = '{$_SESSION['rut']}' or receive = '{$_SESSION['rut']}' or receive = '".consts::$data[6]."' {$martillero}) and id_remate = ".$_POST['id_remate'];
      $res = mysql_query($query, dbConn::$cn);
      if (!$res)
         dbConn::dbError($query);
      while($row = mysql_fetch_assoc($res)) $response['chat'][] = (in_array($row['sender'], consts::$data[7]) ? "Martillero" : $row['sender'])."|".$row['msg'].(in_array($row['sender'], consts::$data[7])? "|p|{$row['receive']}" : "");
      
      
   if ($restante <= 0)
   {
      if (isset($_POST['oferta'])) mysql_query("lock tables acciones, lotes, productos write", dbConn::$cn);  
      $query = "select acciones.id_accion, acciones.rut, acciones.monto, acciones.id_lote, acciones.tipo, productos.nombre from acciones join lotes using (id_lote) join productos using (id_producto) where acciones.id_lote = ".mysql_real_escape_string($id_lote);
      $event_array = array();
      $res = mysql_query($query, dbConn::$cn);
      if (!$res) dbConn::dbError($query);
      while($event_array[] = mysql_fetch_assoc($res));
      mysql_free_result($res);
      array_pop($event_array);
      $ganador = "";
      $ultimo_monto = $response['precioactual'];
      $marca_lote = "<font color=\"#001C8C\">(Lote {$orden_lote})</font>";
      foreach($event_array as $arr)
      {
         
         if ($arr['monto'] >= $ultimo_monto)
         {
            $ganador = $arr['rut'];
            $response['precioactual'] = $ultimo_monto = $arr['monto'];
         }
         switch($arr['tipo'])
         {
         case "Apuesta":
            $response['eventos'][] = "$marca_lote El usuario ".oculta($arr['rut'])." hizo una oferta de ".currf($arr['monto']);
            $response['ofertas']++;
            break;
         case "Adjudicacion":
            $response['eventos'][] = "$marca_lote El usuario ".oculta($arr['rut'])." se ha adjudicado el lote actual a ".currf($arr['monto']).". (lote {$orden_lote}: {$arr['nombre']})";
            break;
         default:
				$response['eventos'][] = "$marca_lote <span class=\"martmsg\">".$arr['tipo']."</span>";
			}
         
      }  
      // si se efectua una oferta, la aplico
      if (isset($_POST['oferta']) && $response['tiempo'] > 0 && ($response['ofertas'] == 0 ? $_POST['oferta'] >= $response['precioactual'] : $_POST['oferta'] > $response['precioactual']) && $id_lote == $_POST['lote'])
      {
         $res = true;
         $oferta = mysql_real_escape_string($_POST['oferta']);
         if (!$remate_pausado)
         {
            $query = "insert into acciones (id_lote, rut, monto, fecha, tipo) values ({$id_lote},{$_SESSION['rut']},{$oferta},NOW(),'Apuesta')";
            $res = mysql_query($query, dbConn::$cn);
         }
         if (isset($_POST['oferta'])) 
            mysql_query("unlock tables", dbConn::$cn);
         if (!$remate_pausado)
         {
            if (!$res)
               dbConn::dbError($query);
            $response['ganador'] = $_SESSION['rut'];
            if ($oferta > $response['precioactual'])
               $response['precioactual'] = $oferta;
            if ($t_restante < consts::$tiempo_limite) // Si estamos al final agregamos segundos
            {
               $response['sumo'] = 1;
               $query = "update lotes set fecha_termino=TIMESTAMPADD(SECOND, ".consts::$tiempo_adicional.", fecha_termino) where id_lote = {$id_lote}";
               $response['tiempo'] += consts::$tiempo_adicional;
               $res = mysql_query($query, dbConn::$cn);
               if (!$res) dbConn::dbError($query);
            }
         }
      }
      // fin aplicar oferta
      if ($response['tiempo'] <= 0) // ya termino
      {
         $response['activo'] = false;
         $response['eventos'][] = "$marca_lote El lote ha finalizado";
         mysql_query("lock tables acciones write");
         $res = mysql_query("select count(id_accion) from acciones where id_lote = {$id_lote} and tipo = 'Adjudicacion'", dbConn::$cn);
         list($conteo) = mysql_fetch_row($res);
         if ($conteo == 0)
         {
            $query = "insert into acciones (id_lote, rut, monto, fecha, tipo) values ({$id_lote},{$ganador},{$response['precioactual']},NOW(),'Adjudicacion')";
            $res2 = mysql_query($query, dbConn::$cn);
         }
         mysql_query("unlock tables");
         
		 
         list($cantidad, $fuerepartido) = mysql_fetch_row(mysql_query("select cantidad, repartido from lotes where id_lote = $id_lote", dbConn::$cn));
         if ($response['tiempo'] <= -consts::$tiempo_postlote / 2 && $cantidad > 1 && $fuerepartido == 0 && $response['ofertas'] > 0)
         {
            mysql_query("lock tables lotes write, remates, acciones", dbConn::$cn);
            $r = mysql_query("select TIMESTAMPDIFF(SECOND,NOW(),TIMESTAMPADD(SECOND,".consts::$tiempo_adj_ganador.", tiempo_pausa)) as tiempoe, tiempo_pausa from remates where id_remate = ".mysql_real_escape_string($_POST['id_remate']), dbConn::$cn);
            list($tiempo,$TP) = mysql_fetch_row($r);
            if (!$remate_pausado) pausar_remate($_POST['id_remate']);
            $response['trestanteganador'] = $tiempo;
            // Hay que enviar las unidades disponibles tambien
            $r_disp = mysql_query("select lotes.cantidad-IF(acc.sacadas is null, 0, acc.sacadas) as disponibles from lotes left join (select sum(cantidad) as sacadas, id_lote from acciones where tipo = 'Adjudicacion' group by id_lote) as acc on (lotes.id_lote = acc.id_lote) where lotes.id_lote = {$id_lote}", dbConn::$cn);
            list($disponibles) = mysql_fetch_row($r_disp);
            $response['cantidaddisp'] = $disponibles;
            if ($tiempo <= consts::$tiempo_adj_ganador / 2)
               $response['habilitar'] = 1;
            if (($tiempo <= 0 && $remate_pausado) || $disponibles == 0)
            {
               mysql_query("update lotes set repartido = true where id_lote = ".$id_lote, dbConn::$cn);
               reanudar_remate($_POST['id_remate']);
               $response['trestanteganador'] = -1;
            }
            mysql_query("unlock tables", dbConn::$cn);
         }
         
         if ($response['tiempo'] <= -consts::$tiempo_postlote && !$remate_pausado)
         {
				mysql_query("update lotes set repartido = true where id_lote = ".$id_lote, dbConn::$cn);
				reanudar_remate($_POST['id_remate']);
				$response['trestanteganador'] = -1; 
            $termino = pasar_sgte_lote($_POST['id_remate']);
            if ($termino === true)
            {
					$response['finremate'] = 1;
            }
         }
         $response['tiempo'] = 0;
      }
      if ($ganador == $_SESSION['rut'])
         $response['ganador'] = 1;
   }
   else
   {
      $response['activo'] = false;
   }
   echo assocArrayToXML("response",$response);
?>
