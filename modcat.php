<?
    // modcat.php
    include("header.php");
    header("Content-type: application/json");
    if (!adminSala() && !adminGeneral()) die(json_encode(false));
    if (!isset($_GET['idcat']) || !is_numeric($_GET['idcat'])) die(json_encode(false));
    // si recibo idprod, agrego a la categoria
    $cat = mysql_real_escape_string($_GET['idcat']);
    if (isset($_GET['idprod']))
    {
        if (!is_numeric($_GET['idprod'])) die(json_encode(false));
        $id_producto = mysql_real_escape_string($_GET['idprod']);
        if (isset($_GET['deleteprod']))
        {
            // borra el prod de la categoria
            $r = mysql_query("delete from pertenece_categoria where id_producto = $id_producto and id_cat = $cat", dbConn::$cn);
            die(json_encode(($r != false)));
        }
        else
        {
            // agrega
            $r = mysql_query("insert ignore into pertenece_categoria (id_producto, id_cat) values ($id_producto, $cat)", dbConn::$cn);
            die(json_encode(($r != false)));
        }
    }
    else if (isset($_GET['delete']))
    {
        $r = mysql_query("delete from pertenece_categoria where id_cat = $cat", dbConn::$cn);
        if (!$r) die(json_encode(false));
        $r = mysql_query("delete from categorias where id_cat = $cat", dbConn::$cn);
        die(json_encode(($r != false)));
    }
    else die(json_encode(false));
    
?>
