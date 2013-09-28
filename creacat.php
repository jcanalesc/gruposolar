<?
    include("header.php");
    header("Content-type: application/json");
    if (!adminGeneral() && !adminSala()) die(consts::$mensajes[9]);
    if (!isset($_GET['name'])) die(consts::$mensajes[8]);
    $nombre = mysql_real_escape_string($_GET['name']);
    $owner = $_SESSION['rut'];
    $r = mysql_query("insert into categorias (nombre, rut_owner) values ('$nombre', $owner)", dbConn::$cn);
    die(json_encode(($r != false)));
?>
