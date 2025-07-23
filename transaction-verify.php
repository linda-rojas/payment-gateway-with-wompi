<?php
require_once './config.php';


function VerificarEstadoTransaccion($transactionId)
{

    if (!$transactionId) {
        throw new Exception('ID de transacción vacío');
    }
    $url = WOMPI_API_BASE . "/transactions/" . $transactionId;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);

    if (isset($data['data']['status'])) {
        return $data['data'];
    } else {
        throw new Exception('Error al consultar la transacción.');
    }
}
