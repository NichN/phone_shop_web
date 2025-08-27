<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class customer_admincontroller extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $customer = DB::table('users')
                ->where('role_id', '>', 3) // Exclude customers (role_id = 4)
                ->get();
            return DataTables::of($customer)
            ->addColumn('action', function ($row) {
                $btn = '<div class="d-flex gap-1">';
                
                // View button - Available for all users (Admin and Staff)
                $btn .= '<button class="btn btn-sm viewCustomer" data-id="'. $row->id .'" data-toggle="tooltip" title="View Details" style="background-color: #e3f2fd; border: 1px solid #90caf9; color: #1976d2; padding: 0.25rem 0.5rem; font-size: 0.875rem; border-radius: 0.2rem;">
                            <i class="fas fa-eye"></i>
                        </button>';
                
                // Edit and Delete buttons - Only for Admin users (role_id = 1)
                if (auth()->user()->role_id == 1) {
                    $btn .= '<button class="btn btn-sm editCate" data-id="'. $row->id .'" data-toggle="tooltip" title="Edit" style="background-color: #fffde7; border: 1px solid #ffe082; color: #fbc02d; padding: 0.25rem 0.5rem; font-size: 0.875rem; border-radius: 0.2rem;">
                                <i class="fas fa-edit"></i>
                            </button>';
                    $btn .= '<button data-id="'.$row->id.'" class="btn btn-sm deleteCate" style="background-color: #ffebee; border: 1px solid #ef9a9a; color: #c62828; padding: 0.25rem 0.5rem; font-size: 0.875rem; border-radius: 0.2rem;">
                                <i class="fas fa-trash-alt"></i>
                            </button>';
                }
                
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
            }
        return view('Admin.customer.index');
    }

    public function show($id)
    {
        $customer = DB::table('users')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->where('users.id', $id)
            ->select('users.*', 'roles.name as role_name')
            ->first();

        if (!$customer) {
            return response()->json(['error' => 'Customer not found'], 404);
        }

        // Get customer's order statistics
        $orderStats = DB::table('orders')
            ->where('user_id', $id)
            ->select(
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(total_amount) as total_spent'),
                DB::raw('MAX(created_at) as last_order_date')
            )
            ->first();

        // Get recent orders
        $recentOrders = DB::table('orders')
            ->where('user_id', $id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $customerData = [
            'id' => $customer->id,
            'name' => $customer->name,
            'email' => $customer->email,
            'phone_number' => $customer->phone_number,
            'address_line1' => $customer->address_line1,
            'address_line2' => $customer->address_line2,
            'city' => $customer->city,
            'state' => $customer->state,
            'role_name' => $customer->role_name ?? 'Customer',
            'profile_image' => $customer->profile_image,
            'email_verified_at' => $customer->email_verified_at,
            'created_at' => $customer->created_at,
            'updated_at' => $customer->updated_at,
            'total_orders' => $orderStats->total_orders ?? 0,
            'total_spent' => $orderStats->total_spent ?? 0,
            'last_order_date' => $orderStats->last_order_date,
            'recent_orders' => $recentOrders
        ];

        return response()->json(['customer' => $customerData]);
    }
}
