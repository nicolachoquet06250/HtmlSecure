<?php

require_once 'classes/HtmlSecure.php';

$nb_encode = 1;
$path = './website';

$html_secure = new HtmlSecure();
$html_secure->get_all_files_of_dir($path);
$html_secure->genere_base64_version($nb_encode);
if(isset($_SERVER['REQUEST_URI'])) {
	$filename = basename(__FILE__);
	echo HtmlSecure::get_html($path.str_replace('/'.$filename, '', $_SERVER['REQUEST_URI']), $nb_encode)[0];
}
elseif (isset($argv[1]) && $argv[1] === 'regenere') {
	foreach ($html_secure->recreate_html_page($nb_encode) as $file) {
		echo "Le fichier {$file} à été regénéré avec succes !\n";
	}
}
