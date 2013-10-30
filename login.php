<?php
	include("header.php");

	$user = $_POST['user'];
	$pass = $_POST['passwd'];

	$res = login($user, $pass);
	header("Location: frontis.php");

?>