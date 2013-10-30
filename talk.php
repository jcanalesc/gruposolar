<?
   include("header.php");
   
   if (esAdmin() && isset($_POST['broadcast']))
   {
		$msg = mysql_real_escape_string(htmlspecialchars($_POST['msg']));
		$id = mysql_real_escape_string($_POST['broadcast']);
		$res = mysql_query("insert into chat (sender, receive, msg, id_remate) values ('{$_SESSION['rut']}', '".consts::$data[6]."', '{$msg}', {$id})", dbConn::$cn);
		echo "done";
	}
   if (isset($_POST['msg']) && isset($_POST['receive']) && isset($_POST['id_remate']) && count($_POST) == 3)
   {
      $idl = $_POST['id_remate'];
      //rematelog("strings recibidos en talk.php: msg({$_POST['msg']}) receive({$_POST['receive']}) id_remate ({$_POST['id_remate']})");
      $res = mysql_query("insert into chat (sender, receive, msg, id_remate) values ('".(ini() ? mysql_real_escape_string($_SESSION['rut']) : consts::$data[5])."','".mysql_real_escape_string($_POST['receive'])."','".mysql_real_escape_string(htmlspecialchars($_POST['msg']))."', {$idl})", dbConn::$cn) or die("fail");
      echo "done";
   }
?>
