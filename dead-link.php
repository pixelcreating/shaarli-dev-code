<?php
$begin = microtime(TRUE);
define('HOST_SHAARLI', 'your-shaarli.example');
define('URL_SHAARLI', 'http://your-shaarli.example/links/');
define('TOTAL_PAGE', 100);
error_reporting(0);
set_time_limit(0);
function get_http_response_code($theURL) { # http://fr2.php.net/manual/fr/function.get-headers.php#97684
    $headers = get_headers($theURL);
    return substr($headers[0], 9, 3);
}
function search_dead_link($url) {
	$page = file_get_contents($url);
	preg_match_all('#<span class="linktitle"><a href="(.*?)"(.*?)></span>#is',$page,$resultat,PREG_PATTERN_ORDER);
	foreach ($resultat[1] as $liens) {
		$scheme = @parse_url($liens);
		$statut = get_http_response_code($liens);
		if($scheme['host'] != null && $scheme['host'] !=HOST_SHAARLI && $scheme['host'] !='qrfree.kaywa.com' && $statut != 200) {
			echo $liens.' ('.$statut.')'.'<br/>';
			error_log($liens.' ('.$statut.')'.PHP_EOL, 3, "journal.log");
		}
	}
}
$page = 1;
while($page <= TOTAL_PAGE) {
	search_dead_link(URL_SHAARLI.'/?page='.$page);
	$page++;
}
$end = microtime(TRUE);
echo round(($end - $begin),6).' seconds';
?>
