<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $orders = [
            ['id' => 1, 'date' => '22/10/2024', 'amount' => '$800', 'status' => 'Completed'],
            ['id' => 2, 'date' => '22/10/2024', 'amount' => '$800', 'status' => 'Returned'],
            ['id' => 3, 'date' => '22/10/2024', 'amount' => '$800', 'status' => 'Completed'],
            ['id' => 4, 'date' => '22/10/2024', 'amount' => '$800', 'status' => 'Processing'],
            ['id' => 5, 'date' => '22/10/2024', 'amount' => '$800', 'status' => 'Returned'],
            ['id' => 6, 'date' => '22/10/2024', 'amount' => '$800', 'status' => 'Completed'],
            ['id' => 7, 'date' => '22/10/2024', 'amount' => '$800', 'status' => 'Processing'],
            ['id' => 8, 'date' => '22/10/2024', 'amount' => '$800', 'status' => 'Completed'],
            ['id' => 9, 'date' => '22/10/2024', 'amount' => '$800', 'status' => 'Returned'],
            ['id' => 10, 'date' => '22/10/2024', 'amount' => '$800', 'status' => 'Completed'],
            ['id' => 11, 'date' => '22/10/2024', 'amount' => '$800', 'status' => 'Processing'],
            ['id' => 12, 'date' => '22/10/2024', 'amount' => '$800', 'status' => 'Returned'],
            ['id' => 13, 'date' => '22/10/2024', 'amount' => '$800', 'status' => 'Completed'],
            ['id' => 14, 'date' => '22/10/2024', 'amount' => '$800', 'status' => 'Processing'],
        ];

        // Filter by status if selected
        if ($request->has('status') && $request->status != '') {
            $orders = array_filter($orders, function($order) use ($request) {
                return $order['status'] == $request->status;
            });
        }

        // Optionally, you can also filter by search or date
        if ($request->has('search') && $request->search != '') {
            $orders = array_filter($orders, function($order) use ($request) {
                return strpos($order['id'], $request->search) !== false;
            });
        }

        if ($request->has('date') && $request->date != '') {
            $orders = array_filter($orders, function($order) use ($request) {
                return $order['date'] == $request->date;
            });
        }

        return view('customer.history', compact('orders'));
    }
}
