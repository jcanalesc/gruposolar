<?
	include("header.php");
	$curl2 = curl_init();
	$res = mysql_query("select id_remate from remates where en_curso = true", dbConn::$cn);
	
	$ides = array();
	while($d = mysql_fetch_row($res))
		$ides[] = $d[0];
	for($i = 0; $i < 50; $i++)
	{
		foreach($ides as $id)
		{
			$data = urlencode("id_remate")."=".urlencode($id)."&auth=".urlencode(md5(consts::$key));
		    curl_setopt_array($curl2, 
		               array(
		                  CURLOPT_HEADER => 1,
		                  CURLOPT_URL => "http://localhost/auction_updater.php",
		                  CURLOPT_POST => 1,
		                  CURLOPT_POSTFIELDS => $data,
		                  CURLOPT_COOKIEFILE => "cookies.txt",
		                  CURLOPT_COOKIEJAR => "cookies.txt"
		                  ));
		    curl_exec($curl2);
		}
		sleep(1);
	}
?>
