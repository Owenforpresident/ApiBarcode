<?php 
$ref = $_GET['ref'];
$ch = curl_init("http://api.img4me.com/?text=".$ref."&font=Impact&fcolor=000000&size=30&bcolor=FFFFFF&type=png");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
));

$result2 = curl_exec($ch);
curl_close($ch);

$url = $result2;
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

