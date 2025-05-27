<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function showStaticInvoice()
    {
        $data = [
            'date' => '08/05/2025 8:25:00 AM',
            'invoice_number' => 'INV0007893',
            'customer' => 'Ny Sreynich',
            'items' => [
                ['name' => 'IPhone 16', 'price' => 599.00],
                ['name' => 'IPhone 13', 'price' => 499.00],
                ['name' => 'IPhone 7',  'price' => 299.00],
            ],
            'total_usd' => 1397.00,
            'total_khr' => '៛5,727,700',
            'cash' => 1400,
            'change_usd' => 3,
            'change_khr' => '៛1,200',
        ];

        return view('customer.invoice', $data);
    }
}
