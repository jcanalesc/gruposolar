<?php
	// guarda_videos.php
	// recibe arreglos de 2 elementos en post: texto, link, visible

	include("header.php");
	if (!adminGeneral()) exit("Acceso denegado");

	header("Content-type: application/json");

	try
	{
		$titulo1 = $_POST['texto'][0];
		$titulo2 = $_POST['texto'][1];
		$link1 = $_POST['link'][0];
		$link2 = $_POST['link'][1];
		$visible1 = $_POST['visible'][0] == "si" ? true : false;
		$visible2 = $_POST['visible'][1] == "si" ? true : false;

		consts::$videos_remate = array(
	    array("text" => $titulo1, "visible" => $visible1, "url" => $link1),
	    array("text" => $titulo2, "visible" => $visible2, "url" => $link2)
	    );

	    consts::save_config();
	    exit("true");
	}
	catch(Exception $e)
	{
		exit("false");
	}
?>