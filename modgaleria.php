<?
    include("header.php");
    if (!esAdmin()) die(consts::$mensajes[9]);
    if (count($_POST) > 0 && isset($_POST['idr']))
    {
        $idr = mysql_real_escape_string($_POST['idr']);
        if (isset($_POST['action']))
        {
            switch($_POST['action'])
            {
                case "del":
                    $foto = str_replace("galeria/small.","",$_POST['foto']);
                    $r = mysql_query("delete from galeria where id_remate = $idr and foto = '$foto'", dbConn::$cn);
                    if ($r)
                    {
                        unlink("galeria/$foto");
                        unlink("galeria/small.$foto");
                        die("ok");
                    }
                    else
                        die("Problemas al intentar eliminar la foto.");
                break;
                case "add":
                    $mensaje = "Error desconocido.";
                    if (count($_FILES) > 0 && isset($_FILES['imagen']))
                    {
                        $type = $_FILES['imagen']['type'];
                        $newname = "$idr.".$_FILES['imagen']['name'];
                        $smallname = "small.$idr.".$_FILES['imagen']['name'];
                        if ($_FILES['imagen']['error'] > 0)
                            $mensaje = "Error con la imagen.";
                        else if (!in_array($type, array("image/jpeg", "image/png", "image/gif", "image/jpg", "image/pjpeg", "image/pgif", "image/ppng", "image/pjpg")))
                            $mensaje = "Tipo de archivo no soportado.";
                        else if ($_FILES['imagen']['size'] > 2*1024*1024)
                            $mensaje = "Tamaño máximo de imagen excedido. (2MB)";
                        else if (!move_uploaded_file($_FILES['imagen']['tmp_name'], "galeria/$newname"))
                            $mensaje = "Problemas al subir el archivo.";
                        else
                        {
                            include("SimpleImage.inc.php");
                            $img = new SimpleImage();
                            $img->load("galeria/$newname");
                            $img->resizeToHeight(200);
                            $img->save("galeria/$smallname");
                            $r = mysql_query("insert into galeria (id_remate, foto) values ($idr, '$newname')", dbConn::$cn);
                            if (!$r) $mensaje = "Problemas al registrar la imagen en la galería.";
                            else
                                $mensaje = "Imagen agregada a la galería correctamente.";
                        }
                    }
                    
                    echo <<<END
<script type="text/javascript">
alert("$mensaje");
parent.editar_galeria($idr);
</script>
END;
                    exit();
                break; 
                case "mod":
                    if (isset($_POST['texto']))
                    {
                        $foto = str_replace("galeria/small.","",$_POST['foto']);
                        $texto = mysql_real_escape_string($_POST['texto']);
                        $r = mysql_query("update galeria set texto = '$texto' where id_remate = $idr and foto = '$foto'", dbConn::$cn);
                        if (!$r) die("Problemas al modificar texto");
                        else die("ok");
                    }
                    
                break;
            }
        }
    }
    if (!isset($_GET['id_remate'])) die(consts::$mensajes[8]);
    $idr = mysql_real_escape_string($_GET['id_remate']);
    $res = mysql_query("select tipo, banner from remates where id_remate = $idr", dbConn::$cn);
    if (!$res || mysql_num_rows($res) == 0) die(consts::$mensajes[8]);
    list($tipo,$banner) = mysql_fetch_row($res);
    if ($tipo != "Presencial") die(consts::$mensajes[8]);
    $res2 = mysql_query("select * from galeria where id_remate = $idr", dbConn::$cn);
?>
<script type="text/javascript">
<!--
$(function()
{
    $("button.delimggal").click(function()
    {
        var foto = $(this).prev().prev().prev().attr("src");
        var idr = <?= $_GET['id_remate'] ?>;
        $.post("modgaleria.php", { "action" : "del", "idr" : idr, "foto" : foto }, function(data)
        {
            if (data == "ok")
                editar_galeria(<?=$_GET['id_remate'] ?>);
            else
                alert(data);
        });
    });
    $("button.acttexto").click(function()
    {
        var texto = $(this).prev().val();
        var foto = $(this).prev().prev().attr("src");
        var idr = <?= $_GET['id_remate'] ?>;
        
        $.post("modgaleria.php", { "action" : "mod", "idr" : idr, "texto" : texto, "foto" : foto }, function(data)
        {
            if (data == "ok")
                editar_galeria(<?=$_GET['id_remate'] ?>);
            else
                alert(data);
        });
    });
    
});
//-->
</script>
<h4>Galería del remate presencial ID: <?= $_GET['id_remate'] ?></h4>
<? if (mysql_num_rows($res2) > 0): ?>
<div style="height: 250px; width: 340px; overflow-x: hidden; overflow-y: auto;">
<ul>
    <? while($row = mysql_fetch_assoc($res2)): ?>
        <li><img class="galimg" src="galeria/small.<?= $row['foto'] ?>" /><input type="text" value="<?= $row['texto'] ?>" /><button class="acttexto">Actualizar texto</button><button class="delimggal">Eliminar</button></li>
    <? endwhile; ?>
</ul>
</div>
<? else: ?>
<p>Este remate no tiene fotos disponibles.</p>
<? endif; ?>
<div style="clear: both;">
    <form enctype="multipart/form-data" method="post" action="modgaleria.php" target="oculto">
        <input type="hidden" name="idr" value="<?= $idr ?>" />
        <input type="hidden" name="action" value="add" />
        Agregar foto:
        <input type="file" name="imagen" />
        <input type="submit" value="Subir" />
    </form>
</div>
<iframe id="oculto" name="oculto" width="0px" height="0px" style="visibility: hidden;"></iframe>
