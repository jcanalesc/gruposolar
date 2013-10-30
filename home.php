<style type="text/css">
p { text-align: center; font-family: Arial; font-size: 10pt; margin: 3px 0px;}
a img { margin: 0px 20px; }
</style>
<? 
   require_once("header.php");
   $owner = consts::$SALA['rut_owner'];
   $r = mysql_query("select * from users where rut = $owner", dbConn::$cn);
   $row = mysql_fetch_assoc($r);
   if (!ini())
   {
      echo "<p>Para participar regístrese y accederá al catálogo de productos que se rematarán.</p>";
   }
   else
   {
      echo "<p>Haga Click en las Opciones del Men&uacute; para Empezar.</p>";
   }
   
   $correo = strlen($row['f_email']) > 0 ? $row['f_email'] : "soporte@portalremate.cl";
   $tel = strlen($row['f_telefono']) > 0 ? $row['f_telefono'] : "9-6114298";
   
?>
<p>CONSULTAS: al Email: <a href="mailto:<?= $correo ?>"><?= $correo ?></a> o Fono: 02-25513304 &nbsp;&nbsp; Móvil: <?= $tel ?></p>
<br /><br />
<p><img src="pag_segura.png" /></p>
<p>Sitio desarrollado y optimizado para las últimas versiones de <a href="http://windows.microsoft.com/es-ES/internet-explorer/products/ie/home" >Internet Explorer</a>, <a href="http://www.getfirefox.net">
Mozilla Firefox
</a> y <a href="http://www.google.com/chrome/?hl=es">Google Chrome</a>.</p>
<p><strong>PORTALREMATE &copy; 2008 - <?= date("Y") ?></strong></p><br />
<p>Copyright &copy; Todos los derechos reservados</p>
