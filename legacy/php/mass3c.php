<?php

require __DIR__ . "/Encryptions/Encryptions_Adyen.php";


$encryption = new Encryptions();

$encryption->setData([
    "adyenkey" => "REDACTED_CREDENTIAL",
    "card" => "REDACTED_NUMERIC_IDENTIFIER",
    "month" => "12",
    "year" => "2023",
    "cvv" => "123",
    "version" => "v2",
]);

$encryption->setScriptPath('adyen.js');
$encryption->setResultFilePath('result.json');
$encryption->setAdyenVersion('25'); // Set the Adyen version
$encryption->execute();
$response = $encryption->getResponse();

var_dump($response);