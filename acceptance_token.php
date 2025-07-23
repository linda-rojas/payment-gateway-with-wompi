<?php

require_once "./config.php";


function obtenerTokenAcceptacion()
{
    $url = WOMPI_API_BASE . "/merchants/" . TEST_PUBLIC_KEY;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
    $response = curl_exec($ch);
    curl_close($ch);

    
    $data = json_decode($response, true);

    if (isset($data['data']['presigned_acceptance']['acceptance_token'])) {
        return $data['data']['presigned_acceptance']['acceptance_token'];
    } else {
        throw new Exception('Error obteniendo acceptance_token');
    }
}

try {
    $token = obtenerTokenAcceptacion();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
