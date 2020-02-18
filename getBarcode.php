<?php 
$EAN13 = $_GET['ean13'];
$url = "http://www.codebarre.be/phpbarcode/barcode.php?code=".$EAN13."&encoding=&scale=2.5&mode=png,2,50,100)";
$img = "img/barcodes/".$EAN13.".png";
$ch = curl_init($url);
$fp = fopen($img, 'wb');
curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_exec($ch);
curl_close($ch);
fclose($fp);
?>
<script> console.log('getBarcode ran.. ') </script>