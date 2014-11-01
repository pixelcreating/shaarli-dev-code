<?php
define('ROOT_FERME', 'http://your-shaarferme.shaarli/');
function list_dir($dir) {
	$test = scandir($dir);
	$array = array();
	  foreach($test as $tableau) {
	if(is_dir($tableau) == true AND $tableau !='.' AND $tableau !='..' AND $tableau !='index.php') {
		$dossier = scandir($tableau);
		// list_dir($dossier);
		$array[$tableau] = $dossier;
	}
	else {
		$array[] = $tableau;
	}
	}
		return $array;
	}
	 $array = list_dir('s/');
if(((fileatime('shaarlis.opml')+60*60*24) < time()) OR !file_exists('shaarlis.opml')) {
	$file = '<opml version="1.1">'.PHP_EOL;
		$file .= "\t".'<head>'.PHP_EOL;
		$file .= "\t\t".'<title>Flux RSS des Shaarlifermes</title>'.PHP_EOL;
		$file .= "\t\t".'<dateCreated>2014-07-09T20:34:03+00:00</dateCreated>'.PHP_EOL;
		$file .= "\t\t".'<dateModified>'.date('c').'</dateModified>'.PHP_EOL; // 		$file .= "\t\t".'<dateCreated>2014-07-09T22:40:47+01:00</dateCreated>'.PHP_EOL;
		$file .= "\t".'</head>'.PHP_EOL;
		$file .= "\t".'<body>'.PHP_EOL;
	 foreach($array as $k=>$a) {
		if(preg_match_all('#data-7987213-(.*)#', $a, $matches, PREG_SET_ORDER)) {
			include 's/data-7987213-'.$matches[0][1].'/config.php';
			$url = ROOT_FERME.$matches[0][1];
			$file .= '<outline text="'.$GLOBALS['title'].'" htmlUrl="'.$url.'/" xmlUrl="'.$url.'/?do=rss"/>'.PHP_EOL;
		}
		file_put_contents('shaarlis.opml', $file);
	 }
		$file .= "\t".'</body>'.PHP_EOL;
		$file .= '</opml>'.PHP_EOL;
		file_put_contents('shaarlis.opml', $file);
		header('Content-type: text/xml');
		echo $file;
}
else {
	header('Content-type: text/xml');
	echo file_get_contents('shaarlis.opml');
}
