<?php 
$ref = $_GET['ref'];
$req = "http://api.img4me.com/?text=".$ref."&font=arial&fcolor=000000&size=10&bcolor=FFFFFF&type=png";
$url = "";
$img = "img/refs/".$ref.".png";
$ch = curl_init($url);
$fp = fopen($img, 'wb');
curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_exec($ch);
curl_close($ch);
fclose($fp);
?>
<script> console.log('getRef ran.. ') </script>