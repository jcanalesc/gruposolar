<?php
include ("header.php");
include("DB.inc.php");


if (!adminGeneral()) die("Acceso denegado");

$modificando = false;
$today_fecha = htmlspecialchars(date("Y-m-d"));
$today_hora = htmlspecialchars(date("H:i"));

$next_id = 1;

$resp = mysql_query("show table status like 'ofertas'", dbConn::$cn);
$row = mysql_fetch_assoc($resp);
$next_id = $row['Auto_increment'];
mysql_free_result($resp);

$oferta_data = array(
    "id_oferta" => $next_id,
    "fecha_inicio" => $today_fecha." ".$today_hora,
    "fecha_termino" => "",
    "id_producto" => "",
    "descripcion" => "",
    "cant_maxima" => "1",
    "stock" => "0",
    "fono" => "",
    "nombre" => "",
    "precio" => "0",
    "parametro_nom" => "",
    "parametro_op1" => "",
    "parametro_op2" => "",
    "parametro_op3" => ""
);

if (isset($_GET['mod_id']))
{
    $modificando = true;

?>
<link rel="stylesheet" type="text/css" href="fancybox/jquery.fancybox-1.3.4.css" />
<link rel="stylesheet" href="jsDatePick_ltr.min.css" />
<link rel="stylesheet" href="timePicker.css" />
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<script language="javascript" src="jsDatePick.jquery.min.1.3.js"></script>
<script language="javascript" src="jquery.timePicker.min.js"></script>
<?
    $ido = $_GET['mod_id'];
    $res = $db->query("select * from ofertas where id_oferta = $ido");
    if ($res !== false && count($res) == 1)
    {
        $oferta_data = $res[0];
    }
}



?>
<style type="text/css">
li.sugg, li.sugg2
{
    display: block;
    margin: 0px;
    padding-right: 6px;
    width: 174px;
    height: 50px;
    font-size: 10px;
    background: #000000;
    color: #ffffff;
    
}
</style>
<script type="text/javascript">

function guardaTexto_of(ido,rut,texto)
{
    $.getJSON("oferta_comprada_mod.php", { "ido": ido, "rut" : rut, "param" : "comment", "val" : texto}, function(obj)
    {
        if (obj != true)
        {
            alert("Problemas al intentar guardar el comentario.");
        }
    });
}

