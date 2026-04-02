<?php
// src/Application/Commands/Order/CreateOrderHandler.php
namespace Application\Commands\Order;

use Application\Commands\CommandHandlerInterface;
use Domain\Order\Entities\Order;
use Domain\Order\Entities\OrderItem;
use Infrastructure\Persistence\MySQLOrderRepository;
use Infrastructure\Persistence\MySQLCartRepository;
use Infrastructure\Persistence\MySQLProductRepository;

class CreateOrderHandler implements CommandHandlerInterface
{
    private MySQLOrderRepository   $orderRepo;
    private MySQLCartRepository    $cartRepo;
    private MySQLProductRepository $productRepo;

    public function __construct()
    {
        $this->orderRepo   = new MySQLOrderRepository();
        $this->cartRepo    = new MySQLCartRepository();
        $this->productRepo = new MySQLProductRepository();
    }

    public function handle(object $command): int
    {
        /** @var CreateOrderCommand $command */

        // 1. Ambil keranjang
        $cart = $this->cartRepo->findByUserId($command->userId);
        if (!$cart) {
            throw new \RuntimeException('Keranjang kosong.');
        }

        $items = $this->cartRepo->getItems($cart->id);
        if (empty($items)) {
            throw new \RuntimeException('Keranjang kosong, tidak bisa checkout.');
        }

        // 2. Hitung subtotal & validasi stok
        $subtotal   = 0;
        $orderItems = [];

        foreach ($items as $item) {
            $product = $this->productRepo->findById($item->productId);
            if (!$product || !$product->isActive) {
                throw new \RuntimeException("Produk '{$item->productName}' tidak tersedia.");
            }
            if ($item->qty > $product->stock) {
                throw new \RuntimeException("Stok '{$product->name}' tidak mencukupi.");
            }

            $lineTotal  = $item->price * $item->qty;
            $subtotal  += $lineTotal;

            $orderItems[] = [
                'product_id'    => $item->productId,
                'product_name'  => $product->name,
                'product_image' => $product->image,
                'qty'           => $item->qty,
                'price'         => $item->price,
                'subtotal'      => $lineTotal,
            ];
        }

        $total = $subtotal + $command->shippingCost;

        // 3. Buat order
        $order = new Order(
            id: null,
            orderCode: Order::generateCode(),
            userId: $command->userId,
            name: $command->name,
            phone: $command->phone,
            address: $command->address,
            cityId: $command->cityId,
            cityName: $command->cityName,
            province: $command->province,
            postalCode: $command->postalCode,
            courier: $command->courier,
            courierService: $command->courierService,
            shippingCost: $command->shippingCost,
            subtotal: $subtotal,
            total: $total,
            notes: $command->notes,
            status: 'pending',
            paymentStatus: 'unpaid',
            createdAt: date('Y-m-d H:i:s'),
        );

        $orderId = $this->orderRepo->save($order);

        // 4. Simpan order items & kurangi stok
        $this->orderRepo->saveItems($orderId, $orderItems);

        foreach ($items as $item) {
            $this->productRepo->decrementStock($item->productId, $item->qty);
            $this->productRepo->incrementSold($item->productId, $item->qty);
        }

        // 5. Kosongkan keranjang
        $this->cartRepo->clearCart($cart->id);

        return $orderId;
    }
}
