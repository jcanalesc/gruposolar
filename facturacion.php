<? include("header.php"); 
   $qq = mysql_query("select id_remate, comision from remates", dbConn::$cn);
   $coms = array();
   while($row = mysql_fetch_row($qq))
      $coms[$row[0]] = $row[1];
?>
<script type="text/javascript">
var comisiones = <?= json_encode($coms) ?>;
var valor_iva = <?= consts::$iva ?>;
</script>
<script language="javascript" src="facturacion_script.js"></script>
<?
   if (isset($_SESSION['rut']))
   {
      if (strspn($_SESSION['rut'], "0123456789") != strlen($_SESSION['rut']) )
         die(consts::$mensajes[8]);
      if (!esAdmin() && (!isset($_SESSION['rut']) || $_SESSION['rut'] != $_SESSION['rut'] ))
         die(consts::$mensajes[9]);
      // Solicita info del usuario
      /*
       * En que remates ha participado? En que lotes?
       * Cuantas ofertas en total ha hecho?
       * Que lotes ha adjudicado y a que precio?
       **/
      
      $queryremate = (isset($_GET['id_remate']) ? " and lotes.id_remate = {$_GET['id_remate']}" : NULL);
      $rutg = mysql_real_escape_string($_SESSION['rut']);
      $query = "select lotes.id_remate, lotes.orden, productos.descripcion, acciones.cantidad*productos.subunidades as cantidad, acciones.fecha, acciones.monto, acciones.tipo from acciones join lotes using (id_lote) join productos using (id_producto) where acciones.tipo = 'Adjudicacion' and acciones.rut = $rutg $queryremate UNION ALL select CONCAT('MR',miniremates.id_miniremate) as id_remate, 1 as orden, productos.descripcion, 1 as cantidad, miniremates.fecha_termino as fecha, miniremates.monto_actual as monto, 'Adjudicacion' as tipo from miniremates join productos using (id_producto) where miniremates.rut_ganador = $rutg";
      $res = mysql_query($query, dbConn::$cn) or dbConn::dbError($query);
      $ofertas = array();
      $adjudicaciones = array();
      $remates = array();
      while($row = mysql_fetch_assoc($res))
      {
         if ($row['tipo'] == "Adjudicacion")
            $adjudicaciones[] = $row;
         else if ($row['tipo'] == "Apuesta")
            $ofertas[] = $row;
         if (!isset($remates[$row['id_remate']]))
            $remates[$row['id_remate']] = 1;
         else
            $remates[$row['id_remate']]++;
      }
      mysql_free_result($res);
      $remates_p = count($remates);
      
      $max_adj = array(0, NULL, NULL);
      foreach($adjudicaciones as $elem)
         if ($elem['monto'] > $max_adj[0])
            $max_adj = array($elem['monto'], $elem['id_remate'], $elem['orden']);
      $max_adj[0] = currf($max_adj[0]);
      $total_adj = count($adjudicaciones);
      $total_of = count($ofertas);
      $contenido_select_remates = "";
      foreach(array_keys($remates) as $k)
        $contenido_select_remates .= "<option value=\"$k\">Remate ID: $k</option>";
        /*
      $res = mysql_query("select id_remate, fecha, hora from remates where id_remate in (select lotes.id_remate from lotes, acciones where lotes.id_lote = acciones.id_lote and acciones.rut = {$_SESSION['rut']} and acciones.tipo = 'Adjudicacion')", dbConn::$cn);
      while(list($id, $fecha, $hora) = mysql_fetch_row($res))
         $contenido_select_remates .= "<option value=\"$id\">Remate del ".implode("/", array_reverse(explode("-", $fecha)))." a las $hora</option>\n";
      */
      $queremate = ($queryremate ? "Remate num. {$_SESSION['id_remate']}" : "Todos los remates");
      $max_adj_texto = $total_adj > 0 ? "{$max_adj[0]} en el lote {$max_adj[2]} del remate {$max_adj[1]}" : "Ninguno";
      $ncliente = ucwords($_SESSION['nombres'])." ".ucwords($_SESSION['apellidop'])." ".ucwords($_SESSION['apellidom']);
      echo <<<END
      <table class="tabla">
         <tr><td>Rut:</td><td>{$_SESSION['rut']}</td></tr>
         <tr><td>Usuario: </td><td >$ncliente</td></tr>
      </table>
      <h4>Adjudicaciones</h4>
      <select name="selecremate" onchange="muestraremates(this);">
      $contenido_select_remates
      </select><div class="botonpdf" onclick="obtenerpdf_usuario({$_SESSION['rut']})">Obtener PDF</div>
      <table class="tabla" id="adjudicaciones">
      <tr><td>ID Remate</td><td>N&deg; Lote</td><td>Descripci&oacute;n</td><td>Cantidad</td><td>Fecha y hora</td><td>Precio final neto</td></tr>
      <tr id="notengo" class=\"fijo\" ><td colspan=\"6\">Sin adjudicaciones.</td></tr>
END;
      
      
      $adjudicaciones = assoc_array_sort($adjudicaciones, "fecha", SORT_DESC);
      foreach($adjudicaciones as $elem)
      {
         echo "<tr><td>{$elem['id_remate']}</td><td>{$elem['orden']}</td><td>{$elem['descripcion']}</td><td>{$elem['cantidad']}</td><td>".implode("/", array_reverse(explode("-", strtok($elem['fecha'], " "))))." a las ".strtok(" ")."</td><td val=\"".($elem['monto']*$elem['cantidad'])."\">".currf(($elem['monto']*$elem['cantidad'])."")."</td></tr>";
      }
      echo "<tr class=\"fijo\" ><td colspan=\"4\">&nbsp;</td><td>Valor neto:</td><td id=\"vneto\"</td></tr>";
      echo "<tr class=\"fijo\" ><td colspan=\"4\">&nbsp;</td><td>Valor comisión(0%):</td><td id=\"vcom\"></td></tr>";
      echo "<tr class=\"fijo\" ><td colspan=\"4\">&nbsp;</td><td>Valor IVA(",(consts::$iva * 100),"%):</td><td id=\"viva\"></td></tr>";
      echo "<tr class=\"fijo\" ><td colspan=\"4\">&nbsp;</td><td>Valor total:</td><td id=\"vtotal\"></td></tr>";
      echo "</table>";
   }
   else if (isset($_GET['remate']))
   {
      // Info del remate, solo admins o requests con hash valido
      if ((isset($_GET['auth']) ? urlsafe_b64decode($_GET['auth']) != md5(consts::$key) : !esAdmin())) 
         die(consts::$mensajes[9]);
      /* Estadisticas de un remate:
       * Usuarios participantes
       * Ofertas totales
       * Promedio ofertas/usuario
       * Relacion precio_final/precio_inicial de cada lote
       * Duracion del remate
       * Cantidad de lotes
       * Lotes no vendidos / saltados
       **/
      $remate = $_GET['remate'];
      $query_array = array
      (
         "select count(*) as response, '' as data, '' as data2, '' as data3, '' as data4 from (select count(acciones.id_accion) from acciones, lotes where acciones.id_lote = lotes.id_lote and lotes.id_remate = $remate group by acciones.rut) as t", // num usuarios
         "select count(acciones.id_accion) as response, '' as data, '' as data2, '' as data3, '' as data4 from acciones, lotes where acciones.id_lote = lotes.id_lote and lotes.id_remate = $remate and tipo = 'Apuesta'", // num ofertas
         "select TIMEDIFF(max(lotes.fecha_termino),min(lotes.fecha_inicio)) as response, '' as data, '' as data2  ,'' as data3, '' as data4 from lotes where id_remate = $remate", // tiempo del remate
         "select count(lotes.id_lote) as response, '' as data, '' as data2,'' as data3, '' as data4  from lotes where lotes.id_remate = $remate", // cantidad de lotes
         "select count(lotes.id_lote) as response, '' as data, '' as data2 ,'' as data3, '' as data4 from lotes where lotes.id_lote not in (select acciones.id_lote from acciones, lotes where acciones.id_lote = lotes.id_lote and lotes.id_remate = $remate) and lotes.id_remate = $remate", // cantidad de lotes saltados (sin acciones)
         "select precios_f.precio as response, acciones.cantidad*productos.subunidades as data, lotes.orden as data2, acciones.rut as data3, productos.descripcion as data4 from acciones join lotes using (id_lote) join productos using (id_producto) join (select max(monto) as precio, id_lote from acciones where tipo = 'Adjudicacion' group by id_lote) as precios_f using (id_lote) where acciones.tipo = 'Adjudicacion' and lotes.id_remate = $remate" // precio maximo, inicial por lote
      );
      /*
      $i = 1;
      foreach($query_array as &$q)
         $q .= " as t".($i++);
      */
      $superquery = implode(" union all ", $query_array);
      $res = mysql_query($superquery, dbConn::$cn) or dbConn::dbError($superquery); 
      list($num_usuarios, , ,) = mysql_fetch_row($res);
      list($num_ofertas, , ,) = mysql_fetch_row($res);
      list($tiempo_total_remate, , ,) = mysql_fetch_row($res);
      list($total_lotes, , ,) = mysql_fetch_row($res);
      list($total_lotes_saltados, , ,) = mysql_fetch_row($res);
      $total_lotes_vendidos = $total_lotes - $total_lotes_saltados;
      $detalle_lotes = array();
      while($row = mysql_fetch_assoc($res))
         $detalle_lotes[] = $row;
      mysql_free_result($res);
      // die($superquery);
      $prom = 0;
      if ($num_usuarios != 0)
         $prom = round((float)($num_ofertas) / (float)($num_usuarios), 2);
      echo <<<END
      <table class="tabla">
         <tr><td colspan="2">Remate ID: $remate <div class="pdfget" id="pdf$remate" title="Obtener informe completo en PDF"/></td></tr>
         <tr><td>Usuarios participantes:</td><td>$num_usuarios</td></tr>
         <tr><td>Ofertas totales:</td><td>$num_ofertas</td></tr>
         <tr><td>Promedio ofertas por usuario:</td><td>$prom</td></tr>
         <tr><td>Duracion del remate:</td><td>$tiempo_total_remate</td></tr>
         <tr><td>Cantidad de lotes: total/rematados/saltados:</td><td>$total_lotes/$total_lotes_vendidos/$total_lotes_saltados</td></tr>
      </table>
END;
   echo "<h3>Detalle por cliente:</h3>\n";
   
   // echo "<tr><td>N&deg; Lote</td><td>Descripci&oacute;n</td><td>Cantidad</td><td>Precio Final</td><td>Cliente que adjudico</td><td></td></tr>";
   if (count($detalle_lotes) == 0)
   {
      echo "<tr><td colspan=\"6\">No hay adjudicaciones realizadas en este remate.</td></tr>";
   }
   $detalle_lotes = assoc_array_sort($detalle_lotes, "data3", SORT_DESC);
   $arreglo_adj = array();
   foreach($detalle_lotes as $arr)
   {
      $arreglo_adj["{$arr['data3']}"][] = "<tr><td>{$arr['data2']}</td><td>{$arr['data4']}</td><td>{$arr['data']}</td><td>".currf(((int)$arr['response']*(int)$arr['data'])."")."</td><td>{$arr['data3']}</td><td></td></tr>";
   }
   foreach($arreglo_adj as $key => $data)
   {
      echo "<table class=\"tabla\">\n";
      echo "<tr><td colspan=\"2\">Adjudicaciones del rut ".$key."</td><td colspan=\"2\"><a href=\"creanota.php?rut=$key&id_remate=$remate\" target=\"_blank\">Ver Nota de Venta</a></td><td colspan=\"2\"><a href=\"creanota.php?rut=$key&id_remate=$remate&enviar=true\" target=\"_blank\">Enviar Nota de Venta por Email</a></td></tr>\n";
      echo "<tr><td>N&deg; Lote</td><td>Descripci&oacute;n</td><td>Cantidad</td><td>Precio Final</td><td>Cliente que adjudico</td><td></td></tr>";
      echo implode("\n", $data);
      echo "\n</table><br />\n";
   }
   
      
      
   }
   else
   {
      echo "<p>Facturaciones</p>";
      $semiadm = "";
      if (!adminGeneral())
        $semiadm = "where rut_owner = {$_SESSION['rut']}";
      $res = mysql_query("select * from remates $semiadm order by fecha desc, hora desc", dbConn::$cn);
      if (mysql_num_rows($res) > 0) while($row = mysql_fetch_assoc($res))
      {
         echo "<p class=\"fitem\" onclick=\"reenvia(this,'facturacion.php?remate={$row['id_remate']}');\">Ver remate num {$row['id_remate']} de fecha {$row['fecha']}".($row['en_curso'] == "1" ? "(Remate en curso)" : "")."</p>\n";
      }
      else
      {
         echo "<p>No hay remates.</p>\n";
      }
      
   }
?>
