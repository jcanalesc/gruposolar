<?php
// writer.php, tiene que ser seguro!! xD
include("header.php");
if (esAdmin() && count($_POST) > 0)
{
   if (isset($_POST['felements']))
   {
      $_SESSION['tipo'] = $_POST['ftipo'].";delete";
   }
   $map = array(
                "users" => array("rut", "usuario"), 
                "productos" => array("id_producto", "producto"),
                "remates" => array("id_remate", "remate")
               );
   $keys = array_keys($_POST);
   foreach($keys as $key)
      if ($key[0] !== 'f')
      {
         echo consts::$mensajes[8]."({$key})";
         exit();
      }
   
   // echo $_SESSION['tipo'];
   
   $ar = explode(";", $_SESSION['tipo']);
   $tabla = $ar[0];
   $tipo = $ar[1];
   // echo $tabla, " ", $tipo;
   if (!in_array($tabla, consts::$data[8]) || !in_array($tipo, consts::$data[10]))
      die(consts::$mensajes[8]);
   // para debug ------------
   /*
   if (esAdmin())
   {
      echo "<p>Bien. Desplegando valores para '{$tipo}' a la tabla {$tabla}:<p>";
      foreach($_POST as $key => $value)
         echo "<p>{$key} : {$value}</p>";
   }
   * */
   // fin para debug --------
   //sanitizacion de datos -------------
   $ok_data = true;
   function invalid($key, $val, $error, &$ok)
   {
      echo consts::$mensajes[12].": ({$key} = {$val}, {$error})<br />\n";
      $ok = false;
      rematelog("Formato invalido: string:($val), key:($key) contra el string de validacion (".consts::$allowed_chars.")");
   }
   if (isset($_POST['ffecha']) && isset($_POST['fhora']) && $tipo == consts::$data[10][0])
   {
      $fecha = strtotime($_POST['ffecha']." ".$_POST['fhora']);
      /*
      if ($fecha === false || $fecha - time() < 15*60)
      {
         echo "Fecha invalida, ingrese una fecha posterior en al menos  15 minutos de la hora actual.";
         $ok_data = false;
      }
      * */
   }
   if ($tipo != consts::$data[10][2]) foreach($_POST as $key => &$val)
   {
      if (strlen($val) == 0) continue;
      $rkey = substr($key, 1, strlen($key) - 1);
      if (in_array($rkey, consts::$data[9])) // es numerico
      {
         if (strlen($val) != strspn($val, "0123456789"))
            invalid($rkey, $val, "numerico", $ok_data);
      }
      else if ($rkey == "rut")
      {
         if (strlen($val) != strspn($val, "0123456789.") || !validaRut($val, $_POST['fdv']))
         invalid($rkey, $val, "Rut invalido ($val, {$_POST['fdv']})", $ok_data);
      }      
      else if ($rkey == "password")
      {
         if(strlen($val) < 4 || strlen($val) != strspn($val, utf8_decode(consts::$allowed_password_chars)))
            invalid($rkey, $val, "Contrase&ntilde;a", $ok_data);
      }
      else if ($rkey == "dv")
      {
         if (strlen($val) != 1 || strspn($val, "0123456789kK") != strlen($val))
            invalid($krey, $val, "debe ser 1 digito o K", $ok_data);
      }
      else if ($rkey =="telefono" || $rkey == "telefono2")
      {
         if (strlen($val) != strspn($val, "0123456789-+")) // que sea un string de telefono pueh
            invalid($krey, $val, "formato invalido", $ok_data);
      }
      else if ($rkey == "email")
      {
         if (!email_valido($val))
            invalid($rkey, $val, "Email invalido", $ok_data);
      }
      else if ($rkey == "valor_puja") // punto flotante
      {
         if (strlen($val) != strspn($val, "0123456789,") && strlen($val) != strspn($val, "0123456789."))
            invalid($rkey, $val, "El valor de la puja debe ser un número entero o decimal.", $ok_data);
         if (strstr($val, ",") !== false)
            $val = str_replace(",", ".", $val);
      }
      else if (false) // es texto
      {
         
         if (strlen($val) != strspn($val, consts::$allowed_chars) )
            invalid($rkey, $val, "texto", $ok_data);
      }
      
   }
   if (!$ok_data) die();
   // fin sanitizacion --------------
   // Agregar a la base de datos
   
   if ($tipo === consts::$data[10][0])
   {
      if ($tabla == consts::$data[8][2])
      {
          $_POST['fid_sala'] = consts::$SALA['id_sala'];
          $keys[] = "fid_sala";
      }
      if ($tabla == "productos" || $tabla == "remates")
      {
          $_POST['frut_owner'] = $_SESSION['rut'];
          $keys[] = "frut_owner";
      }
      foreach($keys as $kname => &$val) 
      { 
         $val = mysql_real_escape_string(substr($val, 1, strlen($val) - 1)); 
      }
      $campos = implode(",",$keys);
      $tmp = consts::$data[9];
      foreach($_POST as $kname => &$val)
      { 
         $val = mysql_real_escape_string($val); 
         if (!in_array($kname, $tmp)) // si no es numerico agrego comillas
         {
            $val = "'".($val)."'";
            if ($kname == "fpassword")
               $val = "MD5(".$val.")";
         }
      }
      $datos = implode(",",$_POST);
      $res = mysql_query(($query = "insert into {$tabla} ({$campos}) values ({$datos})"), dbConn::$cn);
      if ($res)
         echo "<p>".consts::$mensajes[10]."</p>";
      else
      {
         dbConn::dbError($query);
      }
   }
   else if ($tipo === consts::$data[10][1])
   {
      foreach($keys as $kname => &$val) 
      { 
         $val = mysql_real_escape_string(substr($val, 1, strlen($val) - 1)); 
      }
      $tmp = consts::$data[9];
      foreach($_POST as $kname => &$val)
      { 
         $val = mysql_real_escape_string($val); 
         if (!in_array($kname, $tmp)) // si no es numerico agrego comillas
         {
            if (strlen($val) == 0 && $kname != "flink") continue;
            $val = "'".$val."'";
            if ($kname == "fpassword")
               $val = "MD5(".$val.")";
         }
      }
      $query_c = array();
      foreach($keys as $k)
         if (strlen($_POST[("f".$k)]) > 0 || $k == "link") // no modificar casillas que queden vacias
            $query_c[] =  $k."=".$_POST[("f".$k)];
      $cambios = implode(",", $query_c);
      $actlotes = false;
      $arg = ("f".$map[$tabla][0]);
      $query = "update {$tabla} set {$cambios} where ".$map[$tabla][0]." = ".$_POST[$arg];
      $cancel = false;
      if ($tabla == consts::$data[8][2])
      {
         $query2 = "select @tmp:=CONCAT(fecha, ' ', hora) as ts, IF(TIMESTAMPDIFF(SECOND, NOW(), @tmp) < 0 && en_curso = true && TIMESTAMPDIFF(SECOND,(select fecha_termino from lotes where id_lote = (select id_lote from lotes where id_remate = ".$_POST[$arg]." and orden = (select max(orden) from lotes where id_remate = ".$_POST[$arg]."))),NOW()) > 0, 0, 1 ) as andando, lote_actual, en_curso from remates where id_remate = ".$_POST[$arg];
         list($ts, $andando, $lotea, $enc) = mysql_fetch_row(mysql_query($query2,dbConn::$cn));
         /*
         if ($andando == 1)
         {
            echo "<p>No es posible modificar datos de un remate en curso.</p>";
            $cancel = true;
         }
         else 
         */
         if ($ts != string_chop($_POST['ffecha'],1,1)." ".string_chop($_POST['fhora'], 1, 1))
         {
            // if (strtotime($_POST['ffecha']." ".$_POST['fhora']) - time() < 3600)
            if (false)
            {
               echo "<p>La fecha del remate debe ser al menos 1 hora posterior a la fecha actual.</p>";
               $cancel = true;
            }
            $actlotes = true;            
         }
      }
      if ($cancel)
         die();
      $res = mysql_query($query, dbConn::$cn);
      if ($actlotes) 
         actualiza_lotes($_POST[$arg]);
      if ($res)
      {
         echo "<p>".consts::$mensajes[10]."</p>";
      }
      else
      {
         dbConn::dbError($query);
      }
   }
   else if ($tipo == consts::$data[10][2])
   {
      // borra lo que ta en $_POST['felements']
      $a_eliminar = explode(";", $_POST['felements']);
      foreach($a_eliminar as &$elem)
      {
         $elem = $map[$tabla][0]." = ".substr($elem, 1, strlen($elem) - 1);
      }
      $query = "delete from {$tabla} where ".implode(" OR ", $a_eliminar);
      $res = mysql_query($query, dbConn::$cn);
      if ($res)
      {
         echo "<p>".count($a_eliminar)." ".$map[$tabla][1]." eliminado(s) satisfactoriamente.</p>\n";
      }
      else
      {
         dbConn::dbError($query);
      }
   }
   else
      echo consts::$mensajes[8];
}
else echo consts::$mensajes[8];
?>
