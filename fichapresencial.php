<?
    include("DB.inc.php");
    
    function orig($str)
    {
        $parts = explode("/", $str);
        $parts[count($parts) - 1] = "orig_".$parts[count($parts) - 1];
        return implode("/", $parts);
    }
    
    // Ahora el get trae una fecha y una secuencia
    $rows = 1;
    if (isset($_GET['fecha']) && isset($_GET['seq']))
    {
        $fecha = mysql_real_escape_string($_GET['fecha']);
        $seq = mysql_real_escape_string($_GET['seq']);
        $q = $db->query("select SQL_CALC_FOUND_ROWS id_remate from remates where fecha = '$fecha' and (publico = true or en_curso = true) order by banner_size, hora limit $seq,1");
        list($fr) = $db->query("select FOUND_ROWS() as fr");
        $rows = $fr['fr'];
        $idremate = $q[0]['id_remate'];
    }
    else if (isset($_GET['id']))
        $idremate = mysql_real_escape_string($_GET['id']);
    $res = $db->query("select * from galeria where id_remate = $idremate");
    list($res2) = $db->query("select * from remates where id_remate = $idremate");
    list($ano, $mes, $dia) = explode("-", $res2['fecha']);
    $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $fecha_leible = sprintf("el %d de %s del %d a las %s", $dia, $meses[$mes-1], $ano, $res2['hora']);
    $tams = array(1=>"small",2=>"mid",3=>"big");
    
    list($w, $h) = getimagesize(orig($res2['banner']));
    $estilodiv = "width: {$w}px;";
    
    $tipo = $res2['tipo'];
    if ($tipo != "Presencial"):
        echo file_get_contents("http://localhost/remate_panel.php?id=".$idremate);
    else:
?>
<script type="text/javascript">
<!--
$(function()
{
    $("img.presencialfoto").css("cursor", "pointer").live("click", function()
    {
        <?
            if (isset($_GET['id']))
                $args = "\"{$_GET['id']}\"";
            else if (isset($_GET['fecha']) && isset($_GET['seq']))
                $args = "\"{$_GET['seq']}\",\"{$_GET['fecha']}\"";
                
        ?>
        var foto = $(this).attr("src");
        var fotoorig = foto.replace("small.", "");
        $.fancybox("<img src='"+fotoorig+"' /><br/><button onclick='presencial(<?= $args ?>);'>Volver</button>");
    });
});
//-->
</script>
<div id="presencialwrap" style="<?= $estilodiv ?>">
<center><img src="<?= orig($res2['banner']) ?>" /></center>
<h3><strong><?= $res2['descripcion'] ?></strong></h3>
<p>Remate <?= strtolower($res2['tipo']) ?> a realizarse en <?= $res2['lugar'] ?>, <?= $res2['ciudad'] ?> <?= $fecha_leible ?> </p>
<p>El remate está sujeto a una comisión del <?= $res2['comision'] ?>%</p>
<? if (count($res) > 0): ?>
    <table><tr>
    <? foreach ($res as $fila): ?>
        <td><img class="presencialfoto" width="140" height="140" src="galeria/small.<?= $fila['foto'] ?>" /><br /><span><?= $fila['texto'] ?></span></td>
    <? endforeach;?>
    </tr></table>
<? else: ?>
    <p>El remate no posee fotos de galería.</p>
<? endif; ?>

<? endif; ?>

<? 
if (isset($_GET['fecha']) && isset($_GET['seq']) && $seq - 1 >= 0)
{
    $prev = $seq - 1;
    echo "<button data-date='$fecha' class='rematemarcado' data-seq='$prev'>Remate anterior</button>";
}
if (isset($_GET['fecha']) && isset($_GET['seq']) && $rows > $seq + 1)
{
    $next = $seq + 1;
    echo "<button data-date='$fecha' class='rematemarcado' data-seq='$next'>Siguiente remate</button>";
}
?>
<? if ($tipo == "Presencial") { echo '</div>'; } ?>
