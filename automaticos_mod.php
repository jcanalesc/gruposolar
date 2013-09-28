<?
    include("header.php");
    function jdie($text = null, $reload = false)
    {
        echo "<script type=\"text/javascript\">\n";
        if ($text == null)
            $text = "Error al ingresar los datos.";
        echo "alert('$text');\n";
        if ($reload)
        {
            if (isset($_POST['mod']))
                echo "parent.$(\"#floating .close\").click();\n";
            echo "window.parent.goto('miniremates_automaticos.php');\n";
        }
        die("</script>");
    }
    // automaticos_mod.php
    
    if (count($_POST) > 0)
    {
        // agregando nuevo elemento
        $idprod = mysql_real_escape_string($_POST['id_producto']);
        $titulo = mysql_real_escape_string($_POST['titulo']);
        $texto = mysql_real_escape_string($_POST['descr']);
        $minimo = mysql_real_escape_string($_POST['monto_inicial']);
        $foto = "";
        if (count($_FILES) > 0 && $_FILES['foto']['error'] == 0)
        {
            if (!in_array($_FILES['foto']['type'], array("image/jpeg", "image/png", "image/gif", "image/jpg", "image/pjpeg", "image/pgif", "image/ppng", "image/pjpg"))) jdie("tipo incorrecto");
            if ($_FILES['foto']['size'] > 2*1024*1024) jdie("muy grande");
            if (!move_uploaded_file($_FILES['foto']['tmp_name'], "archivos/a_" . $_FILES['foto']['name'] ))
                jdie("Problemas al guardar el archivo.");
            $foto = "archivos/a_" . $_FILES['foto']['name'];
        }
        if (isset($_POST['mod']) && $_POST['mod'] == "true")
        {
            $id = mysql_real_escape_string($_POST['idmr']);
            $modfoto = $foto != "" ? ", foto = '$foto'" : "";
            $query = "update automaticos set id_producto = $idprod, titulo = '$titulo', descripcion = '$texto', minimo = $minimo $modfoto where id_auto = $id";
            $r = mysql_query($query, dbConn::$cn);
            if (!$r)
                jdie("Problemas al guardar los cambios.");
            else
                jdie("Cambios guardados correctamente.", true);
        }
        else
        {
            $query = "insert into automaticos (id_producto, minimo, titulo, descripcion, foto) values ($idprod, $minimo, '$titulo', '$texto', '$foto')";
            $r = mysql_query($query, dbConn::$cn);
            if (!$r)
                jdie("Problemas al guardar el miniremate automatico.");
            else
                jdie("Miniremate automatico registrado correctamente.", true);
        }
        
        $r = mysql_query($query, dbConn::$cn);
        if (!$r)
            jdie("Problemas al intentar registrar el miniremate automático.");
        else
            jdie("Automático ingresado correctamente.", true);
        
        
    }
    else if (count($_GET) > 0)
    {
        switch($_GET['action'])
        {
            case "del":
                    $idmr = mysql_real_escape_string($_GET['idmr']);
                    $r = mysql_query("delete from automaticos where id_auto = $idmr", dbConn::$cn);
                    if (!$r)
                        die("error");
            break;
            case "modshow":
                    $idmr = mysql_real_escape_string($_GET['idmr']);
                    
            break;
            case "multidel":
                    $idmrs = $_GET['idmr'];
                    $r = mysql_query("delete from automaticos where id_auto in (" . implode(",", $idmrs) .")", dbConn::$cn);
                    if (!$r)
                        die("error");
            break;
            case "exec":
                $cantidad = (int)mysql_real_escape_string($_GET['cantidad']);
                $aumento = (int)mysql_real_escape_string($_GET['aumento']);
                $duracion = mysql_real_escape_string($_GET['duracion']);
                $duraciones = explode(":", $duracion);
                $horas = 0;
                $minutos = 0;
                if (count($duraciones) > 1)
                {
                    $horas = (int)$duraciones[0];
                    $minutos = (int)$duraciones[1];
                }
                else
                {
                    $minutos = (int)$duraciones[0];
                }
                
                consts::$automaticos = array($cantidad, $aumento, $horas, $minutos);
                consts::save_config();
                
            break;
            case "toggle":
                $idmr = mysql_real_escape_string($_GET['idmr']);
                $r = mysql_query("select activo from automaticos where id_auto = $idmr", dbConn::$cn);
                if (!$r or mysql_num_rows($r) == 0)
                    die("No se encuentra el miniremate.");
                list($activado) = mysql_fetch_row($r);
                if ($activado == "1")
                {
                    // desactivar
                    $r2 = mysql_query("update automaticos set activo = false where id_auto = $idmr", dbConn::$cn);
                    if (!$r2)
                        die("Problemas al intentar aplicar la operación.");
                    die("off");
                }
                else
                {
                    $r2 = mysql_query("update automaticos set activo = true where id_auto = $idmr", dbConn::$cn);
                    if (!$r2)
                        die("Problemas al intentar aplicar la operación.");
                    die("on");
                }
            break;
        }
        die("ok");
    }
?>
