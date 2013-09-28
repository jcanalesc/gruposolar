<?
   // agregar, modificar y eliminar clientes
   include("header.php");
   
   if (isset($_GET['tipo']) && esAdmin())
   {
       $page = (isset($_GET['page']) ? $_GET['page'] : 0);
      ?>
        <script language="JavaScript" src="administra_script.js.php?tipo=<?= urlencode($_GET['tipo']) ?>"></script>
        <?
        
    $kw = (isset($_GET['search']) ? $_GET['search'] : null);
    if (isset($_GET['units']) && $_GET['units'] == "all")
      create_table($_GET['tipo'], $page, -1, $kw);
    else
      create_table($_GET['tipo'], $page, 50, $kw);
   }
   else
   {
      echo "<p>".htmlspecialchars(consts::$mensajes[8])."</p>";
      // print_r($_SESSION);
   }
?>
