<?
    //modpub.php
    include("header.php");
    if (!adminGeneral()) die(consts::$mensajes[9]);
    
    if (!isset($_GET['action'])) die(consts::$mensajes[8]);
    if ($_GET['action'] == "new")
    {
        $r = mysql_query("insert into publicidades (tipo, html) values ('imagen', '')", dbConn::$cn) or die("Error en la operación");
        die("true");
    }
    if (!isset($_GET['idp'])) die(consts::$mensajes[8]);
    $idp = mysql_real_escape_string($_GET['idp']);
    $action = mysql_real_escape_string($_GET['action']);
    if ($action == "switch")
    {
        $r = mysql_query("select tipo from publicidades where id_pub = $idp", dbConn::$cn);
        if ($r !== false and mysql_num_rows($r) == 1)
            list($tipo) = mysql_fetch_row($r);
        else
            die(consts::$mensajes[8]);
        $tipos = array("imagen", "flash", "custom", "youtube");
        $nuevotipo = $tipos[(array_search($tipo, $tipos) + 1) % count($tipos)];
        $r = mysql_query("update publicidades set tipo = '$nuevotipo' where id_pub = $idp", dbConn::$cn);
        if (!$r) die("Error en la operación.");
        else die("true");
    }
    else if ($action == "mod")
    {
        if (!isset($_GET['html'])) die(consts::$mensajes[8]);
        $nuevohtml = mysql_real_escape_string($_GET['html']);
        $r = mysql_query("update publicidades set html = '$nuevohtml' where id_pub = $idp", dbConn::$cn);
        if (!$r) die("Error en la operación.");
        else die("true");
    } 
    else if ($action == "del")
    {
        $r = mysql_query("delete from publicidades where id_pub = $idp", dbConn::$cn);
        if (!$r) die("Error en la operación.");
        else die("true");
    }
    else die("Error desconocido.");
?>
