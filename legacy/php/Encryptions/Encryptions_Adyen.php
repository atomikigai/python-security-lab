<?php


class Encryptions
{
    private $data;
    private $adyenVersion;
    private $response;

    private $nodeScriptPath = '/REDACTED_OPAQUE_LITERAL.js';

    public function setData(array $data)
    {
        $this->data = $data;
    }

    public function setAdyenVersion($version)
    {
        $this->adyenVersion = $version;
    }

    public function execute()
    {
        if (empty($this->nodeScriptPath)) {
            $this->response = "Error: No se ha definido la ruta del script Node.js.";
            return;
        }

        $adyenkey = escapeshellarg($this->data['adyenkey'] ?? '');
        $card = escapeshellarg($this->data['card'] ?? '');
        $month = escapeshellarg($this->data['month'] ?? '');
        $year = escapeshellarg($this->data['year'] ?? '');
        $cvv = escapeshellarg($this->data['cvv'] ?? '');
        $version = escapeshellarg($this->adyenVersion ?? '');
        $ppkey = escapeshellarg($this->data['ppkey'] ?? '');
        $domain = escapeshellarg($this->data['domain'] ?? '');

        $command = "node {$this->nodeScriptPath} {$adyenkey} {$card} {$month} {$year} {$cvv} {$version} {$ppkey} {$domain}";

        $this->response = shell_exec($command);

        if ($this->response === null) {
            $this->response = "Error: No se pudo ejecutar el comando o no se obtuvo salida.";
        }
    }

    public function getResponse()
    {
        $result = json_decode($this->response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return "Error al decodificar JSON: " . json_last_error_msg();
        } elseif (isset($result['error'])) {
            return "Error: " . $result['error'];
        } else {
            return $result;
        }
    }
}


$encryption = new Encryptions();

$encryption->setData([
    "adyenkey" => "REDACTED_CREDENTIAL",
    "card" => 'REDACTED_NUMERIC_IDENTIFIER',
    "month" => '11',
    "year" => '2025',
    "cvv" => '322',
]);
$encryption->setAdyenVersion('v2');
$encryption->execute();
print_r($response = $encryption->getResponse());



