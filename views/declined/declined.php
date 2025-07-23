<?php
session_start();

$reference   = $_SESSION['reference']   ?? 'N/A';
$amount      = $_SESSION['amount']      ?? '0.00';
$currency    = $_SESSION['currency']    ?? 'COP';
$description = $_SESSION['description'] ?? 'Descripción no disponible';
$user_name   = $_SESSION['user_name']   ?? 'Nombre no disponible';
$user_id     = $_SESSION['legal_id']    ?? 'ID no disponible';
$payer_type  = 'Persona natural';

unset($_SESSION['reference'], $_SESSION['amount'], $_SESSION['currency']);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Comprobante de Pago</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./styles.css">
</head>

<body>

    <div class="card-confirmation card border-danger mb-3" style="max-width: 40rem;">
        <div class="card-body text-center">
            <!-- Icono de alerta -->
            <div class="d-flex justify-content-center align-items-center gap-2 mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon-x" fill="#dc3545" viewBox="0 0 16 16" width="40" height="40">
                    <path d="M2 2L14 14M14 2L2 14" stroke="#dc3545" stroke-width="2" />
                </svg>
                <h3 class="text-danger mb-0">Transacción Rechazada</h3>
            </div>
            <p class="text-muted mb-4">Tu transacción no pudo ser procesada. Intenta de nuevo o contacta con el soporte.</p>

            <div class="mb-4 d-flex justify-content-center align-items-center gap-3">
                <strong class="me-2">Banco:</strong>
                <img src="https://www.misole.co/wp-content/uploads/2019/12/logo-bancolombia.png" class="bancolombia-logo" alt="Bancolombia">
            </div>

            <div class="transaction-info text-start">
                <p><strong class="info-label">Nombre del usuario:</strong> <?= htmlspecialchars($user_name) ?></p>
                <p><strong class="info-label">Número de cédula:</strong> <?= htmlspecialchars($user_id) ?></p>
                <p><strong class="info-label">Tipo de pagador:</strong> <?= htmlspecialchars($payer_type) ?></p>
                <p><strong class="info-label">Descripción del pago:</strong> <?= htmlspecialchars($description) ?></p>
                <p><strong class="info-label">Número de transacción:</strong> <?= htmlspecialchars($reference) ?></p>
                <p><strong class="info-label">Monto intentado:</strong> <?= number_format($amount, 2) . ' ' . htmlspecialchars($currency) ?></p>
            </div>

            <div class="mt-4 d-flex justify-content-center gap-3">
                <a href="http://localhost:8000" class="btn btn-danger">Volver al inicio</a>
            </div>
        </div>
    </div>

</body>

</html>