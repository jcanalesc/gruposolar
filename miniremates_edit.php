<?
	// Edicion de miniremates
	include("header.php");
	if (!adminGeneral()) die(consts::$mensajes[9]);

    $r = mysql_query("select * from miniremates join productos using (id_producto) where miniremates.finalizado = false order by fecha_termino desc", dbConn::$cn);
    $finalizados = array();
    $activos = array();
    if ($r !== false && mysql_num_rows($r) > 0)
        while($row = mysql_fetch_assoc($r))
        {
            $activos[] = $row;
        }
    
    
?>
<script language="javascript" src="jsDatePick.jquery.min.1.3.js"></script>
<link rel="stylesheet" href="jsDatePick_ltr.min.css" />
<script language="javascript" src="jquery.timePicker.min.js"></script>
<link rel="stylesheet" href="timePicker.css" />
<script type="text/javascript">
<!--
function guardaTexto(id, text)
{
    $.getJSON("mr_mod_live.php", { "idmr": id, "texto": text}, function(obj)
    {
        if (obj != true)
        {
            alert("Problemas al guardar los datos del miniremate.");
        }
    });
}
function guardaEstadoPago(id, pago)
{
    $.getJSON("mr_mod_live.php", { "idmr" : id, "pago" : pago}, function(obj)
    {
        if (obj != true)
        {
            alert("Problemas al guardar los datos del miniremate.");
        }
    });
}
var isInside = false;
var busqueda = "";
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
    $("#buscadorproductos").keyup(function()
    {
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
            var refbuscador = $("#buscadorproductos");
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
        
        $("#buscadorproductos").val(datos[0]);
        $("#iprecio").val(datos[1]);
        $("#float-suggestions").hide();
    });
    $("button[data-borra]").live("click",function()
    {
       var idmr = $(this).attr("data-borra");
       if (confirm("Está seguro que desea eliminar el miniremate numero " + idmr + "?"))
       $.get("miniauction_mod.php", { "action" : "del" , "idmr" : idmr }, function(obj)
       {
           if (obj == "true")
           {
               alert("Miniremate eliminado.");
               goto("miniremates_edit.php");
           }
           else
           {
               alert("Problemas al intentar eliminar el miniremate.");
           }
       });
    });
    $("button[data-mod]").click(function()
    {
        $.get("miniremates_modifica.php", { idmr: $(this).attr("data-mod") }, function(data)
        {
            $("#floating div").html(data).css("overflow", "visible");
            $("#floating").css({
                position: "absolute",
                top: "10px",
                left: "10%",
                width: "80%",
                overflow: "visible"
            }).show("slow");
            $.scrollTo("#floating");
        });
    });
    $("#selectall").live("change",function()
    {
        if (!$(this).attr("checked"))
        {
            $("[data-checkmr]").attr("checked", false);
        }
        else
        {
            $("[data-checkmr]").attr("checked", true);
        }
    }); 
    $("#delselected").live("click",function()
    {
        var cuales = $("[data-checkmr]:checked");
        if (cuales.length > 0)
        {
            var ids = [];
            $("[data-checkmr]:checked").each(function()
            {
                ids.push(parseInt($(this).attr("data-checkmr")));
            });
            if (confirm("Está seguro de eliminar los miniremates seleccionados?"))
            {
                $.get("miniauction_mod.php", { "action": "multidel", "idmr": ids }, function(data) 
                {
                    if (data == "ok")
                    {
                        alert("Miniremates eliminados satisfactoriamente.");
                        goto("miniremates_edit.php");
                    }
                    else
                        alert("Error intentando eliminar los miniremates seleccionados.");
                });
            }
        }
    });
    $("#r_exec").click(function()
    {
        var cantidad = $("#r_count").val();
        var aumento = $("#r_rate").val();
        var duracion = $("#r_length").val();
        
        $.get("automaticos_mod.php", { "action" : "exec", "cantidad" : cantidad, "aumento" : aumento, "duracion" : duracion }, function(data)
        {
            if (data == "ok")
                alert("Configuración aplicada. Se mantendrán " + cantidad + " miniremates siendo generados.");
            else
                alert("Problemas al intentar aplicar la configuración.");
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
    $("div.infowindow textarea").live("change", function()
    {
        var idmr = $(this).attr("data-idmr");
        var texto = $(this).val();
        guardaTexto(idmr, texto);
    });
    $("input[name^='pagado_']").live("change", function()
    {
        var idmr = ($(this).attr("name").split("_"))[1];
        var estado = $(this).val();
        guardaEstadoPago(idmr, estado);
    });
    $("#mr_buscador_btn").live("click", function()
    {
        busqueda = $("#mr_buscador").val();
        ir_a_pag(1);
    });
    $("#mr_buscador").live("keypress", function(e)
    {
        var code= (e.keyCode ? e.keyCode : e.which);
        if (code == 13)
        {
            busqueda = $("#mr_buscador").val();
            ir_a_pag(1);
        }
    });
});

function ir_a_pag(pag)
{
    $.get("miniremates_pager.php", { page: pag, busca: busqueda}, function(data)
    {
        $("#finalizados_table").html(data);
    })
}

function getnota(elem)
{
    var td = $(elem).parent().parent().children().eq(2);
    var idmr = td.html();
    var usuario = td.parent().children().eq(5).html();
    window.open("creanota.php?id_remate=MR"+escape(idmr)+"&rut="+escape(usuario));
}
//-->
</script>
<style type="text/css">
input.clickme
{
    cursor: pointer;
    
}
p.centered
{
    text-align: center;
}
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
img.verinfo
{
    cursor: pointer;
}
div.infowindow
{
    display: none;

    width: 300px;
    left: -315px;
    height: 100px;
    top: 50px;
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
#finalizados_table td
{
    font-size: 11px;
}
</style>
<form enctype="multipart/form-data" id="formu" method="post" action="miniauction_mod.php" target="ifr">
<table class="tabla">
<tr><td rowspan="2">Crear nuevo miniremate:</td><td>Producto</td><td>Precio Inicial</td><td>Incremento</td><td>Fecha inicio/término</td><td>Crear</td></tr>
<tr id="form-creamini">
    <td><input type="text" name="id_producto" id="buscadorproductos"/></td>
    <td><input type="text" name="monto_inicial" id="iprecio"/></td>
    <td><input type="text" name="incremento" size="3" id="incr"/>%</td>
    <td>
        Inicio(*): <input type="text" name="fecha_inicio" id="calendarinit" size="7" /><input type="text" name="hora_inicio" id="hourinit" size="5" /><br />
        Término: <input type="text" name="fecha_termino" id="calendar" size="7"/><input type="text" name="hora_termino" id="hour" size="5"/>
    </td>
    <td><input type="submit" class="submitea" value="Crear miniremate" /></td>
</tr>
<tr><td><input type="text" id="titulo" name="titulo" value="Escriba título"/></td><td><textarea name="texto" id="descr">Escriba descripción</textarea></td><td colspan="2">Foto:<input type="file" name="foto" /></td></tr>
</table>
</form>
<p class="centered">*: Un valor de inicio en blanco significa que el miniremate comienza inmediatamente.</p>
<table class="tabla">
    <tr style="background: #FF9E9B;"><td colspan="5">Miniremates vigentes</td></tr>
    <tr><td colspan="2">ID</td><td>Producto</td><td>Precio actual</td><td>Fecha inicio-término</td><td>Ganador</td></tr>
    <?
        foreach($activos as $ac)
        {
            echo "<tr><td><button data-borra=\"{$ac['id_miniremate']}\">Eliminar</button><button data-mod=\"{$ac['id_miniremate']}\">Modificar</button></td><td>{$ac['id_miniremate']}</td><td>{$ac['descripcion']}</td><td>{$ac['monto_actual']}</td><td>Inicio: {$ac['fecha_inicio']}, Termino: {$ac['fecha_termino']}</td><td>{$ac['rut_ganador']}</td></tr>";
        }
    ?>
</table>
<hr />
<p>Random(<?= consts::$automaticos[0] ?>): <input type="text" id="r_count" /> Tasa aumento(%)(<?= consts::$automaticos[1] ?>%): <input type="text" id="r_rate" /> Duracion(horas:minutos)(<?= consts::$automaticos[2].":".consts::$automaticos[3] ?>): <input type="text" id="r_length" /> <button id="r_exec">Crear</button></p>
<hr />
<table class="tabla" id="finalizados_table">
    <?
        $ruta = substr($_SERVER['REQUEST_URI'], 1, strrpos($_SERVER['REQUEST_URI'], "/"));  
        echo file_get_contents("http://localhost/" . $ruta . "miniremates_pager.php?page=1");
    ?>
</table>
<div id="float-suggestions" style="display: none;"></div>
<iframe width="0" height="0" style="visibility: hidden;" name="ifr" id="ifr"></iframe>
