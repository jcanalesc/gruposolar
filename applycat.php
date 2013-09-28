<?
    // applycat.php
    // recibe idremate, cats
    include("header.php");
    if (!adminGeneral() && !adminSala()) die(consts::$mensajes[9]);
    if (!isset($_GET['idremate']) || !is_numeric($_GET['idremate']) || !isset($_GET['cats']) || !is_array($_GET['cats'])) die(consts::$mensajes[8]);
    // aplico las categorías del arreglo "cats" al remate "idremate", la categoria 0 corresponden a los lotes ya existentes.
    $superarreglo = array();
    $cats = $_GET['cats'];
    $idr = mysql_real_escape_string($_GET['idremate']);
    for ($i = 0; $i < count($cats); $i++)
    {
        $val = (int) $cats[$i];
        if ($val == 0)
        {
            $r = mysql_query("select id_producto, cantidad from lotes where id_remate = $idr", dbConn::$cn);
            if (!$r) die("false");
            while($row = mysql_fetch_row($r))
                $superarreglo[] = implode(",", array(count($superarreglo), $row[1], $row[0]));
        }
        else
        {
            $r = mysql_query("select id_producto from pertenece_categoria where id_cat = $val order by orden", dbConn::$cn);
            if (!$r) die("false");
            while($row = mysql_fetch_row($r))
                $superarreglo[] = implode(",",array(count($superarreglo), 1, $row[0]));
        }
    }
    $sent_string = implode("|", $superarreglo);
    guarda_lotes($sent_string);
    die("true");
?>
