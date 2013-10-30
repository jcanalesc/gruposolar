<?
   include("header.php");
   // remadj.php
   if (!esAdmin()) die(consts::$mensajes[9]);
   
   if (!isset($_POST['rut']) || !isset($_POST['lote'])) die(consts::$mensajes[8]);
   
   $rut = mysql_real_escape_string($_POST['rut']);
   $lote = mysql_real_escape_string($_POST['lote']);
   $res = mysql_query("select id_accion, cantidad from acciones where tipo = 'Adjudicacion' and rut = $rut and id_lote = $lote", dbConn::$cn);
   if (mysql_num_rows($res) == 0)
      die("no");
   else
   {
      list($id, $cantidad) = mysql_fetch_row($res); 
      if ($cantidad > 1)
         mysql_query("update acciones set cantidad=cantidad-1 where id_accion = $id", dbConn::$cn) or dbConn::dbError("");
      else
         mysql_query("delete from acciones where id_accion = $id", dbConn::$cn) or dbConn::dbError("");
      die("yes");
   }
?>
