<?php
	include("header.php");
    $page_entries = 10;
    $total_entries = -1;
    $page = $_GET['page'] ? $_GET['page'] : 1;

    $offset = ($page - 1) * $page_entries;

    $criteria = "";
    $str = "";

    // no aceptar strings muy chicos
    if (isset($_GET['busca']) && strlen($_GET['busca']) > 3)
    {
        $str = mysql_real_escape_string($_GET['busca']);
        $criteria = "and (users.nombres like '%$str%' or users.rut like '$str' or users.apellidop like '$str%' or users.apellidom like 'str%' or productos.descripcion like '%$str%')";
    }

    $r = mysql_query("select SQL_CALC_FOUND_ROWS * from miniremates join productos using (id_producto) join users on (users.rut = miniremates.rut_ganador) where miniremates.finalizado = true $criteria order by fecha_termino desc limit $offset,$page_entries", dbConn::$cn);
    if ($r !== false && mysql_num_rows($r) > 0)
    {
        list($total_entries) = mysql_fetch_row(mysql_query("select FOUND_ROWS()", dbConn::$cn));
        while($row = mysql_fetch_assoc($r))
            $finalizados[] = $row;
    }

    $total_pages = ceil($total_entries / $page_entries);

    $ant = "Anterior"; $sig = "Siguiente"; $first = "Primera"; $last = "Ultima";
    if ($total_pages > 1)
    {
        if ($page > 1)
        {
        	$first = "<a href=\"javascript:;\" onclick=\"ir_a_pag(1);\">Primera</a>";
            $ant = "<a href=\"javascript:;\" onclick=\"ir_a_pag(".($page - 1).");\">Anterior</a>";
        }
        if ($page < $total_pages)
        {
            $sig = "<a href=\"javascript:;\" onclick=\"ir_a_pag(".($page + 1).");\">Siguiente</a>";
            $last = "<a href=\"javascript:;\" onclick=\"ir_a_pag(".$total_pages.");\">Ultima</a>";
        }
    }

?>
    <tr style="background: #7BC1D9;"><td colspan="9">Miniremates pasados</td></tr>
    <tr><td colspan="9">Búsqueda: <input type="text" id="mr_buscador" value="<?= $str ?>" /><button id="mr_buscador_btn">Buscar</button></td></tr>
    <tr><td colspan="9"><?= $first ?> | <?= $ant ?> | Pagina <?= $page ?> de <?= $total_pages ?> | <?= $sig ?> | <?= $last ?></td></tr>
    <tr><td><input type="checkbox" id="selectall"/></td><td colspan="2">ID</td>
        <td>Producto</td><td>Precio final</td><td>Ganador</td><td>Nota de venta</td><td>Info</td><td>Pagado</td></tr>
    <?
    foreach($finalizados as $ac)
        {
            $checksi = $ac['pagado'] == 1 ? "checked" : "";
            $checkno = $ac['pagado'] == 0 ? "checked" : "";
            $infocontent  = htmlentities($ac['info']);
            echo "<tr><td><input type=\"checkbox\" data-checkmr=\"{$ac['id_miniremate']}\"/></td>",
                 "<td><button data-borra=\"{$ac['id_miniremate']}\">Eliminar</button></td>",
                 "<td>{$ac['id_miniremate']}</td><td>{$ac['descripcion']}</td><td>{$ac['monto_actual']}</td>",
                 "<td>{$ac['rut_ganador']}</td>",
                 "<td><div class=\"botonpdf\" onclick=\"getnota(this);\">Obtener PDF</div></td>",
                 "<td><img src=\"fotonota.png\" class=\"verinfo\" />",
                 "<div class=\"infowindow\"><textarea data-idmr=\"{$ac['id_miniremate']}\">$infocontent</textarea></div></td>",
                 "<td><input type=\"radio\" name=\"pagado_{$ac['id_miniremate']}\" value=\"1\" {$checksi}/>Sí",
                 "<input type=\"radio\" name=\"pagado_{$ac['id_miniremate']}\" value=\"0\" {$checkno}/>No</td>",
                 "</tr>";
        }
    ?>
<tr><td colspan="9"><button id="delselected">Eliminar seleccionados</button></td></tr>