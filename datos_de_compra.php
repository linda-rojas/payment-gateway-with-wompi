<?php
require_once 'vendor/autoload.php';


require_once 'InformationPurchase.php';

use InformationCompra\InformationCompra;

Dotenv\Dotenv::createImmutable(__DIR__)->load();
$testApiUrl = $_ENV['TEST_API_URL'];
$tesPublicKey = $_ENV['TEST_PUBLIC_KEY'];
$currency = $_ENV['CURRENCY'];
$reference = uniqid('order_');
$wompiIntegrityKey = $_ENV['WOMPI_INTEGRITY_KEY'];

$consumption_percentage = 0.05;
$vat_percentage = 0.19;

$total = new InformationCompra($producto, $cantidad, $precio);
$totalAmount = ($total->getTotal()) * 100; // Convertir a centavos
$totalAmountVat = ($totalAmount * $vat_percentage) * 100; // IVA del 19%
$consumption_in_cents = ($totalAmount * $consumption_percentage) * 100; // Impuesto de consumo del 5%
$string_to_sign = $reference . $totalAmount . $currency . $wompiIntegrityKey;
$signature = hash('sha256', $string_to_sign);


?>

<form>
  <script
    src="https://checkout.wompi.co/widget.js"
    data-render="button"
    data-public-key="<?= $tesPublicKey ?>"
    data-currency="<?= $currency ?>"
    data-amount-in-cents="<?= $totalAmount ?>"
    data-reference="<?= $reference ?>"
    data-signature:integrity="<?= $signature ?>"
    data-redirect-url="http://localhost:8000/"></script>
</form>