<?
    function esta_entre($now, $h1, $h2)
    {
        if ((int)$h1[0] > (int)$now[0] || (int)$h2[0] < (int)$now[0]) return false;
        if ((int)$h1[0] == (int)$now[0] && (int)$h1[1] > (int)$now[1]) return false;
        if ((int)$h2[0] == (int)$now[0] && (int)$h2[1] < (int)$now[1]) return false;
        return true;
        
    }
    // miniauction_updater.php 
    include("header.php");
    
    function jdie()
    {
        die(json_encode(false));
    }
    
    $remates_rut_1111 = 0;
    
    $r0 = mysql_query("select count(*) from miniremates where miniremates.rut_ganador = 1111 and miniremates.finalizado = false and miniremates.fecha_inicio <= NOW()", dbConn::$cn);
    
    list($remates_rut_1111) = mysql_fetch_row($r0);
    $ofertas = 0;
    
    $response = array();
        
    $r = mysql_query("select miniremates.limpio, miniremates.foto, miniremates.delta, miniremates.texto, miniremates.titulo, miniremates.id_miniremate, miniremates.id_producto, miniremates.rut_ganador, TIMEDIFF(miniremates.fecha_termino,NOW()) as restante, miniremates.fecha_termino, miniremates.fecha_inicio, miniremates.monto_inicial, miniremates.monto_actual, miniremates.incremento, miniremates.finalizado as killme from miniremates join productos using (id_producto) where finalizado = false and miniremates.fecha_inicio <= NOW() order by restante limit 12", dbConn::$cn);
    if ($r !== false and mysql_num_rows($r) >= 0)
    {
        while($row = mysql_fetch_assoc($r))
        {
            //error_log($row['fecha_inicio']);
            //error_log( time() - @strtotime($row['fecha_inicio']) );
            // Existe un 25% de probabilidad de que se haga la oferta
            
            // Si hay horario loco, la oferta se hace solo si no sobrepasa la cantidad minima de remates vacios
            
            $now = explode(":", date("H:i"));
            $hinicio = explode(":", consts::$hinicio_rut);
            $htermino = explode(":", consts::$htermino_rut);
            
            $horario = esta_entre($now, $hinicio, $htermino) == true;
            
            
            $se_hace = ((float)rand()/(float)getrandmax()) > 0.85 ? true : false;
            
            if (@strtotime($row['fecha_inicio']) + $row['delta'] <= time() // Se cumplen el delta random
            && $row['limpio'] == "1" // No tiene ofertas previas
            && $row['rut_ganador'] == "1111" // No ha sido ofertado por bots
            && $se_hace // Se cumple el 25% de chance
            && (!$horario || $remates_rut_1111 > consts::$h_vacios_minimos)) // Si esta en el horario bajo, hay suficientes remates sin ofertas
            {
                // agrego una oferta random
                $oferta = $row['monto_inicial'] * (1 + $row['incremento'] / 100);
                $idmr = $row['id_miniremate'];
                $rutrandom = rand(6000000, 15000000);
                $r2 = mysql_query("update miniremates set monto_actual = {$oferta}, rut_ganador = $rutrandom where id_miniremate = {$idmr}", dbConn::$cn);
                $ofertas++;
            }
            if (@strtotime($row['fecha_termino']) + 5 <= time())
            {
//                rematelog($row['fecha_termino']);
                if ($row['limpio'] == "1") // Termino sin ofertas
                {
                    $r2 = mysql_query("delete from miniremates where id_miniremate = {$row['id_miniremate']}", dbConn::$cn);
                    continue;
                }
                else
                    $r2 = mysql_query("update miniremates set finalizado = true where id_miniremate = {$row['id_miniremate']}", dbConn::$cn);
                $row['killme'] = true;
            }
            $row['titulo'] = strtoupper($row['titulo']);
            $rutoculto = preg_replace('/([0-9]*)([0-9]{3})/', "$1XXX", $row['rut_ganador']);
            $rutoculto_puntos = substr(currf($rutoculto), 1);
            $monto = currf($row['monto_actual']);
            $yogano = false;
            if (isset($_COOKIE['sessid']))
            {
                session_id($_COOKIE['sessid']);
                session_start();
            }
            //$debug = "GANA: " . $row['rut_ganador'] . "; soy: " . $_SESSION['rut'];
            if ($row['rut_ganador'] == $_SESSION['rut']) // si yo gano, marco el flag
                $yogano = true;
            $monto2 = currf((string)round($row['monto_actual'] + $row['monto_inicial']*(float)$row['incremento']/100));
//            rematelog("$monto2 ini:{$row['monto_inicial']}");
            $response["{$row['id_miniremate']}"] = array_merge($row, array('monto' => $monto, 'monto2' => $monto2, 'rutoculto_puntos' => $rutoculto_puntos, 'yogano' => $yogano));
        }
        if (count($response) == 0)
            $response = array("none" => true);
        die(json_encode((object)$response));
    }
    else jdie();
?>
