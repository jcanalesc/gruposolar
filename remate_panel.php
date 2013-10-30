
<?
    // remate_panel.php
    function orig($str)
    {
        $parts = explode("/", $str);
        $parts[count($parts) - 1] = "orig_".$parts[count($parts) - 1];
        return implode("/", $parts);
    }
    include("DB.inc.php");
    
    $banner_sizes = array(
                "1" => "small",
                "2" => "mid",
                "3" => "big"
                );
    
    $dirs = explode("/", $_SERVER['REQUEST_URI']);
    $dir = $dirs[1];
    
    $id = mysql_real_escape_string($_GET['id']);
    list($datos) = $db->query("select * from remates where id_remate = $id");
    list($w, $h) = getimagesize(orig($datos['banner']));
    $ancho = $w + 10;
?>
<script type="text/javascript">
$(function()
{
    $(".go-galeria2").click(function()
    {
        location.href="<?= $datos['id_sala'] ?>/principal.php?autoload="+escape("vitrina.php?id_remate=<?= $id ?>");
    });
    $(".go-proc2").click(function()
    {
        var pathproc = "<?= $datos['procedimiento'] ?>";
        if (pathproc.length > 0)
            window.open(pathproc);
        else
            alert("No hay un documento de procedimiento para este remate.");
    });
    $(".go-remate2").click(function()
    {
        location.href=$(this).attr("data-url");
    });
});
</script>
<div id="rematepanel" style="width: <?= $ancho ?>px;">
<p>Remate núm. <?= $id ?></p>
<img src="<?= orig($datos['banner']) ?>" /><br />
<div style="text-align: center;">
<button class="panel go-remate2" data-url="<?= "/{$datos['id_sala']}/remate.php?id=$id" ?>"><img src="pr2-img/1t.png" valign="middle" />Ingresar al Remate Online</button>
<button class="panel go-galeria2"><img src="pr2-img/3t.png" valign="middle" />Ver galería</button>
<button class="panel go-proc2"><img src="pr2-img/2t.png" valign="middle" />Ver procedimiento</button>
</div>
</div>
