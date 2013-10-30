<?
include("header.php");
   if (isset($_POST['msg']) && isset($_POST['asunto']) && count($_POST) == 2)
   {
      // envia
      $from = consts::$from_email;
      $res = mysql_query(($query = "select email, nombres, apellidop, rut from users where inscrito = true and LENGTH(email) > 3"),dbConn::$cn) or dbConn::dbError($query); 
      $correos = array();
      if (mysql_num_rows($res) > 0) while(list($correo, $nombres, $apellido, $rut) = mysql_fetch_row($res))
      {
         $correos[] = $correo;
      }
      $headers = implode("\r\n", array("From: $from",
                                       "Reply-To: $from",
                                       "X-Mailer: PHP/".phpversion(),
                                       "MIME-Version: 1.0",
                                       "Content-type: text/html; charset=utf-8",
                                       "Bcc: ".implode(",", $correos)
                                       ));
      if (mail($from, $_POST['asunto'], nl2br(utf8_encode($_POST['msg'])), $headers, "-f$from"))
         echo "done";
      else
         echo "x";
   }
   else
   {
      //muestra form 
      ?>
      <script language="javascript" src="correo.js"></script>
      <form id="fr" onsubmit="enviacorreo(this); return false;">
      <p>Asunto del correo: <input type="text" name="asunto" /></p>
      <p>Cuerpo del mensaje: </p>
      <p><textarea name="msg">PORTALREMATE.cl
Cuerpo del mensaje</textarea></p>
      <p><input  class="submitea" type="submit" value="Enviar correo a todos los usuarios" /><span id="carga"></span></p>
      </form>
      <?
   }
?>
