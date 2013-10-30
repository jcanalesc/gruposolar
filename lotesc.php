<?php

/*
{
    'id_producto': obj.id_producto,
    'id_lote': obj.id_lote,
    'cantidad': obj.cantidad,
    'nombre': obj.nombre,
    'descripcion': obj.descripcion,
    'fotos': obj.fotos,
    'precio_min': parseInt($(xmldoc).find('precio_min').text()),
    'orden': parseInt($(xmldoc).find('orden').text())
};
*/

// me pasan por get id remate y orden
// devuelvo json como el de arriba
include("DB.inc.php");

$objeto = array();

if (!isset($_GET['id_remate']) || !isset($_GET['orden']))
	die(json_encode($objeto));

$idr = mysql_real_escape_string($_GET['id_remate']);
$orden = mysql_real_escape_string($_GET['orden']);
list(list($tipopuja)) = $db->query("select tipo_puja from remates where id_remate = $idr");
list(list($ordenmax)) = $db->query("select max(orden) from lotes where id_remate = $idr");

if ($orden < 0) $orden = 0;
if ($orden > $ordenmax && strlen($ordenmax) > 0 ) $orden = $ordenmax;

//error_log("orden: " . $orden);

$datos = $db->query("select * from lotes join productos using (id_producto) where lotes.id_remate = $idr and lotes.orden = $orden");

$objeto = $datos[0];
if ($tipopuja == "Sin Minimo")
	$objeto['precio_min'] = 1;
$objeto['fotos'] = array($objeto['foto1'],$objeto['foto2'],$objeto['foto3'],$objeto['foto4']);

echo json_encode($objeto);

?>
