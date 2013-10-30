<?
    // mod_menues.php
    // edicion de los menúes superiores de la portada
    include("header.php");
    if (!adminGeneral()) die(consts::$mensajes[9]);
    if (isset($_GET['index']) && isset($_GET['texto']) && isset($_GET['link']))
    {
        $index = (int)$_GET['index'];
        $texto = $_GET['texto'];
        $link = $_GET['link'];
        rematelog("texto: $texto, link: $link, indice: $index");
        consts::$menu[$index] = array("texto" => $texto, "link" => $link);
        consts::save_config();
        die("true");
    }
    else
    {
        $i = 0;
?>
<script type="text/javascript">
$(function()
{
    $("#menues button").click(function()
    {
        var texto = $(this).parent().prev().prev().children().eq(0).val();
        var url = $(this).parent().prev().children().eq(0).val();
        var conteo = $(this).attr("data-count");
        $.getJSON("mod_menues.php",  { "index": conteo, "texto": texto, "link": url}, function(obj)
        {
            if (obj === true)
            {
                alert("Guardado correctamente.");
                goto("mod_menues.php");
            }
            else
                alert("Problemas en la modificación de los datos.");
        });
    });
});
</script>
<table class="tabla" id="menues">
    <tr>
        <td>Titulo del menú</td><td>Link</td><td>Guardar</td>
    </tr>
    <? foreach (consts::$menu as $item) { ?>
    <tr>
        <td><input type="text" value="<?= $item['texto'] ?>" /></td><td><input type="text" value="<?= htmlspecialchars($item['link']) ?>" /></td><td><button data-count="<?= $i++ ?>">Guardar</button></td>
    </tr>
    <?  }   ?>
</table>
<?
    }
?>
