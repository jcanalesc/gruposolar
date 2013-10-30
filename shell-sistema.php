<?
   include("header.php");
   $linea = "";
   while(true)
   {
      printf(">> ");
      $linea = trim(fgets(STDIN));
      if ($linea == "quit") break;
      $funcion = strtok($linea, " ");
      $args = array();
      while($args[] = strtok(" "));
      array_pop($args);
      if(count($args) > 0)
         var_dump(call_user_func_array($funcion, $args));
      else
         var_dump(call_user_func($funcion));
      echo "\n";
   }
?>
