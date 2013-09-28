<?
    // ficha_miniremate.php
    include("header.php");
    ini();
    if (!isset($_GET['id'])) die(consts::$mensajes[8]);
    $id = $_GET['id'];
    // termina wn
    $r = mysql_query("select * from miniremates where id_miniremate = $id", dbConn::$cn);
    if ($r === false || mysql_num_rows($r) < 1) die(consts::$mensajes[8]);
    $row = mysql_fetch_assoc($r);
?>
<div style="width: 570px;">
<table class="fichamr">
    <tr><td><?= $row['titulo'] ?></td></tr>
    <tr><td><img width="550" height="550" src="<?= $row['foto'] ?>" /></td></tr>
    <tr><td><center><?= $row['texto'] ?></center></td></tr>
</table>
</div>
