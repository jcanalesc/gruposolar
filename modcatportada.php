<?
    // modcatportada.php
    $_GET['sala'] = 1;
    include("header.php");
    $res = json_encode(array('msg' => 'Error al modificar datos.'));
    if (!adminGeneral()) die($res);
    if (isset($_GET['tipo']))
    {
        if ($_GET['tipo'] == "new")
        {
            // crea una nueva
            $r = mysql_query("insert into categorias_portada (texto) values ('Nueva categoria')", dbConn::$cn);
            if ($r) die("true"); else die($res);
        }
        else
        {
            if (!isset($_GET['val']) || !isset($_GET['id_cp'])) die($res);
            $idcp = mysql_real_escape_string($_GET['id_cp']);
            $texto = mysql_real_escape_string($_GET['val']);
            if ($_GET['tipo'] == "nombre")
            {
                $r = mysql_query("update categorias_portada set texto = '$texto' where id_cp = $idcp", dbConn::$cn);
                if ($r) die("true"); else die($res);
            }
            else if ($_GET['tipo'] == "url")
            {
                $r = mysql_query("update categorias_portada set url_pag = '$texto' where id_cp = $idcp", dbConn::$cn);
                if ($r) die("true"); else die($res);
            }
            else if ($_GET['tipo'] == "erase")
            {
                $r = mysql_query("delete from categorias_portada where id_cp = $idcp", dbConn::$cn);
                if ($r) die("true"); else die($res);
            }
        }
    }
    else if (isset($_POST['idcp']) && count($_FILES) > 0)
    {
        ?>
        <script type="text/javascript" src="jquery.js"></script>
        <script type="text/javascript">
        var msg = "<?
        include("SimpleImage.inc.php");
        try
        {
            if (!isset($_FILES['cat_img']) || count($_FILES) != 1) throw new Exception(consts::$mensajes[8]);
            if ($_FILES['cat_img']['error'] > 0)
                throw new Exception("Problemas al subir el archivo.");
            $type = $_FILES['cat_img']['type'];
            if (!in_array($type, array("image/jpeg", "image/png", "image/gif", "image/jpg", "image/pjpeg", "image/pgif", "image/ppng", "image/pjpg"))) 
                throw new Exception("Tipo de archivo no soportado.");
            if ($_FILES['cat_img']['size'] > 2*1024*1024)
            throw new Exception("Archivo demasiado grande.");
            $filename = $_FILES['cat_img']['tmp_name'];
            $original = strtok($_FILES['cat_img']['name'], ".");
            $ext = strtok(".");
            $idcp = mysql_real_escape_string($_POST['idcp']);
            list($width, $height) = array(117,32);
            $new_filename = "imgcat_".substr(md5($idcp), 0, 5).".$ext";
            $res = move_uploaded_file($filename, "$new_filename");
            if (!$res) 
                throw new Exception("Problemas al intentar guardar el archivo subido.");
            $img = new SimpleImage();
            $img->load($new_filename);
            $img->resize($width, $height);
            $img->save($new_filename);
            
            $r = mysql_query("update categorias_portada set foto = '$new_filename' where id_cp = $idcp", dbConn::$cn);
                if (!$r) throw new Exception("Problemas al registrar la nueva imagen.");
            
            echo "Imagen subida correctamente.";
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }
        ?>";
        alert(msg);
        $("button.cancel", top.document).click();
        </script>
        <?
    }
    else echo "false";
?>
