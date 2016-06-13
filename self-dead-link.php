<?php
error_reporting(0);
set_time_limit(0);
if(ob_get_level() == 0) ob_start();
$begin = microtime(TRUE);
function get_headers_curl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,            $url);
    curl_setopt($ch, CURLOPT_HEADER,         true);
    curl_setopt($ch, CURLOPT_NOBODY,         true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT,        15);

    $r = curl_exec($ch);
	return empty(substr($r, 9, 3)) ? 0 : substr($r, 9, 3);
	return $r;
}
function get_title($url) {
	preg_match('#<title ?[^>]*>(.*)</title>#Usi', file_get_contents($url), $title);
	return isset($title[1]) ? $title[1] : null;
}
$link = array();

$down_website=(fopen('down_website.csv','a+'));
$double_website=(fopen('double_website.csv','a+'));

fwrite($down_website,'"http_status", "url", "title"'.PHP_EOL);

$datastore = unserialize(gzinflate(base64_decode(substr(file_get_contents('datastore.php'),strlen('<?php /* '),-strlen(' */ ?>')))));
foreach($datastore as $shaarlink) {
	if(substr($shaarlink['url'], 0, 1) != '?') { # on vérifie que c’est pas un lien local
		$link[] = $shaarlink['url'];
		$statut = get_headers_curl($shaarlink['url']);
		fwrite($down_website,'"'.$statut.'", "'.$shaarlink['url'].'", "'.get_title($shaarlink['url']).'"'.PHP_EOL);
	}
}
foreach(array_count_values($link) as $k=>$v) {
	if($v >1) {
		fwrite($double_website,'"DOUBLON", "'.$k.'"'.PHP_EOL);
	}	
}
