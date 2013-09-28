<?
   // photo uploader
   // Responde en un iframe
   $response = "";
   include("header.php");
   include("SimpleImage.inc.php");
   if (!esAdmin())   die(consts::$mensajes[9]);
   if (count($_FILES) < 1) die(consts::$mensajes[8]);
   $foto = $_POST["num"];
   $producto = $_POST["idprod"];
   if ($_FILES["file"]["error"] > 0)
   {
      $response .= "Error";
      rematelog("Error de subida de archivos: ".$_FILES["file"]["error"]);
   }
   else if ($_FILES["file"]["size"] > 2*1024*1024)
   {
      $response .= "El archivo es demasiado grande. (Máx. 2 MB)";
   }
   else
   {
      $type = $_FILES["file"]["type"];
      if (in_array($type, array("image/jpeg", "image/png", "image/gif", "image/jpg", "image/pjpeg", "image/pgif", "image/ppng", "image/pjpg")))
      {
         // Generar un nombre para el archivo, guardarlo.
         $filename = strtok($_FILES["file"]["name"], ".");
         $ext = strtok(".");
         $filename = substr(hash("sha1", $producto.rand(1000,9999)), 0, 16);
         $filename_full = $filename.".".$ext;
         $move = move_uploaded_file($_FILES["file"]["tmp_name"], "uploads/".$filename_full);
         $img = new SimpleImage();
         $img->load("uploads/$filename_full");
         $img->resize(50,50);
         $img->save("uploads/small/$filename_full");
         if (!$move)
            $response .= "Problemas al cargar el archivo.";
         else
            $response .= "Archivo cargado";
         $query = "update productos set foto{$foto} = 'uploads/{$filename_full}' where id_producto = {$producto}";
         $res = mysql_query($query, dbConn::$cn);
      }
      else
      {
         $response .= "Tipo de archivo no soportado. (solo jpg/jpeg/png/gif soportados)";
      }
   }
?>
<script language="javascript" src="jquery.js"></script>
<script language="javascript">
<!--
var nfoto = <?= $foto ?>;
   $("#subefoto", top.document).html("<?= $response ?> (foto "+nfoto+")");
//-->
</script>
