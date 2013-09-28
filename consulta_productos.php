<?
    include("header.php");
    header("Content-type: application/json");
    if (!adminSala() && !adminGeneral()) die(json_encode(false));
    if (!isset($_GET['text']) || strlen($_GET['text']) < 3) die(json_encode(false));
    $texto = mysql_real_escape_string($_GET['text']);
    $condicion = "rut_owner = {$_SESSION['rut']} and ";
    if (adminGeneral()) $condicion = "";
    $r = mysql_query("select id_producto, descripcion, precio_min from productos where $condicion descripcion like '%$texto%'", dbConn::$cn);
    $res = array();
    if ($r !== false && mysql_num_rows($r) > 0) while($row = mysql_fetch_assoc($r))
    {
        $res[] = array("id" => $row['id_producto'], "descr" => $row['descripcion'], "precio" => $row['precio_min']);
    }
    else
        die(json_encode(false));
    die(json_encode($res));
?>
