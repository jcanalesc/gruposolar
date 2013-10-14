<?
include("header.php");

?>
<script language="javascript">

function reenvia(src, pagina)
{
   $.get(pagina, function(data)
   {
      $(src).parent().html(data);
   });
}
function changepass(formu)
{
   var datos = [$(formu).find("#claveo").val(),
                $(formu).find("#clave1").val(),
                $(formu).find("#clave2").val(),
                "<?= $_SESSION['rut'] ?>"];
   if (datos[1] != datos[2])
   {
      alert("Contrase&ntilde;as no coinciden.");
      $(formu).find("#clave1").select().focus();
      return;
   }
   if (confirm("Está seguro que desea cambiar su contraseña?"))
   {
      $.post("header.php", "func="+escape("changepass")+"&args="+escape(datos.join(";")), function(data)
      {
         if (data != "done")
         {
            alert("Problemas al cambiar la clave:\n"+data);
         }
         else
         {
            alert("Clave cambiada exitosamente.");
            $(formu).find("input[type='password']").val("");
         }
      });
   }
}
</script>
<?
// recibo rut por get, bien simple.
// Obtengo info
$campos = array("nombres", "apellidop", "apellidom", "email", "telefono", "direccion","nacionalidad", "telefono2",  "region", "f_rut", "f_nombre", "f_giro", "f_direccion", "f_region", "f_telefono");
foreach($campos as &$val)
   $val = "users.".$val;

$query = "select users.rut," . implode("," , $campos) . ", comunas.nombre as comuna from users, comunas where comunas.codigo = users.comuna and users.rut = " . mysql_real_escape_string($_SESSION['rut']); 

$res = mysql_query($query, dbConn::$cn) or dbConn::dbError($query);

$campos = mysql_fetch_assoc($res);
mysql_free_result($res);
echo "<strong><p>DATOS DE USUARIO</p></strong>\n";
echo "<table class=\"tabla\" style=\"float: left; margin-right: 15px;\">\n";
foreach($campos as $key => $val)
{
   if (substr($key, 0, 2) == "f_") continue;
   echo "<tr><td>".switches::tra($key)."</td><td>".htmlentities(utf8_decode($val))."</td></tr>\n";
}
echo "</table>\n";
echo "<table class=\"tabla\">\n";
echo "<tr><td colspan=\"2\"><strong>Datos de facturaci&oacute;n:</strong></td></tr>\n";
foreach($campos as $k => $v)
{
   if (substr($k, 0, 2) == "f_")
   {
      echo "<tr><td>".switches::tra($k)."</td><td>".htmlentities(utf8_decode($v))."</td></tr>\n";
   }
}
echo "</table>\n";
?>
<h5><strong>Si desea cambiar su contrase&ntilde;a, ingr&eacute;sela en las casillas a continuaci&oacute;n:</strong></h5>
<form id="cambioclave" onsubmit="changepass(this); return false;">
<table class="tabla">
<tr><td><label for="claveo">Contrase&ntilde;a antigua:</label></td><td><input type="password" name="claveo" id="claveo" /></td></tr>
<tr><td><label for="clave1">Nueva contrase&ntilde;a: </label></td><td><input type="password" name="clave1" id="clave1" /></td></tr>
<tr><td><label for="clave1">Repita contrase&ntilde;a: </label></td><td><input type="password" name="clave2" id="clave2" /></td></tr>
<tr><td colspan="2"><input type="submit" class="submitea" value="Cambiar contrase&ntilde;a"/></td></tr>
</table>
</form>
<?
echo "<p>Para cualquier modificaci&oacute;n de estos datos, comun&iacute;quese a <a href='mailto:".consts::$from_email."'>".consts::$from_email."</a></p>";
?>
