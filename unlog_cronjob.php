<?
   include("header.php");
   if (substr(getcwd(), 0, 9) == "/srv/http")
    $path = "sistema";
   else
    $path = "dev";
   mysql_query("update conectados_hoy set logged = false where logged = true and TIMESTAMPDIFF(SECOND, fecha, NOW()) > 5", dbConn::$cn);
   mysql_query("update remates set publico = false where TIMESTAMPDIFF(DAY, fecha, DATE(NOW())) >= 1", dbConn::$cn);
   mysql_query("update remates set publico = false, en_curso = false where tipo = 'Presencial' and TIMESTAMPDIFF(HOUR, CONCAT(fecha, ' ', hora), NOW()) >= 1", dbConn::$cn);

   $res = mysql_query("select id_remate, id_sala from remates where id_remate in (select id_remate from remates join lotes using(id_lote) where repartido = false group by id_remate)");
   $curl2 = curl_init();
   curl_setopt_array($curl2,
   array(
      CURLOPT_HEADER => 1,
      CURLOPT_URL => "http://localhost/1/header.php",
      CURLOPT_POST => 1,
      CURLOPT_POSTFIELDS => "func=login&args=".urlencode("17596597;monoculiao242"),
      CURLOPT_COOKIEJAR => "cookies.txt",
      CURLOPT_COOKIEFILE => "cookies.txt"
   )
   );
   curl_exec($curl2);
   if ($res !== false && mysql_num_rows($res) > 0) while(list($id, $sala) = mysql_fetch_row($res))
   {
      $data = urlencode("id_remate")."=".urlencode($id)."&auth=".urlencode(md5(consts::$key));
      curl_setopt_array($curl2, 
               array(
                  CURLOPT_HEADER => 1,
                  CURLOPT_URL => "http://localhost/$sala/auction_updater.php",
                  CURLOPT_POST => 1,
                  CURLOPT_POSTFIELDS => $data,
                  CURLOPT_COOKIEFILE => "cookies.txt",
                  CURLOPT_COOKIEJAR => "cookies.txt"
                  ));
      curl_exec($curl2);
      
   }
   curl_close($curl2);
   
   $rr = mysql_query("select id_miniremate, id_producto, auto, TIMESTAMPDIFF(SECOND, NOW(), fecha_termino) as restante from miniremates where finalizado = false and fecha_inicio < NOW() order by restante limit 12", dbConn::$cn);
   $idprods = array();
   $conteo = 0;
   $conteoa = 0;
   if ($rr !== false && mysql_num_rows($rr) > 0) while(($row = mysql_fetch_assoc($rr)) !== false)
   {
    if (isset($idprods[$row['id_producto']]))
        $idprods[$row['id_producto']]++;
    else
        $idprods[$row['id_producto']] = 1;
    if ($row['auto'] == 1)
        $conteoa++;
    $conteo++;
   }
   
   if ($conteo < 12 && $conteoa < consts::$automaticos[0])
   {
        $espacios = 12 - $conteo;
        $afaltantes = consts::$automaticos[0] - $conteoa;
        $faltan = min($espacios, $afaltantes);
        $delta = rand(60,300);
        rematelog("Se pondran $faltan miniremates automaticos");
        $excluir = count($idprods) > 0 ? "and id_producto not in (" . implode(",", array_keys($idprods)) . ")" : "";
        $queryr = "select * from automaticos where activo = true $excluir order by rand() limit $faltan";
       $rr2 = mysql_query($queryr, dbConn::$cn);
       if ($rr2 !== false && mysql_num_rows($rr2) > 0) while(($row = mysql_fetch_assoc($rr2)) !== false)
       {
          $rr3 = mysql_query("insert into miniremates (id_producto, fecha_inicio, fecha_termino, rut_ganador, monto_actual, incremento, monto_inicial, foto, texto, titulo, auto, delta) values ({$row['id_producto']}, NOW(), TIMESTAMPADD(HOUR, ".consts::$automaticos[2].", TIMESTAMPADD(MINUTE, ".consts::$automaticos[3].", NOW())), 1111, {$row['minimo']},".consts::$automaticos[1].", {$row['minimo']}, '{$row['foto']}','{$row['descripcion']}','{$row['titulo']}', true, $delta)", dbConn::$cn);
          if (!$rr3)
            rematelog("Error al crear un miniremate automatizado.");
       }
   }
   
   
   $r2 = mysql_query("select miniremates.id_miniremate, miniremates.rut_ganador, productos.descripcion, users.nombres, users.apellidop, users.apellidom, users.email from miniremates join users on (users.rut = miniremates.rut_ganador) join productos on (productos.id_producto = miniremates.id_producto) where miniremates.finalizado = true and miniremates.limpio = false and miniremates.notificado = false", dbConn::$cn);
   if ($r2 !== false && mysql_num_rows($r2) > 0)
   {
       while(($row = mysql_fetch_assoc($r2)) !== false)
       {
       echo "miniremate n {$row['id_miniremate']} no esta notificado\n";
       $rut_ganador = $row['rut_ganador'];
       $nombrecompleto = "{$row['nombres']} {$row['apellidop']} {$row['apellidom']}";
       $mail = $row['email'];
       $idmr = $row['id_miniremate'];
        //define the receiver of the email 
        $to = "$mail"; 
        //define the subject of the email 
        $subject = 'PortalRemate.cl: Felicitaciones!!'; 
        //create a boundary string. It must be unique 
        //so we use the MD5 algorithm to generate a random hash 
        $random_hash = md5(date('r', time())); 
        //define the headers we want passed. Note that they are separated with \r\n 
        $headers = "From: info@portalremate.cl\r\nReply-To: info@portalremate.cl"; 
        $headers .= "\r\nTo: info@portalremate.cl, soporte@portalremate.cl, $to";
        //add boundary string and mime type specification 
        $headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\""; 
        //read the atachment file contents into a string,
        //encode it with MIME base64,
        //and split it into smaller chunks
        $attachment = chunk_split(base64_encode(file_get_contents("http://localhost/creanota.php?id_remate=MR$idmr&rut=$rut_ganador"))); 
        //echo $attachment;
        
        $msg = <<<EOF
--PHP-mixed-$random_hash
Content-Type: multipart/alternative; boundary="PHP-alt-$random_hash"

--PHP-alt-$random_hash
Content-Type: text/html; charset="utf-8"

<html>
<body>
<strong>PORTALREMATE.CL</strong>

<p>FELICITACIONES, $nombrecompleto!!</p>

<p>HAS GANADO {$row['descripcion']}</p>

<p>En breves minutos un ejecutivo de PortalRemate se contactará con usted para coordinar los pagos y entregas.</p>

<p>Se Despide:<br />

Equipo PortalRemate.cl</p>
</body>
</html>
        
--PHP-alt-$random_hash--
        
--PHP-mixed-$random_hash
Content-Type: application/pdf; name="NotaDeVenta-$rut_ganador-MR$idmr.pdf"
Content-Transfer-Encoding: base64
Content-Disposition: attachment

$attachment 
--PHP-mixed-$random_hash--
EOF;
        //copy current buffer contents into $message variable and delete current output buffer 
        $message = $msg;
        //send the email 
        $mail_sent = @mail($to, $subject, $message, $headers,"-finfo@portalremate.cl" ); 
        //$mail_sent = true;
        echo "Mail enviado a $to\n";
        //if the message is sent successfully print "Mail sent". Otherwise print "Mail failed" 
        echo $mail_sent ? "Mail sent" : "Mail failed"; 
        if ($mail_sent)
            $r3 = mysql_query("update miniremates set notificado = true where id_miniremate = $idmr", dbConn::$cn);
        usleep(300000);
       }
   }
   else echo "problemas con los miniremates:".mysql_error(dbConn::$cn);
   
   
    $r = mysql_query("select users.email, avisar_remate.rut, avisar_remate.id_remate, remates.descripcion, remates.ciudad, remates.lugar, remates.fecha, remates.hora, remates.tipo from avisar_remate join users using (rut) join remates using (id_remate) where TIMESTAMPDIFF(MINUTE, NOW(), CONCAT(remates.fecha, ' ', remates.hora)) >= 30 and TIMESTAMPDIFF(MINUTE, NOW(), CONCAT(remates.fecha, ' ', remates.hora)) <= 130 and avisar_remate.avisado = false", dbConn::$cn); 
    if ($r !== false && mysql_num_rows($r) > 0) while(($row = mysql_fetch_assoc($r)) !== false)
    {
        $mail = $row['email'];
        $rut = $row['rut'];
        $remate = $row['id_remate'];
        $nombrecompleto = "{$row['nombres']} {$row['apellidop']} {$row['apellidom']}";
        list($ano, $mes, $dia) = explode("-", $row['fecha']);
		$meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
		$fecha_leible = sprintf("el %d de %s del %d a las %s", $dia, $meses[$mes-1], $ano, $row['hora']);
        $descripcion = strtoupper("{$row['tipo']} en {$row['lugar']}, {$row['ciudad']} {$fecha_leible}");
        $body = <<<END
<html>
<body>
<strong>AVISO DE INICIO DE <u>PORTALREMATE.CL</u></strong>

<p>USUARIO $rut ($nombrecompleto)</p>

<p>EL REMATE $descripcion, ESTÁ PRÓXIMO A COMENZAR!</p>

<p>INGRESA A <a href="http://www.portalremate.cl">PortalRemate.cl</a> y participa.</p>

<p>Te esperamos!!</p>
<p>Se Despide:<br />

Equipo PortalRemate.cl</p>
</body>
</html>
END;
        $body = strtoupper($body);
        $subject = "PortalRemate.cl: Aviso de Inicio de Remate";
        $headers = implode("\r\n", array("From: info@portalremate.cl",
                                       "To: $mail",
                                       "Reply-To: info@portalremate.cl",
                                       "X-Mailer: PHP/".phpversion(),
                                       "MIME-Version: 1.0",
                                       "Content-type: text/html; charset=utf-8"
                                       ));
        if (!mail($mail, $subject, $body, $headers, "-finfo@portalremate.cl"))
        //if(false)
            rematelog("Fallo al enviar mail de recordatorio.");
        $r2 = mysql_query("update avisar_remate set avisado = true where id_remate = $remate and rut = $rut", dbConn::$cn);
        if (!$r2)
           rematelog("Fallo al intentar actualizar bdd de correos enviados.");
        usleep(300000);
    }
   rematelog("Ejecutada rutina automatica");
?>
