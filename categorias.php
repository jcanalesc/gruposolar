<?
  include("header.php");
  if (!adminGeneral() && !adminSala()) die(consts::$mensajes[9]);

  $r = mysql_query("select * from categorias".(adminGeneral() ? "" : " where rut_owner = {$_SESSION['rut']}"),dbConn::$cn);
  $cats = array();
  if (mysql_num_rows($r) > 0) while($row = mysql_fetch_assoc($r))
    $cats[] = $row;
  mysql_free_result($r);
?>
<style type="text/css">
div.columna
{
    float: left;
    margin: 3px;
    height: 550px;
    overflow-x: hidden;
    overflow-y: auto;
}
div#leftcol
{
    width: 272px;
}
div#rightcol
{
    width: 730px;
}
div#catadd
{
    padding: 5px;
    border: 1px solid #000000;
    margin-bottom: 6px;
}
li.categoria
{
    display: block;
    margin-left: 15px;
    background: #FFFFFF;
    color: #000000;
    list-style-type: disc; 
    border: 1px solid #909090;
}
li.categoria:hover
{
    background: #A6A6A6;
}
li.seleccionado
{
    background: #18EBAA;
}
div#controles
{
    display: none;
    border: 1px solid #818181;
    padding: 3px;
}
div#float-suggestions
{
    display: none;
}
li.sugg
{
    display: block;
    height: 30px;
    font-size: 14px;
    font-weight: normal;
    width: 100%;
}
div.del
{
    width: 24px;
    height: 24px;
    cursor: pointer;
}
</style>
<script type="text/javascript">
var categoriaSel = null;
var catSelList = null;
function dibujaCategoria()
{
    var content = "<tr><td colspan='3'></td><td>ID</td><td>Nombre</td></tr>";
    var obj = catSelList;
    if (obj.length > 0)
    {
        for (i in obj)
        {
                content += "<tr data-index='"+i+"'><td><div class='goup btn'></div></td><td><div class='godown btn'></div></td><td><div class='del'>&nbsp;</div></td><td>"+obj[i].id+"</td><td>"+obj[i].descripcion+"</td></tr>";
        }
    }
    else
        content = "<tr><td>La categoría no tiene productos.</td></tr>";
    $("#prods").html(content);
}
function muestraCategoria()
{
    // se muestra actual en #prods
    $("#controles").show().prev().find("span").html(categoriaSel.html());
    $.getJSON("getcat.php", { "idcat" : categoriaSel.attr("data-idcat") } , function(obj)
    {
        catSelList = obj;
        dibujaCategoria();
    });
}
$(function()
{
    $("#catcreate").click(function()
    {
        var nombre = $("#catname").val();
        if (nombre.length < 1) return;
        $.getJSON("creacat.php", { "name": nombre }, function(obj)
        {
            if (obj == true)
                goto("categorias.php");
            else
                alert("Problemas al crear la categoría.");
        });
    });
    $("li.categoria").click(function()
    {
        categoriaSel = $(this);
        $("li.categoria").removeClass("seleccionado");
        categoriaSel.addClass("seleccionado");
        muestraCategoria();
    });
    $("#addprodcat").click(function()
    {
        var prodid = $("#prodxid").val();
        if ((prodid+"").length > 0 && !isNaN(parseInt(prodid)))
        {
            $.getJSON("modcat.php", { "idprod" : prodid, "idcat" : categoriaSel.attr("data-idcat") } , function(obj)
            {
                if (obj == false)
                {
                    alert("Problemas al intentar agregar el producto a la categoría.");
                }
                else
                {
                    muestraCategoria();
                }
            });
        }
        else
            alert("Ingrese un ID de producto correcto.");
    });
    $("#buscadorproductos").keyup(function()
    {
        var texto = $(this).val();
        if (texto.length < 3) return;
        var content = "";
        $.getJSON("consulta_productos.php", { "text" : texto }, function(obj)
        {
            if (obj.length < 1) return;
            for (i in obj)
            {
                content += "<li class=\"sugg\" data-id=\""+obj[i].id+"\">"+obj[i].descr+"</li>";
            }
            var alto = (obj.length >= 5 ? 250 : obj.length * 50);
            var refbuscador = $("#buscadorproductos");
            $("#float-suggestions").html(content).css({
                position: "absolute",
                top: (refbuscador.position().top-alto)+"px",
                left: refbuscador.position().left+"px",
                height: alto+"px",
                width: refbuscador.width(),
                "overflow-y": "auto",
                "overflow-x": "hidden",
                background: "#393939"
            }).show();
        });
    });
    
    $("li.sugg").live("click",function()
    {
        var idprod = $(this).attr("data-id");
        $("#prodxid").val(idprod);
        $("#float-suggestions").hide().html("");
        $("#addprodcat").click();
    });
    
    $("#erasecat").click(function()
    {
        $.getJSON("modcat.php", { "idcat" : categoriaSel.attr("data-idcat"), "delete": true }, function(obj)
        {
            if (obj == true)
            {
                alert("Categoría \""+categoriaSel.html()+"\"eliminada.");
                goto("categorias.php");
            }
            else
                alert("Problemas al intentar eliminar la categoría seleccionada.");
        });
    });
    
    $("#prods div.del").live("click",function()
    {
        var prodid = parseInt($(this).parent().next().html());
        $.getJSON("modcat.php", { "idcat" : categoriaSel.attr("data-idcat"), "idprod" : prodid, "deleteprod": true }, function(obj)
        {
            if (obj == true)
                muestraCategoria();
            else
                alert("Problemas al intentar eliminar el producto de la categoría.");
        });
    });
    
    $("div.goup").live("click", function()
    {
        var linea = parseInt($(this).parent().parent().attr("data-index"));
        var temp = catSelList[linea];
        catSelList[linea] = catSelList[(linea + catSelList.length - 1) % catSelList.length];
        catSelList[(linea + catSelList.length - 1) % catSelList.length] = temp;
        dibujaCategoria();
    });
    
    $("div.godown").live("click", function()
    {
        var linea = parseInt($(this).parent().parent().attr("data-index"));
        var temp = catSelList[linea];
        catSelList[linea] = catSelList[(linea + 1) % catSelList.length];
        catSelList[(linea + 1) % catSelList.length] = temp;
        dibujaCategoria();
    });
    $("#saveorden").click(function()
    {
        // guarda el objeto con el orden establecido
        var arregloIDs = [];
        for (i in catSelList)
        {
            arregloIDs.push(catSelList[i].id);
        }
        $.getJSON("guardaorden.php", { orden: arregloIDs, idcat: categoriaSel.attr("data-idcat") }, function(obj)
        {
            if(obj === true)
            {
                alert("Orden de categoría guardado.");
            }
            else
                alert("Problemas al guardar.");
        });
    });
});
</script>
<div class="columna" id="leftcol">
<div id="catadd">
<p>Agregar una categoría:</p>
<p>Nombre: <input type="text" id="catname" /></p>
<button id="catcreate">Crear</button>
</div>
<ul>
<? foreach ($cats as $cat) { ?>
<li class="categoria" data-idcat="<?= $cat['id_cat'] ?>"><?= $cat['nombre'] ?></li>
<? } ?>
<?
    if (count($cats) == 0)
        echo "<li class=\"nocategoria\">No hay categorías.</li>";
?>
</ul>
</div>
<div class="columna" id="rightcol">
<p>Categoría: <span>Ninguna</span></p>
<div id="controles">
Agregar un producto a la categoría:
<p>Ingrese ID: <input type="text" id="prodxid" /></p>
<p>Búsqueda: <input type="text" id="buscadorproductos" size="90"/></p>
<p><button id="addprodcat">Agregar</button><button id="erasecat">Borrar esta categoría</button></p>
<p><button id="saveorden">Guardar Orden</button></p>
</div>
<table id="prods" class="tabla">
    <tr><td>Seleccione una categoría.</td></tr>
</table>
</div>
<div id="float-suggestions"></div>
