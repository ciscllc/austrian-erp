<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Models\Order;

$orderId = $_GET['order'] ?? null;
if (!$orderId) {
    die('Order ID required');
}

$order = Order::find($orderId);
if (!$order) {
    die('Order not found');
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Signatur - Auftrag <?= $order->order_number ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        #signature-pad {
            border: 1px solid #ddd;
            border-radius: 4px;
            touch-action: none;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto mt-10">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-xl font-bold mb-4">Digital Signatur</h1>
            <p class="text-gray-600 mb-4">
                Auftrag <strong><?= $order->order_number ?></strong> für <?= htmlspecialchars($order->customer->company_name ?: $order->customer->first_name . ' ' . $order->customer->last_name) ?>
            </p>
            
            <canvas id="signature-pad" width="400" height="200" class="w-full"></canvas>
            
            <div class="flex space-x-4 mt-4">
                <button id="clear-btn" class="flex-1 bg-gray-500 text-white py-2 px-4 rounded">
                    Löschen
                </button>
                <button id="save-btn" class="flex-1 bg-blue-600 text-white py-2 px-4 rounded">
                    Signieren
                </button>
            </div>
        </div>
    </div>

    <script src="/js/signature-pad.js"></script>
    <script>
        const canvas = document.getElementById('signature-pad');
        const signaturePad = new SignaturePad(canvas);
        
        document.getElementById('clear-btn').addEventListener('click', () => {
            signaturePad.clear();
        });
        
        document.getElementById('save-btn').addEventListener('click', async () => {
            if (signaturePad.isEmpty()) {
                alert('Bitte unterschreiben Sie zuerst');
                return;
            }
            
            const signatureData = signaturePad.toDataURL('image/png');
            
            try {
                const response = await fetch(`/api/orders/<?= $orderId ?>/signature`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ signature: signatureData })
                });
                
                if (response.ok) {
                    alert('Unterschrift erfolgreich gespeichert');
                    window.close();
                } else {
                    alert('Fehler beim Speichern der Unterschrift');
                }
            } catch (error) {
                alert('Fehler: ' + error.message);
            }
        });
        
        // Resize canvas for mobile
        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext('2d').scale(ratio, ratio);
            signaturePad.clear();
        }
        
        window.addEventListener('resize', resizeCanvas);
        resizeCanvas();
    </script>
</body>
</html>