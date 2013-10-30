<?
    // editor de weas de publicidad
    include("header.php");
    if (!adminGeneral()) die(consts::$mensajes[9]);
    $r = mysql_query("select * from publicidades", dbConn::$cn);
    $pubs = array();
    if ($r !== false && mysql_num_rows($r) > 0) while($row = mysql_fetch_assoc($r))
    {
        $pubs[] =& new Publicidad($row);
    }
?>
<script type="text/javascript" src="swfobject.js"></script>
<script type="text/javascript">
<?
foreach($pubs as $pub)
{
    $pubid = $pub->id();
    if ($pub->type() == "flash")
        echo "swfobject.registerObject(\"$pubid\", \"9\");\n";
}
?>
</script>
<script type="text/javascript">
<!--
$(function()
{
    $("button.changetype").click(function()
    {
        var idp = $(this).attr("data-idp");
        $.get("modpub.php", {"idp": idp, "action": "switch" }, function(data)
        {
            if (data != "true")
                alert(data);
            else
                goto("publicidad.php");
        });
    });
    $("button.changehtml").click(function()
    {
        var idp = $(this).attr("data-idp");
        var nuevohtml = $(this).prev().val();
        $.get("modpub.php", {"idp": idp, "action": "mod", "html": nuevohtml }, function(data)
        {
            if (data != "true")
                alert(data);
            else
                goto("publicidad.php");
        });
    });
    $("button.delpub").click(function()
    {
        var idp = $(this).attr("data-idp");
        $.get("modpub.php", {"idp": idp, "action": "del" }, function(data)
        {
            if (data != "true")
                alert(data);
            else
                goto("publicidad.php");
        }); 
    });
    $("#newpub").click(function()
    {
        $.get("modpub.php", {"action": "new" }, function(data)
        {
            if (data != "true")
                alert(data);
            else
                goto("publicidad.php");
        }); 
    });
});
//-->
</script>
<table class="tabla">
    <tr><td colspan="2">Lista de cuadros de publicidad, ordenados segun orden de aparicion en la pagina principal</td></tr>
    <?
        foreach($pubs as $pb)
        {
            echo "<tr>";
            echo "<td>".$pb."</td>";
            echo "<td><table><tr><td>Tipo: ".ucwords($pb->type())."<button class='changetype' data-idp='".$pb->id()."'>Cambiar</button></td></tr>";
            if ($pb->type() == "imagen")
            {
                
                $datos = explode("|", $pb->data['html']);
                $link = "#";
                if(count($datos) > 1)
                    $link = $datos[1];

                echo "<tr><td>Cambiar imagen: <form target='hif' action='cambiaimgpub.php' enctype='multipart/form-data' id='pubimg' method='post'><input type='file' name='pubimgfile' /><input type='hidden' name='idp' value='".$pb->id()."' /><input type='submit' class='submitea' value='Cambiar imagen' /><br />Cambiar link: <input type='text' name='pubimglink' value='".$link."'/></form></td></tr>";
                
            }
            else if ($pb->type() == "flash")
            {
                echo "<tr><td valign='top'>Modificar: <form target='hif' action='cambiaimgpub.php' enctype='multipart/form-data' id='pubflash' method='post'><input type='file' name='pubflashfile' /><input type='hidden' name='idp' value='".$pb->id()."' /><input type='submit' class='submitea' value='Cambiar flash' /></form></td></tr>";
            }
            else if ($pb->type() == "youtube")
            {
                echo "<tr><td valign='top'><form target='hif' action='cambiaimgpub.php' enctype='multipart/form-data' id='pubyoutube' method='post'>Modificar imagen: <input type='file' name='pubyoutubeimg' /><input type='hidden' name='idp' value='".$pb->id()."' />Link de youtube: <input type='text' name='pubyoutubelink' /><input type='submit' class='submitea' value='Modificar datos' /></form></td></tr>";
            }
            else
            {
                echo "<tr><td valign='top'>Modificar: <textarea cols='50' rows='10'>".$pb."</textarea><button class='changehtml' data-idp='".$pb->id()."'>Aplicar</button></td></tr>";

            }
            echo "</table></td><td><button class='delpub' data-idp='".$pb->id()."'>Eliminar</button></td>";
            echo "</tr>";
        }
    ?>
</table>
<button id='newpub'>Nuevo</button>
<table class="tabla">
    <tr>
        <td colspan="2">VIDEO REMATES</td>
    </tr>
    <tr>
        <td>Escribir mensaje</td><td><input type="text" id="vremate_mensaje" value="<?= consts::$videos_remate[0]['text'] ?>" /></td>
    </tr>
    <tr>
        <td>Anuncio visible: <input type="radio" name="vremate_visible" value="si" <?= (consts::$videos_remate[0]["visible"] === true ? "checked" : "") ?> >Sí</input><input type="radio" name="vremate_visible" value="no" <?= (consts::$videos_remate[0]["visible"] === true ? "" : "checked") ?> >No</input></td>
        <td>Link de youtube: <input type="text" id="vremate_link" value="<?= htmlspecialchars(consts::$videos_remate[0]['url']) ?>" /></td>
    </tr>
</table>
<table class="tabla">
    <tr>
        <td colspan="2">VIDEO MINIREMATES</td>
    </tr>
    <tr>
        <td>Escribir mensaje</td><td><input type="text" id="mremate_mensaje" value="<?= consts::$videos_remate[1]['text'] ?>" /></td>
    </tr>
    <tr>
        <td>Anuncio visible: <input type="radio" name="mremate_visible" value="si" <?= (consts::$videos_remate[1]["visible"] === true ? "checked" : "") ?> >Sí</input><input type="radio" name="mremate_visible" value="no" <?= (consts::$videos_remate[1]["visible"] === true ? "": "checked") ?> >No</input></td>
        <td>Link de youtube: <input type="text" id="mremate_link" value="<?= htmlspecialchars(consts::$videos_remate[1]['url']) ?>" /></td>
    </tr>
    <tr>
        <td colspan="2"> <button id="videos_confirm">Guardar Cambios</button></td>
    </tr>
</table>
<script type="text/javascript">
<!--
$("#videos_confirm").click(function()
{
    $.ajax({
        url: "guarda_videos.php", 
        data: {
        texto: [$("#vremate_mensaje").val(), $("#mremate_mensaje").val()],
        link: [$("#vremate_link").val(), $("#mremate_link").val()],
        visible: [$("input[name='vremate_visible']:checked").val(), $("input[name='mremate_visible']:checked").val()]
        }, 
        type: "post",
        dataType: "json",
        success: function(obj)
        {
            if (obj !== false)
                alert("Datos guardados.");
            else
                alert("Error al intentar guardar los cambios");
        },
        error: function()
        {
            alert("Error al intentar guardar los cambios.");
        }
    });
});
-->
</script>
<iframe name="hif" id="hif" width="0" height="0" style="visibility: hidden"></iframe>
