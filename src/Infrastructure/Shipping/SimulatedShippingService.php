<?php
// src/Infrastructure/Shipping/SimulatedShippingService.php
namespace Infrastructure\Shipping;

/**
 * Layanan pengiriman simulasi.
 */
class SimulatedShippingService
{
    /**
     * Data kurir dan layanannya (simulasi).
     * Format: courier => [ [service, description, cost, etd], ... ]
     */
    private array $catalog = [
        'jne' => [
            ['service' => 'OKE',  'description' => 'Ongkos Kirim Ekonomis', 'cost' => 10000, 'etd' => '4-5'],
            ['service' => 'REG',  'description' => 'Reguler',               'cost' => 15000, 'etd' => '2-3'],
            ['service' => 'YES',  'description' => 'Yakin Esok Sampai',     'cost' => 35000, 'etd' => '1'],
        ],
        'jnt' => [
            ['service' => 'EZ',      'description' => 'Reguler',  'cost' => 12000, 'etd' => '2-3'],
            ['service' => 'EXPRESS', 'description' => 'Express',  'cost' => 28000, 'etd' => '1'],
        ],
        'sicepat' => [
            ['service' => 'HALU',  'description' => 'Hemat',    'cost' =>  9000, 'etd' => '4-5'],
            ['service' => 'BEST',  'description' => 'Reguler',  'cost' => 14000, 'etd' => '2-3'],
            ['service' => 'GOKIL', 'description' => 'Express',  'cost' => 30000, 'etd' => '1'],
        ],
    ];

    /**
     * Ambil daftar kurir yang tersedia.
     */
    public function getCouriers(): array
    {
        return array_keys($this->catalog);
    }

    /**
     * Ambil layanan & tarif berdasarkan kurir.
     * Mengembalikan array kosong jika kurir tidak dikenali.
     */
    public function getServices(string $courier): array
    {
        return $this->catalog[strtolower($courier)] ?? [];
    }
}
