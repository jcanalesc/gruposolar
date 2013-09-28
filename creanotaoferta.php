<?
function amonona_rut($rut, $dv)
{
    $res = "";
    for ($i = 0; $i < strlen($rut); $i++)
    {
        $res = $rut[strlen($rut) - 1 - $i] . $res;
        if ( ($i + 1) % 3 == 0 && $i + 1 < strlen($rut))
            $res = "." . $res;
    }
    $res = $res . "-" . $dv;
    return $res;
}
// creanota.php
include("fpdf.php");
include("DB.inc.php");
include("header.php");
function truncate($str,$limit)
{
   if (strlen($str) > $limit)
   {
      return substr($str, 0, $limit - 3)."...";
   }
   else return $str;
}
/* Recibo rut y remate por GET*/
if (!isset($_GET['id_oferta']) || !isset($_GET['rut']))
   die(consts::$mensajes[8]);
   
// Obtengo/genero el ID de la nota de venta




$rut = mysql_real_escape_string($_GET['rut']);
$id_remate = mysql_real_escape_string($_GET['id_oferta']);

$res = $db->query("select cantidad, parametro from ofertas_compradas where rut_usuario = $rut and id_oferta = $id_remate");

$cantidad_comprada = $res[0]['cantidad'];
$parametro_elegido = $res[0]['parametro'];

//$idnota = "OF".$id_remate;

//$wer = mysql_query("insert ignore into notasdeventa (rut, id_remate) values ($rut, '$id_remate')", dbConn::$cn);

//$wer = mysql_query("select id from notasdeventa where id_remate = '$id_remate' and rut = $rut", dbConn::$cn);
//list($idnota) = mysql_fetch_row($wer);
    
