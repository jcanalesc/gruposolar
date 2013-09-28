<?
    // miniauction_mod.php
    // recibe json lleno de parametros para crear miniremate
    include("header.php");
    
    function jdie($text = null, $reload = false)
    {
        if ($text == null)
            $text = "Error al ingresar los datos.";
        echo "alert('$text');\n";
        if ($reload)
        {
            if (isset($_POST['mod']))
                echo "parent.$(\"#floating .close\").click();\n";
            echo "window.parent.goto('miniremates_edit.php');\n";
        }
        die("</script>");
    }
    
    if (count($_GET) > 0 && isset($_GET['action']))
    {
        if ($_GET['action'] == "del")
        {
            $idmr = mysql_real_escape_string($_GET['idmr']);
            $r = mysql_query("delete from miniremates where id_miniremate = $idmr", dbConn::$cn);
            if ($r)
                die("true");
            else
                die("false");
        }
        else if ($_GET['action'] == "multidel")
        {
            $ids = $_GET['idmr'];
            foreach($ids as $i)
            {
                $r = mysql_query("delete from miniremates where id_miniremate = $i", dbConn::$cn);
                if (!$r)
                    rematelog("Error al intentar eliminar el miniremate $i: " . mysql_error(dbConn::$cn));
            }
            die("ok");
        }
    }
?>
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript">
<?
    
    if (!adminGeneral()) jdie("No tiene permisos para realizar esa acción");

    if (!isset($_POST['id_producto']) || !isset($_POST['monto_inicial']) || 
        !isset($_POST['incremento']) || !isset($_POST['fecha_termino']) )
            jdie("Llamada invalida");
    
    $idp = mysql_real_escape_string($_POST['id_producto']);
    $monto = mysql_real_escape_string($_POST['monto_inicial']);
    $incr = mysql_real_escape_string($_POST['incremento']);
    $fecha = mysql_real_escape_string($_POST['fecha_termino']." ".$_POST['hora_termino']);
    $fechai = date("Y-m-d H:i:s", time());
    $titulo = mysql_real_escape_string($_POST['titulo']);
    $texto = mysql_real_escape_string($_POST['texto']); 
    $deltaval = rand(60,300);
    if (isset($_POST['fecha_inicio']) && strlen($_POST['fecha_inicio']) > 0)
        $fechai = mysql_real_escape_string($_POST['fecha_inicio']." ".$_POST['hora_inicio']);
    
    
    $hayfoto = false;
    if (isset($_FILES['foto']))
    {
        if ($_FILES['foto']['error'] > 0)
        {
            if (!isset($_POST['mod']))
                jdie("error con la foto");
        }
        else
        {
            $hayfoto = true;
            if (!in_array($_FILES['foto']['type'], array("image/jpeg", "image/png", "image/gif", "image/jpg", "image/pjpeg", "image/pgif", "image/ppng", "image/pjpg"))) jdie("tipo incorrecto");
            if ($_FILES['foto']['size'] > 2*1024*1024) jdie("muy grande");
            $filename = $_FILES['foto']['tmp_name'];
            $nfilename = $_FILES['foto']['name'];
            $foto = "uploads/minir/$nfilename";
            if (!move_uploaded_file($filename, $foto)) jdie("problemas al mover foto");
        }
    }
    $query = "";
    if (isset($_POST['mod']) && $_POST['mod'] == "true" && isset($_POST['idmr']))
    {
        $idmr = mysql_real_escape_string($_POST['idmr']);
        $sqlfoto = ($hayfoto ? "foto='$foto', " : "");
        $query = "update miniremates set fecha_inicio='$fechai', fecha_termino='$fecha', monto_actual=$monto, monto_inicial=$monto, incremento=$incr,$sqlfoto texto='$texto', titulo='$titulo' where id_miniremate = $idmr";
    }
    else
    {
        $query = "insert into miniremates (id_producto, fecha_inicio, fecha_termino, rut_ganador, monto_actual, incremento, monto_inicial, foto, texto, titulo, delta) values ($idp,'$fechai','$fecha',1111,$monto,$incr,$monto, '$foto', '$texto', '$titulo', $deltaval)";
    }
    $r = mysql_query($query, dbConn::$cn);
    rematelog($query);
    if ($r)
        jdie("Datos ingresados correctamente.", true);
    else
        rematelog(mysql_error()) and jdie("error bdd");
?>
