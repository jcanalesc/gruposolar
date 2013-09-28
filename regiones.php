<?
   require_once("header.php");
   $sel = "";
   if (isset($_POST['region']))
   {
      $res = mysql_query("select codigo, nombre from comunas where region LIKE '".mysql_real_escape_string(utf8_encode($_POST['region']))."'", dbConn::$cn);
      // rematelog("selected=".$_POST['selected']."; region=".eval("return \"".$_POST['region']."\";"));
      $flag = false;
      while($row = mysql_fetch_assoc($res))
      {
		$sel = "";
		
		 if (isset($_POST['selected']) && $row['codigo'] == $_POST['selected']) $sel = "selected=\"selected\"";
		echo "<option value=\"".htmlentities($row['codigo'])."\" $sel>{$row['nombre']}</option>\n";
	  }
   }
   else
   {
      $arreglo = array();
      $res = mysql_query("select region from comunas group by region", dbConn::$cn);
      $flag = false;
      echo "<option>Seleccione Región</option>\n";
      while($row = mysql_fetch_assoc($res))
      {
		if (isset($_GET['display']) && $_GET['display'] == "json")
        {
            $arreglo[] = $row['region'];
        }
        else
        {
            $sel = "";
            if (isset($_POST['selected']) && $row['region'] == $_POST['selected'])
                $sel = "selected=\"selected\"";
            echo "<option value=\"".htmlspecialchars($row['region'])."\" $sel>".($row['region'])."</option>\n";
        }
      }
      if (isset($_GET['display']) && $_GET['display'] == "json")
        echo json_encode($arreglo);
    }
   
?>
