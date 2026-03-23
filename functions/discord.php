<?php
// functions/discord.php
function sendToDiscord($orderData) {
    // Ganti dengan webhook URL Discord lo
    $webhookURL = "https://discord.com/api/webhooks/1312380149018132521/klRge9Ewg7XmbTi0iZuP4BOsqC9kKcPHSx30now8SY2G6-HOZWzlSp_9WNrvqNO7dtNH";
    
    $embed = [
        "embeds" => [
            [
                "title" => "📢 ORDER JOKI BARU!",
                "description" => "Ada order baru nih, cepet diproses!",
                "fields" => [
                    [
                        "name" => "🆔 Order Code",
                        "value" => $orderData['id'],
                        "inline" => true
                    ],
                    [
                        "name" => "👤 Customer",
                        "value" => $orderData['username'],
                        "inline" => true
                    ],
                    [
                        "name" => "🎮 Layanan",
                        "value" => $orderData['layanan'],
                        "inline" => false
                    ],
                    [
                        "name" => "📊 Current Rank",
                        "value" => $orderData['current_rank'] ?: "Tidak disebutkan",
                        "inline" => true
                    ],
                    [
                        "name" => "🎯 Target Rank",
                        "value" => $orderData['target'],
                        "inline" => true
                    ],
                    [
                        "name" => "🆔 ID ML",
                        "value" => $orderData['ml_id'],
                        "inline" => false
                    ],
                    [
                        "name" => "💰 Harga",
                        "value" => "Rp " . number_format($orderData['harga'], 0, ',', '.'),
                        "inline" => true
                    ]
                ],
                "color" => 0x00ff00,
                "timestamp" => date("c"),
                "footer" => [
                    "text" => "JokiML System"
                ]
            ]
        ]
    ];
    
    $ch = curl_init($webhookURL);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($embed));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return $httpCode === 204;
}
?>
