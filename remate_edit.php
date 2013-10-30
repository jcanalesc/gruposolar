<?
   include("header.php");
   if (!esAdmin())
      die(consts::$mensajes[9]);
   if (!isset($_POST['id_remate']) || count($_POST) != 1)
      die(consts::$mensajes[8]);
   $id_remate = $_POST['id_remate'];
   
   $_SESSION['remate_editado'] = $id_remate;
/*
    Interfaz para editar lotes del remate
    Entradas parten siendo un javascript.
    Arreglo: arrlotes[orden] = {'id':id, 'nombre':nombre, 'cantidad':cantidad}
    Inicialmente todos los productos se insertan al arreglo.
    getAllXML($type) devuelve:
    <$type>
    * <elem>
    *    <campo>valor</campo>
    *    <campo>valor</campo>
    *    ...
    * </elem>
    * <elem>
    *    ....
    * </elem>
    </$type>
*/
?>
<script language="JavaScript" src="remate_edit_script.js"></script>
<p id="enc">Editando lotes para el remate id. <?= $id_remate ?>:</p>
<div class="contiene">
<table class="tabla" id="lotes">
</table>
</div>
<button onclick="submitLote();">Hecho</button>
<button onclick="multiDel();">Eliminar selecci&oacute;n</button>
<button onclick="getXMLData(false);">Recargar productos</button>
<button onclick="eliminaAcciones();">Eliminar acciones del remate</button>
<button onclick="editar_lotes(<?= $id_remate ?>);">Volver a categorias</button>
<script type="text/javascript">
$(function()
{
    getXMLData(true);
});
</script>
