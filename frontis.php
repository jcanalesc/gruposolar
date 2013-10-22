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

		<link href='http://fonts.googleapis.com/css?family=News+Cycle:400,700' rel='stylesheet' type='text/css'>

		<link rel="stylesheet" type="text/css" href="fancybox/jquery.fancybox-1.3.4.css" />
		<link rel="stylesheet" type="text/css" href="nivo-slider/nivo-slider.css" />
		<link rel="stylesheet" type="text/css" href="frontis.css" />
	</head>
	<body>
		<script type="text/javascript" src="sessiondata.js.php"></script>
		<script type="text/javascript"> var conectado = <?= ini() ? "true" : "false" ?>;</script>
		<script type="text/javascript"> var autorizado_rsm = <?= $_SESSION['autorizado_rsm'] == "1" ? "true" : "false" ?>;</script>

		<div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>

		<div id="content">
			<div id="header">
				<div class="floating" id="logobox">
					<div class="fixed-bottom">
						CALL CENTER: 2 - 4450404
					</div>
				</div>
				<?php
					$loginbox = ini() ? "style=\"display:none;\"" : "";
					$userbox = !ini() ? "style=\"display:none;\"" : "";
				?>
				<div class="floating" id="loginbox" <?= $loginbox ?>>
					<span>INGRESE AL SITIO N°1 EN LÍNEA DE ENERGÍAS RENOVABLES EN CHILE</span>
					<form action="login.php" method="post">
						<table>
							<tr>
								<td>Dirección Email:</td>
								<td><input type="text" name="user" /></td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>Contraseña:</td>
								<td><input type="password" name="passwd" /></td>
								<td><input type="submit" value="Iniciar Sesión" /></td>
							</tr>
							<tr>
								<td><a class="blue_link" href="#">Regístrese Gratis</a></td>
								<td><a class="blue_link" href="#">Recuperar Contraseña</a></td>
							</tr>
						</table>
					</form>
				</div>
				<div class="floating" id="userbox" <?= $userbox ?>>
					<div>
						<a href="principal.php?autoload=ficha_usuario.php"><i class="icon-carro"></i>Mis Compras</a>
						|
						<a href="#"><i class="icon-dinero"></i>Canjear mis Puntos</a>
						|
						<a href="#"><i class="icon-lapiz"></i>Editar mis Datos</a>
					</div>
				</div>
				<div class="floating" id="instaladores"></div>
				<div class="clearfixer"></div>
			</div>
			<div id="center">
				<div class="floating padded" id="leftcol">
					<div class="subtitle">Categorías</div>
					<ul>
						<li><span class="topic red">Térmica</span>
							<ul>
								<li>Termos Solares Atmosféricos</li>
								<li>Termos Solares Presurizados</li>
								<li>Colectores Solares</li>
								<li>Energía Solar para Piscinas</li>
								<li>Repuestos y Accesorios</li>
							</ul>
						</li>
						<li><span class="topic blue">Fotovoltaica</span>
							<ul>
								<li>Paneles Fotovoltaicos</li>
								<li>Inversores</li>
								<li>Reguladores de Carga</li>
								<li>Baterías Ciclo Profundo</li>
								<li>Cables y Conectores</li>
							</ul>
						</li>
						<li><span class="topic green">Eólica</span>
							<ul>
								<li>Generadores Eólicos</li>
								<li>Reguladores de Carga</li>
							</ul>
						</li>
						<li><span class="topic orange">LED</span>
							<ul>
								<li>Ampolletas Led</li>
								<li>Focos Led</li>
								<li>Luminarias Solares</li>
								<li>Accesorios</li>
							</ul>
						</li>
						<li><span class="topic skyblue">Otros</span>
							<ul>
								<li>Bombas para Riego DC y AC</li>
								<li>Motores DC</li>
								<li>Centrales Hidroeléctricas</li>
								<li>Generadores a Combustión</li>
							</ul>
						</li>
						<li><span class="topic purple">Novedades</span>
							<ul>
								<li>Portones Automáticos Solar</li>
								<li>Sirenas Solares</li>
								<li>Refrigeradores Solares</li>
								<li>Ionizadores de Piscina Solar</li>
							</ul>
						</li>
					</ul>
					<div class="facebook-box">
						<div class="fb-like-box" data-href="https://www.facebook.com/energia.solar.9" data-width="190" data-colorscheme="light" data-show-faces="true" data-header="true" data-stream="false" data-show-border="true"></div>
					</div>
					<div class="socialmedia">
						<h2>Síguenos</h2>
						<a href="#"><div class="sm-button fbk"></div></a>
						<a href="#"><div class="sm-button ytb"></div></a>
						<a href="#"><div class="sm-button twi"></div></a>
					</div>
				</div>
				<div class="floating padded" id="ofertas_destacadas">
					<div class="subtitle2"><b>OFERTAS</b> DESTACADAS </div>
					<div id="destacadas_carousel">
						<a href="#"><img src="assets/carousel1.png" /></a>
						<a href="#"><img src="assets/carousel2.png" /></a>
						<a href="#"><img src="assets/carousel3.png" /></a>
						<a href="#"><img src="assets/carousel4.png" /></a>
					</div>
					
				</div>
				<div class="floating padded" id="oferta_del_dia">
					<div class="subtitle2"><b>OFERTA</b> DEL DÍA</div>
					<img src="assets/ofertadeldia.png" />
					<p class="precio-o">Oferta: $<span>138.000</span> c/u</p>
					<p class="precio-n">Normal: $<span>290.000</span> c/u</p>
					<center><button class="btn-buy">Comprar</button></center>
					<div class="creditcards"></div>
				</div>
				<div class="floating padded" id="elearning">
					<div class="subtitle2"><b>CLASES E-</b>LEARNING</div>
					<img data-src="holder.js/250x344" />
					<center><button class="btn-buy">Participar</button></center>
					<div class="creditcards"></div>
				</div>
				<div class="floating padded" id="remates_express">
					<div class="subtitle2"><b>REMATES</b> EXPRESS</div>
					<div class="mauction-template">
						<div class="mauction" data-idma="%idmr%">
							<p class="textochico">GANADOR ACTUAL: <span class="ganador_actual"></span></p>
							<img src="%imagen%" width="100" height="100">
							<p class="nombrep">%nombre_producto%</p>
							<p class="restante">RESTAN: <span class="tiempor"></span></p>
							<p class="ficha">VER FICHA</p>
							<p class="oferta">Oferta actual: $<span class="oferta_actual"></span> + IVA</p>
							<button>MEJORAR OFERTA A $<span class="oferta_mejorar"></span> + IVA</button>
						</div>
					</div>
					<div id="miniremates-wrapper" class="holderjs">
						<?php for ($i = 0; $i < 10; $i++): ?>
						<div class="mauction" data-idma="%idmr%">
							<p class="textochico">GANADOR ACTUAL: <span class="ganador_actual"></span></p>
							<img src="%imagen%" width="100" height="100">
							<p class="nombrep">%nombre_producto%</p>
							<p class="restante">RESTAN: <span class="tiempor"></span></p>
							<p class="ficha">VER FICHA</p>
							<p class="oferta">Oferta actual: $<span class="oferta_actual"></span> + IVA</p>
							<button>MEJORAR OFERTA A $<span class="oferta_mejorar"></span> + IVA</button>
						</div>
						<?php endfor; ?>
					</div>
				</div>
				<div class="floating padded" id="chat">
					<div class="subtitle2"><b>CHAT EN</b> VIVO</div>
					<div class="chatbox-title">
						CHATEA CON NUESTROS INGENIEROS
					</div>
					<div class="chatbox">

					</div>
					<div class="chatbox-controls">
						<input type="text" placeholder="texto aqui"/>
						<button data-action="send">ENVIAR</button>
						<button data-action="close">CERRAR</button>
					</div>
					<div class="chatbox-template">
						<span class="chatmsg"><b>%from%:</b>%msg%</span>
					</div>
				</div>
				<div class="clearfixer"></div>
			</div>
			<div id="footer"></div>
		</div>


		<script type="text/javascript" src="jquery-1.9.0.min.js"></script>
		<script type="text/javascript" src="jquery.scrollTo-1.4.2-min.js"></script>
		<script type="text/javascript" src="fancybox/jquery.fancybox-1.3.4.pack.js"></script>
		<script type="text/javascript" src="nivo-slider/jquery.nivo.slider.pack.js"></script>


		<script type="text/javascript" src="reloj.js"></script>
		<script type="text/javascript" src="swfobject.js"></script>

		<script type="text/javascript" src="frontis.js"></script>
		<script type="text/javascript" src="holder.js"></script>
		<script type="text/javascript">
		$(function()
		{
			$("#destacadas_carousel").nivoSlider();
			Chat.create("#chat", ".chatbox-template", 2);
			if (conectado)
			{
				$("#loginbox").hide();
				$("#userbox").show();				
			}
			else
			{
				$("#loginbox").show();
				$("#userbox").hide();
			}
		});
		</script>

	</body>
</html>