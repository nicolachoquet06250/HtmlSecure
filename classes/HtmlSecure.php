<?php

class HtmlSecure {

	private static $base64_cache_dir = './cache';
	private static $extention = '.64';

	private $liste_pages;
	public function __construct(array $liste_pages = []) {
		$this->liste_pages = $liste_pages;
	}
	public function get_all_files_of_dir($path = '.') {
		if(is_dir($path)) {
			$dir = opendir($path);
			while (($file = readdir($dir)) !== false) {
				if($file !== '.' && $file !== '..') {
					if(is_file("{$path}/{$file}")) {
						$this->liste_pages[] = "{$path}/{$file}";
					}
					if(is_dir("{$path}/{$file}")) {
						$this->get_all_files_of_dir("{$path}/{$file}");
					}
				}
			}
		}
	}
	public function genere_base64_version($nb_encode = 1) {
		foreach ($this->liste_pages as $path) {
			self::genere_base64($path, $nb_encode);
		}
	}
	public static function genere_base64($path, $nb_encode = 1) {
		if(!is_dir(self::$base64_cache_dir)) {
			mkdir(self::$base64_cache_dir, 0777, true);
		}
		if(is_file($path)) {
			$base64_filename = $path;
			for($i = 0, $max = $nb_encode; $i < $max; $i++)
				$base64_filename = base64_encode($base64_filename);
			$base64_content = file_get_contents($path);
			for($i = 0, $max = $nb_encode; $i < $max; $i++)
				$base64_content = base64_encode($base64_content);
			file_put_contents(self::$base64_cache_dir."/{$base64_filename}.64", $base64_content);
			unlink($path);
		}
		$nb_file = 0;
		$directory = str_replace('/'.basename($path), '', $path);
		if(is_dir($directory)) {
			$dir     = opendir($directory);
			while (($file = readdir($dir)) !== false) {
				if ($file !== '.' && $file !== '..' && is_file($path)) {
					$nb_file++;
				}
			}
		}
		if($nb_file === 0) {
			rmdir($directory);
		}
	}
	public function get_html_version($nb_encode = 1): array {
		$liste_html = [];
		foreach ($this->liste_pages as $path) {
			$liste_html[] = self::get_html($path, $nb_encode)[0];
		}

		return $liste_html;
	}
	public static function get_html($path, $nb_encode = 1): array {
		$base64_filename = $path;
		for($i = 0, $max = $nb_encode; $i < $max; $i++) {
			$base64_filename = base64_encode($base64_filename);
		}
		if(is_file(self::$base64_cache_dir.'/'.$base64_filename.self::$extention)) {
			$base64_content = file_get_contents(self::$base64_cache_dir."/{$base64_filename}".self::$extention);
			$html_content = $base64_content;
			for($i = 0, $max = $nb_encode; $i < $max; $i++) {
				$html_content = base64_decode($html_content);
			}
			return [
				$html_content,
				$path
			];
		}
		return [
			'',
			''
		];
	}
	public function recreate_html_page($nb_encode = 1) {
		$liste_pages = [];
		$this->liste_pages = [];
		$this->get_all_files_of_dir('./cache');
		foreach ($this->liste_pages as $path) {
			if($path_regenerated = self::recreate_html($path, $nb_encode, true)) {
				$liste_pages[] = $path_regenerated;
			}
		}
		return $liste_pages;
	}
	public static function recreate_html($path, $nb_encode = 1, $base64_title = false) {
		if($base64_title) {
			$path = str_replace([self::$extention, self::$base64_cache_dir.'/'], '', $path);
			for($i = 0, $max = $nb_encode; $i<$max; $i++) {
				$path = base64_decode($path);
			}
		}
		list($content, $path_regenerated) = self::get_html($path, $nb_encode);
		if(!is_dir(str_replace('/'.basename($path), '', $path))) {
			mkdir(str_replace('/'.basename($path), '', $path), 0777, true);
		}
		if($path_regenerated !== '') {
			file_put_contents($path_regenerated, $content);
			return $path_regenerated;
		}
		return null;
	}
}