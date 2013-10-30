<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
<?
   include("header.php");
   if (!isset($_GET['id'])) die(consts::$mensajes[8]);
   if (!esAdmin()) die(consts::$mensajes[9]);
   $query = "select * from remates where id_remate = ".mysql_real_escape_string($_GET['id']);
   $res = mysql_query($query, dbConn::$cn);
   if (!$res) dbConn::dbError($query);
   $row = mysql_fetch_assoc($res);
   $nremate = $_GET['id'];
   $porc = ($row['tipo_puja'] == "Porcentual");
   $valor_puja = $row['valor_puja'];
   $tiempo_lote = $row['duracion_lote'];
   
?>
<script language="JavaScript" src="jquery.js"></script>
<link rel="StyleSheet" href="auction_a.css" />
<script language="JavaScript" src="remate_script_a.js.php?id=<?= $_GET['id'] ?>"></script> 
<TITLE>PortalRemate: Remate Online</TITLE>
</head>      
<body>
<div id="main">
<div class="enlinea" id="central">
      <div id="head">
      <div class="enlinea" id="titulo">
      Remate &#035;<?= $nremate ?><br />
      <span>Pujas <?= $row['tipo_puja'] == "Fijo" ? "fijas" : "porcentuales"?> de <?= $row['tipo_puja'] == "Fijo" ? currf($row['valor_puja']) : $row['valor_puja']."% del valor inicial"?></span>
      </div>
      <div id="lineap" class="enlinea">Precio Neto Actual: $<span id="pactual"></span></div>
      <div id="datoslog" class="enlinea">
      <?
         if (ini())
            echo "Usuario: <br />{$_SESSION['rut']}";
         else
            echo "Invitado";
      ?>
      </div>
      </div>
      
      <div class="enlinea inside" id="producto">
         <div class="enlinea" id="fotogrande">
            <img src="" width="210" height="210"/>
            <div class="enlinea">
               <div class="enlinea thumb"><img src="" width="55" height="55" index="1" /></div>
               <div class="enlinea thumb"><img src="" width="55" height="55" index="2" /></div>
               <div class="enlinea thumb"><img src="" width="55" height="55" index="3" /></div>
            </div>
         </div>
         <div class="enlinea" id="descr">
            <p></p>
         </div>
         <div class="enlinea" id="verdatos">
         <p class="ty" ty="productos">Ver productos</p>
         <p class="ty" ty="users">Ver clientes</p>
         <p class="ty" ty="remates">Ver remates</p>
         <input type="button" value="Pausar Remate" id="pause" />
         Factor:
         <input id="changefact" type="text" size="4" value="<?= consts::$factor_usuarios ?>" />
         </div>
      </div>
      <div class="enlinea" id="visor">
            <p>Usuarios conectados: <span id="cant"></span></p>
            <span id="list"></span>
      </div>
      <div class="enlinea" id="interactivo">
      <?
         if (!ini())
         {
            echo "<p><a href=\"principal.php\">Inicie sesi&oacute;n</a> para participar.</p>";
            
         }
         else 
         {
      ?>
      
      <p><span id="estado">Usted tiene la mejor oferta</span></p>
      <form id="f_oferta" method="post" onSubmit="ofertar(); return false;">
         <p><input type="text" name="oferta" id="oferta" /></p>
         <button class="puja" type="submit" id="ofertador">Ofertar</button>
      </form>
      
			<button onClick="ceder_lote();">Pasar al siguiente lote</button>
      <audio src="mensajemartillero.wav" id="sonido_msg"></audio>
      <audio src="adjudicacion.wav" id="sonido_adj"></audio>
      <?
         }
      ?>
      </div>
</div>
<div class="enlinea" id="lateral">
      <div class="enlinea inside"><div class="enlinea" id="sup_der"></div><button onClick="location.href='principal.php';">Salir</button></div>
      <div class="enlinea inside" id="eventos">Eventos</div>
      <div class="enlinea inside">
      <form onSubmit="sendb(); return false;">
      <textarea id="broadcast"></textarea><input class="submitea"  type="submit" value="Enviar broadcast" />
      </form>
      </div>
      <div class="enlinea inside" id="inf_der"><? if (ini()) { $martilleando = true; include("chat.php"); } ?></div>
</div>
</div>
<div id="visors" style="display: none;"></div>
<div id="floating" style="display: none;"><button class="close">X</button><div></div></div>
</body>
</html>
