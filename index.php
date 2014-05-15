<?php

/*
CSS Builder. This is a quick and dirty tool for generating one minimized CSS file from a bunch scattered on
different servers.

The CSS files that populate the final product are set in order in css.json. To learn more, see the Github
repo at http://github.com/gvsulib/opac
*/


if((isset($_GET['path'])) && (isset($_GET['key']))) {
	
	include('config.sample.php');
	
	$path = $_GET['path'];
	$key = $_GET['key'];
	
	if($key == $auth_key) {

		// Set initial variables
		$prefs = "css.json";
		$new_css = $path . '/styles.css';
		$i = 1;

		$full_path = $path . '/' . $prefs;

		// Parse the preferences file
		$json = json_decode(file_get_contents($full_path), true);
		$count = count($json);

		// Cycle through all the CSS files and add them to a single file
		while($i <= $count) {
			addStyles($i, $json[$i]);
			$i++;
		}
		
		echo 'Your shiny new CSS file is ready.';
		
	} else { // Key didn't match
		echo 'That key did not work.';
	}
} else { // Missing Key or Path
	
	echo 'Looks like you are missing something.';
}


function addStyles($i, $url) {
	
	// Add new styles to iii.css document

	global $new_css;

	$css = compress(file_get_contents($url));
	
	if($i == 1) { // Erase existing file
	
		file_put_contents ($new_css, $css);
	
	} else { // Append to file
	
		file_put_contents ($new_css, $css, FILE_APPEND);
	}
}

// This function by Reinhold Weber, cited at http://www.catswhocode.com/blog/3-ways-to-compress-css-files-using-php
// Compress CSS on the fly

function compress($buffer) {
	
    // Remove comments
    $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);

    // Remove tabs, spaces, newlines, etc.
    $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);

    return $buffer;
 }


