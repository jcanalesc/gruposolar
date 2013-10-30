<?
    // userlist.php
    include("header.php");
    if (!adminGeneral()) die(consts::$mensajes[9]);
    $r = mysql_query("select rut from users", dbConn::$cn);
    $users = array();
    while($row = mysql_fetch_row($r))
        $users[] = $row[0];
    mysql_free_result($r);
    header("Content-type: application/json");
    echo json_encode($users);
?>
