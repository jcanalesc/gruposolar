<?
    include("header.php");
    if (!adminGeneral()) die(consts::$mensajes[9]);
    
?>
<script type="text/javascript">
<!--
$(function()
{
    $("#guardacursos").click(function()
    {
        var datos = [];
        $("#filacursos td").each(function(index)
        {
            var texto = "<p>"+$("input", this).eq(0).val()+"</p>"+
                        "<p>"+$("input", this).eq(1).val()+"</p>"+
                        "<p>"+$("input", this).eq(2).val()+"</p>";
            var url = $("input", this).eq(3).val();
            var label = $("input", this).eq(4).val();
            datos.push([texto, url, label].join("|"));
        });;
        $.post("header.php", {
                "func" : "saveCursos",
                "args" : datos.join(";")
            }, function(data)
            {
                console.log(data);
                if (data == "ok")
                {
                    alert("Datos guardados correctamente.");
                }
                else
                {
                    alert("Problema al guardar."+data);
                }
            });
    });
});
//-->
</script>
<style type="text/css">
table#curst tr td
{
    text-align: left;
}
table#curst
{
    position: relative;
    margin: 0px auto;
}
</style>
<table id="curst">
    <tr>
        <td colspan="3">Cursos disponibles en portada</td>
    </tr>
    <tr>
        <td>Curso 1:</td>
        <td>Curso 2:</td>
        <td>Curso 3:</td>
    </tr>
    <tr id="filacursos">
    <? foreach (consts::$cursos as $cr): 
            $etiquetas = explode(";",preg_replace(array('/^<p>/','/<\/p><p>/','/<\/p>$/'),array("",";",""),$cr['texto']));
    ?>
        <td>
            <input type="text" value="<?= $etiquetas[0] ?>"/><br />
            <input type="text" value="<?= $etiquetas[1] ?>"/><br />
            <input type="text" value="<?= $etiquetas[2] ?>"/><br />
            Link:
            <input type="text" value="<?= $cr['link'] ?>"/><br />
            Etiqueta del link:
            <input type="text" value="<?= $cr['etiqueta'] ?>"/>
        </td>
    <? endforeach; ?>
    </tr>
    <tr><td colspan="3"><button id="guardacursos">Guardar cambios</button></td></tr>
</table>
