<?
    // guardaorden.php
    // recibo el idcat y el orden
    include("header.php");
    include("DB.inc.php");
    if (!esAdmin()) die(consts::$mensajes[9]);
    if (!isset($_GET['orden']) || !isset($_GET['idcat'])) die(consts::$mensajes[8]);
    
    // Efectivamente, 'orden' es un arreglo de enteros.
    $idcat = mysql_real_escape_string($_GET['idcat']);
    $query = "update pertenece_categoria set orden = %d where id_cat = $idcat and id_producto = %d";
    foreach($_GET['orden'] as $indice => $numero)
    {
        $res = $db->query(sprintf($query, $indice, $numero));
        if (!$res)
            die("false");
    }
    die("true");
    
    
?>
