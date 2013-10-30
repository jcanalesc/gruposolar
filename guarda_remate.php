<?
   include("header.php");
   // guarda_remate.php
   if (isset($_POST['id']) && isset($_POST['puja'])
    && isset($_POST['npuja']) && isset($_POST['durlote'])
    && esAdmin())
   {
      $id = mysql_real_escape_string($_POST['id']);
      $puja = mysql_real_escape_string($_POST['puja']);
      $npuja = mysql_real_escape_string($_POST['npuja']);
      $durlote = mysql_real_escape_string($_POST['durlote']);
      if (strspn($npuja, "0123456789") != strlen($npuja) ||
          strspn($durlote, "01234567890") != strlen($durlote))
      echo "Error de formato.";
      $query = "update remates set tipo_puja='{$puja}', valor_puja={$npuja}, duracion_lote={$durlote} where id_remate = {$id}";
      $res = mysql_query($query, dbConn::$cn);
      if (!$res)
         dbConn::dbError($query);
      else
      {
         actualiza_lotes($id);
         echo "Datos guardados correctamente.";
      }
   }
   else die(consts::$mensajes[8].",".consts::$mensajes[9]);
?>
