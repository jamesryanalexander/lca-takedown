<?php

/*   ---------------------------------------------

Author : James Alexander

License: MIT (see http://opensource.org/licenses/MIT and LICENSE.txt which should be in the root folder with this file)
			
Date of creation : 2013-12-22
Last modified : 2013-12-22

Plugin providing functions which could be used in multiple LCA tools and/or multiple instances of the same tool.
Stored here mostly to keep main files cleaner.
			
---------------------------------------------   */

function setupdataurl($inputfile) {
	/* in case a real file is passed instead of _FILES (should not happen in current setup) 
	or in case something went wrong and file is not stored in system anymore. */
	if (!array_key_exists('tmp_name', $inputfile)) {
		if (array_key_exists('name', $inputfile)) {
			$inputfile['tmp_name'] = $inputfile['name'];
		} else {
			return 'No file appears to be present, please try again';
		}
	}

	$tempfile = array();
	$tempfile['file_name'] = $inputfile['name'];
	$datatemp = file_get_contents($inputfile['tmp_name']);
	$datatemp = base64_encode($datatemp);
	$uri = 'data: '. $inputfile['type'].';base64,'.$datatemp;
	$tempfile['file'] = $uri;
	
	return $tempfile;
}

function curlAPIpost ($url,$data,$headers='',$debug=0) {
	if ($debug=1) {
		$f = fopen('request.txt', 'w');
	}
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_VERBOSE, true);
	if ($debug=1) {
	curl_setopt($ch, CURLOPT_STDERR, $f);
}

$result = curl_exec($ch);
if ($debug=1) {
fclose($f);
}
curl_close($ch);
return $result
}