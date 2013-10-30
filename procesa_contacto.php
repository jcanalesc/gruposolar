<?php

$nombre = $_POST['nombre'];
$ape = $_POST['ape'];
$fono = $_POST['fono'];
$ciudad = $_POST['ciudad'];
$email = $_POST['email'];
$email2 = $_POST['email2'];

$header = 'From: ' . $email . "@" . $email2 . " \r\n";
$header .= "X-Mailer: PHP/" . phpversion() . " \r\n";
$header .= "Mime-Version: 1.0 \r\n";
$header .= "Content-Type: text/plain";

$mensaje .= "Datos Registro: \r\n";
$mensaje .= "Correo Electronico: " . $email . "@" . $email2 . " \r\n";
$mensaje .= "Nombre:  " . $nombre . " \r\n";
$mensaje .= "Apellido:  " . $ape . " \r\n";
$mensaje .= "Telefono:  " . $fono . " \r\n";
$mensaje .= "Ciudad:  " . $ciudad . " \r\n";
$mensaje .= "  \r\n";

$mensaje .= "Enviado el " . date('d/m/Y', time());

$para = 'info@portalremate.cl,avisos@portalremate.cl';
$asunto = 'Solicita informacion futuros remates';

mail($para, $asunto, utf8_decode($mensaje), $header);

?>


<link href="index.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo3 {font-size: 14pt}
.Estilo5 {font-size: 14}
.Estilo7 {font-size: 18px}
.Estilo8 {font-family: Arial, Helvetica, sans-serif}
-->
</style>
<table width="935" height="121" border="0">
  <tr>
    <td width="267" height="117"><img src="logo_gr.png" width="251" height="100"></td>
    <td width="585"><p align="center" class="Estilo3 gallerytitle">&nbsp;</p>
      <p align="center" class="Estilo3 gallerytitle"><strong><font size="4" face="Verdana, Arial, Helvetica, sans-serif">WWW.PORTALREMATE.CL</font></strong></p>
      <p align="center"><strong><font face="Arial, Helvetica, sans-serif">SOLICITUD DE REGISTRO COMPLETA. </font></strong></p>
      <p align="center"><font size="1" face="Arial, Helvetica, sans-serif">.</font><br>
      </p></td>
    <td width="69">&nbsp;</td>
  </tr>
</table>
<table width="1008" height="196" border="0">
  <tr>
    <td width="219" height="21">&nbsp;</td>
    <td width="614">&nbsp;</td>
    <td width="161">&nbsp;</td>
  </tr>
  <tr>
    <td height="55">&nbsp;</td>
    <td><div align="center">
      <p class="gallerytitle Estilo7"><strong><font color="#0000FF" face="Arial, Helvetica, sans-serif">&iexcl; 
        GRACIAS POR PREFERIRNOS !</font></strong></p>
      <p class="gallerytitle Estilo7 Estilo8">Le Informaremos de Nuestros Futuros Remates.</p>
    </div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="104">&nbsp;</td>
    <td><p align="center" class="gallerytitle Estilo5"><font size="2" face="Arial, Helvetica, sans-serif"><br>
        mail: info@portalremate.cl</font></p></td>
    <td>&nbsp;</td>
  </tr>
</table>
<table width="903" border="0">
  <tr>
    <td width="21">&nbsp;</td>
    <td width="814"><div align="center"><strong><font size="1" face="Arial, Helvetica, sans-serif">PATROCINADORES 
        Y MARCAS AUTORIZADAS.</font></strong></div></td>
    <td width="54">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><div align="center"><img src="patrocinadores.jpg" width="814" height="101" /></div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="100">&nbsp;</td>
    <td><p align="center">PORTAL-REMATE &copy;2008-2010 </p>
      <p align="center">info@portalremate.cl</p>
    <p align="center">Copyright &reg; Todos los derechos reservados</p></td>
    <td>&nbsp;</td>
  </tr>
</table>
<p><br>
</p>
<p>&nbsp;</p>

