<?
    // ofertador de miniremates
    include("header.php");
    function jerror($txt = false)
    {
        mysql_query("unlock tables");
        die(json_encode(array('error' => $txt)));
    }
    if (!ini()) jerror("Inicie sesión para poder participar en los miniremates.");
    // json de entrada: oferta e id_miniremate
    if (!isset($_GET['oferta']) || !isset($_GET['id_miniremate'])) jerror("variables unset"); 
    if (!is_numeric($_GET['oferta']) || !is_numeric($_GET['id_miniremate'])) jerror("var2");
    $oferta = (int) $_GET['oferta'];
    $idmr = (int) $_GET['id_miniremate'];
    
    mysql_query("lock tables miniremates write, productos write");
        $r = mysql_query("select * from miniremates join productos using (id_producto) where id_miniremate = {$idmr} and fecha_inicio < NOW() and NOW() < fecha_termino", dbConn::$cn); // tiene que estar en curso
        if ($r === false || mysql_num_rows($r) == 0) jerror("no hay filas");
        $row = mysql_fetch_assoc($r);
        if ($row['finalizado'] == "1") jerror("finished");
        // ver si oferta supera
        
        if ((int)$row['monto_actual'] >= $oferta) jerror("muy bajo");
        if ($row['rut_ganador'] == $_SESSION['rut']) jerror("No puede mejorar su propia oferta."); // no puede mejorar su propia oferta
        
        $rutanterior = $row['rut_ganador'];
        
        $r2 = mysql_query("update miniremates set limpio = false, monto_actual = {$oferta}, rut_ganador = {$_SESSION['rut']} where id_miniremate = {$idmr}", dbConn::$cn);
        mysql_query("unlock tables");
        
        if (!$r2) jerror("falla consulta");
        else
        {
            
            // Mail al que perdio
            $r3 = mysql_query("select email from users where rut = $rutanterior", dbConn::$cn);
            if (!$r3) jerror("falla consulta mail");
            list($mail) = mysql_fetch_row($r3);
            $body = <<<END
<html>
<body>
<strong>PORTALREMATE.CL</strong>

<p>TU OFERTA DE {$row['descripcion']} HA SIDO SUPERADA!</p>

<p>Ingresa Nuevamente a los Remates Express de <a href="http://www.portalremate.cl">www.portalremate.cl</a> , y oferta nuevamente para no dejar pasar esta gran oportunidad.</p>

<p>ApresÚrate!!</p>

<p>Se Despide:<br />

Equipo PortalRemate.cl</p>
</body>
</html>
END;
            $body = strtoupper($body);
            $subject = "PortalRemate.cl: Oferta superada!";
            $headers = implode("\r\n", array("From: info@portalremate.cl",
                                           "To: $mail",
                                           "Reply-To: info@portalremate.cl",
                                           "X-Mailer: PHP/".phpversion(),
                                           "MIME-Version: 1.0",
                                           "Content-type: text/html; charset=utf-8"
                                           ));
            mail($mail, $subject, $body, $headers, "-finfo@portalremate.cl");
    
        }
        die(json_encode(true));
?>
