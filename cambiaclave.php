<?
   include("header.php");
   if (!isset($_GET['key']) || !isset($_GET['rut']))
      die(consts::$mensajes[8]);
   $rut = $_GET['rut'];
   
   $res = mysql_query("select pseudopass from users where rut = ".$rut, dbConn::$cn);
   if (!$res) dbConn::dbError("Consulta invalida");
   list($clave) = mysql_fetch_row($res);
   if ($clave != urlsafe_b64decode($_GET['key']))
      die("Autenticador invalido");
   if (strlen($clave) == 0)
      die("El usuario no ha solicitado cambio de clave.");
   $res = mysql_query("update users set password = pseudopass, pseudopass = NULL where rut = ".$rut, dbConn::$cn);
   if (!$res)
      dbConn::dbError("Problemas cambiando su clave.");
   
?>
<script language="javascript">
<!--
function redirect()
{
   location.href = "frontis.php";
}
setTimeout('redirect()', 5000);
-->
</script>
<p>Clave actualizada exitosamente. En breve sera redireccionado a la pagina inicial.</p>
