<?php
// app/Controllers/ShippingController.php
namespace App\Controllers;

use Core\{Request, Response};
use Infrastructure\Shipping\RajaOngkirService;

class ShippingController
{
    private Request  $req;
    private Response $res;

    public function __construct()
    {
        $this->req = new Request();
        $this->res = new Response();
    }

    /** AJAX: hitung ongkir */
    public function check(): void
    {
        $destinationCity = (int)$this->req->post('city_id');
        $weight          = max(1, (int)$this->req->post('weight'));
        $courier         = strtolower(trim($this->req->post('courier', 'jne')));

        if (!$destinationCity) {
            $this->res->json(['success' => false, 'message' => 'Kota tujuan wajib dipilih.']);
        }

        try {
            $service  = new RajaOngkirService();
            $costs    = $service->getCost(
                (int)STORE_CITY_ID,
                $destinationCity,
                $weight,
                $courier
            );
            $this->res->json(['success' => true, 'data' => $costs]);
        } catch (\Exception $e) {
            $this->res->json(['success' => false, 'message' => 'Gagal mendapatkan ongkir: ' . $e->getMessage()]);
        }
    }
}
