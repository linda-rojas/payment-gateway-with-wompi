<?php
require_once './InformationPurchase.php';
require_once './acceptance_token.php';
require_once './config.php';
require_once './transaction-verify.php';


require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use InformationCompra\InformationCompra;

$currency = $_ENV['CURRENCY'];
$reference = uniqid('order_');
$wompiIntegrityKey = $_ENV['WOMPI_INTEGRITY_KEY'];
$acceptance_token = obtenerTokenAcceptacion();

$total = new InformationCompra();
$totalAmount = ($total->getTotal()) * 100; // Convertir a centavos
$string_to_sign = $reference . $totalAmount . $currency . $wompiIntegrityKey;
$signature = hash('sha256', $string_to_sign);

$data = [
  "acceptance_token" => $acceptance_token,
  "amount_in_cents" => $totalAmount,
  "currency" => "COP",
  "signature" => $signature,
  "payment_method" => [
    "type" => "BANCOLOMBIA_TRANSFER",
    "payment_description" => "Pago a Tienda Wompi",
    "ecommerce_url" => "http://localhost:8000/transaction_status.php",
    "user_type" => "PERSON"
  ],
  "customer_email" => $_ENV['CUSTOMER_EMAIL'],
  // "redirect_url" => "http://localhost:8000/transaction_status.php",
  "reference" => $reference
];


$ch = curl_init(WOMPI_API_BASE . "/transactions");

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  'Content-Type: application/json',
  'Authorization: Bearer ' . TEST_PUBLIC_KEY
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
$response = curl_exec($ch);
curl_close($ch);


$result = json_decode($response, true);


if (isset($result['data']['id'])) {
  $transactionId = $result['data']['id'];

  do {
    $transaction = VerificarEstadoTransaccion($transactionId);
  } while (!array_key_exists('async_payment_url', $transaction['payment_method']['extra']));

  $urlToRedirect = $transaction['payment_method']['extra']['async_payment_url'];
  header("Location: $urlToRedirect");
  exit;
} else {
  echo "Error al crear la transacción.";
};
