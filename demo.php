<?php

require_once 'classes/HtmlSecure.php';

$html_secure = new HtmlSecure();
$html_secure->get_all_files_of_dir('./website');
$html_secure->genere_base64_version(5);
if(isset($_SERVER['REQUEST_URI'])) {
	$filename = basename(__FILE__);
	var_dump('./website'.str_replace('/'.$filename, '', $_SERVER['REQUEST_URI']));
	echo HtmlSecure::get_html('./website'.str_replace('/'.$filename, '', $_SERVER['REQUEST_URI']), 5)[0];
}
elseif (isset($argv[1])) {
	var_dump('./website'.$argv[1]);
	echo HtmlSecure::get_html('./website'.$argv[1], 5)[0];
}
//foreach ($html_secure->recreate_html_page(5) as $file) {
//	echo "Le fichier {$file} à été regénéré avec succes !\n";
//}
