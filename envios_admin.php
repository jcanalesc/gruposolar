<?
	// envios_admin.php
    include("DB.inc.php");
    if (isset($_GET['mode']))
    {
        if ($_GET['mode'] == "get" && isset($_GET['n_factura']))
        {
            header("Content-type: application/json");
            $nfact = mysql_real_escape_string($_GET['n_factura']);
            $r = $db->query("select * from envios where n_factura = $nfact");
            if (count($r) == 0) die("null");
            $datos = $r[0];
            echo json_encode($datos);
            exit();
        }
        else if ($_GET['mode'] == "edit")
        {
            $qs = array();
            foreach ($_GET as $key => $val)
            {
                if ($key == "id_envio" || $key == "mode") continue;
                $qs[] = "$key = ".(substr($val, 0, 2) == "n_" ? $val : "'$val'");
            }
            $r = $db->query("update envios set " . implode(",", $qs) . " where id_envio = " . $_GET['id_envio']);
            if ($r) die("true");
            else die("null");
        }
    }
	if (isset($_POST['add']) && $_POST['add'] == "true")
	{
		// agrega
        $campos = array("n_factura", "n_despacho", "empresa", "ciudad", "fecha", "orden_envio", "tracker");
        $valores = array();
        $nice = array("n_factura" => "Núm de factura", 
                      "n_despacho" => "Núm de guía de despacho",
                      "empresa" => "Empresa de envío",
                      "ciudad" => "Ciudad de destino", 
                      "fecha" => "Fecha de envío",
                      "orden_envio" => "Orden de envío",
                      "tracker" => "Link al rastreador"
                      );
        foreach($campos as $c)
        {
            $vv = $_POST[$c];
            $wrapchar = substr($c, 0, 2) == "n_" ? "" : "'";
            if (
                (substr($c,0,2) == "n_" && strspn($vv, "0123456789") != strlen($vv))
                ||
                ($c == "fecha" && preg_match('/[0-9]{4}\-[0-9]{2}\-[0-9]{2}/', $vv) == 0)
               )
                die("<p>Campo inválido: (".$nice[$c].")</p>");
            
            $valores[$c] = $wrapchar.mysql_real_escape_string($vv).$wrapchar;
        }
        
        $keys = implode(",",array_keys($valores));
        $vals = implode(",",$valores);
        
        $query = "insert into envios ($keys) values ($vals)";
        
        try
        {
            $db->query($query);
            die("<p>Registro realizado correctamente.</p>");
        }
        catch(Exception $e)
        {
            if (substr($e->getMessage(), 0, 4) == "1062")
            die ("<p>Los valores de factura y guía de despacho ya existen.</p>");
            else
            die ("<p>Problemas al intentar realizar el registro.</p>");
        }
        
		exit();
	}
	
	if (!isset($_POST['user']) || !isset($_POST['pass']))
		die("<p>Acceso denegado.</p>");
	if (strtoupper($_POST['user']) != "BODEGA"
		|| strtoupper($_POST['pass']) != "QUILICURA")
		die("<p>Acceso denegado.</p>");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
