<?
    include("header.php");
    if (!adminGeneral() || !isset($_GET['nsala'])) die(json_encode(false));
    $idsala = mysql_real_escape_string($_GET['nsala']);
    if (!is_numeric($idsala)) die(json_encode(false));
    if (isset($_GET['own']))
    {
        rematelog("nsala: {$_GET['nsala']}, own: {$_GET['own']}");
        $newown = mysql_real_escape_string($_GET['own']);
        if (!is_numeric($newown)) die(json_encode(false));
        $r = mysql_query("update salas set rut_owner = $newown where id_sala = $idsala", dbConn::$cn);
        if ($r)
            die(json_encode(true));
        else
            die(json_encode(false));
    }
    else if (isset($_GET['name']))
    {
        $newnombre = mysql_real_escape_string($_GET['name']);
        $r = mysql_query("update salas set nombre = '$newnombre' where id_sala = $idsala", dbConn::$cn);
        if ($r)
            die(json_encode(true));
        else
            die(json_encode(false));
    }
?>
