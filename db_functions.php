
<?php
require $filepath_liam."api/lib/php_crud_api_transform.inc.php";

	function api($method, $url, $data = false) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($ch, CURLOPT_URL, $url);
		if ($data) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			$headers = array();
			$headers[] = 'Content-Type: application/json';
			$headers[] = 'Content-Length: ' . strlen($data);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		return curl_exec($ch);
	}
	function clrjson($input){
		$jsonObject = json_decode($input, true);

	$output = php_crud_api_transform($jsonObject);
	//$output = json_encode($jsonObject, JSON_PRETTY_PRINT);
	return $output;
	}
	?>