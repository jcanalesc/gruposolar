<?php
	include("header.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
		<meta name="robots" content="NOODP">
		<meta name="googlebot" content="NOODP">
		<meta name="Description" content="<?= consts::$descripcion_pag ?>">
		<meta name="Keywords" content="<?= consts::$palabras_clave ?>">

		<link rel="stylesheet" type="text/css" href="frontis.css" />
		<link rel="stylesheet" type="text/css" href="fancybox/jquery.fancybox-1.3.4.css" />

		<script type="text/javascript" src="jquery.js"></script>
		<script type="text/javascript" src="jquery.scrollTo-1.4.2-min.js"></script>
		<script type="text/javascript" src="fancybox/jquery.fancybox-1.3.4.pack.js"></script>
		<script type="text/javascript" src="reloj.js"></script>
		<script type="text/javascript"> var conectado = <?= ini() ? "true" : "false" ?>;</script>
		<script type="text/javascript"> var autorizado_rsm = <?= $_SESSION['autorizado_rsm'] == "1" ? "true" : "false" ?>;</script>
		<script type="text/javascript" src="swfobject.js"></script>

		<script type="text/javascript" src="frontis.js"></script>

	</head>
	<body>
		<div id="content">
			<div id="header">
				<div class="floating" id="logobox">
					
				</div>
				<div class="floating" id="loginbox">
					asd</div>
				<div class="floating" id="userbox"></div>
				<div class="floating" id="instaladores"></div>
				<div class="clearfixer"></div>
			</div>
			<div id="center">
				<div class="floating" id="leftcol">
					<div class="subtitle">Categorías</div>
					<ul>
						<li>Térmica
							<ul>
								<li>Termos Solares Atmosféricos</li>
								<li>Termos Solares Presurizados</li>
							</ul>
						</li>
					</ul>
				</div>
				<div class="floating" id="ofertas_destacadas">
					<div id="destacadas_carousel">
						<a href="#"><img src="img1.png"></a>
						<a href="#"><img src="img2.png"></a>
						<a href="#"><img src="img3.png"></a>
						<a href="#"><img src="img4.png"></a>
					</div>
				</div>
				<div class="floating" id="oferta_del_dia"></div>
				<div class="floating" id="elearning"></div>
				<div class="floating" id="remates_express"></div>
				<div class="floating" id="chat"></div>
				<div class="clearfixer"></div>
			</div>
			<div id="footer"></div>
		</div>
	</body>
</html>