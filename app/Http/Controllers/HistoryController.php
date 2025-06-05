<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        if (!session()->has('orders')) {
            session(['orders' => [
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
            ]]);
        }

        $orders = session('orders');

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $orders = array_filter($orders, fn($order) => $order['status'] == $request->status);
        }

        // Filter by search
        if ($request->has('search') && $request->search != '') {
            $orders = array_filter($orders, fn($order) => strpos((string) $order['id'], $request->search) !== false);
        }

        // Filter by date
        if ($request->has('date') && $request->date != '') {
            $orders = array_filter($orders, fn($order) => $order['date'] == $request->date);
        }

        return view('customer.history', ['orders' => $orders]);
    }
    public function destroy($id)
    {
        $orders = session('orders', []);

        // Remove the order with matching ID
        $orders = array_filter($orders, fn($order) => $order['id'] != $id);

        // Re-index the array to avoid gaps
        $orders = array_values($orders);

        session(['orders' => $orders]);

        return redirect()->route('history')->with('success', 'Order deleted successfully.');
    }
}
