<?php
error_reporting(0);
set_time_limit(0);
if(ob_get_level() == 0) ob_start();
$begin = microtime(TRUE);
function get_http_response_code($theURL) { # http://fr2.php.net/manual/fr/function.get-headers.php#97684
   $headers = @get_headers($theURL);
   if(empty($headers[0])) {
	   $code = 000;
   }
   else {
	   $code = substr($headers[0], 9, 3);
   }
   return $code;
}
$link = array();
$datastore = unserialize(gzinflate(base64_decode(substr(file_get_contents('data/datastore.php'),strlen('<?php /* '),-strlen(' */ ?>')))));
$fileopen=(fopen('website.csv','a+'));
$total = count($datastore);
$progress = 0;
foreach($datastore as $shaarlink) {
	$link[] = $shaarelink['url'];
	$progress +=1;
	$statut = get_http_response_code($shaarlink['url']);
	if($statut != 200) {
		$msglog = '"'.$statut.'", "'.$shaarlink['url'].'"'.PHP_EOL;
		echo floor($progress/$total).'% ('.$progress.'/'.$total.') '.$statut.' '.$shaarlink['url'].PHP_EOL;
		ob_flush();
        flush();
		fwrite($fileopen,$msglog);
	}
}
foreach(array_count_values($link) as $k=>$v) {
	if($v >1) {
		$msglog = '"DOUBLON", "'.$k.'"'.PHP_EOL;
	}
	fwrite($fileopen,$msglog);
}

$end = microtime(TRUE);
echo round(($end - $begin),6).' seconds';
echo "Done";
fclose($fileopen);
ob_end_flush();
?>
