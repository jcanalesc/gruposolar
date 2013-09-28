<? 
require_once("header.php");    
if (!isset($_GET['page']))
    $_GET['page'] = 0;
$page = $_GET['page'];
$rperpage = 2;
$offset = $rperpage*$page;
$append = "";
$text = "";
$criteria = "";

if (isset($_GET['search']) && isset($_GET['criteria']))
{
    $text = mysql_real_escape_string($_GET['search']);
    $criteria = mysql_real_escape_string($_GET['criteria']);
    switch($criteria)
    {
        case "producto": break;
        case "online":
            $append = "and tipo like '%Online%'"; 
            break;
        case "santiago":
            $append = "and (lugar like '%santiago%' or ciudad like '%santiago%')";
            break;
        case "regiones":
            $append = "and lugar not like '%santiago%' and ciudad not like '%santiago%'";
            break;
        case "presencial":
            $append = "and tipo like '%Presencial%'";
            break;
        case "ciudad":
            $append = "and (ciudad like '%$text%' or lugar like '%$text%')";
            break;
    }       
}
$res = null;
$empresa = "";
if (isset($_GET['empresa']))
{
    $empresa = mysql_real_escape_string($_GET['empresa']);
    $empresa = " and rut_owner = (select rut from users where link_empresa = '{$empresa}')";
}

if ($criteria != "producto")
    $res = mysql_query("select SQL_CALC_FOUND_ROWS * from remates where (id_remate in (select id_remate from lotes where repartido = false group by id_remate) or id_remate not in (select id_remate from lotes group by id_remate)) $append $empresa and (publico = true and en_curso = true) and id_remate != ".consts::$remate_destacado." order by banner_size desc, fecha, hora limit $offset, $rperpage", dbConn::$cn);
else
{
    $query = "select SQL_CALC_FOUND_ROWS * from remates where id_remate in (select id_remate from lotes join productos using (id_producto) where productos.descripcion like '%$text%' UNION ALL select id_remate from remates where tipo = 'Presencial' and descripcion like '%$text%') and id_remate in (select id_remate from lotes where repartido = false group by id_remate union all select id_remate from remates where tipo = 'Presencial') and (publico = true or en_curso = true) $empresa group by id_remate limit $offset, $rperpage";
    $res = mysql_query($query, dbConn::$cn);
    rematelog($query);
}
list($rowcount) = mysql_fetch_row(mysql_query("select FOUND_ROWS()", dbConn::$cn));
$remates = array();
$banner_sizes = array(
                "1" => "small",
                "2" => "mid",
                "3" => "big"
                );
$empresa = null;

if ($res !== false && mysql_num_rows($res) > 0) while($row = mysql_fetch_assoc($res))
{
    $repartido = "";
    if ($row['tipo'] != "Presencial")
    {
        $rtmp = mysql_query("select repartido from lotes where id_remate = {$row['id_remate']} order by fecha_termino desc limit 1", dbConn::$cn);
        list($repartido) = mysql_fetch_row($rtmp);
        $repartido = (int)$repartido;
    }
    else
        $repartido = ($row['en_curso'] != "1" && $row['publico'] != "1" ? true : false);
    $fechacomienzo = strtotime("{$row['fecha']} {$row['hora']}");
    $empezo = ($fechacomienzo < time() ? 1 : 0);
    $estado = ($empezo ? ($repartido ? "Finalizado" : "En curso") : "Aun no comienza");
    $estadocss = ($empezo ? ($repartido ? "red" : "green") : "yellow");
    $remates[] = array
                (
                    'sala' => $row['id_sala'],
                    'url' => "{$row['id_sala']}/remate.php?id={$row['id_remate']}",
                    'tipo' => "{$row['tipo']}",
                    'estado' => $estado,
                    'estadocss' => $estadocss,
                    'lugar' => "{$row['lugar']}",
                    'ciudad' => "{$row['ciudad']}",
                    'fecha' => "{$row['fecha']}",
                    'hora' => "{$row['hora']}",
                    'comision' => "{$row['comision']}%",
                    'tipo_productos' => "{$row['tipo_productos']}",
                    'contacto' => "{$row['contacto']}",
                    'visible' => $row['publico'] ? "Disponible" : "No disponible",
                    'id' => $row['id_remate'],
                    'banner' => $row['banner'],
                    'banner_size' => $banner_sizes[$row['banner_size']],
                    'proc' => $row['procedimiento'],
                    'requiere_auth' => $row['requiere_auth'],
                    'texto_usuario_noauth' => $row['texto_usuario_noauth']
                );
}

$busqueda = false;
$text = "";
$criteria = "";

$pagecount = ceil($rowcount/(float)$rperpage);
foreach ($remates as $rem)
{ 
    $color = $rem['estadocss'];
    $presencial = "";
    if ($rem['tipo'] == "Presencial")
    {
        $presencial = "data-presencial=\"{$rem['id']}\"";
    }
    ?>
        <? include ("template_remate.php"); ?>
    <? 
}
?>
<div id="barra">
    <?
    if ($page > 0)
    {
        echo '<span class="pager changepage" data-page="'.($page - 1).'">Anterior</span>';
    }
    $actual = $page+1;
    if ($pagecount != 0)
    {
        for($i = 1; $i <= $pagecount; $i++)
        {
            if ($i != $actual)
                echo "<span class='pager changepage' data-page='".($i-1)."'>$i</span>";
            else
                echo "<span class='pager'>$i</span>";
            }
    }
    else
    {
        echo "<span class='pager'>No se encontraron resultados.</span>";
    }
    if ($page < $pagecount - 1)
    {
        echo '<span class="pager changepage" data-page="'.($page + 1).'">Siguiente</span>';
    }
?>
</div>
