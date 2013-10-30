<?php
	include("header.php");
	function add_p($str)
	{
		return "<p>" . $str . "</p>";
	}

	function parr($arr)
	{
		return implode("", array_map("add_p", $arr));
	}

	if (!isset($_GET['id']) || !is_numeric($_GET['id'])) die("Llamada inválida");

	$id = mysql_real_escape_string($_GET['id']);

	$r = mysql_query("select * from ofertas where id_oferta = $id");

	if (mysql_num_rows($r) == 0) die("Oferta no encontrada.");

	$oferta = mysql_fetch_assoc($r);
	$telefonos = str_replace(",", " - ", $oferta['fono']);

	mysql_free_result($r);

	$stock = 0;
	if ($oferta['stock'] > 0)
	{
		list($cantidad_comprada) = mysql_fetch_row(mysql_query("select sum(cantidad) from ofertas_compradas where id_oferta = $id"));
		$stock = $oferta['stock'] - $cantidad_comprada;
		if ($stock <= 0)
			$stock = 0;
	}
	else
		$stock = PHP_INT_MAX;

	$fecha_inicio_ut = strtotime($oferta['fecha_inicio']);
	$fecha_termino_ut = strtotime($oferta['fecha_termino']);

	//$restante_format = date("H:i:s", ($fecha_termino_ut - $fecha_inicio_ut));

	//$restante_format = strftime("%H:%M:%S", ($fecha_termino_ut - $fecha_inicio_ut));
	$secs = (int)($fecha_termino_ut - time());
	if ($secs < 0) $secs = 0;
	$mins = (int)($secs / 60);
	$hours = (int)($mins / 60);

	//echo $hours,":",$mins,":",$secs;
	$mins -= $hours*60;
	$secs -= ($mins*60 + $hours*3600);
	

	$restante_format = sprintf("%02d:%02d:%02d", $hours, $mins, $secs);

	$parrafos_titulo = parr(explode("\n", $oferta['descripcion']));

	$logeado = ini();

	$flecha = "pr2-img/listitem.png";

	$rut = $_SESSION['rut'];
	$username = $_SESSION['nombres'];

	$compro = false;
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="oferta.css" />
	<link rel="stylesheet" type="text/css" href="fancybox/jquery.fancybox-1.3.4.css" />
	<script type="text/javascript" src="jquery.js"></script>
	<script type="text/javascript" src="fancybox/jquery.fancybox-1.3.4.pack.js"></script>
	<script type="text/javascript">
	function currency(number)
	{
	   var i = 0;
	   var curr = "";
	   var arr = new Array();
	   for (var i = 0; i < number.length; i++)
	   {
	      if (i % 3 == 0 && i != 0)
	         arr.push(".");
	      arr.push(number.charAt(number.length - 1 - i));
	   }
	   return arr.reverse().join("");
	}

	var precio = <?= $oferta['precio'] ?>;
	var ts_inicio = <?= $fecha_inicio_ut ?>;
	var ts_termino = <?= $fecha_termino_ut ?>;
	var handler = null;
	function get_remaining_time()
	{
		var unix = parseInt(Math.round(Date.now()/1000));
		var secs = ts_termino - unix;

		if (secs <= 0)
		{
			// deshabilitar el boton de compra
			$("#boton_compra").attr("disabled", true);
			$("#aviso_finalizado").show();
			secs = 0;
		}
		var mins = parseInt(secs/60);
		var hours = parseInt(mins/60);

		mins = mins - parseInt(hours*60);
		secs = secs - parseInt(hours*3600) - parseInt(mins*60);
		
		
		secs = secs > 9 ? secs+"" : "0"+secs;
		mins = mins > 9 ? mins+"" : "0"+mins;
		hours = hours > 9 ? hours+"" : "0"+hours;
		//return hours+":"+mins+":"+secs;
		$("#tiempo_restante").html(hours+":"+mins+":"+secs);

	}

	$(document).ready(function()
	{

		$("#f1").submit(function()
		{
			var rut = $("input[name='rut']", this).val();
			var pass = $("input[name='pass']", this).val();
			if (rut.length < 1)
			{
				alert("Escriba su rut.");
				return false;
			}
			else if (pass.length < 1)
			{
				alert("Escriba su contraseña.");
				return false;
			}
			$.post("header.php", {func: "login", args: rut+";"+pass }, function(data)
			{
				if (data == "<?= consts::$mensajes[3] ?>")
					location.reload();
				else
					alert(data);
			});
			return false;
		});
		$("#f2").submit(function()
		{
			$.post("oferta_comprar.php", {
				id_producto: <?= $oferta['id_producto'] ?>,
				id_oferta: <?= $oferta['id_oferta'] ?>,
				cantidad: $("select[name='cantidad']").val()
			}, function(data)
			{
				if (data == "ya_compro")
				{
					alert("Usted ya compró esta Oferta Express.");
				}
				else if (data == "error")
				{
					alert("Ha ocurrido un error al intentar realizar su compra.");
				}
				else if (data == "fuera_de_plazo")
				{
					alert("Esta oferta no está disponible. Puede que aún no empiece, o puede haber ya terminado.");
				}
				else
				{
					$("#formu_compra").hide();
					$("#formu_confirma").show();
				}
			});
			return false;
		})
		$(".logout_link").click(function()
		{
			$.post("header.php", {func: "logout"}, function(data)
			{
				location.reload();
			});
		})
		$("select[name='cantidad']").change(function()
		{
			var num = $(this).val();
			var total = num * precio;
			$("#totalapagar").html("$" + currency(total+""));
		});
		$("#link_reg").click(function()
		{
			$.get("registro.php", function(data)
			{
				$.fancybox(data);
			});
			return false;
		});
		$("#link_forgot").click(function()
		{
			$.get("recupera_clave.php", function(data)
			{
				$.fancybox(data);
			});
			return false;
		});
<? if (adminGeneral()): ?>
		var estado_telefonos = "html";
		var val_telefonos = "<?= $oferta['fono'] ?>";
		$("#telefonos").css({"cursor":"pointer"}).attr("title", "Doble clic para editar").dblclick(function()
		{
			if (estado_telefonos == "html")
			{
				estado_telefonos = "edit";
				$("#telefonos").html('<input type="text" id="input_telefonos" /> (Enter para confirmar)');
				$("#input_telefonos").val(val_telefonos);
				$("#input_telefonos").live("keyup", function(event)
				{
					if (event.keyCode == 13)
					{
						$.get("oferta_mod.php", {"col": "fono", "val": $(this).val(), "ido" : <?= $id ?>}, function(data)
						{	
							if (data == "ok")
							{
								var tels = val_telefonos.split(",").join(" - ");
								$("#telefonos").html("<p>"+tels+"</p>");
								estado_telefonos = "html";
							}
						});
					}
					else
					{
						val_telefonos = $(this).val();
					}
					return false;
				});
			}
		});
<? endif; ?>
		get_remaining_time();
		handler = setInterval("get_remaining_time()", 1000);

	});
	</script>
