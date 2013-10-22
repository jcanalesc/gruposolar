<? 
include("header.php");
 $numericos = array("id_producto", "id_oferta", "cant_maxima", "precio");

if (strtolower($_SERVER['REQUEST_METHOD']) == "get")
{
    $col = mysql_real_escape_string($_GET['col']);
    $val = mysql_real_escape_string($_GET['val']);
    $ido = mysql_real_escape_string($_GET['ido']);
    if (!in_array($col, $numericos))
        $val = "'$val'";
    if (strlen($val) == 0) die("Debe escribir algo.");
    $res = mysql_query("update ofertas set $col = $val where id_oferta = $ido");
    if ($res)
        die("ok");
    else
        die("Error al intentar modificar el atributo de la oferta.");
}
?>
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript">
<?
$ido = $_POST['id_oferta'];
	
	// oferta_mod.php
	function jdie($idf, $text = null, $link = false, $close = false)
    {
        if ($text == null)
            $text = "Error al ingresar los datos.";
        echo "alert('$text');\n";
        if ($link)
        {	
            echo "window.parent.open('oferta.php?id=".$idf."');\n";
        }
        if ($close)
        {
            echo "parent.$.fancybox.close();\n";
        }
        die("</script>");
    }

	if (!adminGeneral()) jdie(0, "Acceso denegado");

	$oferta = array();

	//print_r($_POST);

	foreach($_POST as $k => $v)
	{

		if (substr($k,0,5) == "fecha")
		{
            $hour = strlen($v[1] <= 5) ? $v[1].":00" : $v[1];
			$oferta[$k] = $v[0]." ".$hour;
		}
		else
			$oferta[$k] = $v;
	}
	unset($k);
	unset($v);

	foreach($_FILES as $k => $v)
	{
        error_log("$k: " . $v['error']);
        if ($v['error'] == UPLOAD_ERR_NO_FILE) continue;

        if ($v['error'] > 0)
        {
        	jdie(0, "Error al intentar subir uno de los archivos. ($k)");
        }
        else
        {
        	$tipos['banner'] = array("image/jpeg", "image/png", "image/gif", "image/jpg", "image/pjpeg", "image/pgif", "image/ppng", "image/pjpg");
        	$tipos['procedimiento'] = array("application/pdf","application/x-pdf");

            if (!in_array($v['type'], $tipos[$k])) jdie(0,"Tipo de archivo incorrecto. ($k)");
            if ($v['size'] > 4*1024*1024) jdie(0,"Tamaño máximo de 4 MB excedido. ($k)");
            $filename = $v['tmp_name'];
            $nfilename = $v['name'];
            $foto = "uploads/ofertas/oferta_{$ido}_$k." . pathinfo($nfilename, PATHINFO_EXTENSION);
            if (!move_uploaded_file($filename, $foto)) jdie(0,"Problemas al grabar archivo. ($k)");
            $oferta[$k] = $foto;
        }
    }
    unset($k);
	unset($v);

    $cadena_cols = array();
    $cadena_vals = array();
    $cadena_update = array();
    
    foreach ($oferta as $k => $v)
    {
        $tmp = "$k = ";
    	$cadena_cols[] = $k;
    	if (in_array($k, $numericos))
        {
    		$cadena_vals[] = "$v";
            $tmp .= "$v";
        }
    	else
        {
    		$cadena_vals[] = "'$v'";
            $tmp .= "'$v'";
        }
        if ($k != "id_oferta")
            $cadena_update[] = $tmp;
    }



    $cadena_cols = implode(",", $cadena_cols);
    $cadena_vals = implode(",", $cadena_vals);
    $cadena_update = implode(",", $cadena_update);


    $query = "insert into ofertas ($cadena_cols) values ($cadena_vals)";

    $res = mysql_query($query, dbConn::$cn);
    if (!$res)
    {
        if (mysql_errno(dbConn::$cn) == 1062) // duplicate key
        {
            $res2 = mysql_query("update ofertas set $cadena_update where id_oferta = $ido", dbConn::$cn);
            if ($res2)
                jdie(0,"Oferta modificada correctamente.", false, true);
            else
                jdie(0,"Error al intentar modificar los datos.");
        }
    	jdie(0,"Error al registrar los datos.");
    }
    else
    	jdie($ido,"Oferta creada.", true);
?>