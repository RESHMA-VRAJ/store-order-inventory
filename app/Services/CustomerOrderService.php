<?php

namespace App\Services;

use App\Models\CustomerOrder;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Jobs\SendOrderConfirmation;

class CustomerOrderService
{
    public function createOrder(int $customerId, array $items): CustomerOrder
    {
        $order = DB::transaction(function () use ($customerId, $items) {
            // Lock product rows in a consistent order to reduce deadlocks.
            $productIds = collect($items)->pluck('product_id')->map(fn($id) => (int) $id)->sort()->values();

            $products = [];

            foreach ($productIds as $productId) {
                $product = Product::whereKey($productId)->lockForUpdate()->first();

                if (!$product) {
                    throw ValidationException::withMessages([
                        'items' => ["Product {$productId} was not found."],
                    ]);
                }

                $products[$productId] = $product;
            }

            $subtotal = 0;
            $totalTax = 0;
            $orderLines = [];

            // Calculate totals using the locked, current product data.
            foreach ($items as $item) {
                $product = $products[(int) $item['product_id']];
                $quantity = (int) $item['quantity'];

                // if ($product->stock < $quantity) {
                //     throw ValidationException::withMessages([
                //         'items' => [
                //             "Insufficient stock for {$product->name}. "
                //             . "Available: {$product->stock}.",
                //         ],
                //     ]);
                // }
                // if ($product->stock < $item['quantity']) {
                //     throw ValidationException::withMessages([
                //         'items' => ["Insufficient stock for product: {$product->name}."],
                //     ]);
                // }
                 if ($product->stock < $quantity) {
                    throw ValidationException::withMessages([
                        'items' => [
                            "Insufficient stock for {$product->name}. "
                            . "Available: {$product->stock}.",
                        ],
                    ]);
                }

                $unitPrice = (float) $product->price;
                $taxPercentage = (float) $product->tax_percentage;

                $lineSubtotal = round($unitPrice * $quantity, 2);
                $lineTax = round(($lineSubtotal * $taxPercentage) / 100, 2);

                $lineTotal = round($lineSubtotal + $lineTax, 2);

                $subtotal += $lineSubtotal;
                $totalTax += $lineTax;

                $orderLines[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'tax_percentage' => $taxPercentage,
                    'line_total' => $lineTotal,
                ];
            }

            $subtotal = round($subtotal, 2);
            $totalTax = round($totalTax, 2);

            $order = CustomerOrder::create([
                'customer_id' => $customerId,
                'subtotal' => $subtotal,
                'tax' => $totalTax,
                'grand_total' => round($subtotal + $totalTax, 2),
            ]);

            foreach ($orderLines as $line) {
                $order->customer_order_details()->create([
                    'product_id' => $line['product']->id,
                    'quantity' => $line['quantity'],
                    'unit_price' => $line['unit_price'],
                    'tax_percentage' => $line['tax_percentage'],
                    'line_total' => $line['line_total'],
                ]);

                $line['product']->decrement('stock', $line['quantity']);
            }

            return $order;
        }, 3);

         SendOrderConfirmation::dispatch($order->id)->afterCommit();

        return $order->load([
            'customer',
            'customer_order_details.product',
        ]);
    }
}
