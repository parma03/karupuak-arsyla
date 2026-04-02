<?php
// src/Infrastructure/Shipping/RajaOngkirService.php
namespace Infrastructure\Shipping;

class RajaOngkirService
{
    private string $apiKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey  = RAJAONGKIR_API_KEY;
        $this->baseUrl = RAJAONGKIR_BASE_URL;
    }

    /** Ambil daftar provinsi */
    public function getProvinces(): array
    {
        return $this->get('/province');
    }

    /** Ambil daftar kota berdasarkan provinsi */
    public function getCities(int $provinceId = 0): array
    {
        $query = $provinceId ? "?province=$provinceId" : '';
        return $this->get('/city' . $query);
    }

    /**
     * Hitung ongkos kirim
     * @param int    $originCity      ID kota asal (toko)
     * @param int    $destinationCity ID kota tujuan
     * @param int    $weight          Berat dalam gram
     * @param string $courier         jne | pos | tiki
     */
    public function getCost(int $originCity, int $destinationCity, int $weight, string $courier): array
    {
        $payload = [
            'origin'      => $originCity,
            'destination' => $destinationCity,
            'weight'      => $weight,
            'courier'     => strtolower($courier),
        ];

        $result = $this->post('/cost', $payload);

        // Flatten hasil ke format sederhana
        $services = [];
        $results  = $result['rajaongkir']['results'][0]['costs'] ?? [];

        foreach ($results as $cost) {
            $services[] = [
                'service'     => $cost['service'],
                'description' => $cost['description'],
                'cost'        => $cost['cost'][0]['value'] ?? 0,
                'etd'         => $cost['cost'][0]['etd']   ?? '-',
            ];
        }

        return $services;
    }

    /** Cari kota berdasarkan nama (untuk autocomplete) */
    public function searchCity(string $name): array
    {
        $cities = $this->getCities();
        $all    = $cities['rajaongkir']['results'] ?? [];

        return array_values(array_filter($all, function ($city) use ($name) {
            return stripos($city['city_name'], $name) !== false
                || stripos($city['type'],      $name) !== false;
        }));
    }

    private function get(string $endpoint): array
    {
        $ch = curl_init($this->baseUrl . $endpoint);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => ['key: ' . $this->apiKey],
            CURLOPT_TIMEOUT        => 15,
        ]);
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result, true) ?? [];
    }

    private function post(string $endpoint, array $data): array
    {
        $ch = curl_init($this->baseUrl . $endpoint);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($data),
            CURLOPT_HTTPHEADER     => ['key: ' . $this->apiKey],
            CURLOPT_TIMEOUT        => 15,
        ]);
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result, true) ?? [];
    }
}
