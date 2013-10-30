<?
    // getcat.php
    // recibe idcat por get, retorna json arreglo de objetos con propiedades id_producto, descripcion
    include("header.php");
    header("Content-type: application/json");
    if (!adminSala() && !adminGeneral()) die(consts::$mensajes[9]);
    if (!isset($_GET['idcat']) || !is_numeric($_GET['idcat'])) die(consts::$mensajes[8]);
    $idcategoria = mysql_real_escape_string($_GET['idcat']);
    $r = mysql_query("select pertenece_categoria.orden, pertenece_categoria.id_producto, productos.descripcion from pertenece_categoria join productos using (id_producto) where pertenece_categoria.id_cat = $idcategoria order by pertenece_categoria.orden", dbConn::$cn);
    $datos = array();
    if ($r !== false && mysql_num_rows($r) > 0) while($row = mysql_fetch_assoc($r))
        $datos[] = array("id" => $row['id_producto'], "descripcion" => $row['descripcion']);
    die(json_encode($datos));
?>
