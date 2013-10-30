<script language="JavaScript" src="menu_script.js"></script>
<div class="menu">
<?php
$menu = array
   (
      "Home" => "home.php",
      "Mi cuenta" => "ficha_usuario.php?rut=".urlencode((isset($_SESSION['rut']) ? $_SESSION['rut'] : "")),
      "Mis facturaciones" => "facturacion.php?rut=".urlencode((isset($_SESSION['rut']) ? $_SESSION['rut'] : "")),
      // "Procedimiento Remate" => "archivos/procedimiento_portalremate.pdf"
   );
   
$menu_admin = array
   (
      "Editar productos" => "administra.php?tipo=".consts::$data[8][1],
      // "Editar remates" => "administra.php?tipo=".consts::$data[8][2],
      // "Facturaciones" => "facturacion.php",
      // "Adjudicar manualmente" => "manual_adj.php",
      // "Categorías" => "categorias.php"
   );
$menu_super_admin = array
    (
        "Configuración" => "config.php?rand=".rand(1000,9000),
        "Editar clientes" => "administra.php?tipo=".consts::$data[8][0],
        // "Editar salas" => "salas.php",
        // "Categorías de la portada" => "categorias_portada.php",
        // "Publicidad" => "publicidad.php",
        "Miniremates" => "miniremates_edit.php",
        "Miniremates automáticos" => "miniremates_automaticos.php",
        "Menú superior" => "mod_menues.php",
        // "Cursos" => "cursos.php",
        "Ofertas" => "oferta_edit.php"
    );
   
   if (ini())
   {
      foreach ($menu as $name => $var)
         if (file_exists(strtok($var,"?")))
             echo "<li onclick=\"goto('".htmlspecialchars($var)."');\">".htmlspecialchars($name)."</li>\n";
      //echo "<li onclick=\"window.open('archivos/procedimiento_portalremate.pdf');\">Procedimiento Remate</li>\n";
      if (esAdmin()) // Es administrador
         foreach ($menu_admin as $name => $var)
            if (file_exists(strtok($var,"?")))
               echo "<li onclick=\"goto('".htmlspecialchars($var)."');\">".htmlspecialchars($name)."</li>\n";
    if (adminGeneral()) // Es administrador
    {
         foreach ($menu_super_admin as $name => $var)
            if (file_exists(strtok($var,"?")))
               echo "<li onclick=\"goto('".htmlspecialchars($var)."');\">".htmlspecialchars($name)."</li>\n";
         // echo "<li onclick=\"location.href='/mailbomber/main.php';\">Emails Masivos</li>";
    }
      echo "<li style=\"float:right;\" onclick=\"window.open('".consts::$pdfbases."');\"> Bases</li>";
    
      /*
    $wr = mysql_query("select id_sala from salas", dbConn::$cn);
    $salast = array();
    while($row = mysql_fetch_row($wr))
        $salast[] = $row[0];
      if (($ix = array_search($_GET['sala'], $salast)) > 0)
      echo "<li style="\"float: right;\" onclick=\"location.href='".($salas[$ix-1])."/principal.php';\"
      */
   }
   else
   {
      echo "<li onclick=\"goto('registro.php');\">Reg&iacute;strese</li>\n";
      echo "<li onclick=\"goto('recupera_clave.php');\">Olvid&eacute; Mi Clave</li>\n";
      
      
   }
   if (isset($_GET['autoload']) && substr($_GET['autoload'], 0, 11) == "vitrina.php")
    {
    
        $rr = substr($_GET['autoload'], 22);
        echo "<li style=\"font-height: 16px;\" onclick=\"location.href='remate.php?id=$rr';\">Ir al Remate Online</li>\n";
    }
?>
</div>