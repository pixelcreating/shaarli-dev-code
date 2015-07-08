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
$datastore = unserialize(gzinflate(base64_decode(substr(file_get_contents('data/datastore.php'),strlen('<?php /* '),-strlen(' */ ?>')))));
$fileopen=(fopen('website.log','a+'));
echo '<pre>';
foreach($datastore as $shaarlink) {
	$statut = get_http_response_code($shaarlink['url']);
	if($statut != 200) {
		$msglog = '('.$statut.') '.$shaarlink['url'].PHP_EOL;
		$msg = '(<a href="https://en.wikipedia.org/wiki/HTTP_'.$statut.'">'.$statut.'</a>) '.$shaarlink['url'].PHP_EOL;
		echo $msg;
		ob_flush();
        flush();
		fwrite($fileopen,$msglog);
	}
}
echo '</pre>';
$end = microtime(TRUE);
echo round(($end - $begin),6).' seconds';
echo "Done";
fclose($fileopen);
ob_end_flush();
?>
