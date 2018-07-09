<?php

require_once 'classes/HtmlSecure.php';

$nb_encode = 5;

$html_secure = new HtmlSecure();
$html_secure->get_all_files_of_dir('./website');
$html_secure->genere_base64_version($nb_encode);
if(isset($_SERVER['REQUEST_URI'])) {
	$filename = basename(__FILE__);
	echo HtmlSecure::get_html('./website'.str_replace('/'.$filename, '', $_SERVER['REQUEST_URI']), $nb_encode)[0];
}
elseif (isset($argv[1]) && $argv[1] === 'regenere') {
	foreach ($html_secure->recreate_html_page($nb_encode) as $file) {
		echo "Le fichier {$file} à été regénéré avec succes !\n";
	}
}
