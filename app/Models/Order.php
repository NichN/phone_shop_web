<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'order_num',
        'delivery_id',
        'subtotal',
        'phone_number',
        'address',
        'delivery_fee',
        'total_amount',
        'guest_name',
        'guest_address',
        'phone_guest',
        'guest_eamil',
        'guest_token',
        'delivery_type',
        'status',
        'code_verify',
        'rate_id',
        'img_verify'
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
