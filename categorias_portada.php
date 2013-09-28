<?
    include("header.php");
    if (!adminGeneral()) die(consts::$mensajes[8]);
    // interfaz para modificar las categorias del frontis, texto, foto y la galeria asociada
    $cat_portada = array();
    $r = mysql_query("select * from categorias_portada", dbConn::$cn);
    if ($r !== false)
        while($row = mysql_fetch_assoc($r))
            $cat_portada[$row['id_cp']] = $row;
?>
<script type="text/javascript">
<!--
// arreglar esta mierda
var objetoCats = <?= json_encode($cat_portada) ?>;
function modificarnombre($tr)
{   
    //console.log("modificarnombre");
    var id_cp = $tr.find("td:first").html();
    var nombre = $tr.find("input[type='text'].texto").val();
    $.getJSON("modcatportada.php", { "tipo": "nombre", "id_cp": id_cp, "val": nombre }, function(obj)
    {
        if (obj === true) goto("categorias_portada.php");
        else alert(obj.msg);
    });    
}
function modificarUrl($tr)
{
    var id_cp = $tr.find("td:first").html();
    var url = $tr.find("input[type='text'].urlcat").val();
    $.getJSON("modcatportada.php", { "tipo": "url", "id_cp" : id_cp, "val" : url }, function(obj)
    {
        if (obj === true) goto("categorias_portada.php");
        else alert(obj.msg);
    });    
}
function eliminaCat(id)
{
    $.getJSON("modcatportada.php", {"tipo": "erase", "id_cp": id, "val": "-" }, function(obj)
    {
        if (obj === true) goto("categorias_portada.php");
        else alert(obj.msg);
    });
}
$(function()
{
    $("#modcats input[type='text']").live("keyup", function(event)
    {
        if (event.keyCode == '13')
        {
            if ($(this).hasClass("texto"))
            {
                modificarnombre($(this).parent().parent());
            }
            if ($(this).hasClass("urlcat"))
            {
                modificarUrl($(this).parent().parent());
            }
        }
    });
    $("button.do-mod").live("click",function()
    {
        modificarnombre($(this).parent().parent()); // le paso el jquery del tr correspondiente
    });
    $("button.modurl").click(function()
    {
        modificarUrl($(this).parent().parent());
    });
    $("button.erasecat").click(function()
    {
        eliminaCat($(this).parent().parent().attr("data-idcp"));
    });
    $("#newcat").click(function()
    {
        $.getJSON("modcatportada.php", {"tipo": "new"}, function(obj)
        {
            if (obj === true)
                goto("categorias_portada.php");
            else
                alert("Hubo problemas al intentar crear la categoría.");
        });
    });
});
//-->
</script>
<table id="modcats" class="tabla">
    <tr><td colspan="5">Categorias de portada</td></tr>
    <tr><td>ID</td><td>Texto</td><td>Foto</td><td>Pagina de catalogo</td><td>Eliminar</td></tr>
    <?
        foreach($cat_portada as $cp)
        {
            $foto = (strlen($cp['foto']) > 0 ? "<img width='117' height='32' src='{$cp['foto']}' />" : "Sin foto");
            echo "<tr class='modifica' data-idcp='{$cp['id_cp']}'><td>{$cp['id_cp']}</td><td>{$cp['texto']}<br /><input type='text' class='texto'/><button class='do-mod'>Modificar</button></td><td>$foto<form method='POST' action='modcatportada.php' data-prev='{$cp['foto']}' class='modimg' target='hidf' enctype='multipart/form-data'><input type='file' name='cat_img' /><input type='hidden' name='idcp' value='{$cp['id_cp']}' /><input type='submit' class='submitea' value='Actualizar imagen' /></form></td><td>".htmlspecialchars($cp['url_pag'])."<br /><input type='text' class='urlcat' /><button class='modurl'>Modificar</button></td><td><button class='erasecat'>Eliminar</button></td></tr>";
        }
    ?>
</table>
<button id="newcat">Crear nueva categoría</button>
<iframe name="hidf" id="hidf" width="0" height="0" style="visibility: hidden;"></iframe>