</head>
<body>
	<div id="container">
		<div id="logo">
			<a href="http://www.portalremate.cl"><img src="<?= consts::$logo ?>" /></a>
			<span id="slogan"><?= consts::$slogan ?></span>
		</div>
		<div id="centro">
			<div id="centro_t1">Ofertas Express PortalRemate.cl</div>
			<div id="centro_t2"><em>No dejes pasar esta Oportunidad. El que sabe... sabe...</em></div>
			<div id="caja">
				<p>Ofertas Express PortalRemate.cl</p>
				<?= $parrafos_titulo ?>
			</div>
		</div>
		<div id="derecho">
			<p>Fono Reserva: </p>
			<p id="telefonos"><?= $telefonos ?></p>
			<div id="reloj">
				Finaliza en: <img src="reloj.png" align="middle" /> <span id="tiempo_restante"><?= $restante_format ?></span>
			</div>
		</div>
		<div style="clear: both;"></div>
		<div id="bases">
			Ver Bases y Condiciones: <a href="<?= $oferta['procedimiento'] ?>" target="_blank"><img src="pr2-img/pdf.png" align="middle"/></a>
		</div>
		<div id="foto_oferta">
			<img src="<?= $oferta['banner'] ?>" />
		</div>
		<? if (!$logeado): ?>
			<div id="formu_login" class="formu">
				<form method="post" id="f1">
					<p>DESEA COMPRAR EN LINEA?<br />
						"OFERTAS AL COSTO".</p>
					<p>Inicie su Sesión con su Usuario de PortalRemate.cl</p>
					<table>
						<tr>
							<td style="text-align: right;">
								Rut usuario:
							</td>
							<td>
								<input type="text" name="rut" class="short"/>
							</td>
						</tr>
						<tr>
							<td style="text-align: right;">
								Clave:
							</td>
							<td>
								<input type="password" name="pass" class="short"/>
							</td>
						</tr>
						<tr>
							<td style="text-align: right;">
								&nbsp;
							</td>
							<td>
								<input type="submit" value="Iniciar sesión" />
							</td>
						</tr>
					</table>
					<a href="#" id="link_reg" ><span class="opt">
						<img src="<?= $flecha ?>" align="middle" />
						Regístrese Gratis
					</span></a>

					<a href="#" id="link_forgot"><span class="opt">
						<img src="<?= $flecha ?>" align="middle" />
						Olvidé mi Contraseña
					</span></a>
				</form>
			</div>
		<? else: ?>
			<div id="formu_compra" class="formu">
				<div id="logon_box">
					<strong>Bienvenido:</strong> <?= $username ?><br />
					<strong>Rut:</strong> <?= $rut ?><br />
					<strong>Estado:</strong> Conectado <span class="bull">&bull;</span><br />
						<center><a href="#" class="logout_link">Cerrar Sesión</a></center>
				</div>
				<form method="post" id="f2">
					<input type="hidden" name="id_producto" value="<?= $oferta['id_producto'] ?>" />
					<input type="hidden" name="id_oferta" value="<?= $oferta['id_oferta'] ?>" />
					<table id="tbl">
						<tr>
							<td>Producto:</td>
							<td style="text-align: left;"><strong><?= strtoupper($oferta['nombre']) ?></strong></td>
							
						</tr>
						<tr>
							<td>Precio Unitario:</td>
							<td style="text-align: left;"><?= currf($oferta['precio']) ?> pesos (IVA incluido)</td>
						</tr>
						<tr>
							<td>Cantidad:</td>
							<td style="text-align: left;">
							<? if ($stock != 0): ?>
								<select name="cantidad">
									<? 
									$disp = min($stock,$oferta['cant_maxima']);
									for ($i = 1; $i <= $disp; $i++): ?>
										<option value="<?= $i ?>"><?= $i ?></option>
									<? endfor; ?>
								</select>
							<? else: ?>
								<p>No quedan unidades de este producto.</p>
							<? endif; ?>
							</td>
						</tr>
						<?
						$opts = array();
						for ($i = 1; $i <= 3; $i++)
						{
							if (strlen($oferta['parametro_op'.$i]) > 0)
								$opts[$i] = $oferta['parametro_op'.$i];
						}

						if (count($opts) > 0):
						?>
						<tr>
							<td><?= $oferta['parametro_nom'] ?></td>
							<td>
								<select name="parametro">
									<?
									foreach ($opts as $k => $v)
										echo "<option value=\"$k\">$v</option>\n";
									?>
								</select>
							</td>
						</tr>
						<? endif; ?>
						<tr>
							<td>Total a Pagar:</td>
							<td style="text-align: left;"><span id="totalapagar"><?= currf($oferta['precio']) ?></span> pesos (IVA incluido)</td>
						</tr>
						<tr>
							<td colspan="2">
								<center><input type="submit" class="comprar" value="COMPRAR" id="boton_compra" <? if ($stock == 0) echo "disabled"; ?>/></center>
							</td>
						</tr>
					</table>
				</form>
			</div>
			<div id="formu_confirma" class="formu">
				<p>Estimado <?= $username ?>:<p>
				<p>Su pedido ha sido Procesado Exitosamente.</p>
				<p>Se ha enviado a su Correo Electrónico La Nota de Venta 
				   y en breves minutos un Ejecutivo lo Llamará para coordinar 
				   el pago y retiro del producto.</p>
				<p>Atte.</p>
				<p>Equipo PortalRemate.cl</p>

				<center><a href="#" class="logout_link">Cerrar Sesión</a></center>
			</div>
			<div id="aviso_finalizado">
				<p>AVISO:</p>
				<p class="cn">"ESTA OFERTA HA SIDO FINALIZADA".</p>
				<p class="cn">"DEBE ESTAR ATENTO A LAS PRÓXIMAS OFERTAS"</p>
			</div>
		<? endif; ?>
		
		<div style="clear: both; text-align: center; padding: 15px;">
			PortalRemate.cl es un sitio de Ofertas de Remates en Linea. Todos los Derechos Reservados.
		</div>
	</div>
</body>
</html>