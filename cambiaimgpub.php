<script type="text/javascript">
var msg = "<?
    include("header.php");
    include("SimpleImage.inc.php");
    try
    {
        if (!adminGeneral()) throw new Exception(consts::$mensajes[9]);
        if (!isset($_FILES) || !isset($_POST['idp']) ) throw new Exception(consts::$mensajes[8]);
        
        if (isset($_FILES['pubimgfile']))
        {
            $idp = mysql_real_escape_string($_POST['idp']);
            $imagen = "";
            $resp = "Link cambiado correctamente";

            $res0 = mysql_query("select html from publicidades where id_pub = $idp", dbConn::$cn);
            $row0 = mysql_fetch_row($res0);
            $datoshtml = explode("|",$row0[0]);
            $imagen = $datoshtml[0];

            if ($_FILES['pubimgfile']['error'] <= 0)
            {
                $type = $_FILES['pubimgfile']['type'];
                if (!in_array($type, array("image/jpeg", "image/png", "image/gif", "image/jpg", "image/pjpeg", "image/pgif", "image/ppng", "image/pjpg"))) throw new Exception("Tipo de archivo no soportado.");
                if ($_FILES['pubimgfile']['size'] > 2*1024*1024)
                    throw new Exception("Archivo demasiado grande.");
                $filename = $_FILES['pubimgfile']['tmp_name'];
                $original = strtok($_FILES['pubimgfile']['name'], ".");
                $ext = strtok(".");
                
                
                list($width, $height) = array(166,140);
                $new_filename = $original.".".$ext;
                $res = move_uploaded_file($filename, "pub/$new_filename");
                if (!$res) throw new Exception("Problemas al intentar guardar el archivo subido.");
                /*
                $img = new SimpleImage();
                $img->load("pub/$new_filename");
                $img->resize($width, $height);
                $img->save("pub/$new_filename");
                * */
                $imagen = "pub/$new_filename";
                $resp = "Imagen y link actualizados correctamente.";
            }

            $htmlstring = mysql_real_escape_string($imagen . "|" . $_POST['pubimglink']);
        

            $r = mysql_query("update publicidades set html = '$htmlstring' where id_pub = $idp ", dbConn::$cn);
            if (!$r) throw new Exception("Problemas al registrar los nuevos datos.");
            
            echo $resp;
        }
        else if (isset($_FILES['pubflashfile']))
        {
            if ($_FILES['pubflashfile']['error'] > 0)
                throw new Exception("Problemas al subir el archivo.");
            $type = $_FILES['pubflashfile']['type'];
            if (!in_array($type, array("application/x-shockwave-flash"))) throw new Exception("Tipo de archivo no soportado.");
            if ($_FILES['pubimgfile']['size'] > 10*1024*1024)
                throw new Exception("Archivo demasiado grande.");
            $filename = $_FILES['pubflashfile']['tmp_name'];
            $original = strtok($_FILES['pubflashfile']['name'], ".");
            $ext = strtok(".");
            $idp = mysql_real_escape_string($_POST['idp']);
            
            $new_filename = $original.$ext;
            $res = move_uploaded_file($filename, "pub/$new_filename");
            if (!$res) throw new Exception("Problemas al intentar guardar el archivo subido.");
            $new_filename = mysql_real_escape_string($new_filename);
            
            $r = mysql_query("update publicidades set html = 'pub/$new_filename' where id_pub = $idp ", dbConn::$cn);
            if (!$r) throw new Exception("Problemas al registrar el objeto Flash.");
            
            echo "Archivo Flash subido correctamente.";
        }
        else if (isset($_FILES['pubyoutubeimg']))
        {
            $idp = mysql_real_escape_string($_POST['idp']);
            $r1 = mysql_query("select html from publicidades where id_pub = $idp", dbConn::$cn);
            $link = "";
            $imagen = "";
            if (mysql_num_rows($r1) > 0)
            {
                list($texto) = mysql_fetch_row($r1);
                list($link, $imagen) = explode("////", $texto);
            }
            $changes = false;
            if ($_FILES['pubyoutubeimg']['error'] == 0)
            {
                $filename = $_FILES['pubyoutubeimg']['tmp_name'];
                $original = strtok($_FILES['pubyoutubeimg']['name'], ".");
                $ext = strtok(".");
                
                
                list($width, $height) = array(166,140);
                $new_filename = "$original.$ext";
                $res = move_uploaded_file($filename, "pub/$new_filename");
                if (!$res) throw new Exception("Problemas al intentar guardar el archivo subido.");
                /*
                $img = new SimpleImage();
                $img->load("pub/$new_filename");
                $img->resize($width, $height);
                $img->save("pub/$new_filename");
                * */
                $new_filename = mysql_real_escape_string("pub/$new_filename");
                
                $imagen = $new_filename;
                
                $changes = true;
                
            }
            if (isset($_POST['pubyoutubelink']) && strlen($_POST['pubyoutubelink']) > 0)
            {
                $link = mysql_real_escape_string($_POST['pubyoutubelink']);
                $changes = true;
            }
            if ($changes === true)
            {
                $r2 = mysql_query("update publicidades set html = '".($link."////".$imagen)."' where id_pub = $idp", dbConn::$cn);
                if ($r2 === false) throw new Exception("Problemas al intentar actualizar publicidad.");
            }
            
            echo "Publicidad Youtube actualizada correctamente.";
        }
    }
    catch (Exception $e)
    {
        echo $e->getMessage();
    }
?>";
alert(msg);
</script>
