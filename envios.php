<?
    include("DB.inc.php");
    function rematelog($msg)
	{
	   error_log("RemateLog: $msg");
	}
    if (count($_POST) > 0)
    {
        if (isset($_POST['id_guia']) || isset($_POST['tipo']))
        {
            $nd = mysql_real_escape_string($_POST['id_guia']);
            try
            {
                if (strspn($nd, "0123456789") != strlen($nd))
                    die("<p>Formato del número ingresado inválido.</p>");
                echo "<style type=\"text/css\">@import url('envios.css');</style>\n";
                
                if ($_POST['tipo'] == "1")
                {
                    $tipo = "factura";
                    $arg = "n_factura = $nd";
                }
                else
                {
                    $tipo = "guía de despacho";
                    $arg = "n_despacho = $nd";
                }
                
                $r = $db->query("select * from envios where $arg");
                if (count($r) == 0)
                    die("<p>Número de $tipo inválido o no existente.</p>");
                    

                $trackers = array(
                    "turbus" => "http://www.turbus.cl/wtbus/indexCargoSeguimiento.jsf",
                    "pullman" => "http://www.pullmancargo.cl/WEB/seguimiento.html",
                    "arriero" => "http://www.arriero.cl/",
                    "binder" => "http://www.transbinder.cl/"
                    );
                foreach($trackers as &$trk)
                {
                    $trk = "<a target=\"_blank\" href=\"$trk\">$trk</a>";
                }
                echo "<table class=\"desp\">";
                echo "<tr><td>Orden de envío</td><td>N&deg; Factura</td><td>N&deg; Guía de Despacho</td><td>Empresa</td><td>Ciudad de Destino</td><td>Fecha de env&iacute;o</td><td>Link al rastreador</td></tr>";
                foreach($r as $despacho)
                {
                    echo "<tr>";
                        foreach($despacho as $key => $val)
                        {
                            if ($key == "tracker")
                                switch(strtolower($despacho['empresa']))
                                {
                                    case "turbus":
                                    case "tur-bus":
                                    case "tur bus":
                                        echo "<td>".$trackers['turbus']."</td>";
                                        break;
                                    case "pullman":
                                    case "pulman":
                                        echo "<td>".$trackers['pullman']."</td>";
                                        break;
                                    case "arriero":
                                    case "elarriero":
                                    case "el arriero":
                                        echo "<td>".$trackers['arriero']."</td>";
                                        break;
                                    case "binder":
                                        echo "<td>".$trackers['binder']."</td>";
                                        break;
                                    default:
                                        echo "<td></td>";
                                }
                            else if ($key != "id_envio")
                                echo "<td>".$val."</td>";
                        }
                    echo "</tr>";
                }
                
            }
            catch(Exception $e)
            {
                rematelog($e->getMessage());
                die("Error en la consulta.");
            }
            
        }
        exit();
    }
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
<link rel="stylesheet" href="jsDatePick_ltr.min.css" />
<link rel="stylesheet" href="timePicker.css" />
<link rel="stylesheet" type="text/css" href="fancybox/jquery.fancybox-1.3.4.css" />
<link href="envios.css" type="text/css" rel="stylesheet" />
<script type="text/javascript">
<!--
$(function()
{
    $("#adminpop").click(function()
    {
        $("#admin").toggle();
    });
    $("#formbusqueda").submit(function()
    {
        if ($(this).find("select[name='tipo']").val() =="Seleccione")
        {
            alert("Seleccione un tipo de documento.");
            return false;
        }
        return true;
    })
});
-->
</script>
</head>
<body>
<center>
    <div>
        <div id="fotosuperior"><a href="http://despachos.chisol.cl/"><img src="icon.png" /></a></div>
        <p>SEGUIMIENTO WEB DE ENVÍOS Y DESPACHOS</p>
        <div id="query">
            <form method="post" action="envios.php" target="ifr" id="formbusqueda">
            <table>
                <tr><td colspan="2"><p>Ingrese el número de su Factura o Guía de Despacho</p><p>(Válido sólo para envíos a Regiones)</p></td>
                    </tr>
                <tr>
                    <td>
                        Buscar por:
                    </td>
                    <td><select name="tipo" style="width: 200px;">
                    <option>Seleccione</option>
                    <option value="1">Factura</option>
                    <option value="2">Guía de Despacho</option>
                </select></td></tr>
                <tr><td>Número: </td><td><input type="text" name="id_guia" style="width: 196px;"/></td></tr>
                <tr><td colspan="2"><center><input type="submit" value="Consultar envíos" /></center></td></tr>   
            </table>
            </form>
        </div>
    </div>
    <hr />
    <p><iframe name="ifr" src="" width="70%"></iframe></p>
    <p id="adminpop">ADMINISTRADOR</p>
    <div id="admin">
        <p>Ingresar como Administrador:</p>
        <form method="post" action="envios_admin.php">
            <table>
                <tr><td>Usuario: </td><td><input type="text" name="user" /></td></tr>
                <tr><td>Contrase&ntilde;a: </td><td><input type="password" name="pass" /></td></tr>
            </table>
            <p><input type="submit" value="Ingresar" /></p>
        </form>
    </div>
</center>
</body>
</html>
