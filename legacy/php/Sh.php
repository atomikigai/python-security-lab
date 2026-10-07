<?php
$data = 'REDACTED_NUMERIC_IDENTIFIER|05|2027|460
REDACTED_NUMERIC_IDENTIFIER|05|2027|460
REDACTED_NUMERIC_IDENTIFIER|05|2027|460';

$data = urlencode($data);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://example.invalid/legacy");
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
$response = curl_exec($ch);

echo $response;