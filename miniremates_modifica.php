<?
    // miniremates_modifica.php
    // get: idmr
    include("header.php");
    if (!adminGeneral()) die(consts::$mensajes[9]);
    if (!isset($_GET['idmr'])) die(consts::$mensajes[8]);
    $idmr = mysql_real_escape_string($_GET['idmr']);
    
    $r = mysql_query("select * from miniremates where id_miniremate = $idmr", dbConn::$cn);
    $datos = mysql_fetch_assoc($r);
    list($fechaini, $horaini) = explode(" ", $datos['fecha_inicio']);
    list($fechaend, $horaend) = explode(" ", $datos['fecha_termino']);
    list($anoini, $mesini, $diaini) = explode("-", $fechaini);
    list($anofin, $mesfin, $diafin) = explode("-", $fechaend);
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
    new JsDatePick
            ({
                useMode:2,
                target:"calendar2",
                dateFormat:"%Y-%m-%d",
                yearsRange:[2011,2020],
                selectedDate: {
                    year: <?= $anofin ?>,
                    month: <?= $mesfin ?>,
                    day: <?= $diafin ?>
                }
            });
    new JsDatePick
            ({
                useMode:2,
                target:"calendarinit2",
                dateFormat:"%Y-%m-%d",
                yearsRange:[2011,2020],
                selectedDate: {
                    year: <?= $anoini ?>,
                    month: <?= $mesini ?>,
                    day: <?= $diaini ?>
                }
            });
    $("#hour2").timePicker({ step: 15, startTime: "<?= $horaend ?>" });
    $("#hourinit2").timePicker({step:15, startTime: "<?= $horaini ?>"});
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
<form id="form-modmini" enctype="multipart/form-data" method="post" target="ifr" action="miniauction_mod.php">
    <input type="hidden" name="mod" value="true" />
    <input type="hidden" name="idmr" value="<?= $datos['id_miniremate'] ?>" />
<table>
<tr>
    <td><input type="text" name="id_producto" value="<?= $datos['id_producto'] ?>" id="buscadorproductos2"/></td>
    <td><input type="text" name="monto_inicial" value="<?= $datos['monto_inicial'] ?>" id="iprecio2"/></td>
    <td><input type="text" name="incremento" value="<?= $datos['incremento'] ?>" size="3" id="incr"/>%</td>
    <td>
        Inicio(*): <input type="text" name="fecha_inicio" value="<?= $fechaini ?>" id="calendarinit2" size="7" /><input type="text" name="hora_inicio" value="<?= $horaini ?>" id="hourinit2" size="5" /><br />
        Término: <input type="text" name="fecha_termino" value="<?= $fechaend ?>" id="calendar2" size="7"/><input type="text" name="hora_termino" value="<?= $horaend ?>" id="hour2" size="5"/>
    </td>
    <td><input type="submit" class="submitea" value="Guardar cambios" /></td>
</tr>
<tr><td><input type="text" id="titulo2" name="titulo" value="<?= $datos['titulo'] ?>"/></td><td><textarea name="texto" id="descr2"><?= $datos['texto'] ?></textarea></td><td colspan="2">Foto:<input type="file" name="foto" /></td></tr>
</table>
</form>
