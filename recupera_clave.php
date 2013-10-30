<?
   require_once("header.php");
   $mensaje = "";
   if (count($_POST) == 1)
   {
       echo '<script language="javascript" src="jquery.js"></script>';
      if (!isset($_POST['rut']) || strspn($_POST['rut'], "0123456789") != strlen($_POST['rut']))
         $mensaje = consts::$mensajes[8];
      else
      {
         // verificar existencia
         $query = "select rut, email, nombres, ultimocambio from users where rut = " . mysql_real_escape_string($_POST['rut']);
         $res = mysql_query($query, dbConn::$cn);
         if (!$res) dbConn::dbError($query);
         if (mysql_num_rows($res) == 0)
            $mensaje = "El rut especificado no existe";
         else
         {
            list($rut, $correo, $nombre, $uc) = mysql_fetch_row($res);
            $password = rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9);
            
            if (!empty($uc) && @strtotime($uc) + 3600 > time())
            {
               $mensaje = "Estimado Cliente: Usted ya ha Solicitado Recuperar Contraseña.<br />"."Favor Revisar su Cuenta Email,   Luego Validar Link Enlace y <br />"."Posteriormente Ingresar Nueva Contraseña.";
            }
            else
            {
               $res = mysql_query("update users set pseudopass = MD5('{$password}') where rut = {$rut}", dbConn::$cn);
               if (!$res)
                $mensaje = "Problemas al intentar cambiar la clave.";
               else
               {
                 $from  = consts::$from_email;
                 $to = $correo;
                 $subject = consts::$asunto_email_recclave;
                 $headers = implode("\r\n", array(
                                            "From: $from",
                                            "To: $to",
                                            "Reply-To: $from",
                                            "X-Mailer: PHP/".phpversion(),
                                            "MIME-Version: 1.0",
                                            "Content-type: text/html; charset=iso-8859-1"
                                            ));
                 $cc = $rut;
                 $body = consts::cuerpo_email_recclave($nombre, $rut, $password);
                 
                 if (!mail($to, $subject, $body, $headers, "-f$from"))
                 {
                    $mensaje = "Problemas enviando el correo electronico";
                 }
                 else
                 {
                    $res = mysql_query("update users set ultimocambio = now() where rut = {$rut}", dbConn::$cn);
                    $mensaje = "Correo enviado.";
                 }
               }
            }
         }
      }
    echo <<<END
    <script language="javascript">
    <!--
    $("#resultado", top.document).html("$mensaje");
    //-->
    </script>
END;
   }
   else
   {
?>
<div style="width: 500px; height: 100%;">
<h4>Recuperación de contraseña</h4>
<p>Para obtener una nueva contraseña en su correo electrónico, <br />
Ingrese su Rut (sin puntos ni guión, y sin dígito verificador) <br />
y presione Enter o haga Click en el bot&oacute;n Enviar.</p>
<form target="oculto" action="recupera_clave.php" method="post">
<input type="text" name="rut" style="float: left;"/><input   class="submitea" type="submit" value="Enviar" />
</form>
<p><span id="resultado"></span></p>
<iframe style="visibility: hidden; width: 0px; height: 0px;" name="oculto" id="oculto"></iframe>
</div>
<?
   }
?>