<meta name="robots" content="NOODP">
<meta name="googlebot" content="NOODP">
<meta name="Description" content="REMATES ONLINE ENERGIA SOLAR, ENERGIA EOLICA, GENERADORES, GASTRONOMIA, MOTOSIERRAS, AUDIO, MATERIALES DE CONSTRUCCION, HERRAMIENTAS BENCINERAS, PANELES FOTOVOLTAICOS">
<meta name="Keywords" content="REMATES ONLINE,ENERGIA SOLAR,ENERGIA EOLICA,GENERADORES,GASTRONOMIA,MOTOSIERRAS,AUDIO,MATERIALES DE CONSTRUCCION,HERRAMIENTAS BENCINERAS,PANELES FOTOVOLTAICOS">
<script language="JavaScript" src="jquery.js"></script>
<script type="text/javascript" src="fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<script language="javascript" src="jsDatePick.jquery.min.1.3.js"></script>
<script language="javascript" src="jquery.timePicker.min.js"></script>
<script type="text/javascript" src="jquery.scrollTo-1.4.2-min.js"></script>
<script type="text/javascript" src="tiny_mce/tiny_mce.js"></script>
<link href="envios.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" href="jsDatePick_ltr.min.css" />
<link rel="stylesheet" href="timePicker.css" />
<link rel="stylesheet" type="text/css" href="fancybox/jquery.fancybox-1.3.4.css" />
<script type="text/javascript">
<!--
var actual = -1;
function busca()
{
    var fact = $("#mod_id").val();
    if (fact.length > 0)
    {
        $.getJSON("envios_admin.php", { "mode": "get", "n_factura" : fact } , function(obj)
        {
            if (obj == null)
            {
                alert("Despacho no encontrado. Verifique el número de factura.");
            }
            else
            {
                actual = obj.id_envio;
                for (x in obj)
                {
                    $("#m_"+x).val(obj[x]);
                }
                $("[id^='m_']").attr("disabled", false);
            }
        });
        return false;
    }
    return false;
}
	$(function()
	{
		new JsDatePick({
			useMode:2,
			target:"fechaenvio",
			dateFormat:"%Y-%m-%d",
			yearsRange:[2011,2020]
		});
        
        new JsDatePick({
            useMode:2,
			target:"m_fecha",
			dateFormat:"%Y-%m-%d",
			yearsRange:[2011,2020]
        });
        
        $("[id^='m_']").attr("disabled", "disabled");
        
        $("#buscamod").submit(busca);
        $("#m_save").click(function()
        {
            if (confirm("Está seguro de modificar el despacho?"))
            {
                var datos = {};
                $("[id^='m_']").filter(":not(#m_save)").each(function()
                {
                    datos[$(this).attr("id").substr(2)] = $(this).val();
                });
                datos.mode = "edit";
                datos.id_envio = actual;
                $.getJSON("envios_admin.php", datos, function(obj)
                {
                    if (obj == null)
                        alert("Error en la edición. Revise los campos.");
                    else
                        alert("Despacho modificado correctamente.");
                });
            }
        });
	});
//-->
</script>
</head>
<body>
	<center>
		<p>Use este formulario para cargar envíos al sistema.</p>
		<span class="newenvio">
			<form action="envios_admin.php" method="post" target="ifr">
				<table>
					<tr><td>N&deg; de factura: </td><td><input type="text" name="n_factura" /></td></tr>
					<tr><td>N&deg; de gu&iacute;a de despacho: </td><td><input type="text" name="n_despacho" /></td></tr>
					<tr><td>Empresa: </td><td><input type="text" name="empresa" /></td></tr>
					<tr><td>Ciudad destino: </td><td><input type="text" name="ciudad" /></td></tr>
                    <tr><td>Orden de envío: </td><td><input type="text" name="orden_envio" /></td></tr>
					<tr><td>Fecha: </td><td><input type="text" name="fecha" id="fechaenvio" /></td></tr>
                    <tr><td>Link al rastreador: </td><td><input type="text" name="tracker" /></td></tr>
				</table>
				<input type="hidden" name="add" value="true" />
				<p><input type="submit" value="Ingresar datos" /></p>
			</form>
		</span>
        <hr />
        <span class="modenvio">
            <form id="buscamod" onsubmit="busca(); return false;">
                <table>
                    <tr><td>Ingrese número de factura para editar despacho:</td><td><input type="text" id="mod_id" /></td></tr>
                    <tr><td colspan="2"><input type="submit" value="Buscar y editar" /></td></tr>
                </table>
            </form>
            <table>
                <tr><td>N&deg; de factura: </td><td><input type="text" id="m_n_factura" /></td></tr>
                <tr><td>N&deg; de gu&iacute;a de despacho: </td><td><input type="text" id="m_n_despacho" /></td></tr>
                <tr><td>Empresa: </td><td><input type="text" id="m_empresa" /></td></tr>
                <tr><td>Ciudad destino: </td><td><input type="text" id="m_ciudad" /></td></tr>
                <tr><td>Orden de envío: </td><td><input type="text" id="m_orden_envio" /></td></tr>
                <tr><td>Fecha: </td><td><input type="text" name="fecha" id="m_fecha" /></td></tr>
                <tr><td>Link al rastreador: </td><td><input type="text" name="tracker" id="m_tracker" /></td></tr>
                <tr><td colspan="2"><button id="m_save">Guardar cambios</button></td></tr>
            </table>
        </span>
        <hr />
    <div id="foot">
	<iframe name="ifr" src=""></iframe>
    <button onclick="location.href='envios.php'">Volver</button>
    </div>
	</center>
</body>
</html>
