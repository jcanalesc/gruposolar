<?php
	// recibe ini, ter y min por GET
	// devuelve "ok" o un error
	// setea las vars 

	include("header.php");
	
	$ini = $_GET['ini'];
	$ter = $_GET['ter'];
	$min = (int)$_GET['min'];

	consts::$hinicio_rut = $ini;
	consts::$htermino_rut = $ter;
	consts::$h_vacios_minimos = $min;

	consts::save_config();

	die("ok");
?>