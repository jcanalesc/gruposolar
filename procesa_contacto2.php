<?php
include("header.php");

$nombre = mysql_real_escape_string($_POST['nombre']);
$ape = mysql_real_escape_string($_POST['ape']);
$fono = mysql_real_escape_string($_POST['fono']);
$region = mysql_real_escape_string($_POST['region']);
$comuna = mysql_real_escape_string($_POST['comuna']);
$email = mysql_real_escape_string($_POST['email']);
$email2 = mysql_real_escape_string($_POST['email2']);

// Conexion a bdd, insertar fila
mysql_select_db("correos", dbConn::$cn);
$good = true;
$respuesta = "SOLICITUD DE REGISTRO COMPLETA.";

$r0 = mysql_query("select count(*) as conteo from usuarios where email = '".($email."@".$email2)."'", dbConn::$cn);
$row0 = mysql_fetch_row($r0);
if ($row0[0] > 0)
{
	$respuesta = "Usted ya está suscrito a PortalRemate.";
	$good = false;
}

$r = mysql_query("insert into usuarios (nombre, apellido, fono, comuna, email) values ('{$nombre}','{$ape}','{$fono}',$comuna,'{$email}@{$email2}')", dbConn::$cn);


if (!$r) 
{
	$good = false;
    $respuesta = "Hubo problemas al intentar realizar su registro.";
}
    

if ($good)
{
	$r2 = mysql_query("select nombre from comunas where codigo = {$comuna}", dbConn::$cn);
	$row = mysql_fetch_row($r2);
	$comuna = $row[0];

	mysql_free_result($r);
	mysql_free_result($r2);

	$header = 'From: ' . $email . "@" . $email2 . " \r\n";
	$header .= "X-Mailer: PHP/" . phpversion() . " \r\n";
	$header .= "Mime-Version: 1.0 \r\n";
	$header .= "Content-Type: text/plain";
	$mensaje = "";
	$mensaje .= "Datos Registro: \r\n";
	$mensaje .= "Correo Electronico: " . $email . "@" . $email2 . " \r\n";
	$mensaje .= "Nombre:  " . $nombre . " \r\n";
	$mensaje .= "Apellido:  " . $ape . " \r\n";
	$mensaje .= "Telefono:  " . $fono . " \r\n";
	$mensaje .= "Comuna, Region:  " . $comuna . " , " . $region . " \r\n";
	$mensaje .= "  \r\n";

	$mensaje .= "Enviado el " . date('d/m/Y', time());

	$para = 'info@portalremate.cl';
	$asunto = 'Solicita informacion futuros remates';

	mail($para, $asunto, utf8_decode($mensaje), $header);
}
echo $respuesta;
?>
