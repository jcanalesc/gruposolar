<?
    include("header.php");
    // remate_edit_new.php
    // recibe por post id_remate
    // $remate = $_POST['id_remate'];
    if (!esAdmin()) die(consts::$mensajes[9]);
    $r = mysql_query("select * from categorias ".(adminGeneral() ? "" : "where rut_owner = {$_SESSION['rut']}"), dbConn::$cn);
    $cats = array();
    if ($r !== false) while($row = mysql_fetch_assoc($r))
    {
        $cats[] = $row;
    }
    $idremate = $_POST['id_remate'];
    $r = mysql_query("select count(id_lote) from lotes where id_remate = $idremate", dbConn::$cn);
    list($cantlotes) = mysql_fetch_row($r);
    $_SESSION['remate_editado'] = $idremate;
?>
<script type="text/javascript" src="tool-man/core.js"></script>
<script type="text/javascript" src="tool-man/events.js"></script>
<script type="text/javascript" src="tool-man/css.js"></script>
<script type="text/javascript" src="tool-man/coordinates.js"></script>
<script type="text/javascript" src="tool-man/drag.js"></script>
<script type="text/javascript" src="tool-man/dragsort.js"></script>
<script type="text/javascript" src="tool-man/cookies.js"></script>
<script language="JavaScript" src="remate_edit_script.js"></script>
<script type="text/javascript">
<!--
var dragsort = ToolMan.dragsort();

function verticalOnly(item)
{
    item.toolManDragGroup.verticalOnly();
}
(function( $ ){
  $.fn.sortable = function() {
    this.each(function()
    {
        dragsort.makeListSortable(this, verticalOnly);
    });
  };
})( jQuery );

function add(obj)
{
    var listitem = $(obj).parent().remove();
    $("button", listitem).replaceWith("<button onclick='erase(this);'>&lt;</button>");
    listitem.appendTo("#lista2");
    $("ul#lista2").sortable();
}
function erase(obj)
{
    var listitem = $(obj).parent().remove();
    $("button", listitem).replaceWith("<button onclick='add(this);'>&gt;</button>");
    listitem.appendTo("#lista");
}
function print()
{
    $("li").each(function()
    {
        console.log($(this).html());
    });
}
$(function()
{
    $("ul#lista2").sortable();
    $("#controls-apply").click(function()
    {
        var cats_info = new Array();
        $("#lista2 li").each(function()
        {
            cats_info.push($(this).attr("data-catid"));
        });
        $.get("applycat.php", { "idremate": <?= $idremate ?>, "cats": cats_info }, function(obj)
        {
            if (obj == "true")
                alert("Remate construido. \n Puede editar los lotes haciendo clic en 'Ir a la edicion de lotes'.");
            else
                alert("Problemas al intentar aplicar las categorías al remate.");
        });
    });
    $("#controls-clean").click(function()
    {
        $.post("header.php", { func: "borra_acciones_remate", args: <?= $idremate ?> }, function(data)
        {
            if (data == "done")
                alert("Acciones eliminadas.");
            else
                alert("Problemas al intentar eliminar las acciones del remate.");
        });
    });
    $("#controls-pass").click(function()
    {
        ir_a_lotes(<?= $idremate ?>);
    });
});
// --></script>
<style type="text/css">
#editor div
{
    margin: 0px;
    border: 1px solid #000000;
    padding: 0px;
}
#editor
{
    width: 843px;
    height: 310px;
}
#editor .listdiv
{
    width: 300px;
    height: 300px;
    overflow-x: hidden;
    overflow-y: auto;
}
#editor #leftlist
{
    float: left;
}
#editor #rightlist
{
    float: left;
}
#editor #controls
{
    float: right;
    width: 210px;
    height: 300px;
}
#editor #bottomlist
{
    clear: both;
    width: 608px;
    height: 260px;
    overflow: auto;
}
.listdiv li
{
    margin: 4px;
    float: none;
    cursor: move;
}
</style>
<div id="editor">
<div id="leftlist" class="listdiv">
<p>Categorias disponibles:</p>
    <ul id="lista">
    <? 
    foreach ($cats as $c) 
    {
        echo "<li data-catid=\"{$c['id_cat']}\">{$c['nombre']} <button onclick='add(this);'>&gt;</button></li>";
    }
    ?>
    </ul>
</div>
<div id="rightlist" class="listdiv">
<p>Categorias incluidas en el remate:</p>
    <ul id="lista2">
    <?
        if ($cantlotes > 0)
            echo "<li data-catid=\"0\">Lotes actuales del remate <button onclick='erase(this);'>&lt;</button></li>";
    ?>
    </ul>
</div>
<div id="controls">
    <button id="controls-apply">Aplicar cambios</button>
    <button id="controls-clean">Eliminar acciones asociadas</button>
    <button id="controls-pass">Ir a la edicion de lotes</button>
</div>
</div>
