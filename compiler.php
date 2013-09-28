<?
function compila($origen)
{
   $destino = fopen("compiled/".$origen, "wb");
   bcompiler_write_header($destino);
   bcompiler_write_file($destino, $origen);
   bcompiler_write_footer($destino);
   fclose($destino);
}

$dh = opendir("/var/www");
while($file = readdir($dh))
{
   if (strstr($file, ".php") !== false && $file != "/compiler.php")
   {
      compila($file);
   }
}
?>