try
{
   $datos = $db->query("select rut, dv, CONCAT(nombres, ' ', apellidop, ' ', apellidom) as nombre, '' as giro, direccion, comunas.nombre as comuna, users.region, users.email, users.telefono from users, comunas where rut = $rut and comunas.codigo = users.comuna");
        
   $nombre = utf8_decode($datos[0]['nombre']);
   $rutf = $datos[0]['rut'];
   $dv = $datos[0]['dv'];
   $direccion = utf8_decode($datos[0]['direccion']);
   $direccion2 = utf8_decode($datos[0]['giro']);
   $ciudad = utf8_decode($datos[0]['comuna'].", ".$datos[0]['region']);
   $telefono = $datos[0]['telefono'];
   $contacto = $datos[0]['email'];
   $fecha = date("d/m/Y", time());
   
   /*
   if (strlen($_GET['id_remate']) < 3 || substr($_GET['id_remate'],0,2) != "MR")
   {
        $compras = $db->query("select acciones.cantidad*productos.subunidades as cantidad, productos.descripcion, precios_f.precio, (acciones.cantidad*productos.subunidades*precios_f.precio) as total from acciones join lotes using(id_lote) join productos using (id_producto) join (select max(monto) as precio, id_lote from acciones where tipo = 'Adjudicacion' group by id_lote) as precios_f using (id_lote) where acciones.tipo = 'Adjudicacion' and acciones.rut = $rut and precios_f.id_lote = acciones.id_lote and acciones.id_lote in (select id_lote from lotes where id_remate = $id_remate)");
        list($id_sala, $fecha) = mysql_fetch_row(mysql_query("select id_sala, fecha from remates where id_remate = $id_remate", dbConn::$cn));
   }
   else
   {
       $id_remate = substr($_GET['id_remate'],2);
       $qr = "select productos.subunidades as cantidad, productos.descripcion, miniremates.monto_actual as precio, (productos.subunidades*miniremates.monto_actual) as total from miniremates join productos using (id_producto) where miniremates.rut_ganador = $rut and miniremates.id_miniremate = $id_remate";
       rematelog($qr);
       $compras = $db->query($qr);
       $id_sala = "MR";
       list($fecha) = mysql_fetch_row(mysql_query("select DATE(fecha_termino) from miniremates where id_miniremate = $id_remate", dbConn::$cn));
   }
   */
   // Obtengo datos de la empresa, si aplica
   $custom = false;

   $compras = $db->query("select ofertas.parametro_nom, ofertas.parametro_op".$parametro_elegido." as eleccion, ofertas_compradas.cantidad, productos.descripcion, ofertas.precio, (ofertas.precio*ofertas_compradas.cantidad) as total from ofertas_compradas join ofertas using (id_oferta) join productos using (id_producto) where ofertas_compradas.id_oferta = $id_remate and ofertas_compradas.rut_usuario = $rut");

   $resfecha = $db->query("select DATE(fecha_compra) as fecha_compra, UNIX_TIMESTAMP(fecha_compra) as fechaunix from ofertas_compradas where id_oferta = $id_remate and rut_usuario = $rut");
   $fecha = $resfecha[0]['fecha_compra'];
   $fechaunix = substr($resfecha[0]['fechaunix'], 5);
   $idnota = "OF".$id_remate."-".$rut."-".$fechaunix;
   
       
   $datosusuario = array(
                    'f_nombre' => " ",
                    'f_rut' => "76977000",
                    'f_dv' => 3,
                    'f_direccion' => "Av. Matta 1414 - Santiago Centro",
                    'f_telefono' => "5513304 / 96114298"
                    );
   $datosbancarios = array("TRANSFERENCIA BANCARIA", "CHISOL S.A.", "RUT: 76.977.000-3", "CTA CTE BANCO SANTANDER", "NÚMERO: 61-47504-4", "CORREO: info@chisol.cl");
   
   foreach($datosbancarios as &$val)
    $val = utf8_decode($val);
   $pdf = new FPDF('P', 'mm', 'Letter');
   $pdf->SetFont("Arial", "", 20);
   $pdf->AddPage();
   // 33.99, 28.56, 17.82, 15.40, 8.40, 14.55, 2.79 -- 13.7 -- 24.91, 41.15
   $anchoCampo = 28.56+17.82+15.40+8.40+14.55+2.79;
   // $pdf->Image("logooriginal2.png", null, null, 58, 27, "png");
   
       $pdf->Image("logochisol.png", null, null, 58, 27, "png");
   
   list($xx, $yy) = array($pdf->GetX(), $pdf->GetY());
   $pdf->SetXY(48, 0);
   $pdf->Cell(0, 13.5, "ID: $idnota", 0, 1, 'R');
   $pdf->SetXY($xx, $yy);
   
   $pdf->SetY($pdf->GetY() - 10);
   $pdf->Cell(0,10,"Rut: ".amonona_rut($datosusuario['f_rut'], $datosusuario['f_dv']), 0, 1, 'R');
   $pdf->SetFont("Arial", "B", 18);
   $pdf->Cell(0,10,"NOTA DE VENTA",0, 1, 'R');
   $pdf->SetLineWidth(1);
   $pdf->SetDrawColor(102,102,153);
   $pdf->Line($pdf->GetX(), $pdf->GetY() - 2, $pdf->GetX() + 28+$anchoCampo+10+16, $pdf->GetY() - 2);
   $pdf->Line($pdf->GetX() + 28+$anchoCampo+10+16, $pdf->GetY() - 2, $pdf->GetX() + 28+$anchoCampo+10+16, $pdf->getY() - 9);
   $pdf->Line($pdf->GetX() + 28+$anchoCampo+10+16, $pdf->GetY() - 9, $pdf->GetX() + 28+$anchoCampo+10+16 + 60, $pdf->getY() - 9);
   $pdf->SetLineWidth(0.1);
   $pdf->SetDrawColor(0);
   $pdf->Cell(0,2,"",'B',1); // hbar
   $pdf->Ln(9);
   $pdf->SetFont("Arial", "", 12);
   $pdf->SetLineWidth(0.6);
   $pdf->Cell(33.99,5,"CLIENTE", 'BR');
   $pdf->Cell($anchoCampo, 5, "$nombre", 'LT');
   $pdf->SetX(13.7 + $pdf->GetX());
   $pdf->Cell(25, 5, "OTRO", 'BR');
   $pdf->Cell(41.15, 5, "",'LT',1);
   $pdf->Ln(3);
   $pdf->SetDrawColor(192);
   $pdf->SetLineWidth(0.1);
   $pdf->Cell(33.99,5,"RUT");
   $pdf->Cell($anchoCampo, 5, "$rutf-$dv", 'B');
   $pdf->SetX(13.7 + $pdf->GetX());
   $pdf->Cell(25, 5, "Fecha");
   $pdf->Cell(41.15, 5, implode("/", array_reverse(explode("-", $fecha))),'B',1);
   $pdf->Ln(2);
   $pdf->Cell(33.99,5,"DIRECCION");
   $pdf->Cell($anchoCampo, 5, "$direccion",'B',1);
   $pdf->Ln(2);
   $pdf->Cell(33.99,5,$particular['particular'] == 1 ? "" : "GIRO");
   $pdf->Cell($anchoCampo, 5, "$direccion2",'B',1);
   $pdf->Ln(2);
   $pdf->Cell(33.99,5,"CIUDAD");
   $pdf->Cell($anchoCampo, 5, "$ciudad",'B',1);
   $pdf->Ln(2);
   $pdf->Cell(33.99,5,"TELEFONO");
   $pdf->Cell($anchoCampo, 5, "$telefono",'B',1);
   $pdf->Ln(2);
   $pdf->Cell(33.99,5,"CONTACTO");
   $pdf->Cell($anchoCampo, 5, "$contacto",0,1);
   $pdf->Ln(2);
   $pdf->SetDrawColor(0);
   $pdf->Cell(0,2,"",'B',1);
   $pdf->Ln(2);
   $pdf->SetLineWidth(0.6);
   $pdf->SetFont("Arial", "B", 12);
   $pdf->Cell(20, 7, "Cantidad", 'TL');
   $pdf->Cell($anchoCampo + 24, 7, "Descripcion", 'TL',0,'C');
   $pdf->Cell(35,7, "Precio Unidad", 'TL',0,'C');
   $pdf->Cell(35,7, "Total", 'TLR', 1, 'C');
   $pdf->SetFont("Arial", "", 10);
   // (20, ancho+24, 35, 35)
   $neto = 0;
   $iva = 0;
   $total = 0;
   foreach($compras as $fila)
   {
      $string_parametro = strlen($fila['parametro_nom']) > 0 ? ucwords($fila['parametro_nom']).": ".ucwords($fila['eleccion']) : "";
      $palabras = explode(" ", utf8_decode($fila['descripcion'].$string_parametro));
      $columnas = array();
      $actCol = 0;
      foreach($palabras as $pl)
      {
          if ($actCol < 4 && isset($columnas[$actCol]) && strlen($columnas[$actCol]) >= 45) $actCol++;
          $columnas[$actCol] .= "$pl ";
      }
      $rownum = count($columnas);
      if ($rownum == 5)
        $columnas[4] = truncate($columnas[4], 50);
      $altoCelda = 5 * ($rownum);
      
      $pdf->Cell(20, $altoCelda, $fila['cantidad'], 'TL',0,'C');
      $xpos = $pdf->GetX();
      $ypos = $pdf->GetY();
      for ($i = 0; $i < $rownum; $i++)
      {
        $pdf->Cell($anchoCampo + 24, 5,$columnas[$i], ($i == 0 ? 'T' : '').'L',0,'L');
        $pdf->SetXY($xpos, $ypos + 5*($i+1));
      }
        
      $pdf->SetXY($xpos + $anchoCampo + 24, $ypos);
      $pdf->Cell(35,$altoCelda, currf($fila['precio']), 'TL',0,'C');
      $pdf->Cell(35,$altoCelda, currf($fila['total']), 'TLR', 1, 'C');
      $neto += $fila['total'];
   }
   
   $com = 0;
   $netoycom = $neto * (1 + (float)$com/100);
   $iva = (int)round($netoycom * 0);
   $total = $netoycom + $iva;
   
   $pdf->Cell(28 + $anchoCampo + 16, 7,"", 'T', 0, 'C');
   $pdf->Cell(35, 7, "Neto + IVA", 'LTB', 0, 'R');
   $pdf->Cell(35, 7, currf((string)$neto), 1, 0, 'C');
   $pdf->Ln();
   
   $pdf->Cell(28.56+17.82+15.40+8.40+2.79+13.7+6,7,"",0, 0, 'L');
   $pdf->Cell(14.55+24.3, 7, "", 0, 0);
   $pdf->Cell(35, 7, utf8_decode("Comisión: ($com%)"), 'LTB', 0, 'R');
   $pdf->Cell(35, 7, currf((string)round((float)$com/100*$neto)), 1, 0, 'C');
   $pdf->Ln();
   /*
   $pdf->Cell(28.56+17.82+15.40+8.40+2.79+13.7+6,7,"",0, 0, 'L');
   $pdf->Cell(14.55+24.3, 7, "", 0, 0);
   $pdf->Cell(35, 7, "IVA", 'LTB', 0, 'R');
   $pdf->Cell(35, 7, currf((string)$iva), 1, 0, 'C');
   $pdf->Ln();
   */
   $pdf->Cell(28.56+17.82+15.40+8.40+2.79+13.7+6,7,"",0, 0, 'L');
   $pdf->Cell(14.55+24.3, 7, "", 0, 0);
   $pdf->Cell(35, 7, "Total", 'LTB', 0, 'R');
   $pdf->Cell(35, 7, currf((string)$total), 1, 0, 'C');
   $pdf->Ln();
   $pdf->Cell(28.56+17.82+15.40+8.40+2.79+13.7,7,"FORMA DE PAGO",'B', 1, 'L');
   $pdf->SetFont("Arial", "", 10);
   
   
   
   $pdf->Cell(28.56+17.82+15.40+8.40+2.79+13.7,5,$datosbancarios[0],'R', 1, 'L');
   $pdf->Cell(28.56+17.82+15.40+8.40+2.79+13.7,4,$datosbancarios[1],'R', 1, 'L');
   $pdf->Cell(28.56+17.82+15.40+8.40+2.79+13.7,4,$datosbancarios[2],'R', 1, 'L');
   $pdf->Cell(28.56+17.82+15.40+8.40+2.79+13.7,4,$datosbancarios[3],'R', 1, 'L');
   $pdf->Cell(28.56+17.82+15.40+8.40+2.79+13.7,4,$datosbancarios[4],'R', 1, 'L');
   $pdf->Cell(28.56+17.82+15.40+8.40+2.79+13.7,4,$datosbancarios[5],'R', 1, 'L');
   $pdf->Cell(0,3, "",'B', 1);
   $pdf->Cell(0,5,"",'LR',1);
   $pdf->Cell(0,5, "ESTE COMPROBANTE NO REEMPLAZA LA FACTURA. SE EMITIRA FACTURA ELECTRONICA UNA VEZ", 'LR', 1, 'C');
   $pdf->Cell(0,5, "APROBADO EL PAGO ENVIANDOLA A SU DIRECCION DE CORREO ELECTRONICO.", 'LR', 1, 'C');
   $pdf->Cell(0,5,"",'LRB',1);
   $pdf->Cell(0,10,"",'B', 1);
   $pdf->Cell(0, 10, "Direccion: ".$datosusuario['f_direccion']." - Fono: ".$datosusuario['f_telefono'], 0, 1, 'C');
   $fecha2 = implode("-", explode("/", $fecha));
   
   $pdf->Output("NotadeVenta$rut-$dv-OfertaExpress$id_remate-$fecha2.pdf", "I");
}
catch (Exception $e)
{
   echo $e->getMessage();
   rematelog($e->getMessage());
}
?>
