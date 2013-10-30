<?
   include("header.php");
   
   // lotes.php devuelve un XML con la informacion del lote actual del remate de id $_POST['id_remate'].
   if (isset($_POST['id_remate']) && count($_POST) == 1)
   {
      list($tipopuja) = mysql_fetch_row(mysql_query("select tipo_puja from remates where id_remate = {$_POST['id_remate']}", dbConn::$cn));
      $query = "select productos.id_producto, lotes.id_lote, productos.nombre, productos.descripcion, lotes.cantidad, productos.foto1, productos.foto2, productos.foto3, productos.foto4, productos.precio_min, lotes.orden, remates.tipo_puja, remates.valor_puja from productos, lotes, remates where lotes.id_producto = productos.id_producto and lotes.id_lote = remates.lote_actual and remates.id_remate = ".mysql_real_escape_string($_POST['id_remate']);
      $res = mysql_query($query, dbConn::$cn);
      if (!$res)
         dbConn::dbError($query);
      $row = mysql_fetch_assoc($res);
      $xml = new SimpleXMLElement("<?xml version=\"1.0\"?><lote_actual></lote_actual>");
      foreach($row as $key => $value)
      {
         if ($key == "precio_min" && $tipopuja == "Sin Minimo")
            $xml->addChild($key, "1");
         else
            $xml->addChild($key, $value);
      }
      echo $xml->asXML();
   }
   else echo consts::$mensajes[8];
?>
