<?
    // get_cities
    include("header.php");
    header("Content-type: application/json");
    
    
    
    $r = mysql_query("select ciudad from remates where publico = true", dbConn::$cn);
    
    $opciones = array();
    while($row = mysql_fetch_row($r))
    {
        $opciones[] = $row[0];
    }
    $opciones = array_unique($opciones);
    die(json_encode($opciones));
?>
