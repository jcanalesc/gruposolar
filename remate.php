<?
   include("header.php");
   if (!isset($_GET['id'])) die(consts::$mensajes[8]);
   $query = "select * from remates where id_remate = ".mysql_real_escape_string($_GET['id']);
   $res = mysql_query($query, dbConn::$cn);
   if (!$res) dbConn::dbError($query);
   
   if (mysql_num_rows($res) == 0) die(consts::$mensajes[8]);
   $row = mysql_fetch_assoc($res);
   $nremate = $_GET['id'];
   if ($row['id_sala'] != consts::$SALA['id_sala'])
   die("El remate no pertenece a la sala indicada.");
   $conectado = ini();
   
   $dirs = explode("/", $_SERVER['REQUEST_URI']);
   $path = $dirs[1];

   if ($row['requiere_auth'] == "1" and $_SESSION['autorizado_rsm'] == "0")
   {
      die("Usuario no autorizado.");
   }

   // Datos de contacto

   $contacto_email = "soporte@portalremate.cl";
   $contacto_fijo = "25513304 - 25523345";
   $contacto_movil = "95114298";

   if (strlen($row['contacto_email']) > 0)
      $contacto_email = $row['contacto_email'];
   if (strlen($row['contacto_fijo']) > 0)
      $contacto_fijo = $row['contacto_fijo'];
   if (strlen($row['contacto_movil']) > 0)
      $contacto_movil = $row['contacto_movil'];
   if ($row['tipo_puja'] == "Sin Minimo")
   {
      include("remate2.php");
   }
   else
   {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
<link rel="StyleSheet" href="auction.css"/>
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="jquery.sound.js"></script>
<script language="JavaScript" src="remate_script.js.php?id=<?= $_GET['id'] ?>"></script> 
<TITLE>PortalRemate: Remate Online</TITLE>
</head>      
<body>
<div id="main">
<div class="enlinea" id="central">
      <div id="head">
      
      <div class="enlinea" id="titulo">
        <img src="<?= consts::$logo ?>" width="112" height="40" style="margin-left: 26px;"/>
      </div>
      <div id="lineap" class="enlinea">Precio <?= ($row['tipo_puja'] == "Sin Minimo" ? "" : "Neto ")?>Actual: $<span id="pactual"></span></div>
      <div id="datoslog" class="enlinea">
      <?
         if (ini())
            echo "Usuario:<br />{$_SESSION['rut']}";
         else
            echo "No conectado";
      ?>
      </div>
      </div>
      <div class="enlinea" id="producto">
         <div id="fotogrande">
            <img src="" width="300" height="300"/>
         </div> 
         <div class="enlinea" id="fotoschicas">
            <div class="enlinea thumb"><img src="" width="55" height="55" index="1" /></div>
            <div class="enlinea thumb"><img src="" width="55" height="55" index="2" /></div>
            <div class="enlinea thumb"><img src="" width="55" height="55" index="3" /></div>
         </div>
         <div class="enlinea" id="descr">
            <p></p>
         </div>

         <div id="conectados">Usuarios Conectados: <span></span></div>
         <div id="wrap">
         <div class="enlinea flecha"><a href="#" id="lote_anterior"><img src="anterior.png" class="flechaimg"/>Anterior</a></div>
         <div class="enlinea" id="middle">&nbsp;</div>
         <div class="enlinea flecha"><a href="#" id="lote_siguiente">Siguiente<img src="siguiente.png" class="flechaimg"/></a></div>
         </div>
         
      </div>
      <div class="enlinea" id="lower">
         <div id="pop_sm" title="Toma de unidades" class="enlinea">
         </div>

         <div class="enlinea" id="interactivo">
         <?
            if (!$conectado)
            {
               echo "<p><a href=\"../frontis.php\">Inicie sesi&oacute;n</a> para participar.</p>";
               
            }
            else 
            {
         ?>
         <p><span id="estado">Usted tiene la mejor oferta</span></p>
         <form id="f_oferta" method="post" onSubmit="ofertar(); return false;">
            <p><input type="hidden" name="oferta" id="oferta" /></p>
            <button type="submit" id="ofertador" class="plomo btn_oferta">Ofertar</button>
         </form>
         <audio src="mensajemartillero.wav" id="sonido_msg"></audio>
         <audio src="adjudicacion.wav" id="sonido_adj"></audio>
         <p style="font-size: 10pt;">Consultas: Email: <a href="mailto:<?= $contacto_email ?>">
            <?= $contacto_email ?></a><br />Fijo: <?= $contacto_fijo ?> - Móvil: <?= $contacto_movil ?></p>
         <?
            }
         ?>
      </div>
   </div>
</div>
<div class="enlinea" id="lateral">
      <div class="enlinea inside"><p class="clock-caption">CUENTA REGRESIVA</p><div class="enlinea" id="sup_der"></div><button onClick="location.href='/frontis.php';" style="margin-left: 20px;">Volver</button></div>
      <div class="enlinea inside" id="eventos">Eventos</div>
      <div class="enlinea inside" id="inf_der"><? if (ini()) include("chat.php"); ?></div>
</div>
</div>

</body>
</html>
<?
   }
?>