function guardaEstado_of(ido, rut, estado)
{
    $.getJSON("oferta_comprada_mod.php", { "ido": ido, "rut" : rut, "param" : "pagado", "val" : estado}, function(obj)
    {
        if (obj != true)
        {
            alert("Problemas al intentar guardar el estado de pago.");
        }
    });
}
var current_history_target = null;
var current_history_ido = null;
function generate_history(ido, container, data)
{
    // primero se destruye el historial anterior si es que hay
    if (current_history_target != null)
    {
        current_history_target.remove();
    }
    current_history_target = container;
    current_history_ido = ido;


    container.append("<td colspan='6'><table></table></td>");
    var curr_table = container.find("table");

    //header

    var headerstr = "<tr><td>Ganador</td><td>Nota de Venta</td><td>Info</td><td>Pagado</td></tr>";

    curr_table.append(headerstr);

    for(var i = 0; i < data.length; i++)
    {
        var str = "<td>" + data[i].ganador + "</td>" + 
                  "<td>" + "<a class=\"botonpdf\" target=\"_blank\" href=\"creanotaoferta.php?rut="+data[i].ganador+"&id_oferta="+ido+"\">Obtener PDF</a>" + "</td>" + 
                  "<td>" + "<img src=\"fotonota.png\" class=\"verinfo\" />" + 
                           "<div class=\"infowindow\"><textarea data-rut=\"" + data[i].ganador +"\" data-ido=\""+ ido + 
                           "\">"+ (data[i].comment != null ? data[i].comment : "") +"</textarea></div>" + "</td>" + 
                  "<td><input type=\"radio\" name=\"pagado_"+data[i].ganador+"_"+ido+"\" value=\"1\" "+(data[i].pagado == "1" ? "checked" : "")+"/>Sí" +
                 "<input type=\"radio\" name=\"pagado_"+data[i].ganador+"_"+ido+"\" value=\"0\" "+(data[i].pagado == "0" ? "checked" : "")+"/>No</td>";

        curr_table.append("<tr>"+str+"</tr>");
    }
}
$(function()
{
	new JsDatePick
            ({
                useMode:2,
                target:"calendar",
                dateFormat:"%Y-%m-%d",
                yearsRange:[2011,2020]
            });
    new JsDatePick
            ({
                useMode:2,
                target:"calendarinit",
                dateFormat:"%Y-%m-%d",
                yearsRange:[2011,2020]
            });
    $("#hour").timePicker({ step: 15 });
    $("#hourinit").timePicker({step:15});
    var refbuscador = null;
    var refprecio = null;
	$("input.autocompletado").keyup(function()
    {
        refbuscador = $(this);
        refprecio = $($(this).attr("data-precio"));
        var texto = $(this).val();
        if (texto.length < 3) { $("#float-suggestions").hide(); return; }
        var content = "";
        
        $.getJSON("consulta_productos.php", { "text" : texto }, function(obj)
        {
            if (obj.length < 1) return;
            for (i in obj)
            {
                content += "<li class=\"sugg\" data-prod=\""+obj[i].id+","+obj[i].precio+"\">"+obj[i].descr+"</li>";
            }
            var alto = 156;
            $("#float-suggestions").html(content).css({
                position: "absolute",
                top: (refbuscador.position().top-alto)+"px",
                left: refbuscador.position().left+"px",
                height: alto+"px",
                width: "185px",
                "overflow-y": "auto",
                "overflow-x": "hidden",
                background: "#393939",
                "z-index": "999"
            }).show();
            
        });
    });
    $("li.sugg").live("click",function()
    {
        // Setear la id en la caja y fin
        var datos = $(this).attr("data-prod").split(",");
        
        refbuscador.val(datos[0]);
        refprecio.val(datos[1]);
        $("#float-suggestions").hide();
    });

<? if (!$modificando): ?>
    $(".modbutton").click(function()
    {
        var ido = $(this).parent().parent().attr("data-ido");
        $.fancybox("<iframe width='900' height='500' src='oferta_edit.php?mod_id="+ido+"'></iframe>");
    });
    $(".delbutton").click(function()
    {
        var refer = this;
        var ido = $(this).parent().parent().attr("data-ido");        
        if (confirm("Realmente desea eliminar la oferta con ID: "+ido+"?"))
        {
            $.get("oferta_del.php",{ "id" : ido }, function(data)
            {
                if (data == "ok")
                {
                    alert("Oferta eliminada.");
                    $(refer).parent().parent().remove();
                }
                else
                {
                    alert("Problemas al intentar eliminar la oferta.");
                }
            });
        }
    });
    $(".histbutton").click(function()
    {
        var my_row = $(this).parent().parent();
        var ido = my_row.attr("data-ido");
        if (ido == current_history_ido)
        {
            current_history_target.remove();
            return;
        }
        $.getJSON("oferta_history.php", {"ido" : ido}, function(obj)
        {
            if (obj != null)
            {
                var target_row = my_row.after("<tr></tr>");
                target_row = target_row.next();
                generate_history(ido, target_row, obj);
            }
        });
    });
    $("img.verinfo").live("click",function()
    {
        var shown = $(this).next().css("display") != "none";
        $("div.infowindow").hide();
        if (!shown)
        {
            $(this).next().show();
        }
    });
    $("input[name^='pagado_']").live("change", function()
    {
        var elems = $(this).attr("name").split("_");
        var ido = elems[2];
        var rut = elems[1];
        var estado = $(this).val();
        guardaEstado_of(ido,rut,estado);
    });
    $("div.infowindow textarea").live("change", function()
    {
        var rut = $(this).attr("data-rut");
        var ido = $(this).attr("data-ido");
        var texto = $(this).val();
        guardaTexto_of(ido,rut, texto);
    });
<? endif; ?>
});
</script>
<style type="text/css">
img.verinfo
{
    cursor: pointer;
}
div.infowindow
{
    display: none;

    width: 300px;
    left: 36px;
    height: 100px;
    top: 59px;
    margin-top: -100px;
    margin-right: -300px;
    background: #fff;
    border: 2px solid #999;
    border-radius: 7px;
    -moz-border-radius: 7px;
    position: relative;
}
div.infowindow textarea
{
    width: 90%;
    height: 85%;
}
</style>
<form method="post" action="oferta_mod.php" enctype="multipart/form-data" id="new_oferta" target="ifr">
<input type="hidden" name="id_oferta" value="<?= $oferta_data['id_oferta'] ?>" />
<table class="tabla">
	<tr><td colspan="2">Nueva Oferta</td></tr>
	<tr><td>ID</td><td><input type="text" value="<?= $oferta_data['id_oferta'] ?>" disabled /></td></tr>
	<tr><td>Nombre visible</td><td><input type="text" name="nombre" value="<?= $oferta_data['nombre'] ?>"/></td></tr>
	<tr><td>Descripción</td><td><textarea rows="3" name="descripcion"><?= $oferta_data['descripcion'] ?></textarea></td></tr>
	<tr><td>Producto (búsqueda)</td><td><input type="text" class="autocompletado" data-precio="#iprecio" name="id_producto" value="<?= $oferta_data['id_producto'] ?>"/></td></tr>
	<tr><td>Precio unitario</td><td><input type="text" name="precio" id="iprecio" value="<?= $oferta_data['precio'] ?>"/></td></tr>
	<tr><td>Fecha inicio</td><td><input type="text" class="date_input" name="fecha_inicio[]" id="calendarinit" value="<?= strtok($oferta_data['fecha_inicio'], ' ') ?>" /> a las <input type="text" class="time_input" name="fecha_inicio[]" id="hourinit" value="<?= strtok(' ') ?>"/></td></tr>
	<tr><td>Fecha término</td><td><input type="text" class="date_input" name="fecha_termino[]" id="calendar" value="<?= strtok($oferta_data['fecha_termino'], ' ') ?>"/> a las <input type="text" class="time_input" name="fecha_termino[]" id="hour" value="<?= strtok(' ') ?>"/></td></tr>
    <tr><td>Stock disponible total (0 para stock infinito)</td><td><input type="text" name="stock" value="<?= $oferta_data['stock'] ?>"/></td></tr>
	<tr><td>Unidades por cliente</td><td><input type="text" name="cant_maxima" value="<?= $oferta_data['cant_maxima'] ?>"/></td></tr>
	<tr><td>Teléfono (separar por comas varios teléfonos)</td><td><input type="text" name="fono" value="<?= $oferta_data['fono'] ?>"/></td></tr>
    <tr><td>Título del parámetro a mostrar:</td><td><input type="text" name="parametro_nom" value="<?= $oferta_data['parametro_nom'] ?>"/></td></tr>
    <tr><td>Parámetro - Opción 1</td><td><input type="text" name="parametro_op1" value="<?= $oferta_data['parametro_op1'] ?>"/></td></tr>
    <tr><td>Parámetro - Opción 2</td><td><input type="text" name="parametro_op2" value="<?= $oferta_data['parametro_op2'] ?>"/></td></tr>
    <tr><td>Parámetro - Opción 3</td><td><input type="text" name="parametro_op3" value="<?= $oferta_data['parametro_op3'] ?>"/></td></tr>
	<tr><td>Banner</td><td><input type="file" name="banner" /></td></tr>
	<tr><td>Procedimiento</td><td><input type="file" name="procedimiento" /></td></tr>
	<tr><td colspan="2"><input type="submit" value="<? if($modificando) echo 'Modificar oferta'; else echo 'Ingresar nueva oferta'; ?>" /></td></tr>
</table>
</form>
<div id="float-suggestions" style="display: none;"></div>
<iframe width="0" height="0" style="visibility: hidden;" name="ifr" id="ifr"></iframe>
<? if (!$modificando): ?>
    <table class="tabla">
        <tr>
            <th>ID</th>
            <th>Oferta</th>
            <th>Fechas inicio-término</th>
            <th>Ver</th>
            <th colspan="2">&nbsp;</th>
        </tr>
        <?
            $ofertas = $db->query("select * from ofertas order by fecha_inicio desc");
            foreach($ofertas as $row):
            ?>
            <tr data-ido="<?= $row['id_oferta'] ?>">
                <td><?= $row['id_oferta'] ?></td>
                <td><?= $row['nombre'] ?></td>
                <td>Desde: <?= $row['fecha_inicio'] ?> Hasta: <?= $row['fecha_termino'] ?></td>
                <td><a target="_blank" href="/oferta.php?id=<?= $row['id_oferta'] ?>"><button>Ver</button></a></td>
                <td><button class="histbutton">Historial</button></td>
                <td><button class="modbutton">Editar</button></td>
                <td><button class="delbutton">X</button></td>
            </tr>
            <? endforeach; ?>
    </table>
<? endif; ?>