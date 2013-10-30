<iframe id="hiddenf" name="hiddenf" style="visibility: hidden; width: 0px; height: 0px;"></iframe>
<?
   include("header.php");
   if (isset($_POST["tipo"]) && isset($_POST["id"]) && count($_POST) == 2 && esAdmin())
   {
      $_SESSION['tipo'] = $_POST['tipo'].";update";
      // Se modifica el dato id de la tabla tipo
      $map = array(
                    "users" => array("rut", "usuario"), 
                    "productos" => array("id_producto", "producto"),
                    "remates" => array("id_remate", "remate")
                  );
      if (!in_array($_POST["tipo"], array_keys(consts::$data[8]))) die (consts::$mensajes[8]);
      $pkey = mysql_real_escape_string($map[$_POST["tipo"]][0]);
      $query = "select * from ".mysql_real_escape_string($_POST['tipo'])." where {$pkey} = ".mysql_real_escape_string($_POST['id']);
      $res = mysql_query($query, dbConn::$cn);
      if ($res === false || mysql_num_rows($res) < 1)
      {
         dbConn::dbError($query);
         exit();
      }
      $flag = false;
      $row = mysql_fetch_assoc($res);
      // setear la wea del dueño de un remate
        
      echo "<p> Modificando al ".$map[$_POST['tipo']][1]. " de ".switches::tra($pkey)." ".$row[$pkey].($pkey == "rut" ? "-".$row['dv'] : "")."</p>\n";
      // keep going on
      echo "<form id=\"mod_form\" onsubmit=\"do_edit(); return false;\">\n";
      echo "<table class=\"tabla\">\n";
      $disabled_fields = array("fecha_inscripcion", "fecha_ultimavisita", "lote_actual","f_dv", "dv", "logged", "pseudopass","tiempo_pausa", "ultimo_orden", "rut_owner", "banner_size", "tipo", "finalizado");
      $boolean_fields = array("logged", "activated", "banned", "en_curso", "garantia", "inscrito", "publico", "disabled", "finalizado","afecto_a_iva", "requiere_auth", "autorizado_rsm", "iva_comision");
      if (!adminGeneral())
      {
        $disabled_fields[] = "factor";
        $disabled_fields[] = "id_sala";
      }
      $flagbanner = false;
      foreach($row as $key => $value)
      {
			if ($key == "f_rut")
			echo "<tr><td colspan=\"2\"><strong>Datos de facturaci&oacute;n:</strong></td></tr>";
         
         if ($key == "banner")
         {
             $flagbanner = true;
             continue;
         }
         if ($key == "banner_size" && adminGeneral())
        {
            $inputs = "";
            for ($i = 1; $i <= 3; $i++)
            {
                $ch = ($value == $i ? 'checked="checked"' : "");
                $inputs .= "<input type=\"radio\" name=\"fbanner_size\" value=\"$i\" $ch/>";
            }
            echo "<tr><td>Tamaño del banner</td><td>Chico{$inputs}Grande</td></tr>";
            continue;
        }
        if ($key == "procedimiento") continue;
        if ($key == "tipo")
        {
            echo "<tr><td>Tipo de remate:</td><td>$value</td></tr>";
        }
        if (in_array($key, $disabled_fields)) continue;
         if (substr($key, 0, 4) != "foto")
         {
            if ($row['tipo'] == "Presencial" && in_array($key, array("tipo_puja", "valor_puja", "duracion_lote")))
                continue;
            $path = preg_replace('/(.*)\/([^\/]*)/', '$1', $_SERVER['REQUEST_URI']);
               echo "<tr><td>".switches::tra($key)."</td><td>";
               if (in_array($key, $boolean_fields))
               {
                  $checked = ($value == "1" ? "checked=\"checked\"" : "");
                  $nchecked = ($value == "0" ? "checked=\"checked\"" : "");
                  echo "<input type=\"radio\" name=\"f{$key}\" $checked value=\"1\">Si</input>";
                  echo "<input type=\"radio\" name=\"f{$key}\" $nchecked value=\"0\">No</input>";
               }
               else if ($key == "tipo_puja")
               {
                  $selected = ($value == "Fijo" ? "selected=\"selected\"" : "");
                  $selected2 = ($value == "Porcentual" ? "selected=\"selected\"" : "");
                  $selected3 = ($value == "Sin Minimo" ? "selected=\"selected\"" : "");
                  echo "<select name=\"f{$key}\">",
                       "<option value=\"Fijo\" $selected >Fijo</option>",
                       "<option value=\"Porcentual\" $selected2 >Porcentual</option>",
                       "<option value=\"Sin Minimo\" $selected3 >Sin Minimo</option>",
                       "</select>";
               }
               else if ($key == "tipo_productos")
               {
                   $esnuevo = ($value == "Nuevos" ? true : false);
                   $esusado = !$esnuevo;
                   echo "<select name=\"ftipo_productos\">";
                   echo "<option value=\"Nuevos\" $esnuevo>Nuevos</option>";
                   echo "<option value=\"Usados\" $esusado>Usados</option>";
                   echo "</select>";
               }
               else if ($key == "datos_bancarios" || $key == "causal")
               {
                   echo "<textarea name=\"f$key\" rows=\"6\">$value</textarea>";
               }
               else if ($key == "texto_usuario_noauth")
               {
                  echo "<textarea name=\"ftexto_usuario_noauth\">$value</textarea>";
               }
               else if ($key == "descripcion")
               {
                  echo "<textarea id=\"f{$key}\" name=\"f{$key}\">{$value}</textarea>";
               }
               else if (!in_array($key, array("comuna", "region", "f_comuna", "f_region")))
               {
                  if ($key == "rut" || $key == "f_rut")
                  {
                      echo "<div>";
                  }
                  echo "<input id=\"f{$key}\" name=\"f{$key}\" ".($key == "password" ? "type=\"password\" " : "type=\"text\" value=\"{$value}\" ").($key == $map[$_POST['tipo']][0] ? " disabled=\"disabled\"" : "")." />";
                  if ($key == "rut" || $key == "f_rut")
                  {
							$pre = (substr($key, 0, 2) == "f_" ? "f_" : "");
                     echo "&nbsp;<input id=\"f{$pre}dv\" name=\"f{$pre}dv\" type=\"text\" value=\"".$row[($pre.'dv')]."\" ".($pre == "" ? "disabled=\"disabled\"" : "")." size=\"1\" /></div>";
                  }
                  
               }
               else if ($key == "region" || $key == "f_region")
               {
                   rematelog($path);
				   $pre = (substr($key, 0, 2) == "f_" ? "f_" : "");
                  echo "<select id=\"f{$pre}region\" name=\"f{$pre}region\" onchange=\"act_comunas(this);\">";
                  echo post_request("localhost/regiones.php", array('selected' => $value));
                  echo "</select>";
               }
               else if ($key == "comuna" || $key == "f_comuna")
               {
                   rematelog($path);
				  $pre = (substr($key, 0, 2) == "f_" ? "f_" : "");
                  echo "<select id=\"f{$pre}comuna\" name=\"f{$pre}comuna\">\n";
                  // rematelog("Region: ".utf8_decode($row[$pre."region"]));
				  echo post_request("localhost/regiones.php", array('selected' => $value, 'region' => $row[$pre."region"]));
                  echo "</select>";
               }
               
               echo "</td></tr>\n";
         }
         else if (substr($key, 0, 4) == "foto")
         {
            $flag = true;
            // echo "<tr><td colspan=\"3\">M&oacute;dulo para subir fotos en construcci&oacute;n.</td></tr>";
         }
         
         if ($key == "password")
            echo "<tr><td>Confirme password</td><td><input type=\"password\" id=\"fpassword2\" /></td></tr>\n";
      }
      if ($_POST['tipo'] == "remates")
      {
          $campoOwner = $row['rut_owner'];
          if (adminGeneral())
            $campoOwner = "<input type=\"text\" name=\"frut_owner\" id=\"frut_owner\" value=\"$campoOwner\" />";
          echo "<tr><td>Dueño del remate:</td><td>".$campoOwner."</td></tr>";
      }
      echo "</table><input class=\"submitea\"  type=\"submit\" value=\"Enviar\" /></form>";
      if ($flag)
      {
         echo "<table>\n";
         foreach(array("foto1" => "", "foto2" => "", "foto3" => "", "foto4" => "") as $key => $value)
         {
            echo "<tr><td>".switches::tra($key)."</td>";
            $num = substr($key, 4, 1);
            echo "<td><form id=\"frm{$num}\" action=\"photo_uploader.php\" enctype=\"multipart/form-data\" method=\"post\" target=\"hiddenf\" >\n";
            echo "<input type=\"hidden\" name=\"num\" value=\"{$num}\" />\n";
            echo "<input type=\"hidden\" name=\"idprod\" value=\"{$row['id_producto']}\" />\n";
            echo "<input id=\"f{$key}\" name=\"file\" type=\"file\" size=\"20\" value=\"{$value}\"/><input class=\"submitea\" type=\"submit\" value=\"Subir\" /></form></td>";
            echo "</tr>";
         }
         echo "</table><br /><span id=\"subefoto\"></span>\n";
      }
      if ($flagbanner)
      {
          $switchedkey = switches::tra("banner");
          echo <<<END
<form id="bannerupload" action="bannerupload.php" enctype="multipart/form-data" method="post" target="hiddenf">
<input type="hidden" name="idremate" value="{$row['id_remate']}" />
<table>
<tr><td>Banner:</td><td>
<input type="file" name="banner" value="" /></td></tr>
<tr><td>Procedimiento:</td>
<td><input type="file" name="procedimiento" value="" /></td></tr>
<tr><td><input type="submit" value="Actualizar archivos" /></td></tr>
</table>
</form>
END;
      }
      if ($_POST['tipo'] == consts::$data[8][2])
      {
        if ($row['tipo'] != "Presencial")
        {
            echo "<button onclick='editar_lotes({$row['id_remate']});'>Editar lotes asociados</button>";
        }
        else
        {
            echo "<button onclick='editar_galeria({$row['id_remate']});'>Editar galeria de fotos</button>";
        }
      }
   }
   else die (consts::$mensajes[8])
?>
