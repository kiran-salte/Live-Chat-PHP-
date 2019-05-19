<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function configeditor ($keyword, $config, $append = 0, $file = null) {
	if ($file == null) {
		$file = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'setting.php';
	}

	$fh = fopen($file, 'r');
	$data = fread($fh, filesize($file));
	fclose($fh);

	$pattern = "/\/\* $keyword START \*\/(\s*)(.*?)(\s*)\/\* $keyword END \*\//is";

	if ($append == 1) {
		$replacement = "/* $keyword START */\r\n\r\n\\2\r\n".$config."\r\n\r\n/* $keyword END */";
	} else {
		$replacement = "/* $keyword START */\r\n\r\n".$config."\r\n\r\n/* $keyword END */";
	}

	$newdata = preg_replace($pattern, $replacement, $data);

	if (is_writable($file)) {
		if (!$handle = fopen($file, 'w')) {
			 echo "Cannot open file ($file)";
			 exit;
		}

		if (fwrite($handle, $newdata) === FALSE) {
			echo "Cannot write to file ($file)";
			exit;
		}

		fclose($handle);

	} else {
		echo "The file $file is not writable. Please CHMOD config.php to 777.";
		exit;
	}
        
}