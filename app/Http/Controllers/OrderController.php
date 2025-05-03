<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function showInvoice($id)
    {
        $order = [
            'id' => $id,
            'date' => '22/10/2024',
            'customer_name' => 'Ny Sreyneich',
            'address' => '#St 251, Toul Kork, BoeungKak1, Phnom Penh',
            'items' => [
                ['name' => 'iPhone 15', 'quantity' => 1, 'price' => 1000],
                ['name' => 'Laptop', 'quantity' => 1, 'price' => 800],
                ['name' => 'Power Bank', 'quantity' => 1, 'price' => 20],
            ],
            'tax' => 1.50,
        ];

        // Calculate totals
        $subtotal = array_sum(array_map(function ($item) {
            return $item['quantity'] * $item['price'];
        }, $order['items']));

        $total = $subtotal + $order['tax'];

        // Add the calculated values to the order array
        $order['subtotal'] = $subtotal;
        $order['total'] = $total;

        return view('customer.invoice', compact('order'));
    }
}
