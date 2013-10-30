<?
   include("header.php");
   if (!adminGeneral()) die(consts::$mensajes[9]); 
   $mensaje = "";
   $arreglo = array();
   $arreglo["empresas"] = array("image/jpeg", "image/png", "image/gif", "image/jpg", "image/pjpeg", "image/pgif", "image/ppng", "image/pjpg");
   $arreglo["logo"] = $arreglo["empresas"];
   $arreglo["bases"] = $arreglo["pdfbases"] = $arreglo["docregistro"] = array("application/pdf","application/x-pdf", "application/msword");
   if (count($_FILES) > 0)
   {
        foreach($_FILES as $name => $ff)
        {
            if ($ff['size'] == 0) continue;
            if ($ff['error'] > 0)
            {
                $mensaje .= "Error al intentar subir el archivo. ($name).\\n";
                continue;
            }
            $type = $ff['type'];
            if (!in_array($type, $arreglo[$name]))
            {
                $mensaje .= "Tipo de archivo incorrecto ($name).\\n";
                continue;
            }
            if ($ff['size'] > 2*1024*1024 || $ff['size'] <= 0)
            {
                $mensaje .= "Tamaño del archivo no permitido. ($name). \\n";
                continue;
            }
            $filename = $ff['name'];
            $location = $ff['tmp_name'];
            //rematelog($location);
            //rematelog("archivos/$filename");
            if (!move_uploaded_file($location, "archivos/$filename"))
            {
                $mensaje .= "Problemas al subir el archivo. ($name).\\n";
                continue;
            }

            consts::${$name} = "archivos/$filename";
            consts::save_config();
        }
   }
   if (count($_POST) > 0)
   {
      foreach($_POST as $key => $val)
      {
         if (isset(consts::$$key) && consts::$$key != $val && $key != "bases")
         {
            // guarda
            if (substr($key, 0, strlen("tiempo")) == "tiempo" && !is_numeric($val))
            {
               $mensaje .= "Variable '$key' fue ingresada con un formato inválido. Debe ser numérica.\\n";
               continue;
            }
            if (strlen($val) > 0)
               consts::$$key = $val;
            else
               $mensaje .= "Variable '$key' esta vacía. Asigne un valor.\\n";
         }
         else if ($key == "basestexto")
         {
             //$lineas = implode("\n", explode("<br />",  $val));
             file_put_contents("bases.txt", $val);
         }
      }
      if ($mensaje == "")
      {
         consts::save_config();
         $mensaje = "Variables guardadas correctamente.";
      }
      echo <<<EOF
<script language="javascript">
<!--
parent.alert("$mensaje");
//-->
</script>
EOF;
      exit();
   }
?>
<script type="text/javascript">
tinyMCE.init({
    mode: "textareas",
    theme: "advanced",
    theme_advanced_toolbar_location: "top",
    theme_advanced_resizing: true,
    theme_advanced_buttons1: "forecolor,bold,italic,underline,strikethrough,formatselect,fontselect,fontsizeselect,|,justifyleft,justifycenter,justifyright,justifyfull",
    editor_deselector: "noeditor"
});
</script>
<h3>Configuraci&oacute;n del sistema</h3>
<p>Para guardar los cambios hechos a cualquiera de las variables, presione el bot&oacute;n "Guardar Cambios".</p>
<form id="conf_form" target="hid" method="post" action="config.php" enctype="multipart/form-data">
<table class="tabla">
<tr><td>Variable</td><td>Descripci&oacute;n</td><td>Valor</td></tr>
<?
   foreach(consts::$modificables as $variable)
   {
      if (substr($variable, 0, strlen("cuerpo")) != "cuerpo" &&
          !in_array($variable, array("bases", "empresas","marquesina","logo","pdfbases","video1","video2", "docregistro","hinicio_rut","htermino_rut","h_vacios_minimos")))
         echo "<tr><td>$variable</td><td width=\"460\">".switches::tra($variable)."</td><td><input type=\"text\" name=\"$variable\" size=\"30\" value=\"".consts::$$variable."\"/></td></tr>";
   }
?>
<!--
<tr>
<td rowspan="2" width="230">Para los cuerpos de mensajes, use '%rut%', '%nombre%' o '%password%' para fijar los lugares donde se ver&aacute; escrita esa variable dentro del email.</td>
<td colspan="2"><p><?= switches::tra('cuerpo_email_recclaveb') ?></p>
<textarea cols="65" rows="5" name="cuerpo_email_recclaveb"><?= htmlspecialchars(consts::$cuerpo_email_recclaveb) ?></textarea>
</td>
</tr>
<tr>
<td colspan="2"><p><?= switches::tra('cuerpo_email_registrob') ?></p>
<textarea cols="65" rows="5" name="cuerpo_email_registrob" ><?= htmlspecialchars(consts::$cuerpo_email_registrob) ?></textarea>
</td>
-->
<!-- Filas agregadas -->
<tr>
    <td>Cambiar foto de empresas:</td><td colspan="2"><input type="file" name="empresas" /></td>
</tr>
<tr>
    <td>Cambiar documento de bases y condiciones de miniremates:</td><td colspan="2"><input type="file" name="bases" /></td>
</tr>
<tr>
    <td>Cambiar logo principal:</td><td colspan="2"><input type="file" name="logo" /></td>
</tr>
<tr>
    <td>Cambiar documento de bases generales:</td><td colspan="2"><input type="file" name="pdfbases" /></td>
</tr>
<tr>
  <td>Documento de registro, con bases y condiciones</td><td colspan="2"><input type="file" name="docregistro" /></td>
</tr>
<tr>
    <td>Marquesina :</td><td colspan="2"><textarea name="marquesina" rows="10" cols="65"><?= consts::$marquesina ?></textarea></td>
</tr>
<tr>
    <td>Bases :</td><td colspan="2"><textarea name="basestexto" class="noeditor" cols="100" rows="8">
        <?= file_get_contents("bases.txt"); ?>
    </textarea></td>
</tr>
<!-- End -->
</tr>
<tr><td colspan="3"><input type="submit" class="submitea" value="Guardar Cambios" /></td></tr>
</table>
</form>
<iframe name="hid" id="hid" style="visibility: hidden; width:0px; height:0px;"></iframe>
