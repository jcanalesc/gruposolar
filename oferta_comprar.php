<?
	// oferta_comprar.php
	// recibe el post que trae el id_producto y la cantidad, el rut se saca por sesion
	include("header.php");
	include("DB.inc.php");
	if (!ini() || count($_POST) == 0) die("Acceso denegado");

	$oferta = array(
		"rut" => $_SESSION['rut'],
		"id_producto" => $_POST['id_producto'],
		"cantidad" => $_POST['cantidad'],
		"id_oferta" => $_POST['id_oferta'],
		"parametro" => $_POST['parametro']
		);
	if (!is_numeric($oferta['id_producto']) || !is_numeric($oferta['cantidad'])) die("Llamada invalida");

	$idp = $oferta['id_producto'];
	$ido = $oferta['id_oferta'];
	$rut = $oferta['rut'];
	$cantidad = $oferta['cantidad'];
	$parametro = empty($oferta['parametro']) ? "1" : $oferta['parametro'];

	$producto = mysql_fetch_assoc(mysql_query("select * from productos where id_producto = $idp", dbConn::$cn));
	$oferta_props = mysql_fetch_assoc(mysql_query("select * from ofertas where id_oferta = $ido"));

	$ya_compro = mysql_num_rows(mysql_query("select * from ofertas_compradas where id_oferta = $ido and rut_usuario = $rut", dbConn::$cn)) > 0;

	if ($ya_compro)
	{
		die("ya_compro");
	}

	// comprar

	$ini = strtotime($oferta_props['fecha_inicio']);
	$fin = strtotime($oferta_props['fecha_termino']);

	if ($ini > time() || time() > $fin)
	{
		die("fuera_de_plazo");
	}


	$res = mysql_query("insert into ofertas_compradas (id_oferta, rut_usuario, cantidad, parametro) values ($ido, $rut, $cantidad, $parametro)", dbConn::$cn);
	if (!$res)
		die("error");
	else
	{
		// manda correo

	  list($datos_usuario) = $db->query("select * from users where rut = $rut");
		
	  $from  = consts::$from_email;
      $to = $datos_usuario['email'];
      $rut = $datos_usuario['rut'];
      $nombre = strtoupper($datos_usuario['nombres']);
      $subject = "PortalRemate.cl: Oferta Express Nota de Venta";
      $random_hash = md5(date('r', time())); 
      $headers = implode("\r\n", array("From: $from",
                                       "Reply-To: $from",
                                       "Cc: soporte@portalremate.cl, publicidad@chisol.cl",
                                       /* "Content-type: text/html; charset=\"utf-8\"" */
                                        "Content-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\""
                                       ));
        //read the atachment file contents into a string,
        //encode it with MIME base64,
        //and split it into smaller chunks
      
        $attachment = chunk_split(base64_encode(file_get_contents("http://www.portalremate.cl/creanotaoferta.php?rut=$rut&id_oferta=$ido")));
        $attach_procedimiento = chunk_split(base64_encode(file_get_contents($oferta_props['procedimiento'])));
        //echo $attachment;
        $ncompleto = ucwords(strtolower($datos_usuario['nombres']." ".$datos_usuario['apellidop']." ".$datos_usuario['apellidom']));
        $body = <<<EOF
--PHP-mixed-$random_hash
Content-Type: multipart/alternative; boundary="PHP-alt-$random_hash"

--PHP-alt-$random_hash
Content-Type: text/html; charset="utf-8"

<html>
<head>
<style type="text/css">
body
{
   font-family: Arial, Helvetica, sans-serif;
}
</style>
</head>
<body>
<p><a href="http://www.portalremate.cl">PORTALREMATE.CL</a></p>
<p>FELICITACIONES, $ncompleto!!</p>

<p>HAS COMPRADO LA OFERTA EXPRESS DEL DIA.</p>

<p>En breves minutos un ejecutivo de PortalRemate se contactará con usted para coordinar los pagos y entregas.</p>

<p>Adjuntamos Nota de Venta y Procedimiento.</p>

<p>Se Despide:<br />
Equipo PortalRemate.cl</p>
</body>
</html>

--PHP-alt-$random_hash--

--PHP-mixed-$random_hash
Content-Type: application/pdf; name="NotaDeVenta-OfertaExpress-$ido.pdf"
Content-Transfer-Encoding: base64
Content-Disposition: attachment

$attachment

--PHP-mixed-$random_hash
Content-Type: application/pdf; name="Procedimiento.pdf"
Content-Transfer-Encoding: base64
Content-Disposition: attachment

$attach_procedimiento

--PHP-mixed-$random_hash--
EOF;

      if (!mail($to, $subject, $body, $headers, "-f$from"))
		die("error");
	  else
	  	die("ok");
	}

?>