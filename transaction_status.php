<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Estado de Transacción</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php

    require './transaction-verify.php';

    $transactionId = $_GET['id'] ?? null;

    if (!$transactionId) {
        die("<div class='alert alert-danger' role='alert'>No se encontró el ID de la transacción.</div>");
    }


    try {
        session_start();

        $data = VerificarEstadoTransaccion($transactionId);

        // Mostrar información de la transacción
        $status = $data['status'];
        $reference = $data['reference'];
        $amount = $data['amount_in_cents'] / 100;
        $currency = $data['currency'];
        $description = $data['payment_method']['payment_description'];
        $user_name = $data['merchant']['name'];
        $legal_id = $data['merchant']['legal_id'];

        // Puedes guardar en session aquí:
        $_SESSION['reference'] = $reference;
        $_SESSION['amount'] = $amount;
        $_SESSION['currency'] = $currency;
        $_SESSION['description'] = $description;
        $_SESSION['user_name'] = $user_name;
        $_SESSION['legal_id'] = $legal_id;

        // Mostrar el estado de la transacción con un mensaje estilizado
        if ($status === 'APPROVED') {
            header("Location: ./views/approved/approved.php");
        } elseif ($status === 'PENDING') {
            echo "<div class='alert alert-warning' role='alert'>El pago está pendiente. Por favor, verifica más tarde.</div>";
        } elseif ($status === 'DECLINED') {
            header("Location: ./views/declined/declined.php");
        } else {
            echo "<div class='alert alert-secondary' role='alert'>Estado desconocido: " . $status . "</div>";
        }

        // Mostrar más detalles de la transacción
        echo "<br><strong>Referencia:</strong> " . $reference . "<br>";
        echo "<strong>Monto:</strong> " . $amount . " " . $currency;
    } catch (Exception $e) {
        echo "<div class='alert alert-danger' role='alert'>" . $e->getMessage() . "</div>";
    }

    ?>
</body>

</html>