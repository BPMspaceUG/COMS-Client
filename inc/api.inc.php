<?php

function api($data) {

	require_once('api.secret.inc.php');

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_URL, $url);

    $data = json_encode($data);
    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Content-Length: ' . strlen($data);
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    return curl_exec($ch);
}
?>