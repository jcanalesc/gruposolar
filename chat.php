<? require_once("header.php"); ?>
<div class="chat_window">
<div id="chatw">
<div id="chat">
</div>
<form onsubmit="send(); return false;">
<span style="color: white;">CHAT A MARTILLERO:</span> <input type="text" id="msg"/><button type="submit" id="chat_enviar">Enviar</button>
<?
	if (esAdmin() && isset($martilleando))
   {
		echo "<select id=\"receive\" name=\"receive\" value=\"Seleccione Destinatario\"></select>";
	}
	else
	{
		echo "<input id=\"receive\" type=\"hidden\" name=\"receive\" value=\"".consts::$data[11]."\" />";
	}
?>
</form>
</div>
</div>
