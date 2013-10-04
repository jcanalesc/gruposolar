<?php
	include("header.php");

	$user = $_POST['user'];
	$pass = $_POST['passwd'];

	$res = login($user, $pass);
	if ($res == consts::$mensajes[3])
	{
		header("Location: frontis.php");
		exit();
	}
?>