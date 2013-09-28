<?
    // miniremates_modifica.php
    // get: idmr
    include("header.php");
    if (!adminGeneral()) die(consts::$mensajes[9]);
    if (!isset($_GET['idmr'])) die(consts::$mensajes[8]);
    $idmr = mysql_real_escape_string($_GET['idmr']);
    
    $r = mysql_query("select * from automaticos where id_auto = $idmr", dbConn::$cn);
    $datos = mysql_fetch_assoc($r);
?>
<script type="text/javascript">
$(function()
{
    $("li.sugg2").live("click",function()
    {
        // Setear la id en la caja y fin
        var datos = $(this).attr("data-prod").split(",");
        
        $("#buscadorproductos2").val(datos[0]);
        $("#iprecio2").val(datos[1]);
        $("#float-suggestions").hide();
    });
    $("#buscadorproductos2").keyup(function()
    {
        var texto = $(this).val();
        if (texto.length < 3) { $("#float-suggestions").hide(); return; }
        var content = "";
        $.getJSON("consulta_productos.php", { "text" : texto }, function(obj)
        {
            if (obj.length < 1) return;
            for (i in obj)
            {
                content += "<li class=\"sugg2\" data-prod=\""+obj[i].id+","+obj[i].precio+"\">"+obj[i].descr+"</li>";
            }
            var alto = 156;
            var refbuscador = $("#buscadorproductos2");
            $("#float-suggestions").html(content).css({
                position: "absolute",
                top: (refbuscador.position().top-alto)+"px",
                left: refbuscador.position().left+"px",
                height: alto+"px",
                width: "185px",
                "overflow-y": "auto",
                "overflow-x": "hidden",
                background: "#393939"
            }).show();
            
        });
    });
});
</script>
<form id="form-modmini" enctype="multipart/form-data" method="post" target="ifr" action="automaticos_mod.php">
    <input type="hidden" name="mod" value="true" />
    <input type="hidden" name="idmr" value="<?= $datos['id_auto'] ?>" />
<table>
<tr>
    <td>ID: <input type="text" name="id_producto" value="<?= $datos['id_producto'] ?>" id="buscadorproductos2"/></td>
</tr>
<tr><td>Minimo: <input type="text" name="monto_inicial" value="<?= $datos['minimo'] ?>" id="iprecio2"/></td></tr>
<tr><td>Titulo: <input type="text" id="titulo2" name="titulo" value="<?= $datos['titulo'] ?>"/></td><td>Descripcion: <textarea name="descr" id="descr2"><?= $datos['descripcion'] ?></textarea></td><td colspan="2">Foto:<input type="file" name="foto" /></td></tr>
<tr><td><input type="submit" class="submitea" value="Guardar cambios" /></td></tr>
</table>
</form>
