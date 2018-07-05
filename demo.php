<?php

require_once 'classes/HtmlSecure.php';

$html_secure = new HtmlSecure(['./website/index.html',]);
$html_secure->genere_base64_version(1);
foreach ($html_secure->get_html_version(1) as $page) echo $page."\n";
