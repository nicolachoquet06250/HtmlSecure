<?php

class HtmlSecure {

	private static $base64_cache_dir = './cache';

	private $liste_pages;
	public function __construct(array $liste_pages = []) {
		$this->liste_pages = $liste_pages;
	}

	public function genere_base64_version($nb = 1) {
		foreach ($this->liste_pages as $path) {
			self::genere_base64($path, $nb);
		}
	}

	public static function genere_base64($path, $nb = 1) {
		if(!is_dir(self::$base64_cache_dir)) {
			mkdir('./cache', 0777, true);
		}

		if(is_file($path)) {
			$base64_filename = str_replace('.html', '', basename($path));
			for($i = 0, $max = $nb; $i<$max; $i++)
				$base64_filename = base64_encode($base64_filename);
			$base64_content = file_get_contents($path);
			for($i = 0, $max = $nb; $i<$max; $i++)
				$base64_content = base64_encode($base64_content);
			file_put_contents(self::$base64_cache_dir."/{$base64_filename}.64", $base64_content);
		}
	}

	public function get_html_version($nb = 1): array {
		$liste_html = [];
		foreach ($this->liste_pages as $path) {
			$liste_html[] = self::get_html($path, $nb);
		}

		return $liste_html;
	}

	public static function get_html($path, $nb = 1): string {
		$filename = str_replace('.html', '', basename($path));
		for($i = 0, $max = $nb; $i<$max; $i++)
			$base64_filename = base64_encode($filename);
		if(is_file(self::$base64_cache_dir."/{$base64_filename}.64")) {
			$base64_content = file_get_contents(self::$base64_cache_dir."/{$base64_filename}.64");
			$html_content = $base64_content;
			for($i = 0, $max = $nb; $i<$max; $i++)
				$html_content = base64_decode($html_content);
			return $html_content;
		}
		return '';
	}
}