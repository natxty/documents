<?php
/**
 * User: natxty
 * Date: 10/3/11
 * Time: 1:24 PM
 */

$files = array();
$init_path = "/httpdocs/products-services/";

function directoryToArray($directory, $recursive = TRUE, $include_directories_in_list = FALSE) {
	$array_items = array();
	if ($handle = opendir($directory)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != "..") {
				if (is_dir($directory. "/" . $file)) {
					if($recursive) {
						$array_items = array_merge($array_items, directoryToArray($directory. "/" . $file, $recursive));
					}
					if ( $include_directories_in_list ) {
						$file = $directory . "/" . $file;
						$array_items[] = preg_replace("/\/\//si", "/", $file);
					}
				} else {
					$file = $directory . "/" . $file;
					$array_items[] = preg_replace("/\/\//si", "/", $file);
				}
			}
		}
		closedir($handle);
	}
	return $array_items;
}

function pre($array) {
	print "<pre>\n";
	print_r($array);
	print "\n\n";
	print "<pre>\n";
}


//list_files_recursive($init_path);

$files = directoryToArray($init_path, true, false);

pre($files);
