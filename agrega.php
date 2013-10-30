<?php
   include("header.php");
   if (isset($_POST['tipo']) && count($_POST) == 1)
   {
       if (!adminGeneral() && $_POST['tipo'] == "remates") die("<p>No tiene permitido crear nuevos remates.</p>");
      $_SESSION['tipo'] = $_POST['tipo'].";insert"; // experimental
      // Obtener columnas a llenar
      $res = mysql_query("show columns from {$_POST['tipo']};", dbConn::$cn);
      if ($res !== false && mysql_num_rows($res) > 0)
      {
         echo "<p>Formulario de registro</p>\n<form id=\"add_form\" onsubmit=\"do_add(); return false;\"><table class=\"tabla\">";
         $colarray = array();
         while($col = mysql_fetch_assoc($res)) $colarray[] = $col['Field'];
         $disabled_fields = array("fecha_inscripcion", "fecha_ultimavisita", "lote_actual", "pseudopass", "inscrito", "en_curso", "tiempo_pausa", "ultimo_orden", "id_sala", "banner_size", "banner", "finalizado", "rut_owner");
         $boolean_fields = array("logged", "activated", "banned", "en_curso", "garantia", "publico", "disabled", "afecto_a_iva", "autorizado_rsm", "requiere_auth", "iva_comision");
         
         $flag = false;
         $map = array(
                    "users" => array("rut", "usuario"), 
                    "productos" => array("id_producto", "producto"),
                    "remates" => array("id_remate", "remate")
                  );
         foreach($colarray as $key)
         {
            $value = "";
        if ($key == "banner_size" && adminGeneral())
        {
            echo '<tr><td>Tamaño del banner</td><td>Chico<input type="radio" name="fbanner_size" value="1" /><input type="radio" name="fbanner_size" value="2" /><input type="radio" name="fbanner_size" value="3" checked="checked"/>Grande</td></tr>';
            continue;
        }
         if (in_array($key, $disabled_fields)) continue;
         
         if (substr($key, 0, 4) != "foto")
         {
               if ($key == $map[$_POST['tipo']][0] && $_POST['tipo'] != "users") continue;
               echo "<tr><td>".switches::tra($key)."</td><td>";
               if (in_array($key, $boolean_fields))
               {
                  $value = "1";
                  $checked = ($value == "1" ? "checked=\"checked\"" : "");
                  $nchecked = ($value == "0" ? "checked=\"checked\"" : "");
                  echo "<input type=\"radio\" name=\"f{$key}\" $checked value=\"1\">Si</input>";
                  echo "<input type=\"radio\" name=\"f{$key}\" $nchecked value=\"0\">No</input>";
               }
               else if ($key == "datos_bancarios")
               {
                   echo "<textarea name=\"fdatos_bancarios\"></textarea>";
               }
               else if ($key == "tipo_productos")
               {
                   echo "<select name=\"ftipo_productos\">";
                   echo "<option value=\"Nuevos\" selected=\"selected\">Nuevos</option>";
                   echo "<option value=\"Usados\">Usados</option>";
                   echo "</select>";
               }
               else if ($key == "tipo")
               {
                   echo "<select name=\"ftipo\" onchange=\"revisatipo(this);\">";
                   echo "<option value=\"Online\" selected=\"selected\">Online</option>";
                   echo "<option value=\"Presencial\">Presencial</option>";
                   echo "</select>";
               }
               else if ($key == "ciudad")
               {
                   echo "<select name=\"fciudad\">";
                   // aca va algun generador
                   include("ciudades.html");   
                   echo "</select>";
               }
               else if ($key == "texto_usuario_noauth")
               {
                  echo "<textarea name=\"ftexto_usuario_noauth\"></textarea>";
               }
               else if ($key == "tipo_puja")
               {
                  $value = "Fijo";
                  $selected = ($value == "Fijo" ? "selected=\"selected\"" : "");
                  $selected2 = ($value == "Porcentual" ? "selected=\"selected\"" : "");
                  $selected3 = ($value == "Sin Minimo" ? "selected=\"selected\"" : "");
                  echo "<select name=\"f{$key}\">",
                        "<option value=\"Fijo\" $selected >Fijo</option>",
                        "<option value=\"Porcentual\" $selected2 >Porcentual</option>",
                        "<option value=\"Sin Minimo\" $selected3 >Sin Minimo</option>",
                        "</select>";
               }
               else if ($key == "descripcion")
               {
                  echo "<textarea id=\"f{$key}\" name=\"f{$key}\">{$value}</textarea>";
               }
               else if (!in_array($key, array("comuna", "region")))
               {
                  echo "<input id=\"f{$key}\" name=\"f{$key}\" ".($key == "password" ? "type=\"password\" " : "type=\"text\" value=\"{$value}\"")." />";
               }
               else if ($key == "region")
               {
                  echo "<select id=\"fregion\" name=\"fregion\" onchange=\"act_comunas(this);\">";
                  include ("regiones.php");
                  echo "</select>";
               }
               else if ($key == "comuna")
               {
                  echo "<select id=\"fcomuna\" name=\"fcomuna\" disabled=\"disabled\">\n";
                  echo "<option value= \"-\" selected=\"selected\">Seleccione regi&oacute;n</option>\n";
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
         echo "</table>\n";
         echo "<input class=\"submitea\" type=\"submit\" value=\"Enviar\" />\n";
         echo "</form>";
      }
      else
      {
         echo consts::$mensajes[8].": ".$_POST['tipo'];
      }
   }
   else die(consts::$mensajes[8]);
?>
