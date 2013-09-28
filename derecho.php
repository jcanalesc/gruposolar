<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
<title>PortalRemate</title>
<link type="text/css" href="jqui/css/smoothness/jquery-ui-1.8.11.custom.css" rel="Stylesheet" />	
<script type="text/javascript" src="jqui/js/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="jqui/js/jquery-ui-1.8.11.custom.min.js"></script>
<style type="text/css">
#pop
{
	width: 500px;
	height: 400px;
	font-size: 14px;
	text-align: center;
}
</style>
<script>
$(document).ready(function()
{
	
});
function envia_derecho()
{
	alert($("#derechof").serialize());
}
</script>
</head>
<body>
<div id="pop" title="Toma de unidades">
	<p id="leyenda">
	<span>¡FELICITACIONES!, USTED TIENE LA MEJOR OFERTA:<br />
SELECCIONE LA CANTIDAD DE UNIDADES ADICIONALES QUE DESEA ADJUDICAR:<br /></span>
	<span>ATENCIÓN: EN ESTE MOMENTO EL GANADOR ESTA SELECCIONANDO LA CANTIDAD DEL LOTE ADJUDICADO. FAVOR ESPERE. <br />
<br />
UNA VEZ QUE EL GANADOR TOME, USTED PODRA ADJUDICAR LAS UNIDADES RESTANTES. POR EL MOMENTO SELECCIONE LAS UNIDADES QUE DESEA TOMAR.<br /></span>
	</p>
	<form id="derechof" method="post" action="#" onsubmit="envia_derecho();">
	<div class="numerico">
		<div class="filabuttons">
			<input type="radio" name="cantidad" value="1" id="c1" /><label for="c1">1</label>
			<input type="radio" name="cantidad" value="2" id="c2" /><label for="c2">2</label>
			<input type="radio" name="cantidad" value="3" id="c3" /><label for="c3">3</label>
		</div>
		<div class="filabuttons">
			<input type="radio" name="cantidad" value="4" id="c4" /><label for="c4">4</label>
			<input type="radio" name="cantidad" value="5" id="c5" /><label for="c5">5</label>
			<input type="radio" name="cantidad" value="6" id="c6" /><label for="c6">6</label>
		</div>
		<div class="filabuttons">
			<input type="radio" name="cantidad" value="7" id="c7" /><label for="c7">7</label>
			<input type="radio" name="cantidad" value="8" id="c8" /><label for="c8">8</label>
			<input type="radio" name="cantidad" value="9" id="c9" /><label for="c9">9</label>
		</div>
		<input type="submit" value="ADJUDICAR"/>
	</div>
	</form>
	<p>NOTA: LA CANTIDAD QUE USTED SELECCIONE, SERÁ ENVIADA A SU SISTEMA DE FACTURACIÓN INMEDIATAMENTE</p>
</div>
</body>
</html>
