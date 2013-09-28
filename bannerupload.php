<script type="text/javascript">
var msg = "<?
    include("header.php");
    include("SimpleImage.inc.php");
    try
    {
        if (!esAdmin()) throw new Exception(consts::$mensajes[9]);
        if (!isset($_FILES) || !isset($_FILES['banner']) || !isset($_POST['idremate']) || count($_FILES) != 2) throw new Exception(consts::$mensajes[8]);
        $hay = false;
        if ($_FILES['banner']['error'] == 0)
        {
            $hay = true;
            $type = $_FILES['banner']['type'];
            if (!in_array($type, array("image/jpeg", "image/png", "image/gif", "image/jpg", "image/pjpeg", "image/pgif", "image/ppng", "image/pjpg"))) throw new Exception("Tipo de archivo de la imagen no soportado.");
            if ($_FILES['banner']['size'] > 2*1024*1024)
                throw new Exception("Archivo de imagen demasiado grande.");
            $filename = $_FILES['banner']['tmp_name'];
            $original = strtok($_FILES['banner']['name'], ".");
            $ext = strtok(".");
            $idr = mysql_real_escape_string($_POST['idremate']);
            
            $r = mysql_query("select banner_size from remates where id_remate = $idr", dbConn::$cn);
            if (!$r) throw new Exception("Error en ID de remate.");
            list($size) = mysql_fetch_row($r);
            $dims = array( 
                3 => array(644*1.2,296*1.2),
                2 => array(322*1.2,296*1.2),
                1 => array(322*1.2,296*0.6),
					 0 => array(644*1.2,410*1.2)
                );
            list($width, $height) = $dims[$size];
            $new_filename = substr(md5($idr.rand(400,800)), 0, 5).".$ext";
            $res = move_uploaded_file($filename, "uploads/remates/orig_$new_filename");
            if (!$res) throw new Exception("Problemas al intentar guardar el archivo subido.");
            $img = new SimpleImage();
            $img->load("uploads/remates/orig_$new_filename");
            $img->resizeToHeight($height);
            $img->resizeToWidth($width);
            $img->save("uploads/remates/$new_filename");
				
            $img2 = new SimpleImage();
				$img2->load("uploads/remates/orig_$new_filename");
            $img2->resizeToHeight($dims[0][1]);
            $img2->resizeToWidth($dims[0][0]);
            $img2->save("uploads/remates/extra_$new_filename");
				
            $r = mysql_query("update remates set banner = 'uploads/remates/$new_filename' where id_remate = $idr", dbConn::$cn);
            if (!$r) throw new Exception("Problemas al registrar la nueva imagen.");
            
            echo "Imagen subida correctamente. \\n";
        }
        if ($_FILES['procedimiento']['error'] == 0)
        {
            $hay = true;
            $type = $_FILES['procedimiento']['type'];
            if (!in_array($type, array("application/pdf","application/x-pdf", "application/msword"))) throw new Exception("Tipo de archivo del documento no soportado.");
            if ($_FILES['procedimiento']['size'] > 2*1024*1024)
                throw new Exception("Documento demasiado grande.");
            $filename = $_FILES['procedimiento']['tmp_name'];
            $original = strtok($_FILES['procedimiento']['name'], ".");
            $ext = strtok(".");
            $idr = mysql_real_escape_string($_POST['idremate']);
            
            
            $new_filename = substr(md5($idr.rand(400,800)), 0, 5).".$ext";
            $res = move_uploaded_file($filename, "uploads/remates/$new_filename");
            if (!$res) throw new Exception("Problemas al intentar guardar el archivo subido.");
            
            
            $r = mysql_query("update remates set procedimiento = 'uploads/remates/$new_filename' where id_remate = $idr", dbConn::$cn);
            if (!$r) throw new Exception("Problemas al registrar el nuevo documento.");
            
            echo "Documento subido correctamente. \\n";
        }
        if (!$hay)
            echo "Seleccione un archivo para subir.";
    }
    catch (Exception $e)
    {
        echo $e->getMessage();
    }
?>";
alert(msg);
</script>
