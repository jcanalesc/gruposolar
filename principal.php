<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
<meta name="robots" content="NOODP">
<meta name="googlebot" content="NOODP">
<meta name="Description" content="REMATES ONLINE ENERGIA SOLAR, ENERGIA EOLICA, GENERADORES, GASTRONOMIA, MOTOSIERRAS, AUDIO, MATERIALES DE CONSTRUCCION, HERRAMIENTAS BENCINERAS, PANELES FOTOVOLTAICOS">
<meta name="Keywords" content="REMATES ONLINE,ENERGIA SOLAR,ENERGIA EOLICA,GENERADORES,GASTRONOMIA,MOTOSIERRAS,AUDIO,MATERIALES DE CONSTRUCCION,HERRAMIENTAS BENCINERAS,PANELES FOTOVOLTAICOS">
<script language="JavaScript" src="jquery.js"></script>
<script language="JavaScript" src="reloj.js"></script>
<script type="text/javascript" src="fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<script language="javascript" src="jsDatePick.jquery.min.1.3.js"></script>
<script language="javascript" src="jquery.timePicker.min.js"></script>
<script type="text/javascript" src="jquery.scrollTo-1.4.2-min.js"></script>
<script type="text/javascript" src="tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
<!--
$(function()
{
    $("#floating .close").css("cursor", "pointer").click(function()
    {
        $("#floating").hide("slow");
    });
});
-->
</script>
<link rel="StyleSheet" href="estilo.css" />
<link rel="stylesheet" type="text/css" href="fancybox/jquery.fancybox-1.3.4.css" />
<link rel="stylesheet" href="jsDatePick_ltr.min.css" />
<link rel="stylesheet" href="timePicker.css" />
<title>PortalRemate</title>
</head>
<body>
<div id="main">
<?php
require_once("header.php");
ini();
?>
<div class="head"><a href="frontis.php" style="margin: 0px; border: 0px;"><img src="logo_gr.png" /></a></div>
<div class="vertical" id="lateral">
<div id="clock"><span>-</span></div>
<?php
include("loginbox.php");
include("menu.php");
?>
</div>
<div class="vertical" id="principal">
<? include("home.php"); ?>
</div>
</div>
<div id="floating" style="display: none;"><button class="close">X</button><span></span><div class="wrapper"></div></div>
<? if (adminGeneral()): ?>
        <? 
           $parts = explode("/", $_SERVER['REQUEST_URI']);
                $path = $parts[1];
            $numsalas  = mysql_num_rows(mysql_query("select id_sala from salas", dbConn::$cn));
            $sala = $_GET['sala'];
        ?>
        <? if ($sala > 1): ?>
            <div class="flecha" id="flecha-izq" onclick="location.href='<?= "/".($sala - 1)."/principal.php" ?>';">&lt;</div>
        <? endif; ?>
        
        <? if ($sala < $numsalas): ?>
            <div class="flecha" id="flecha-der" onclick="location.href='<?= "/".($sala + 1)."/principal.php" ?>';">&gt;</div>
        <? endif; ?>
<? endif; ?>
</body>
<? if (isset($_GET['autoload']))
{
  echo <<<END
<script type="text/javascript">
$(function()
{
    goto("{$_GET['autoload']}");
});
</script>
END;
    
}
?>
</html>
