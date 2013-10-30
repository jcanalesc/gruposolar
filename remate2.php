<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
<link rel="StyleSheet" href="auction.css"/>
<style type="text/css">
.pujasmb
{
   margin: 0px 20px;
}
</style>
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
         <div id="pop_sm" title="Toma de unidades" class="enlinea" style="display: none;">
         </div>

         <div class="enlinea" id="interactivo" style="width: 695px;">
         <?
            if (!$conectado)
            {
               echo "<p><a href=\"../frontis.php\">Inicie sesi&oacute;n</a> para participar.</p>";
               
            }
            else 
            {
         ?>
         <p><span id="estado">Usted tiene la mejor oferta</span></p>
            <p>Seleccione su oferta</p>
            <table class="t_transparente">
               <tr>
                  <td>A) $<span class="pujasms" id="pujasm_1">1</span></td><td><button class="pujasmb" data-puja="1" disabled>OFERTAR</button></td>
                  <td>B) $<span class="pujasms" id="pujasm_2">500</span></td><td><button class="pujasmb" data-puja="2" disabled>OFERTAR</button></td>
                  <td>C) $<span class="pujasms" id="pujasm_3">1.000</span></td><td><button class="pujasmb" data-puja="3" disabled>OFERTAR</button></td>
               </tr>
            </table>
         <form id="f_oferta" method="post" onSubmit="ofertar(); return false;" style="display: none;">
            <p><input type="hidden" name="oferta" id="oferta" /></p>
            <button type="submit" id="ofertador" class="plomo">Ofertar</button>
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
