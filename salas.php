<?
    // salas.php, crear y editar salas. Ver que remates se han hecho de qué personas, y ver los dueños historicos y el dueño actual.
    require_once("header.php");
    ini();
    if (!adminGeneral()) die(consts::$mensajes[9]);
    $r = mysql_query("select * from salas", dbConn::$cn);
    $salas = array();
    if ($r !== false && mysql_num_rows($r) > 0) while ($row = mysql_fetch_assoc($r))
        $salas[] = $row;
?>
<style type="text/css">
table.sala
{
    border: 2px solid #676767;
    margin: 6px;
}
table.sala td
{
    border: 1px solid #717171;
}
span.changeowner
{
    font-weight: bold;
    text-decoration: underline;
    cursor: pointer;
}
#nuevasala
{
    border: 2px solid #848484;
    width: 350px;
}
.change-select, #float-suggestions
{
    display: none;
}
li.sugg
{
    display: block;
    height: 10px;
    font-size: 14px;
    font-weight: normal;
    width: 100%;
}
#float-suggestions p
{
    color: #FFFFFF;
    font-size: 14px;
    font-weight: normal;
    margin: 0px;
    font-weight: bold;
}
</style>
<script type="text/javascript">
<!--
var users = null;
var floating_over = null;
var dblclicked = null;
$(function()
{
    $.getJSON("userlist.php", function(obj)
    {
        users = obj;
    });
    $("input.change-select").keyup(function()
    {
        var text = $(this).val();
        var textlen = text.length;
        if (textlen < 2)
        {
            $("#float-suggestions").hide();
            return;
        }
        var suggestions = "";
        var suggcount = 0;
        for (i in users)
        {
            var chopped_user = users[i].substr(0,textlen);
            if (chopped_user == text)
            {
                suggcount++;
                suggestions += "<li class=\"sugg\">"+users[i]+"</li>";
            }
        }
        // show suggestions
        var suggestions_html = "<ul>"+suggestions+"</ul>";
        if (suggestions == "")
            suggestions_html = "<p>No hay coincidencias.</p>";
        var altura = (suggcount < 3 ? (suggcount*30) : 100);
        $("#float-suggestions").css(
        {
            position: "absolute",
            top: ($(this).position().top-altura)+"px",
            left: $(this).position().left+"px",
            height: altura+"px",
            width: $(this).width(),
            "overflow-y": "auto",
            "overflow-x": "hidden",
            background: "#393939"
        }).html(suggestions_html).show();
        floating_over = $(this).parent();
    });
    $("button.cancel").click(function()
    {
        $("#float-suggestions").hide();
        $(this).parent().parent().find(".change-select").hide(100);
    });
    $(".changeowner").click(function()
    {
        $(".change-select").hide();
        $("#float-suggestions").hide();
        $(this).find(".change-select").show();
    });
    $("li.sugg").live("click",function()
    {
        var selection = $(this).html();
        if (floating_over != null)
        {
            
            // guardar cambio
            $.getJSON("modsala.php", { "nsala": floating_over.attr("data-roomid"), "own": selection }, function(data)
            {
                if (data == true)
                {
                    floating_over.find(".owner").html(selection);
                    floating_over.find(".change-select").hide(100);
                }
                else
                {
                    alert("Problemas al intentar guardar el cambio.");
                }
                floating_over = null;
            });
        }
        $("#float-suggestions").hide();
        
    });
    function nuevasala()
    {
        var nombre = $("#nombresala").val();
        if (nombre.length <= 0) return;
        $.get("creasala.php", { "name" : nombre } , function(data)
        {
            goto("salas.php");
        });
    }
    $("#crearsala").click(nuevasala);
    $("span.roomname").css("cursor", "pointer").click(function()
    {
        dblclicked = $(this);
        $(this).next().show();
    });
    $("button.changename").click(function()
    {
        var nuevonombre = $(this).prev().val();
        $.getJSON("modsala.php", { "nsala": dblclicked.attr("data-roomid"), "name": nuevonombre }, function(obj)
        {
            if (obj == true)
            {
                dblclicked.html(nuevonombre);
                dblclicked.next().hide();
            }
            else
            {
                alert("Problemas al intentar cambiar el nombre de la sala.");
            }
        });
    });
});
//-->
</script>
<div id="nuevasala">
<p>Crear nueva sala:</p>
<table>
<tr><td>Nombre: </td><td><input type="text" id="nombresala" /></td><td><button id="crearsala">Crear</button></td></tr>
</table>
</div>
<p>Mostrando salas existentes:</p>
<? foreach ($salas as $sala) { ?>
<table class="sala">
    <tr>
        <td>Sala N&deg;:</td><td><?= $sala['id_sala'] ?></td>
    </tr>
    <tr><td>Nombre:</td><td><span data-roomid="<?= $sala['id_sala']?>" class="roomname"><?= $sala['nombre'] ?></span><span class="change-select"><input type="text" value="<?= $sala['nombre']?>"/><button class="changename">Aplicar</button><button class="cancel">Cancelar</button></span></td></tr>
    <tr><td>Due&ntilde;o actual (clic para cambiar):</td><td><span class="changeowner" data-roomid="<?= $sala['id_sala'] ?>"><span class="owner"><?= $sala['rut_owner'] ?></span><input type="text" class="change-select" /><button class="change-select cancel">Cancelar</button></span></td></tr>
</table>
<div id="float-suggestions"></div>
<? } ?>
