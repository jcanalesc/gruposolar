<?
   function truncate($str,$limit)
   {
      if (strlen($str) > $limit)
      {
         return substr($str, 0, $limit - 3)."...";
      }
      else return $str;
   }
   include ("header.php");
   if (!ini()) die(consts::$mensajes[9]);
   $remate =  $_GET['id'];
   include("fpdf.php");
   $usuario = "";
   $usuario2 = "";
   $usuario3 = "";
   if (isset($_GET['rut']))
   {
		list($dv) = mysql_fetch_row(mysql_query("select dv from users where rut = ".mysql_real_escape_string($_GET['rut']), dbConn::$cn));
      $usuario = " and acciones.rut = {$_GET['rut']} ";
      $usuario2 = " del usuario de RUT: {$_GET['rut']}-{$dv}";
      $usuario3 = "-{$_GET['rut']}";
   }
   $pdf = new FPDF('P', 'mm', 'Letter');
   $pdf->SetFont("Arial", "", 20);
   $pdf->AddPage();
   $pdf->Image("logooriginal.png", null, null, 0, 15, "png");
   $pdf->Cell(0, 15, "Resumen Remate num. {$_GET['id']}$usuario2", 0, 1, 'C');
   $pdf->Ln();
   $pdf->SetFontSize(12);
   $rut_si_es_user = (isset($_GET['rut']) ? "" : "acciones.rut," );
   $query = "select acciones.cantidad, $rut_si_es_user lotes.orden, productos.descripcion, acciones.monto from acciones, lotes, productos where acciones.id_lote = lotes.id_lote and lotes.id_producto = productos.id_producto and acciones.id_lote in (select id_lote from lotes where id_remate = {$_GET['id']}) and acciones.tipo = 'Adjudicacion' $usuario order by acciones.rut";
   $res = mysql_query($query,dbConn::$cn) or dbConn::dbError($query);
   $primero = true;
   $ind = 0;
   $monto_total = 0;
   $anchocelda = 0;
   $columnas = array();
   if (mysql_num_rows($res) > 0)
   {
      while($row = mysql_fetch_assoc($res))
      {
         $columnas = array_keys($row);
         $anchos = (isset($_GET['rut']) ? array(20, 15 , 125, 26) : array(20,20, 30,90,26));
         if ($primero === true)
         {
            $i = 0;
            foreach($columnas as $col)
               $pdf->Cell($anchos[$i++], 6, switches::tra($col), 1, 0);
            $pdf->Ln();
            $primero = false;
         }
         $i = 0;
         $pdf->SetFontSize(10);
         foreach($columnas as $col)
         {
            if ($col == "monto")
            {
               $monto_total += $row[$col];
               $row[$col] = currf($row[$col]);
            }
            
            $ancho = $pdf->GetStringWidth($row[$col]);
            $pdf->Cell($anchos[$i++], 9, truncate($row[$col], 40),"LRB", 0);
         }
         $pdf->Ln();
         $ind++;
      }
      $pdf->Cell(160, 6, "Monto neto:", "LRB", 0, "R");
      $pdf->Cell(26, 6, currf((string)$monto_total), "LRB", 1);
      $pdf->Cell(160, 6, "Monto IVA:", "LRB", 0, "R");
      $pdf->Cell(26, 6, currf((string)((int)((float)$monto_total * consts::$iva))), "LRB", 1);
      $pdf->Cell(160, 6, "Monto Total:", "LRB", 0, "R");
      $pdf->Cell(26, 6, currf((string)((int)((float)$monto_total * (1 + consts::$iva)))), "LRB", 1);
   }
   else
   {
      $pdf->Cell(0, 6, "Este remate no presenta adjudicaciones/ventas: todos los lotes fueron saltados o no tuvieron ofertas.", 0, 1);
   }
   $pdf->Ln(5);
   $pdf->Cell(0, 1, "", "B", 1);
   $pdf->Ln(10);
   // estadisticas
   if (esAdmin())
   {
      $remate = $_GET['id'];
      $query_array = array
         (
            "select count(*) as response, '' as data, '' as data2, '' as data3 from (select count(acciones.id_accion) from acciones, lotes where acciones.id_lote = lotes.id_lote and lotes.id_remate = $remate group by acciones.rut) as t", // num usuarios
            "select count(acciones.id_accion) as response, '' as data, '' as data2, '' as data3 from acciones, lotes where acciones.id_lote = lotes.id_lote and lotes.id_remate = $remate and tipo = 'Apuesta'", // num ofertas
            "select TIMEDIFF(max(lotes.fecha_termino),min(lotes.fecha_inicio)) as response, '' as data, '' as data2  ,'' as data3 from lotes where id_remate = $remate", // tiempo del remate
            "select count(lotes.id_lote) as response, '' as data, '' as data2,'' as data3  from lotes where lotes.id_remate = $remate", // cantidad de lotes
            "select count(lotes.id_lote) as response, '' as data, '' as data2 ,'' as data3 from lotes where lotes.id_lote not in (select acciones.id_lote from acciones, lotes where acciones.id_lote = lotes.id_lote and lotes.id_remate = $remate) and lotes.id_remate = $remate"
         );
      $superquery = implode(" union all ", $query_array);
      $res = mysql_query($superquery, dbConn::$cn) or dbConn::dbError($superquery); 
      list($num_usuarios, , ,) = mysql_fetch_row($res);
      list($num_ofertas, , ,) = mysql_fetch_row($res);
      list($tiempo_total_remate, , ,) = mysql_fetch_row($res);
      list($total_lotes, , ,) = mysql_fetch_row($res);
      list($total_lotes_saltados, , ,) = mysql_fetch_row($res);
      $total_lotes_vendidos = $total_lotes - $total_lotes_saltados;
      
      $pdf->SetFontSize(20);
      $pdf->Cell(0, 6, "Estadisticas del remate $remate", 0, 1);
      $pdf->Ln();
      $pdf->SetFontSize(12);
      $pdf->Write(6, "Usuarios participantes: $num_usuarios\nCantidad de ofertas: $num_ofertas\nDuracion total del remate: $tiempo_total_remate\nTotal de lotes rematados: $total_lotes\n - Lotes vendidos: $total_lotes_vendidos\n - Lotes saltados: $total_lotes_saltados");
   }
   $pdf->Output("Informe Remate {$remate}{$usuario3}.pdf", "I");
   // no sirve
   /*
   if (file_exists("reporte-remate-{$_GET['id']}-$instance.pdf"))
      exec("rm reporte-remate-{$_GET['id']}-$instance.pdf");
   exec("wget -q -O reporte-remate-$remate.html \"http://localhost/facturacion.php?remate=$remate&auth=$auth_key\"");
   
   exec("xvfb-run -a -s \"-screen 0 640x480x16\" wkhtmltopdf  reporte-remate-$remate.html reporte-remate-{$_GET['id']}.pdf &> /dev/null");
   exec("rm reporte-remate.$remate.html");
   echo "<meta http-equiv=\"refresh\" content=\"0;url=reporte-remate-{$_GET['id']}.pdf\">";
   * */
?>
