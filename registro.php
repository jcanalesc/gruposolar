<?
   include("header.php");
   if (count($_POST) > 0)
   {
       echo '<script language="javascript" src="jquery.js"></script>';
      $empresa = false;
      $cols = array("rut", "nombre", "direccion", "telefono", "region", "comuna", "email", "dv");
      if ($_POST['tipof'] == "Persona")
      {
         foreach($cols as $c)
         {
            $_POST["rf_".$c] = $_POST["r".$c];
         }
         $_POST["rf_giro"] = "Particular";
         $_POST["rf_nombre"] = "{$_POST['rnombres']} {$_POST['rapellidop']} {$_POST['rapellidom']}";
      }
      unset($_POST['tipof']);
      echo '<script language="javascript">
      alert(';
      // registra, osea una cuenta no activada con clave al azar.
      $keys = array();
      $values = array();
      $datos_correctos = true;
      if (!email_valido($_POST['remail']) || !email_valido($_POST['rf_email']))
      {
         echo '"Correo electrónico invalido\n"+';
         $datos_correctos = false;
      }
		else if ($_POST['remail'] != $_POST['remail2'])
		{
			echo '"Los correos electrónicos escritos no coinciden.\n"+';
			$datos_correctos = false;
		}
      else if (!validaRut($_POST['rrut'], $_POST['rdv']) || !validaRut($_POST['rf_rut'], $_POST['rf_dv']))
      {
         echo '"Rut invalido\n"+';
         $datos_correctos = false;
      }
		else if (true);
		
      foreach($_POST as $k => $v)
      {
         if ($k != "remail2")
         {
            $keys[] = substr($k, 1, strlen($k) - 1);
            $nval = mysql_real_escape_string($v);
            if (in_array($k, array( "rrut", "rf_rut", "rcomuna", "rf_comuna")))
               $values[] = $nval;
            else
               $values[] = "'{$nval}'";
         
         }
      }
      $es_usuario_menor = false;
      $pwd = rand(0,9).rand(0,9).rand(0,9).rand(0,9);
      $keys[] = 'fecha_inscripcion'; $values[] = "NOW()";
      $keys[] = 'password'; $values[] = "MD5('{$pwd}')";
      if ((int)$_POST['rrut'] >= consts::$rutminimo and (int)$_POST['rrut'] <= consts::$rutmaximo)
      {
          $keys[] = 'activated';
          $values[] = 'false';
          $es_usuario_menor = true;
      }
      
      $allkeys = implode(",", $keys);
      $allvals = implode(",", $values);
      
      $query = "insert into users ({$allkeys}) values ({$allvals})";
      $res = mysql_query($query, dbConn::$cn);
      if (!$res)
      {
        $datos_correctos = false;
         $num = mysql_errno(dbConn::$cn);
         if ($num == 1062)
         {
            echo '"El rut especificado ya se encuentra en uso.\n"+';
            
         }
         else
         {
            echo '"Error: '.$num.mysql_error(dbConn::$cn).'"+';
         }
      }
   // mandar mail
   $redirecciona = "";
   if ($datos_correctos === true)
   {
      $from  = consts::$from_email;
      $to = $_POST['remail'];
      $rut = $_POST['rrut'];
      $nombre = strtoupper($_POST['rnombres']);
      $subject = consts::$asunto_email_registro;
      $random_hash = md5(date('r', time())); 
      $headers = implode("\r\n", array("From: $from",
                                       "To: $to",
                                       "Reply-To: $from",
                                       /* "Content-type: text/html; charset=\"utf-8\"" */
                                        "Content-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\""
                                       ));
      //$body = consts::cuerpo_email_registro($nombre, $rut, $pwd);
        //read the atachment file contents into a string,
        //encode it with MIME base64,
        //and split it into smaller chunks
      
        $attachment = chunk_split(base64_encode(file_get_contents(consts::$docregistro))); 
        //echo $attachment;
        
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
<strong>Estimado $nombre,</strong>

<p>Le damos la bienvenida a PortalRemate. Su cuenta ha sido correctamente activada para usarla. Para entrar a la página y participar en los remates, ingrese <a href="http://www.portalremate.cl">aquí</a> con los siguientes datos:</p>

<ul>
<li><strong>Rut:</strong> $rut</li>
<li><strong>Contraseña:</strong> $pwd</li>
</ul>

<p>Además le Adjuntamos en formato PDF el contrato donde se estipulan todas las cláusulas que usted acepto al momento del registro y son válidas para efectos de compras bajo este portal.</p>
</body>
</html>

--PHP-alt-$random_hash--

EOF;

$incluir = consts::$registro_incluir_pdf == "1";

if ($incluir)
{
   $body .= <<<EOF
--PHP-mixed-$random_hash
Content-Type: application/pdf; name="ContratoPortalRemate.pdf"
Content-Transfer-Encoding: base64
Content-Disposition: attachment

$attachment

EOF;
}
$body .= <<<EOF
--PHP-mixed-$random_hash--
EOF;




      if (!mail($to, $subject, $body, $headers, "-f$from"))
      //if (false)
      {
         echo '"Cuenta creada. Problemas al enviar el email de confirmacion.\n"+';
         
      }
      else
      {
        echo '"Gracias por inscribirse en PortalRemate. Su contraseña \n ha sido enviada a su correo electrónico."+';
         $redirecciona .= " parent.location.reload();";
      }
   }
      echo "\"\"); $redirecciona</script>";
      exit();
   }
?>
<html>
<head>
<script language="javascript" src="registro_script.js"></script>
<style type="text/css">
#regwrapper .tabla td
{
	text-align: center;
}
#regwrapper h2
{
	margin-top: 0px;
}
#regwrapper
{
	width: 720px;
}
</style>
</head>
<body>
<div id="regwrapper">
<center><form id="reg" onSubmit="registra(this); return false;" target="hd" method="post">
   <img src="LOGO1.png" style="float: left;" />
   <h2>Formulario de registro (*Campos Obligatorios)</h2>
	<h4>Bienvenidos a la Plataforma de Remates Online</h4>
   <table class="tabla">
      <tr><td colspan="2"><center><input type="radio" checked="checked" id="radio_persona" name="tipof" value="Persona"/><label for="radio_persona">Persona</label> <input type="radio" id="radio_empresa" name="tipof" value="Empresa" /><label for="radio_empresa">Empresa</label></center></td></tr>
      <tr><td>RUT (sin puntos ni gui&oacute;n):</td><td><input type="text" obligatorio="si" maxlength="10" size="10" name="rrut" />&nbsp;-&nbsp;<input obligatorio="si" type="text" size="1" maxlength="1" name="rdv"/></td></tr>
      <tr><td>Nombre(s):</td><td><input obligatorio="si" type="text" name="rnombres" /></td></tr>
      <tr><td>Apellido Paterno:</td><td><input type="text" obligatorio="si" name="rapellidop" /></td></tr>
      <tr><td>Apellido Materno:</td><td><input type="text" name="rapellidom" /></td></tr>
      <tr><td>Correo electr&oacute;nico:</td><td><input type="text" obligatorio="si" name="remail" /></td></tr>
      <tr><td>Confirme correo electr&oacute;nico:</td><td><input type="text" obligatorio="si" name="remail2" /></td></tr>
      <tr><td>Nacionalidad</td><td>
                              <select name="rnacionalidad">
                                 <option value="Chilena">Chilena</option>
                                 <option value="Otra">Otra</option>
                              </select>
                              </td></tr>
      <tr><td>Direcci&oacute;n:</td><td><input type="text" name="rdireccion" obligatorio="si" /></td></tr>
      <tr><td>Regi&oacute;n:</td><td>
                              <select id="rregion" obligatorio="si" name="rregion" onChange="act_comunas(this,'rcomuna');">
                                 <? include ("regiones.php"); ?>
                              </select>
                              </td></tr>
      <tr><td>Comuna:</td><td>
                           <select id="rcomuna" name="rcomuna" obligatorio="si" disabled="disabled">
                              <option value="-" selected="selected">Seleccione regi&oacute;n</option>
                           </select>
                           </td></tr>
      <tr><td>Tel&eacute;fono de contacto:</td><td><input type="text" obligatorio="si" name="rtelefono" /></td></tr>
      <tr><td>Celular:</td><td><input type="text" obligatorio="si" name="rtelefono2" /></td></tr>
      <tr id="facturacion_row"><td colspan="2">
      <p><strong>Datos de facturaci&oacute;n</strong></p>
      <center><p><table class="tabla">
      <tr><td>Nombre: </td><td><input obligatorio="si" type="text" name="rf_nombre" /></td></tr>
      <tr><td>Rut: </td><td><input type="text" obligatorio="si" name="rf_rut" size="10" /> - <input type="text" obligatorio="si" name="rf_dv" size="1"/></td></tr>
      <tr><td>Giro: </td><td><input type="text" obligatorio="si" name="rf_giro"  /></td></tr>
      <tr><td>Direcci&oacute;n: </td><td><input type="text" obligatorio="si" name="rf_direccion"  /></td></tr>
      <tr><td>Tel&eacute;fono: </td><td><input type="text" obligatorio="si" name="rf_telefono"  /></td></tr>
      <tr><td>Regi&oacute;n: </td><td><select name="rf_region" obligatorio="si" id="rf_region" onChange="act_comunas(this, 'rf_comuna');"><? include('regiones.php'); ?></select></td></tr>
      <tr><td>Comuna: </td><td><select name="rf_comuna" obligatorio="si" id="rf_comuna"></select></td></tr>
      <tr><td>Correo electr&oacute;nico: </td><td><input type="text" obligatorio="si" name="rf_email"  /></td></tr>
      </table></p></center>
      <p>Nota: Se emitir&aacute; Factura de Venta Electr&oacute;nica al correo electr&oacute;nico con los datos anteriormente indicados.</p>
      </td></tr>
      <tr><td colspan="2" align="center"><textarea cols="60" rows="6" readonly="readonly"><? echo file_get_contents("bases.txt");?></textarea></td></tr>
      <tr><td colspan="2" align="center">Acepto los t&eacute;rminos y condiciones: <input type="checkbox" id="accept" /></td></tr>
      <tr><td colspan="2" align="center"><input class="submitea" type="submit" value="Enviar datos" /></td></tr>
   </table>
</form>
</center>
<iframe id="hd" name="hd" style="width: 0px; height: 0px; visibility: hidden;"></iframe>
<span class="formulario"></span>
</div>
</body>
</html>

