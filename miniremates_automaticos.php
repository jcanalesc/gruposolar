<?
	// Edicion de miniremates
	include("header.php");
	if (!adminGeneral()) die(consts::$mensajes[9]);
    $automaticos = array();
    
    $res = mysql_query("select * from automaticos join (select descripcion as descprod, id_producto from productos) as k using (id_producto)", dbConn::$cn);
    while(($row = mysql_fetch_assoc($res)) !== false)
    {
        $automaticos[] = $row;
    }
    
    
?>
<script type="text/javascript">
<!--
var isInside = false;
$(function()
{
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
    
    $("div.del.btn").click(function()
    {
       var idauto = $(this).parent().parent().children().eq(1).html();
       if (confirm("Está seguro que desea eliminar el miniremate automatico numero " + idauto + "?"))
       $.get("automaticos_mod.php", { "action" : "del" , "idmr" : idauto }, function(obj)
       {
           if (obj == "ok")
           {
               alert("Miniremate automatico eliminado.");
               goto("miniremates_automaticos.php");
           }
           else
           {
               alert("Problemas al intentar eliminar el miniremate automatico.");
           }
       });
    });
    $("div.edit[data-mod]").click(function()
    {
        $.get("automaticos_modifica.php", { idmr: $(this).attr("data-mod") }, function(data)
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
    $("#selectall").change(function()
    {
        if (!$(this).attr("checked"))
        {
            $("[data-checkauto]").attr("checked", false);
        }
        else
        {
            $("[data-checkauto]").attr("checked", true);
        }
    });
    $("#delselected").click(function()
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
                $.get("automaticos_mod.php", { "action": "multidel", "idmr": ids }, function(data) 
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
    $(".mra_estado").click(function()
    {
        var idmra = $(this).attr("data-idauto");
        var htmlref = this;
        $.get("automaticos_mod.php", { "action" : "toggle", "idmr" : idmra}, function(data)
        {
            if (data == "on")
            {
                $(htmlref).removeClass("mra_pausar").addClass("mra_activar");
                alert("Miniremate activado.");
            }
            else if (data == "off")
            {
                $(htmlref).removeClass("mra_activar").addClass("mra_pausar");
                
                alert("Miniremate desactivado.");
            }
            else
            {
                alert("Error en la operación: " + data);
            }
        });
    });
    $("#horario_aplicar").click(function(ev)
    {
        ev.stopPropagation();
        var hi = $("#hinicio_rut").val();
        var ht = $("#htermino_rut").val();
        var minimo = $("#h_vacios_minimos").val();
        var reg = /^\d{2}:\d{2}$/;
        if (reg.test(hi) && reg.test(ht) && !isNaN(parseInt(minimo)) && parseInt(minimo) >= 0)
        {
            $.get("set_horario.php", { "ini" : hi, "ter" : ht, "min" : minimo }, function(data)
            {
                if (data == "ok")
                    alert("Datos guardados.");
                else
                    alert("Error: " + data);
            });
        }
        else
        {
            alert("Revise los datos. El formato de los horarios es HH:MM, y la cantidad de miniremates minima es un numero no negativo.");
        }
    });
});
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
div.mra_estado
{
    display: block;
    margin: 0px;
    padding: 0px;
    border: 0px;
    width: 32px;
    height: 32px;
    cursor: pointer;
}
div.mra_pausar
{
    background: url('pausa.png') no-repeat;
}
div.mra_activar
{
    background: url('play.png') no-repeat;
}
</style>

<table class="tabla">
<tr><td rowspan="2">Crear nuevo automatico:</td><td>Producto</td><td>Precio Inicial</td><td colspan="2">&nbsp;</td>
    <td rowspan="3">
        <table>
            <tr>
                <td>Inicio hora inactividad:</td><td><input type="text" id="hinicio_rut" value="<?= consts::$hinicio_rut ?>"/></td>
            </tr>
            <tr>
                <td>Fin hora inactividad:</td><td><input type="text" id="htermino_rut" value="<?= consts::$htermino_rut ?>" /></td>
            </tr>
            <tr>
                <td>Minimo de miniremates inactivos:</td><td><input type="text" id="h_vacios_minimos" value="<?= consts::$h_vacios_minimos ?>" /></td>
            </tr>
            <tr>
                <td colspan="2"><button id="horario_aplicar">Aplicar</button></td>
            </tr>
        </table>
    </td>
</tr>
<form enctype="multipart/form-data" id="formu" method="post" action="automaticos_mod.php" target="ifr">
<tr id="form-creamini">
    <td><input type="text" name="id_producto" id="buscadorproductos"/></td>
    <td><input type="text" name="monto_inicial" id="iprecio"/></td>
    <td colspan="2"><input type="submit" class="submitea" value="Crear automatico" /></td>
</tr>
<tr><td><input type="text" id="titulo" name="titulo" value="Escriba título"/></td><td><textarea name="descr" id="descr">Escriba descripción</textarea></td><td colspan="2">Foto:<input type="file" name="foto" /></td></tr>
</table>
</form>
<hr />
<table class="tabla">
    <tr style="background: #FF9E9B;"><td colspan="6">Miniremates automáticos disponibles</td></tr>
    <tr><td><input type="checkbox" id="selectall" /></td><td>ID</td><td>Producto</td><td>Precio minimo</td><td colspan="2">Columna de edicion</td></tr>
    <?
        foreach($automaticos as $at)
        {
            $estadomra = $at['activo'] == "1" ? "mra_activar" : "mra_pausar";

            echo "<tr><td><input type=\"checkbox\" data-checkauto=\"{$at['id_auto']}\" /></td><td>{$at['id_auto']}</td><td>{$at['descprod']}</td><td>{$at['minimo']}</td><td><div class=\"edit\" data-mod=\"{$at['id_auto']}\"></div></td><td><div class=\"del btn\"></div></td><td><div data-idauto=\"{$at['id_auto']}\" class=\"mra_estado ".$estadomra."\"></div></td></tr>";
        }
    ?>
    <tr><td colspan="7"><button id="delselected">Eliminar seleccionados</button></td></tr>
</table>
<div id="float-suggestions" style="display: none;"></div>
<iframe width="0" height="0" style="visibility: hidden;" name="ifr" id="ifr"></iframe>
