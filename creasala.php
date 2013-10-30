<?
    include("header.php");
    if (!adminGeneral()) die(consts::$mensajes[9]);
    if (!isset($_GET['name'])) die(consts::$mensajes[8]);
    $name = mysql_real_escape_string($_GET['name']);
    $own = $_SESSION['rut'];
    
    $r = mysql_query("insert into salas (nombre, rut_owner) values ('$name', $own)", dbConn::$cn);
    if (!$r) die(consts::dbError("insercion sala"));
?>
