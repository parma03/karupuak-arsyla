<?php
// src/Infrastructure/Payment/MidtransService.php
namespace Infrastructure\Payment;

use Domain\Order\Entities\Order;
use Domain\User\Entities\User;

class MidtransService
{
    private string $serverKey;
    private string $snapApiUrl;

    public function __construct()
    {
        $this->serverKey  = MIDTRANS_SERVER_KEY;
        $this->snapApiUrl = MIDTRANS_SNAP_API_URL . '/transactions';
    }

    public function createSnapToken(Order $order, ?User $user, array $items): string
    {
        $itemDetails = [];
        foreach ($items as $item) {
            $itemDetails[] = [
                'id'       => (string)$item->productId,
                'price'    => (int)$item->price,
                'quantity' => $item->qty,
                'name'     => substr($item->productName, 0, 50),
            ];
        }

        // Tambah ongkir sebagai item
        if ($order->shippingCost > 0) {
            $itemDetails[] = [
                'id'       => 'SHIPPING',
                'price'    => (int)$order->shippingCost,
                'quantity' => 1,
                'name'     => 'Ongkos Kirim (' . strtoupper($order->courier) . ')',
            ];
        }

        $payload = [
            'transaction_details' => [
                'order_id'     => $order->orderCode,
                'gross_amount' => (int)$order->total,
            ],
            'item_details'    => $itemDetails,
            'customer_details' => [
                'first_name' => $order->name,
                'phone'      => $order->phone,
                'email'      => $user?->email ?? 'customer@arsyla.com',
                'billing_address' => [
                    'first_name' => $order->name,
                    'phone'      => $order->phone,
                    'address'    => $order->address,
                    'city'       => $order->cityName,
                    'postal_code' => $order->postalCode ?? '',
                    'country_code' => 'IDN',
                ],
            ],
            'callbacks' => [
                'finish' => APP_URL . '/payment/finish',
            ],
        ];

        $response = $this->post($this->snapApiUrl, $payload);

        if (isset($response['token'])) {
            return $response['token'];
        }

        throw new \RuntimeException('Gagal membuat token Midtrans: ' . json_encode($response));
    }

    public function verifySignature(array $payload): bool
    {
        $orderId           = $payload['order_id']           ?? '';
        $statusCode        = $payload['status_code']        ?? '';
        $grossAmount       = $payload['gross_amount']       ?? '';
        $incomingSignature = $payload['signature_key']      ?? '';

        $expected = hash('sha512', $orderId . $statusCode . $grossAmount . $this->serverKey);
        return hash_equals($expected, $incomingSignature);
    }

    private function post(string $url, array $payload): array
    {
        $encoded = base64_encode($this->serverKey . ':');

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization: Basic ' . $encoded,
            ],
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $result = curl_exec($ch);
        $error  = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new \RuntimeException('cURL Error: ' . $error);
        }

        return json_decode($result, true) ?? [];
    }
}
