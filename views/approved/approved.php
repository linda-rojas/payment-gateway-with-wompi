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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
</head>

<body>

    <div class="card-confirmation" id="receipt-card">
        <div class="card-body text-center">
            <div class="d-flex justify-content-center align-items-center gap-2 mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon-check" fill="#28a745" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM6.97 10.03a.75.75 0 0 0 1.08 0l3.992-3.992a.75.75 0 1 0-1.06-1.06L7.5 8.44 5.53 6.47a.75.75 0 0 0-1.06 1.06l2.5 2.5z" />
                </svg>
                <h3 class="text-success mb-0">Pago aprobado</h3>
            </div>
            <p class="text-muted mb-4">Tu transacción fue procesada exitosamente. Gracias por tu compra.</p>

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
                <p><strong class="info-label">Monto pagado:</strong> <?= number_format($amount, 2) . ' ' . htmlspecialchars($currency) ?></p>
            </div>

            <div class="mt-4 d-flex justify-content-center gap-3">
                <a href="http://localhost:8000" class="btn btn-success">Volver al inicio</a>
                <button onclick="generatePDF()" class="btn btn-outline-secondary">Descargar comprobante</button>
            </div>
        </div>
    </div>

    <script>
        async function generatePDF() {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF();
            const now = new Date();
            const fecha = now.toLocaleDateString();
            const hora = now.toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            });

            const empresa = "Tienda Virtual Wompi";
            const titulo = "COMPROBANTE DE PAGO";

            const datosCliente = {
                "Nombre": "<?= htmlspecialchars($user_name) ?>",
                "Cédula": "<?= htmlspecialchars($user_id) ?>",
                "Tipo de pagador": "<?= htmlspecialchars($payer_type) ?>"
            };

            const datosPago = {
                "Banco": "Bancolombia",
                "Descripción": "<?= htmlspecialchars($description) ?>",
                "Transacción #": "<?= htmlspecialchars($reference) ?>",
                "Monto": "$<?= number_format($amount, 2) . ' ' . htmlspecialchars($currency) ?>"
            };

            doc.setFont('helvetica', 'bold');
            doc.setFontSize(16);
            doc.setTextColor(33, 37, 41);
            doc.text(empresa, 20, 20);

            doc.setFontSize(12);
            doc.setFont('helvetica', 'normal');
            doc.setTextColor(100);
            doc.text(titulo, 20, 28);
            doc.text(`Fecha: ${fecha} - ${hora}`, 20, 34);

            doc.setDrawColor(200);
            doc.line(20, 38, 190, 38);

            let y = 50;
            doc.setFontSize(12);
            doc.setFont('helvetica', 'bold');
            doc.setTextColor(44, 62, 80);
            doc.text("🔹 Información del cliente:", 20, y);

            doc.setFont('helvetica', 'normal');
            doc.setFontSize(11);
            y += 8;
            for (const [label, value] of Object.entries(datosCliente)) {
                doc.text(`${label}:`, 25, y);
                doc.setTextColor(60);
                doc.text(`${value}`, 70, y);
                doc.setTextColor(44, 62, 80);
                y += 8;
            }

            y += 5;
            doc.setFont('helvetica', 'bold');
            doc.text("🔹 Detalles del pago:", 20, y);
            doc.setFont('helvetica', 'normal');
            doc.setFontSize(11);
            y += 8;

            for (const [label, value] of Object.entries(datosPago)) {
                doc.text(`${label}:`, 25, y);
                doc.setTextColor(60);
                doc.text(`${value}`, 70, y);
                doc.setTextColor(44, 62, 80);
                y += 8;
            }

            y += 5;
            doc.setDrawColor(200);
            doc.line(20, y, 190, y);

            doc.setFontSize(10);
            doc.setTextColor(120);
            doc.setFont('helvetica', 'italic');
            doc.text("✅ Este comprobante ha sido generado electrónicamente.", 20, y + 10);
            doc.text("Gracias por confiar en nosotros.", 20, y + 16);

            doc.save("comprobante_pago.pdf");
        }
    </script>

</body>

</html>