<?
$rut_ganador = "11432122";
       $mail = "jureljuan@gmail.com";
       $idmr = 57;
        //define the receiver of the email 
        $to = "$mail"; 
        //define the subject of the email 
        $subject = 'PortalRemate.cl: Felicitaciones!!'; 
        //create a boundary string. It must be unique 
        //so we use the MD5 algorithm to generate a random hash 
        $random_hash = md5(date('r', time())); 
        //define the headers we want passed. Note that they are separated with \r\n 
        $headers = "From: info@portalremate.cl\r\nReply-To: info@portalremate.cl"; 
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
Content-Type: text/html; charset="iso-8859-1" 
Content-Transfer-Encoding: 7bit

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
        echo "Mail enviado a $to\n";
        //if the message is sent successfully print "Mail sent". Otherwise print "Mail failed" 
        echo $mail_sent ? "Mail sent" : "Mail failed"; 
?>
