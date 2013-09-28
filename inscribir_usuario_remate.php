<?
    // inscribir_usuario_remate.php
    // recibe rut, id_remate
    // es de uso publico, cuidaito
    include("header.php");
    if (!ini())
        die("unlogged");
    if (!isset($_SESSION['rut']) || !isset($_GET['id_remate']) || !is_numeric($_GET['id_remate']) || count($_GET) != 1) die(consts::$mensajes[8]);
    $rut = mysql_real_escape_string($_SESSION['rut']);
    $idr = mysql_real_escape_string($_GET['id_remate']);
    $r = mysql_query("insert into avisar_remate (id_remate, rut) values ($idr, $rut)", dbConn::$cn);
    die($r ? "true" : "false");
?>
