<?php
// app/Controllers/ShippingController.php
namespace App\Controllers;

use Core\{Request, Response};
use Infrastructure\Shipping\SimulatedShippingService;

class ShippingController
{
    private Request  $req;
    private Response $res;

    public function __construct()
    {
        $this->req = new Request();
        $this->res = new Response();
    }

    /**
     * AJAX: kembalikan daftar layanan & tarif simulasi berdasarkan kurir.
     * Tidak memanggil API eksternal — cocok untuk simulasi skripsi.
     */
    public function check(): void
    {
        $courier = strtolower(trim($this->req->post('courier', '')));

        if (!$courier) {
            $this->res->json(['success' => false, 'message' => 'Kurir wajib dipilih.']);
        }

        $service  = new SimulatedShippingService();
        $services = $service->getServices($courier);

        if (empty($services)) {
            $this->res->json(['success' => false, 'message' => 'Kurir tidak dikenali.']);
        }

        $this->res->json(['success' => true, 'data' => $services]);
    }
}
